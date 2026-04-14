
import { createClient } from "https://esm.sh/@supabase/supabase-js@2"

const corsHeaders = {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
}

Deno.serve(async (req) => {
    if (req.method === 'OPTIONS') {
        return new Response('ok', { headers: corsHeaders })
    }

    const debugLog: string[] = []

    try {
        const supabaseUrl = Deno.env.get('SUPABASE_URL')
        const supabaseKey = Deno.env.get('SUPABASE_SERVICE_ROLE_KEY')

        if (!supabaseUrl || !supabaseKey) {
            return new Response(JSON.stringify({ error: 'Server Misconfiguration' }), { status: 500, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const supabase = createClient(supabaseUrl, supabaseKey)

        const authHeader = req.headers.get('Authorization')
        if (!authHeader) {
            return new Response(JSON.stringify({ error: 'Missing Authorization header' }), { status: 401, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const token = authHeader.replace('Bearer ', '')
        const { data: { user }, error: userError } = await supabase.auth.getUser(token)

        if (userError || !user) {
            return new Response(JSON.stringify({ error: `Unauthorized: ${userError?.message || 'Unknown error'}` }), { status: 401, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        let body
        try {
            body = await req.json()
        } catch {
            return new Response(JSON.stringify({ error: 'Invalid JSON body' }), { status: 400, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const { action, email, password } = body

        const WC_URL = Deno.env.get('WC_URL')
        const WC_KEY = Deno.env.get('WC_CONSUMER_KEY')
        const WC_SECRET = Deno.env.get('WC_CONSUMER_SECRET')

        if (!WC_URL || !WC_KEY || !WC_SECRET) {
            return new Response(JSON.stringify({ error: 'Missing WC Configuration' }), { status: 500, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const auth = btoa(`${WC_KEY}:${WC_SECRET}`)
        const headers = {
            'Authorization': `Basic ${auth}`,
            'Content-Type': 'application/json'
        }

        if (action === 'connect') {
            // 1. Verify User Exists in WC
            const customerRes = await fetch(`${WC_URL}/wp-json/wc/v3/customers?email=${email}`, { headers })
            const customers = await customerRes.json()

            if (!customers || customers.length === 0) {
                return new Response(JSON.stringify({ error: 'E-mail não encontrado na loja.' }), { status: 404, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
            }

            // In a real connect flow, we might verify password via a custom plugin endpoint, or just trust the email ownership if they are logged into Supabase
            // For now, we assume email match is sufficient if they are authenticated in Supabase

            await supabase.from('profiles').update({ is_store_connected: true }).eq('id', user.id)

            return new Response(JSON.stringify({ success: true, customer: customers[0] }), {
                headers: { ...corsHeaders, 'Content-Type': 'application/json' },
            })
        }

        if (action === 'sync') {
            // 1. Fetch Orders
            debugLog.push(`Fetching orders for ${email}`)
            const ordersRes = await fetch(`${WC_URL}/wp-json/wc/v3/orders?search=${email}`, { headers })
            const wcOrders = await ordersRes.json()

            const recentOrders = Array.isArray(wcOrders) ? wcOrders.map((o: any) => ({
                id: String(o.id),
                number: o.number,
                date: new Date(o.date_created).toLocaleDateString('pt-BR'),
                status: o.status === 'completed' ? 'Concluído' : (o.status === 'processing' ? 'Enviado' : o.status),
                total: parseFloat(o.total),
                itemsCount: o.line_items.length,
                items: o.line_items.map((li: any) => ({
                    id: li.id,
                    name: li.name,
                    image: li.image?.src || 'https://via.placeholder.com/150',
                    price: parseFloat(li.price),
                    quantity: li.quantity
                }))
            })) : []

            // 2. Fetch Wallet/Customer Data (Cashback & Pending)
            debugLog.push("Fetching customer wallet data")
            const custRes = await fetch(`${WC_URL}/wp-json/wc/v3/customers?email=${email}`, { headers })

            let cashback = 0
            let pendingCashback = 0

            if (custRes.ok) {
                const custData = await custRes.json()
                if (custData && custData.length > 0) {
                    const customer = custData[0]
                    if (customer.analux_wallet_balance !== undefined) {
                        cashback = parseFloat(customer.analux_wallet_balance)
                    }
                    if (customer.analux_wallet_pending !== undefined) {
                        pendingCashback = parseFloat(customer.analux_wallet_pending)
                    }
                }
            }

            // 3. Update Profiles Table (Sync Critical Data)
            debugLog.push(`Updating profiles: cashback=${cashback}, pending=${pendingCashback}`)
            const { error: profileError } = await supabase.from('profiles').update({
                cashback: cashback,
                pending_cashback: pendingCashback,
                points: recentOrders.filter((o: any) => ['Concluído', 'Enviado'].includes(o.status)).reduce((acc: number, curr: any) => acc + Math.floor(curr.total), 0)
            }).eq('id', user.id)

            if (profileError) debugLog.push(`Profile Update Error: ${profileError.message}`)

            // 3.5 Fetch VIP Offers (Category 'vip' or specific tag)
            debugLog.push("Fetching VIP offers...");
            // Assuming category slug is 'vip'
            const vipRes = await fetch(`${WC_URL}/wp-json/wc/v3/products?category_slug=vip&status=publish`, { headers });
            let vipProducts = [];

            if (vipRes.ok) {
                const vipData = await vipRes.json();
                if (Array.isArray(vipData)) {
                    vipProducts = vipData.map((p: any) => ({
                        id: String(p.id),
                        name: p.name,
                        image: p.images?.[0]?.src || 'https://via.placeholder.com/300',
                        regularPrice: parseFloat(p.regular_price || p.price),
                        memberPrice: parseFloat(p.sale_price || p.price) * 0.9, // Logic: display price, member gets discount via coupon
                        category: 'VIP',
                        permalink: p.permalink
                    }));
                }
            } else {
                debugLog.push(`Failed to fetch VIP products: ${vipRes.status}`);
            }

            // 4. Update Store Data (Detailed Views)
            const { error: upsertError } = await supabase.from('store_data').upsert({
                user_id: user.id,
                recent_orders: recentOrders,
                exclusive_offers: vipProducts
                // We would also fetch favorites/exclusive offers here if we had the logic
            }, { onConflict: 'user_id' })

            return new Response(JSON.stringify({
                success: true,
                orders: recentOrders,
                vipProducts: vipProducts,
                cashback,
                pendingCashback,
                debug: debugLog
            }), {
                headers: { ...corsHeaders, 'Content-Type': 'application/json' },
            })
        }

        if (action === 'create_coupon') {
            const couponCode = `VIP-${user.id.split('-')[0].toUpperCase()}`
            let finalCouponCode = couponCode

            // 1. Check if coupon exists
            try {
                const checkRes = await fetch(`${WC_URL}/wp-json/wc/v3/coupons?code=${couponCode}`, { headers })
                if (checkRes.ok) {
                    const existingCoupons = await checkRes.json()

                    if (Array.isArray(existingCoupons) && existingCoupons.length === 0) {
                        // 2. Create Coupon if not exists
                        debugLog.push(`Creating coupon ${couponCode} for ${email}`)

                        // Fetch discount percentage from settings
                        let discountAmount = '5';
                        const { data: settingsData } = await supabase
                            .from('app_settings')
                            .select('value')
                            .eq('key', 'vip_offer_discount')
                            .single();

                        if (settingsData && settingsData.value) {
                            discountAmount = String(settingsData.value); // Ensure string
                        }

                        const createRes = await fetch(`${WC_URL}/wp-json/wc/v3/coupons`, {
                            method: 'POST',
                            headers,
                            body: JSON.stringify({
                                code: couponCode,
                                amount: discountAmount,
                                discount_type: 'percent',
                                description: `Cupom VIP para Assinante ${email}`,
                                individual_use: true,
                                exclude_sale_items: true,
                                email_restrictions: [email]
                            })
                        })

                        if (!createRes.ok) {
                            const err = await createRes.json()
                            debugLog.push(`Failed to create coupon: ${err.message}`)
                            // Fallback to generic coupon if creation fails
                            finalCouponCode = 'ANALUX_VIP'
                        } else {
                            debugLog.push(`Coupon created successfully with ${discountAmount}% discount`)
                        }
                    } else {
                        debugLog.push('Coupon already exists.')
                    }
                }
            } catch (e) {
                debugLog.push(`Error checking/creating coupon: ${e.message}`)
                finalCouponCode = 'ANALUX_VIP'
            }

            console.log('WC Proxy Debug:', JSON.stringify(debugLog))

            return new Response(JSON.stringify({
                success: true,
                coupon: finalCouponCode,
                debug: debugLog
            }), {
                headers: { ...corsHeaders, 'Content-Type': 'application/json' },
            })
        }

        return new Response(JSON.stringify({ error: 'Invalid action' }), { status: 400, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })

    } catch (error) {
        console.error('WC Proxy Error:', error)
        return new Response(JSON.stringify({ error: error.message, debug: debugLog }), {
            status: 500,
            headers: { ...corsHeaders, 'Content-Type': 'application/json' },
        })
    }
})
