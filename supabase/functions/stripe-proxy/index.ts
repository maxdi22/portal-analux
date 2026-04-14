// @ts-ignore
import { createClient } from "https://esm.sh/@supabase/supabase-js@2"
// @ts-ignore
import Stripe from "https://esm.sh/stripe@14.24.0?target=deno"

const corsHeaders = {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type, stripe-signature',
}

Deno.serve(async (req) => {
    // 1. Handle CORS Preflight
    if (req.method === 'OPTIONS') {
        return new Response('ok', { headers: corsHeaders })
    }

    try {
        console.log(`[Request] ${req.method} ${req.url}`);

        // 2. Configuration & Initialization
        const supabaseUrl = Deno.env.get('SUPABASE_URL')
        const supabaseKey = Deno.env.get('SUPABASE_SERVICE_ROLE_KEY')
        const stripeKey = Deno.env.get('STRIPE_SECRET_KEY');
        const webhookSecret = Deno.env.get('STRIPE_WEBHOOK_SECRET');

        // FORCE 200 OK response for Config Errors to debug
        if (!supabaseUrl || !supabaseKey || !stripeKey) {
            console.error("Critical: Config Missing", { supabaseUrl: !!supabaseUrl, supabaseKey: !!supabaseKey, stripeKey: !!stripeKey });
            return new Response(JSON.stringify({ success: false, error: 'Server Config Error: Missing Env Vars' }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const supabase = createClient(supabaseUrl, supabaseKey)
        const stripe = new Stripe(stripeKey, {
            apiVersion: '2023-10-16',
            httpClient: Stripe.createFetchHttpClient(),
        })

        // 3. Request Routing
        const signature = req.headers.get('Stripe-Signature');

        if (signature) {
            // WEBHOOK LOGIC
            const body = await req.text();
            let event;
            try {
                if (webhookSecret) {
                    event = await stripe.webhooks.constructEventAsync(body, signature, webhookSecret);
                } else {
                    event = JSON.parse(body);
                }
            } catch (err) {
                return new Response(`Webhook Error: ${err.message}`, { status: 400 });
            }

            // Webhook Event Handling Loop
            switch (event.type) {
                case 'checkout.session.completed': {
                    const session = event.data.object;
                    const userId = session.metadata?.user_id;

                    if (userId) {
                        console.log(`[Webhook] Linking user ${userId} to Customer ${session.customer}`);
                        await supabase.from('subscriptions').upsert({
                            user_id: userId,
                            stripe_customer_id: session.customer,
                            stripe_subscription_id: session.subscription,
                            plan: 'ESSENTIAL', // Default derived from product logic or metadata
                            status: 'ACTIVE',
                            frequency: 'Mensal',
                            current_box_status: 'Preparando sua Box',
                            updated_at: new Date().toISOString()
                        }, { onConflict: 'user_id' });
                    }
                    break;
                }
                // Add other webhook cases here if needed
            }

            return new Response(JSON.stringify({ received: true }), { headers: { 'Content-Type': 'application/json' } });

        } else {
            // CLIENT API LOGIC
            const authHeader = req.headers.get('Authorization')
            if (!authHeader) {
                return new Response(JSON.stringify({ success: false, error: 'Missing Authorization Header' }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
            }

            const token = authHeader.replace('Bearer ', '')
            const { data: { user }, error: userError } = await supabase.auth.getUser(token)

            if (userError || !user) {
                console.error("[Auth Check Failed]", userError);
                return new Response(JSON.stringify({ success: false, error: 'Unauthorized', details: userError?.message }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
            }

            // Parse Body safely
            let body;
            try {
                body = await req.json();
            } catch (e) {
                return new Response(JSON.stringify({ success: false, error: 'Invalid JSON Body' }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
            }

            const { action, priceId, returnUrl, session_id } = body;
            console.log(`[Action] ${action} | User: ${user.id}`);


            if (action === 'finalize_checkout') {
                if (!session_id) return new Response(JSON.stringify({ success: false, error: 'Missing session_id' }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });

                try {
                    console.log("[Finalize] Retrieving session:", session_id);
                    const session = await stripe.checkout.sessions.retrieve(session_id, { expand: ['subscription'] });

                    if (!session) throw new Error("Stripe Session not found");

                    const subData = session.subscription;
                    const subId = typeof subData === 'string' ? subData : subData?.id;

                    if (!subId) throw new Error("No subscription ID found in session");

                    console.log("[Finalize] Upserting subscription...");

                    const { error: upsertError } = await supabase.from('subscriptions').upsert({
                        user_id: user.id,
                        stripe_customer_id: session.customer,
                        stripe_subscription_id: subId,
                        plan: 'ESSENTIAL',
                        status: 'ACTIVE',
                        frequency: 'Mensal',
                        current_box_status: 'Preparando sua Box',
                        updated_at: new Date().toISOString()
                    }, { onConflict: 'user_id' });

                    if (upsertError) {
                        console.error("[Finalize] DB Error:", upsertError);
                        throw new Error(`Database Error: ${upsertError.message}`);
                    }

                    // TRIGGER SYNC IMMEDIATELY
                    await syncSubscriptionInternal(supabase, stripe, user.id);

                    return new Response(JSON.stringify({ success: true }), { headers: { ...corsHeaders, 'Content-Type': 'application/json' } });

                } catch (err) {
                    console.error("[Finalize] Logic Error:", err);
                    return new Response(JSON.stringify({
                        success: false,
                        error: `Processing Error: ${err.message}`,
                        details: err.stack
                    }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                }
            }

            if (action === 'sync_subscription') {
                try {
                    await syncSubscriptionInternal(supabase, stripe, user.id);
                    return new Response(JSON.stringify({ success: true }), { headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                } catch (err) {
                    return new Response(JSON.stringify({ success: false, error: err.message }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                }
            }

            if (action === 'create_checkout_session') {
                if (!priceId) return new Response(JSON.stringify({ success: false, error: "Missing priceId" }), { status: 200, headers: corsHeaders });
                try {
                    const session = await stripe.checkout.sessions.create({
                        customer_email: user.email,
                        line_items: [{ price: priceId, quantity: 1 }],
                        mode: 'subscription',
                        success_url: `${returnUrl}?session_id={CHECKOUT_SESSION_ID}`,
                        cancel_url: `${returnUrl}`,
                        metadata: { user_id: user.id }
                    });
                    return new Response(JSON.stringify({ url: session.url }), { headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                } catch (e) {
                    return new Response(JSON.stringify({ success: false, error: e.message }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                }
            }

            if (action === 'create_portal_session') {
                try {
                    const { data: sub } = await supabase.from('subscriptions').select('stripe_customer_id').eq('user_id', user.id).single();
                    if (!sub?.stripe_customer_id) throw new Error("No Customer ID found");

                    const session = await stripe.billingPortal.sessions.create({
                        customer: sub.stripe_customer_id,
                        return_url: returnUrl,
                    });
                    return new Response(JSON.stringify({ url: session.url }), { headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                } catch (e) {
                    return new Response(JSON.stringify({ success: false, error: e.message }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                }
            }

            if (action === 'get_invoices') {
                try {
                    const { data: sub } = await supabase.from('subscriptions').select('stripe_customer_id').eq('user_id', user.id).single();
                    if (!sub?.stripe_customer_id) return new Response(JSON.stringify({ invoices: [] }), { headers: { ...corsHeaders, 'Content-Type': 'application/json' } });

                    const invoices = await stripe.invoices.list({
                        customer: sub.stripe_customer_id,
                        limit: 5,
                        status: 'paid'
                    });

                    const formattedInvoices = invoices.data.map(inv => ({
                        id: inv.id,
                        number: inv.number,
                        amount: (inv.amount_paid / 100).toFixed(2),
                        date: new Date(inv.created * 1000).toLocaleDateString('pt-BR'),
                        pdf: inv.invoice_pdf,
                        status: inv.status
                    }));

                    return new Response(JSON.stringify({ invoices: formattedInvoices }), { headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                } catch (e) {
                    return new Response(JSON.stringify({ success: false, error: e.message }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
                }
            }

            return new Response(JSON.stringify({ success: false, error: 'Unknown Action' }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } });
        }

    } catch (e) {
        console.error("MAIN CATCH BLOCK:", e);
        // FORCE 200
        return new Response(JSON.stringify({
            success: false,
            error: 'Critical Server Error',
            details: e instanceof Error ? e.message : String(e)
        }), { status: 200, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
    }
})

// HELPER FUNCTION (Internal)
async function syncSubscriptionInternal(supabase, stripe, userId) {
    const { data: localSub } = await supabase.from('subscriptions').select('stripe_subscription_id, stripe_customer_id').eq('user_id', userId).maybeSingle();

    if (localSub?.stripe_subscription_id) {
        // 1. Fetch Subscription
        const subData = await stripe.subscriptions.retrieve(localSub.stripe_subscription_id);

        let status = (subData.status === 'active' || subData.status === 'trialing') ? 'ACTIVE' : 'PAUSED';
        if (subData.status === 'canceled') status = 'CANCELLED';

        // Format Dates
        const nextDate = new Date(subData.current_period_end * 1000).toLocaleDateString('pt-BR');
        const memberSince = new Date(subData.created * 1000).toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });

        // 2. Fetch Payment Method (Card info)
        let billingInfo = { brand: '', lastFour: '', expiry: '' };

        try {
            // Try to list payment methods for the customer
            const methods = await stripe.paymentMethods.list({ customer: localSub.stripe_customer_id, type: 'card' });
            if (methods.data.length > 0) {
                const card = methods.data[0].card;
                billingInfo = {
                    brand: card.brand,
                    lastFour: card.last4,
                    expiry: `${card.exp_month}/${card.exp_year}`
                };
            }
        } catch (e) {
            console.warn("Failed to fetch payment methods:", e);
        }

        // 3. Update Tables
        await Promise.all([
            supabase.from('subscriptions').update({
                status: status, // Update to match stripe status
                next_box_date: nextDate,
                member_since: memberSince,
                stripe_current_period_end: new Date(subData.current_period_end * 1000).toISOString(),
                updated_at: new Date().toISOString()
            }).eq('user_id', userId),

            supabase.from('profiles').update({
                billing: billingInfo
            }).eq('id', userId)
        ]);
    }
}
