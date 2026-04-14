import React from 'react';
import { Lock, CreditCard } from 'lucide-react';
import { supabase } from '../services/supabase';

const PausedLockScreen: React.FC = () => {
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
            alert('Não foi possível conectar ao portal de pagamento.');
        }
    };

    return (
        <div className="flex flex-col items-center justify-center min-h-[60vh] text-center px-4 animate-fadeIn">
            <div className="w-24 h-24 bg-amber-100 text-amber-500 rounded-full flex items-center justify-center mb-6 shadow-inner">
                <Lock size={48} strokeWidth={1.5} />
            </div>

            <h2 className="text-3xl font-serif text-analux-primary mb-4">Acesso Bloqueado</h2>

            <p className="text-gray-500 max-w-md mb-8">
                Sua assinatura encontra-se pausada no momento. Para voltar a aproveitar todos os benefícios do Club Exclusive, incluindo curadoria, peças e comunidade, por favor atualize seu método de pagamento.
            </p>

            <button
                onClick={handleManageSubscription}
                className="flex items-center gap-2 px-8 py-4 bg-analux-primary text-white font-bold rounded-xl hover:bg-analux-dark transition-all shadow-lg hover:shadow-xl hover:-translate-y-1"
            >
                <CreditCard size={20} />
                Regularizar Assinatura
            </button>

            <p className="text-xs text-gray-400 mt-6 mt-4 max-w-sm">
                Você será redirecionado para o ambiente seguro do Stripe para atualizar seus dados.
            </p>
        </div>
    );
};

export default PausedLockScreen;
