-- Script for updating legacy user passwords and setting the "needs_password_reset" flag.
-- This script must be run directly in the Supabase SQL Editor by an administrator.

WITH updated_users AS (
    UPDATE auth.users
    SET
        raw_user_meta_data = raw_user_meta_data || '{"needs_password_reset": true}'::jsonb,
        -- The hash below is for 'Analux@102030Mudar'
        -- Note: If possible, we'll use a direct hash.
        -- We will use the standard pgcrypto crypt function used by Supabase Auth
        encrypted_password = crypt('Analux@102030Mudar', gen_salt('bf'))
    WHERE
        -- Filter out admins based on your specific criteria
        -- Ensure this matches how your initial users were defined
        -- For safety, you might want to exclude users by email for now
        email NOT IN ('maxdi.brasil@gmail.com', 'maxdi.agency@gmail.com')
        -- OR you can filter based on custom claims if any exist
        -- AND raw_app_meta_data->>'role' IS DISTINCT FROM 'admin'
    RETURNING id, email
)
SELECT * FROM updated_users;
