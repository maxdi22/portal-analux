import { createClient } from '@supabase/supabase-js';
import { config } from './config';

// Inicializa o cliente usando as variáveis validadas
export const supabase = createClient(config.supabase.url, config.supabase.anonKey);
