import React from 'react';
import {
  X, CreditCard, Loader2, AlertCircle, CheckCircle2, Gem, ArrowRight, RefreshCw
} from 'lucide-react';
import { supabase } from '../services/supabase';

interface ReactivationModalProps {
  currentPlan: string | null;
  currentCycle: string | null;
  hasStripeCustomer: boolean;
  onClose: () => void;
}

type Cycle = 'monthly' | 'semiannual';

const PLANS = [
  {
    name: 'Essencial',
    desc: 'Experiência Analux premium de entrada',
    featured: false,
    monthly:    { price: '189', priceId: 'price_1TNjcPDWlGIoBgz1T0SlVW6H', label: 'Flex' },
    semiannual: { price: '169', priceId: 'price_1TNjcPDWlGIoBgz1kexIZLN2', label: 'Semestral (6 meses)' },
  },
  {
    name: 'Signature',
    desc: 'A experiência superior da nossa curadoria',
    featured: true,
    monthly:    { price: '239', priceId: 'price_1TNjcQDWlGIoBgz1jtpNxY9l', label: 'Flex' },
    semiannual: { price: '219', priceId: 'price_1TNjcQDWlGIoBgz1RidzBHQW', label: 'Semestral (6 meses)' },
  },
];

const CYCLE_MAP: Record<string, Cycle> = {
  'Flex': 'monthly',
  'Mensal': 'monthly',
  'Semestral': 'semiannual',
  'monthly': 'monthly',
  'semiannual': 'semiannual',
};

const ReactivationModal: React.FC<ReactivationModalProps> = ({
  currentPlan,
  currentCycle,
  hasStripeCustomer,
  onClose,
}) => {
  // Pre-select the plan they already had, or default to Essencial
  const defaultPlan = PLANS.find(p =>
    p.name === currentPlan ||
    p.name.toUpperCase() === currentPlan?.toUpperCase() ||
    currentPlan === 'BASIC' && p.name === 'Essencial' ||
    currentPlan === 'PREMIUM' && p.name === 'Premium'
  ) ?? PLANS[0];

  const defaultCycle: Cycle = (currentCycle ? CYCLE_MAP[currentCycle] : null) ?? 'monthly';

  const [selectedPlan, setSelectedPlan] = React.useState(defaultPlan.name);
  const [selectedCycle, setSelectedCycle] = React.useState<Cycle>(defaultCycle === 'monthly' || defaultCycle === 'semiannual' ? defaultCycle : 'monthly');
  const [loading, setLoading] = React.useState(false);
  const [error, setError] = React.useState<string | null>(null);

  const plan = PLANS.find(p => p.name === selectedPlan)!;
  const cycleData = plan[selectedCycle];

  const handleReactivate = async () => {
    setLoading(true);
    setError(null);

    try {
      // Always create a new checkout session (works for both cases)
      const { data, error: fnError } = await supabase.functions.invoke('stripe-proxy', {
        body: {
          action: 'create_checkout_session',
          priceId: cycleData.priceId,
          returnUrl: window.location.origin + '/dashboard',
        }
      });

      if (fnError) throw new Error(fnError.message);
      if (!data?.url) throw new Error(data?.error ?? 'Erro ao criar sessão de pagamento.');

      // Redirect to Stripe Checkout
      window.location.href = data.url;

    } catch (err: any) {
      console.error('[ReactivationModal] Error:', err);
      setError(err.message || 'Erro inesperado. Tente novamente.');
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-[120] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fadeIn">
      <div className="bg-white w-full max-w-lg rounded-[40px] shadow-2xl overflow-hidden animate-scaleIn">

        {/* Header */}
        <div className="bg-analux-primary px-8 pt-8 pb-6 relative">
          <button
            onClick={onClose}
            disabled={loading}
            className="absolute top-5 right-5 p-2 text-white/60 hover:text-white hover:bg-white/10 rounded-full transition-all"
          >
            <X size={20} />
          </button>

          <div className="flex items-center gap-3 mb-2">
            <div className="w-10 h-10 bg-amber-400/20 rounded-full flex items-center justify-center">
              <RefreshCw size={20} className="text-amber-300" />
            </div>
            <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-amber-300">
              {hasStripeCustomer ? 'Regularizar Assinatura' : 'Ativar Assinatura'}
            </span>
          </div>
          <h3 className="text-2xl font-serif text-white">
            {hasStripeCustomer
              ? 'Reativar seu plano'
              : 'Iniciar sua assinatura'}
          </h3>
          <p className="text-white/60 text-xs mt-1">
            {hasStripeCustomer
              ? 'Escolha o plano e ciclo para retomar sua experiência Analux.'
              : 'Você ainda não possui assinatura ativa. Escolha seu plano abaixo.'}
          </p>
        </div>

        <div className="p-8 space-y-6">
          {/* Plan Selector */}
          <div>
            <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 block">
              Plano
            </label>
            <div className="grid grid-cols-2 gap-3">
              {PLANS.map(p => (
                <button
                  key={p.name}
                  onClick={() => setSelectedPlan(p.name)}
                  className={`p-4 rounded-2xl border-2 text-left transition-all ${
                    selectedPlan === p.name
                      ? 'border-analux-primary bg-analux-primary/5'
                      : 'border-gray-100 hover:border-analux-primary/30'
                  }`}
                >
                  <div className="flex items-center justify-between mb-1">
                    <span className="text-xs font-bold text-analux-primary">{p.name}</span>
                    {p.featured && (
                      <Gem size={12} className="text-analux-secondary" />
                    )}
                  </div>
                  <p className="text-[10px] text-gray-400">{p.desc}</p>
                </button>
              ))}
            </div>
          </div>

          {/* Cycle Selector */}
          <div>
            <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 block">
              Ciclo de Cobrança
            </label>
            <div className="space-y-2">
              {(['monthly', 'semiannual'] as Cycle[]).map(cycle => {
                const data = plan[cycle];
                const isSelected = selectedCycle === cycle;
                const isBest = cycle === 'semiannual';
                return (
                  <button
                    key={cycle}
                    onClick={() => setSelectedCycle(cycle)}
                    className={`w-full flex items-center justify-between p-4 rounded-2xl border-2 transition-all ${
                      isSelected
                        ? 'border-analux-primary bg-analux-primary/5'
                        : 'border-gray-100 hover:border-analux-primary/30'
                    }`}
                  >
                    <div className="flex items-center gap-3">
                      <div className={`w-4 h-4 rounded-full border-2 flex items-center justify-center transition-all ${
                        isSelected ? 'border-analux-primary' : 'border-gray-300'
                      }`}>
                        {isSelected && (
                          <div className="w-2 h-2 rounded-full bg-analux-primary" />
                        )}
                      </div>
                      <div className="text-left">
                        <p className="text-sm font-bold text-analux-primary">{data.label}</p>
                        {isBest && (
                          <p className="text-[9px] text-analux-secondary font-bold uppercase tracking-wider">
                            Melhor custo-benefício
                          </p>
                        )}
                      </div>
                    </div>
                    <div className="text-right">
                      <p className="text-lg font-serif text-analux-primary">R$ {data.price}</p>
                      <p className="text-[10px] text-gray-400">/mês</p>
                    </div>
                  </button>
                );
              })}
            </div>
          </div>

          {/* Summary */}
          <div className="bg-analux-contrast rounded-2xl p-4 flex justify-between items-center">
            <div>
              <p className="text-xs text-gray-400">Plano escolhido</p>
              <p className="text-sm font-bold text-analux-primary">
                {plan.name} · {cycleData.label}
              </p>
            </div>
            <div className="text-right">
              <p className="text-xl font-serif text-analux-primary">R$ {cycleData.price}</p>
              <p className="text-[10px] text-gray-400">/mês</p>
            </div>
          </div>

          {/* Error */}
          {error && (
            <div className="flex items-center gap-2 p-3 bg-red-50 border border-red-100 rounded-xl text-red-600 text-xs animate-fadeIn">
              <AlertCircle size={14} className="shrink-0" />
              {error}
            </div>
          )}

          {/* CTA */}
          <button
            onClick={handleReactivate}
            disabled={loading}
            className="w-full py-4 bg-analux-primary text-white rounded-2xl font-bold text-xs uppercase tracking-widest shadow-lg shadow-analux-primary/20 hover:bg-analux-dark hover:scale-[1.02] transition-all flex items-center justify-center gap-2 disabled:opacity-70 disabled:cursor-wait"
          >
            {loading ? (
              <>
                Redirecionando para o pagamento...
                <Loader2 size={16} className="animate-spin" />
              </>
            ) : (
              <>
                <CreditCard size={16} />
                Ir para Pagamento Seguro
                <ArrowRight size={16} />
              </>
            )}
          </button>

          <p className="text-center text-[10px] text-gray-400 flex items-center justify-center gap-1">
            <CheckCircle2 size={10} className="text-green-500" />
            Pagamento 100% seguro via Stripe · SSL criptografado
          </p>
        </div>
      </div>
    </div>
  );
};

export default ReactivationModal;
