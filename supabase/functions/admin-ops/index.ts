import { createClient } from "https://esm.sh/@supabase/supabase-js@2"

const corsHeaders = {
    'Access-Control-Allow-Origin': '*',
    'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
}

Deno.serve(async (req) => {
    if (req.method === 'OPTIONS') {
        return new Response('ok', { headers: corsHeaders })
    }

    try {
        const supabaseUrl = Deno.env.get('SUPABASE_URL')
        const supabaseKey = Deno.env.get('SUPABASE_SERVICE_ROLE_KEY')

        if (!supabaseUrl || !supabaseKey) {
            return new Response(JSON.stringify({ error: 'Server Misconfiguration' }), { status: 500, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const supabase = createClient(supabaseUrl, supabaseKey)

        const authHeader = req.headers.get('Authorization')
        console.log('[admin-ops] Auth Header present:', !!authHeader)

        if (!authHeader) {
            console.error('[admin-ops] Missing Authorization header')
            return new Response(JSON.stringify({ error: 'Missing Authorization header' }), { status: 401, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const token = authHeader.replace('Bearer ', '').trim()
        console.log('[admin-ops] Verifying token...')
        
        const { data: { user: currentUser }, error: userError } = await supabase.auth.getUser(token)

        if (userError || !currentUser) {
            console.error('[admin-ops] Token verification failed:', userError?.message || 'No user found')
            return new Response(JSON.stringify({ error: 'Unauthorized: Invalid token' }), { status: 401, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        console.log('[admin-ops] User verified:', currentUser.email)

        // Check if user is admin
        const { data: profile, error: profileError } = await supabase
            .from('profiles')
            .select('role')
            .eq('id', currentUser.id)
            .single()

        if (profileError || !['admin', 'superadmin'].includes(profile?.role)) {
            return new Response(JSON.stringify({ error: 'Forbidden: Admin access only' }), { status: 403, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
        }

        const body = await req.json()
        const { action, targetUserId, newPassword } = body

        if (action === 'update_password') {
            if (!targetUserId || !newPassword) {
                return new Response(JSON.stringify({ error: 'Missing targetUserId or newPassword' }), { status: 400, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })
            }

            const { data, error } = await supabase.auth.admin.updateUserById(
                targetUserId,
                { password: newPassword }
            )

            if (error) throw error

            return new Response(JSON.stringify({ success: true, user: data.user }), {
                headers: { ...corsHeaders, 'Content-Type': 'application/json' },
            })
        }

        return new Response(JSON.stringify({ error: 'Invalid action' }), { status: 400, headers: { ...corsHeaders, 'Content-Type': 'application/json' } })

    } catch (error: any) {
        return new Response(JSON.stringify({ error: error.message }), {
            status: 500,
            headers: { ...corsHeaders, 'Content-Type': 'application/json' },
        })
    }
})
