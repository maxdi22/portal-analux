import React from 'react';
import { AlertTriangle, X } from 'lucide-react';
import { useUser } from '../context/UserContext';
import ReactivationModal from './ReactivationModal';

const PausedWarningBanner: React.FC = () => {
    const { user } = useUser();
    const [dismissed, setDismissed] = React.useState(false);
    const [showModal, setShowModal] = React.useState(false);

    if (dismissed) return null;

    const hasStripeCustomer = !!(user as any)?.stripeCustomerId ||
        !!(user as any)?.subscription?.stripeCustomerId;

    return (
        <>
            <div className="bg-gradient-to-r from-amber-500 to-orange-500 text-white px-4 py-2.5 flex items-center justify-between gap-3 w-full shadow-md z-50 relative">
                <div className="flex items-center gap-2 flex-1 min-w-0">
                    <AlertTriangle size={16} className="shrink-0" />
                    <span className="text-sm font-medium truncate">
                        Assinatura pausada — regularize para continuar recebendo.
                    </span>
                </div>
                <div className="flex items-center gap-2 shrink-0">
                    <button
                        onClick={() => setShowModal(true)}
                        className="px-3 py-1 bg-white text-amber-600 rounded-full font-bold text-[10px] uppercase tracking-wider hover:bg-amber-50 transition-colors shadow-sm whitespace-nowrap"
                    >
                        Reativar
                    </button>
                    <button
                        onClick={() => setDismissed(true)}
                        className="p-1 hover:bg-white/20 rounded-full transition-colors"
                        aria-label="Fechar"
                    >
                        <X size={14} />
                    </button>
                </div>
            </div>

            {showModal && (
                <ReactivationModal
                    currentPlan={user?.subscription?.plan ?? null}
                    currentCycle={user?.subscription?.frequency ?? null}
                    hasStripeCustomer={hasStripeCustomer}
                    onClose={() => setShowModal(false)}
                />
            )}
        </>
    );
};

export default PausedWarningBanner;
