-- 
-- CONFIGURAÇÃO DA CENTRAL DE MARKETING & ADS (Analux)
-- Execute este script no SQL Editor do seu Supabase.
--

-- 1. Tabela de Configurações de Marketing (Se não existir, usamos a app_settings já existente)
-- O sistema usará a chave 'marketing_config_v1' na tabela app_settings.

-- 2. Tabela de Atividade de Visitantes (Sensores)
CREATE TABLE IF NOT EXISTS public.visitor_activity (
    id UUID DEFAULT gen_random_uuid() PRIMARY KEY,
    guest_id TEXT NOT NULL, -- ID anônimo via localStorage
    user_id UUID REFERENCES auth.users(id), -- ID se logado
    event_type TEXT NOT NULL, -- 'page_view', 'click', 'lead_convert', 'heartbeat'
    page_path TEXT NOT NULL,
    metadata JSONB DEFAULT '{}'::jsonb, -- info do browser, dispositivo, ref
    created_at TIMESTAMPTZ DEFAULT now()
);

-- 3. Habilitar Realtime para monitoramento ao vivo
-- Isso permite que o Dashboard ADS veja os usuários online instantaneamente.
ALTER PUBLICATION supabase_realtime ADD TABLE visitor_activity;

-- 4. Políticas de Segurança (RLS)
ALTER TABLE public.visitor_activity ENABLE ROW LEVEL SECURITY;

-- Permite que qualquer visitante insira logs (anônimo ou logado)
CREATE POLICY "Allow anonymous reporting" 
ON public.visitor_activity FOR INSERT 
WITH CHECK (true);

-- Permite que apenas Admins vejam os logs
CREATE POLICY "Allow admins to view activity" 
ON public.visitor_activity FOR SELECT 
USING (
  EXISTS (
    SELECT 1 FROM public.profiles -- Ajuste conforme o nome da sua tabela de perfis
    WHERE profiles.id = auth.uid() 
    AND profiles.role IN ('admin', 'superadmin')
  )
);

-- Índices para performance
CREATE INDEX IF NOT EXISTS idx_visitor_activity_guest_id ON public.visitor_activity(guest_id);
CREATE INDEX IF NOT EXISTS idx_visitor_activity_created_at ON public.visitor_activity(created_at DESC);
CREATE INDEX IF NOT EXISTS idx_visitor_activity_type ON public.visitor_activity(event_type);
