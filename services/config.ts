/**
 * Gerenciador de Configuração do Ambiente
 * Centraliza e valida as variáveis de ambiente (VITE_*)
 */

interface Config {
    supabase: {
        url: string;
        anonKey: string;
    };
    stripe: {
        publishableKey: string;
        testPriceId: string;
    };
    isProduction: boolean;
}

const getEnv = (key: string, required = true): string => {
    const value = import.meta.env[key];
    if (!value && required) {
        console.error(`%c [Config Error] Variável obrigatória faltando: ${key} `, 'background: #ff0000; color: #fff; font-weight: bold; padding: 4px;');
        // Não lançamos erro aqui para não quebrar o render completamente, 
        // mas o console fica bem visível.
        return '';
    }
    return value || '';
};

export const config: Config = {
    supabase: {
        url: getEnv('VITE_SUPABASE_URL'),
        anonKey: getEnv('VITE_SUPABASE_ANON_KEY'),
    },
    stripe: {
        publishableKey: getEnv('VITE_STRIPE_PUBLISHABLE_KEY'),
        testPriceId: getEnv('VITE_STRIPE_PRICE_ID', false), // Price ID can be optional at start
    },
    isProduction: import.meta.env.PROD,
};

// Validação de inicialização
if (!config.supabase.url || !config.supabase.anonKey) {
    console.warn('%c Erro de Conectividade: Configure seu arquivo .env com as chaves do Supabase. ', 'color: #ff9900; font-weight: bold;');
}
