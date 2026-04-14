import React from 'react';
import { supabase } from '../services/supabase';
import { AlertTriangle } from 'lucide-react';

const PausedWarningBanner: React.FC = () => {
    const handleManageSubscription = async () => {
        try {
            const { data, error } = await supabase.functions.invoke('stripe-proxy', {
                body: {
                    action: 'create_portal_session',
                    returnUrl: window.location.href
                }
            });

            if (error) throw error;
            if (data?.url) {
                window.location.href = data.url;
            }
        } catch (err) {
            console.error('Erro ao redirecionar para o Stripe:', err);
            alert('Não foi possível conectar ao portal de pagamento. Verifique se sua assinatura está ativa.');
        }
    };

    return (
        <div className="bg-amber-500 text-white px-4 py-3 flex flex-col sm:flex-row items-center justify-center gap-3 w-full shadow-md z-50">
            <div className="flex items-center gap-2 font-medium text-sm text-center">
                <AlertTriangle size={18} />
                <span>Sua assinatura está pausada devido a uma falha no pagamento.</span>
            </div>
            <button
                onClick={handleManageSubscription}
                className="px-4 py-1.5 bg-white text-amber-600 rounded-full font-bold text-xs uppercase tracking-wider hover:bg-amber-50 transition-colors shadow-sm"
            >
                Atualizar Pagamento
            </button>
        </div>
    );
};

export default PausedWarningBanner;
