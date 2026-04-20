import React from 'react';
import { CreditCard, MapPin, RefreshCw, AlertCircle, CheckCircle2, PauseCircle, Download, Clock, Sparkles } from 'lucide-react';
import { SubscriptionStatus, SubscriptionFrequency } from '../types';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';
import OnboardingModal from './OnboardingModal';
import ReactivationModal from './ReactivationModal';

const SubscriptionManagement: React.FC = () => {
  const { user, updateUser, refreshData } = useUser();
  const onUpdateUser = updateUser;
  const statusColors = {
    [SubscriptionStatus.ACTIVE]: 'bg-green-100 text-green-700',
    [SubscriptionStatus.PAUSED]: 'bg-amber-100 text-amber-700',
    [SubscriptionStatus.CANCELLED]: 'bg-red-100 text-red-700',
  };

  const [invoices, setInvoices] = React.useState<any[]>([]);

  React.useEffect(() => {
    // Sync verification with Stripe on mount
    if (user.id) {
      refreshData();

      // 1. Sync Subscription Details
      supabase.functions.invoke('stripe-proxy', { body: { action: 'sync_subscription' } })
        .then(({ error }) => {
          if (!error) refreshData();
        });

      // 2. Fetch Invoices
      supabase.functions.invoke('stripe-proxy', { body: { action: 'get_invoices' } })
        .then(({ data, error }) => {
          if (!error && data?.invoices) {
            setInvoices(data.invoices);
          }
        });
    }
  }, [user.id]);

  const statusIcons = {
    [SubscriptionStatus.ACTIVE]: <CheckCircle2 size={16} />,
    [SubscriptionStatus.PAUSED]: <PauseCircle size={16} />,
    [SubscriptionStatus.CANCELLED]: <AlertCircle size={16} />,
  };

  const handleToggleAutoRenew = () => {
    if (user.subscription) {
      onUpdateUser({
        ...user,
        subscription: {
          ...user.subscription,
          autoRenew: !user.subscription.autoRenew
        }
      });
    }
  };

  const handleManageSubscription = async () => {
    if (user.subscription?.status === SubscriptionStatus.PAUSED && !user.onboardingCompleted) {
      alert("Por favor, preencha o seu DNA de Estilo antes de reativar sua assinatura.");
      setShowEditStyleModal(true);
      return;
    }

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

  const handleChangeFrequency = (freq: SubscriptionFrequency) => {
    handleManageSubscription();
  };

  /* Style Editing State */
  const [showEditStyleModal, setShowEditStyleModal] = React.useState(false);

  /* Reactivation Modal */
  const [showReactivationModal, setShowReactivationModal] = React.useState(false);

  // Detect if user needs reactivation: PAUSED or no stripe customer at all
  const hasStripeCustomer = !!(user as any).subscription?.stripeCustomerId ||
    !!(user as any).stripeCustomerId;
  const needsReactivation =
    user.subscription?.status === SubscriptionStatus.PAUSED ||
    user.subscription?.status === SubscriptionStatus.CANCELLED ||
    (!user.subscription?.status && user.subscription?.plan); // manual lead with plan but no status

  const handleOpenReactivation = () => {
    setShowReactivationModal(true);
  };

  /* Address Editing State */
  const [isEditingAddress, setIsEditingAddress] = React.useState(false);
  const [addressForm, setAddressForm] = React.useState(user.address);

  // Update form when user data loads
  React.useEffect(() => {
    if (user.address) setAddressForm(user.address);
  }, [user.address]);

  const handleSaveAddress = async () => {
    try {
      await onUpdateUser({ address: addressForm });
      setIsEditingAddress(false);
      alert('Endereço atualizado com sucesso!');
    } catch (error) {
      console.error("Erro ao salvar endereço:", error);
      alert('Erro ao atualizar endereço. Tente novamente.');
    }
  };

  return (
    <div className="space-y-8 animate-fadeIn relative">
      <header>
        <h1 className="text-3xl font-serif text-analux-primary">Gestão de Assinatura</h1>
        <p className="text-gray-500 text-sm">Controle seus planos, pagamentos e dados de entrega.</p>
      </header>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Status Card */}
        <div className="lg:col-span-2 space-y-6">
          <div className="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <div className="flex flex-col md:flex-row justify-between items-start mb-8 gap-4">
              <div>
                <span className={`inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider ${user.subscription?.status ? statusColors[user.subscription.status] : 'bg-gray-100 text-gray-500'}`}>
                  {user.subscription?.status ? statusIcons[user.subscription.status] : <AlertCircle size={16} />}
                  {user.subscription?.status === SubscriptionStatus.ACTIVE ? 'Assinatura Ativa' :
                    user.subscription?.status === SubscriptionStatus.PAUSED ? 'Assinatura Pausada' :
                      user.subscription?.status === SubscriptionStatus.CANCELLED ? 'Assinatura Cancelada' :
                        'Nenhuma Assinatura'}
                </span>
                <h2 className="text-2xl font-serif text-analux-primary mt-4">Plano {user.subscription?.plan || '...'}</h2>
                <p className="text-gray-400 text-sm">Assinante desde {user.subscription?.memberSince || '-'}</p>
              </div>
              <div className="bg-analux-contrast/50 p-4 rounded-2xl min-w-[150px]">
                <p className="text-[10px] text-gray-400 uppercase font-bold">Próxima Cobrança</p>
                <p className="text-xl font-bold text-analux-primary">
                  {(() => {
                    const t = (user.subscription?.tier_code || user.subscription?.plan || '').toLowerCase();
                    const isSemi = user.subscription?.frequency === 'semiannual';
                    if (t === 'essencial') return isSemi ? 'R$ 169/mês' : 'R$ 189/mês';
                    if (t === 'signature' || t === 'premium') return isSemi ? 'R$ 219/mês' : 'R$ 239/mês';
                    return 'R$ --';
                  })()}
                </p>
                <p className="text-xs text-analux-secondary font-medium">em {user.subscription?.nextBoxDate || '-'}</p>
              </div>
            </div>

            {/* Renovation Preferences */}
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6 py-6 border-t border-gray-50">
              <div>
                <h4 className="text-xs font-bold text-analux-primary uppercase tracking-widest mb-3 flex items-center gap-2">
                  <Clock size={14} className="text-analux-secondary" /> Frequência de Renovação
                </h4>
                <div className="flex bg-analux-contrast p-1 rounded-xl">
                  {Object.values(SubscriptionFrequency).map((freq) => (
                    <button
                      key={freq}
                      onClick={() => handleChangeFrequency(freq)}
                      className={`flex-1 py-2 text-xs font-bold rounded-lg transition-all ${user.subscription?.frequency === freq
                        ? 'bg-white text-analux-primary shadow-sm'
                        : 'text-gray-400 hover:text-analux-primary'
                        }`}
                    >
                      {freq}
                    </button>
                  ))}
                </div>
              </div>
              <div className="flex flex-col justify-end">
                <div className="flex items-center justify-between p-3 bg-analux-contrast rounded-xl">
                  <span className="text-xs font-bold text-analux-primary">Renovação Automática</span>
                  <button
                    onClick={handleManageSubscription} // Redirect to portal to manage cancel/renew
                    className={`w-12 h-6 rounded-full transition-colors relative ${user.subscription?.autoRenew ? 'bg-green-500' : 'bg-gray-300'}`}
                  >
                    <div className={`absolute top-1 w-4 h-4 bg-white rounded-full transition-all ${user.subscription?.autoRenew ? 'left-7' : 'left-1'}`} />
                  </button>
                </div>
              </div>
            </div>

            <div className="flex flex-wrap gap-4 pt-6 border-t border-gray-50">
              {user.subscription?.status === SubscriptionStatus.PAUSED ? (
                // PAUSED: show reactivation banner + button
                <>
                  <div className="w-full bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                    <AlertCircle size={18} className="text-amber-500 shrink-0 mt-0.5" />
                    <div>
                      <p className="text-sm font-bold text-amber-700">Assinatura pausada por falta de pagamento</p>
                      <p className="text-xs text-amber-600 mt-0.5">Reative escolhendo seu plano e realizando um novo pagamento.</p>
                    </div>
                  </div>
                  <button
                    onClick={handleOpenReactivation}
                    className="w-full py-4 bg-amber-500 text-white rounded-xl text-sm font-bold hover:bg-amber-600 transition-all flex items-center justify-center gap-2 shadow-lg"
                  >
                    <RefreshCw size={18} />
                    Reativar Assinatura Agora
                  </button>
                </>
              ) : !user.subscription?.status || !user.subscription?.plan ? (
                // LEAD MANUAL or no subscription — prompt to activate
                <>
                  <div className="w-full bg-analux-secondary/10 border border-analux-secondary/30 rounded-2xl p-4 flex items-start gap-3">
                    <Sparkles size={18} className="text-analux-secondary shrink-0 mt-0.5" />
                    <div>
                      <p className="text-sm font-bold text-analux-primary">Você ainda não tem uma assinatura ativa</p>
                      <p className="text-xs text-gray-500 mt-0.5">Escolha seu plano e comece a receber suas boxes exclusivas.</p>
                    </div>
                  </div>
                  <button
                    onClick={handleOpenReactivation}
                    className="w-full py-4 bg-analux-primary text-white rounded-xl text-sm font-bold hover:bg-analux-dark transition-all flex items-center justify-center gap-2 shadow-lg"
                  >
                    <CreditCard size={18} />
                    Ativar Assinatura
                  </button>
                </>
              ) : (
                // ACTIVE: normal controls
                <>
                  <button
                    onClick={handleManageSubscription}
                    className="flex-1 min-w-[140px] py-3 bg-analux-primary text-white rounded-xl text-sm font-semibold hover:bg-analux-dark transition-all flex items-center justify-center gap-2"
                  >
                    Gerenciar Assinatura
                  </button>
                  <button
                    onClick={handleManageSubscription}
                    className="flex-1 min-w-[140px] py-3 border border-red-200 text-red-500 rounded-xl text-sm font-semibold hover:bg-red-50 transition-all"
                  >
                    Cancelar Plano
                  </button>
                </>
              )}
            </div>
          </div>

          <div className="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 relative">
            <div className="flex justify-between items-center mb-6">
              <h3 className="text-lg font-serif text-analux-primary flex items-center gap-2">
                <MapPin size={20} className="text-analux-secondary" /> Endereço de Entrega
              </h3>
              <button
                onClick={() => setIsEditingAddress(true)}
                className="text-sm text-analux-secondary font-medium hover:underline"
              >
                Editar
              </button>
            </div>
            <div className="p-4 bg-analux-contrast rounded-2xl border border-analux-secondary/10">
              {user.address.street ? (
                <>
                  <p className="text-sm text-analux-primary font-medium">{user.address.street}, {user.address.number}</p>
                  <p className="text-xs text-gray-500">{user.address.neighborhood} - {user.address.city}, {user.address.state}</p>
                  <p className="text-xs text-gray-400 mt-1">CEP: {user.address.zipCode}</p>
                </>
              ) : (
                <p className="text-sm text-gray-400 italic">Nenhum endereço cadastrado.</p>
              )}
            </div>
          </div>

          {/* STYLE DNA CARD */}
          <div className="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
              <h3 className="text-lg font-serif text-analux-primary flex items-center gap-2 mb-1">
                <Sparkles size={20} className="text-analux-secondary" /> Meu DNA de Estilo
              </h3>
              <p className="text-xs text-gray-500">
                {user.styleProfile?.styles?.length ? user.styleProfile.styles.join(', ') : 'Perfil não preenchido'}
              </p>
            </div>
            <button
              onClick={() => setShowEditStyleModal(true)}
              className="px-6 py-3 bg-analux-contrast text-analux-secondary font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-analux-secondary hover:text-white transition-all"
            >
              {user.onboardingCompleted ? 'Editar Perfil' : 'Preencher Agora'}
            </button>
          </div>

          {/* Upgrade Options Section */}
          <div className="space-y-6">
            <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
              <Sparkles size={20} className="text-analux-secondary" /> Viva Experiências Superiores
            </h3>
            <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
              {[
                {
                  id: 'signature',
                  name: 'Signature',
                  desc: 'A experiência superior da nossa curadoria.',
                  monthly: { price: '239', perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Curadoria Superior'] }
                }
              ].filter(p => !['signature', 'premium'].includes((user.subscription?.tier_code || user.subscription?.plan || '').toLowerCase())).map((plan) => (
                <div key={plan.name} className="bg-white rounded-[32px] p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all group relative overflow-hidden">
                  <div className="absolute top-0 right-0 w-24 h-24 bg-analux-secondary/5 rounded-full -mr-12 -mt-12 group-hover:scale-150 transition-transform duration-700"></div>

                  <div className="relative z-10">
                    <h4 className="text-lg font-serif text-analux-primary mb-1">Plano {plan.name}</h4>
                    <p className="text-xs text-gray-500 mb-4">{plan.desc}</p>

                    <div className="flex items-baseline gap-1 mb-4">
                      <span className="text-2xl font-serif text-analux-primary">R$ {plan.monthly.price}</span>
                      <span className="text-[10px] text-gray-400">/mês</span>
                    </div>

                    <div className="space-y-2 mb-6">
                      {plan.monthly.perks.map((perk, i) => (
                        <div key={i} className="flex items-center gap-2">
                          <CheckCircle2 size={12} className="text-analux-secondary" />
                          <span className="text-[10px] font-medium text-gray-600">{perk}</span>
                        </div>
                      ))}
                    </div>

                    <button
                      onClick={() => alert(`O upgrade para o plano ${plan.name} estará disponível em breve!`)}
                      className="w-full py-3 bg-white border border-analux-primary/10 text-analux-primary rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-analux-primary hover:text-white transition-all shadow-sm"
                    >
                      Fazer Upgrade
                    </button>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        {/* Payment & Billing */}
        <div className="space-y-6">
          <div className="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <h3 className="text-lg font-serif text-analux-primary flex items-center gap-2 mb-6">
              <CreditCard size={20} className="text-analux-secondary" /> Pagamento
            </h3>
            <div className="flex items-center gap-4 p-4 border border-gray-100 rounded-2xl mb-4">
              <div className="w-12 h-8 bg-gray-100 rounded flex items-center justify-center font-bold text-gray-500 text-[10px]">
                {user.billing.brand ? user.billing.brand.toUpperCase() : 'CC'}
              </div>
              <div>
                <p className="text-sm font-semibold text-analux-primary">•••• •••• •••• {user.billing.lastFour || '----'}</p>
                <p className="text-[10px] text-gray-400 uppercase">
                  {user.billing.expiry ? `Expira em ${user.billing.expiry}` : 'Sem cartão salvo'}
                </p>
              </div>
            </div>
            <button
              onClick={handleManageSubscription}
              className="w-full py-3 text-xs font-bold text-analux-secondary uppercase tracking-widest border border-analux-secondary/20 rounded-xl hover:bg-analux-secondary/5"
            >
              Trocar Cartão (Via Stripe)
            </button>
          </div>

          <div className="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100">
            <h3 className="text-lg font-serif text-analux-primary mb-6">Faturas Recentes</h3>
            <div className="space-y-4">
              {invoices.length > 0 ? (
                invoices.map((inv) => (
                  <div key={inv.id} className="flex items-center justify-between p-3 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition-colors rounded-lg">
                    <div>
                      <p className="text-sm font-bold text-gray-700">R$ {inv.amount}</p>
                      <p className="text-[10px] text-gray-400">{inv.date}</p>
                    </div>
                    {inv.pdf && (
                      <a href={inv.pdf} target="_blank" rel="noreferrer" className="bg-analux-contrast p-2 rounded-full text-analux-secondary hover:bg-analux-secondary hover:text-white transition-all">
                        <Download size={14} />
                      </a>
                    )}
                  </div>
                ))
              ) : (
                <div className="text-center py-4">
                  <p className="text-gray-400 text-xs italic">Nenhuma fatura encontrada.</p>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Address Edit Modal */}
      {isEditingAddress && (
        <div className="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
          <div className="bg-white rounded-3xl p-8 max-w-lg w-full shadow-2xl animate-fadeIn">
            <h3 className="text-2xl font-serif text-analux-primary mb-6">Editar Endereço</h3>

            <div className="grid grid-cols-2 gap-4">
              <div className="col-span-2">
                <label className="text-xs font-bold text-gray-400 uppercase">CEP</label>
                <input
                  type="text"
                  value={addressForm.zipCode}
                  onChange={e => setAddressForm({ ...addressForm, zipCode: e.target.value })}
                  className="w-full p-3 bg-gray-50 rounded-xl border border-gray-100 focus:outline-none focus:border-analux-secondary"
                />
              </div>
              <div className="col-span-2">
                <label className="text-xs font-bold text-gray-400 uppercase">Rua</label>
                <input
                  type="text"
                  value={addressForm.street}
                  onChange={e => setAddressForm({ ...addressForm, street: e.target.value })}
                  className="w-full p-3 bg-gray-50 rounded-xl border border-gray-100 focus:outline-none focus:border-analux-secondary"
                />
              </div>
              <div>
                <label className="text-xs font-bold text-gray-400 uppercase">Número</label>
                <input
                  type="text"
                  value={addressForm.number}
                  onChange={e => setAddressForm({ ...addressForm, number: e.target.value })}
                  className="w-full p-3 bg-gray-50 rounded-xl border border-gray-100 focus:outline-none focus:border-analux-secondary"
                />
              </div>
              <div>
                <label className="text-xs font-bold text-gray-400 uppercase">Bairro</label>
                <input
                  type="text"
                  value={addressForm.neighborhood}
                  onChange={e => setAddressForm({ ...addressForm, neighborhood: e.target.value })}
                  className="w-full p-3 bg-gray-50 rounded-xl border border-gray-100 focus:outline-none focus:border-analux-secondary"
                />
              </div>
              <div>
                <label className="text-xs font-bold text-gray-400 uppercase">Cidade</label>
                <input
                  type="text"
                  value={addressForm.city}
                  onChange={e => setAddressForm({ ...addressForm, city: e.target.value })}
                  className="w-full p-3 bg-gray-50 rounded-xl border border-gray-100 focus:outline-none focus:border-analux-secondary"
                />
              </div>
              <div>
                <label className="text-xs font-bold text-gray-400 uppercase">Estado</label>
                <input
                  type="text"
                  value={addressForm.state}
                  onChange={e => setAddressForm({ ...addressForm, state: e.target.value })}
                  className="w-full p-3 bg-gray-50 rounded-xl border border-gray-100 focus:outline-none focus:border-analux-secondary"
                />
              </div>
            </div>

            <div className="flex gap-4 mt-8">
              <button
                onClick={() => setIsEditingAddress(false)}
                className="flex-1 py-3 text-gray-500 font-bold hover:bg-gray-50 rounded-xl transition-colors"
              >
                Cancelar
              </button>
              <button
                onClick={handleSaveAddress}
                className="flex-1 py-3 bg-analux-primary text-white font-bold rounded-xl hover:bg-analux-dark transition-colors shadow-lg"
              >
                Salvar Alterações
              </button>
            </div>
          </div>
        </div>
      )}
      {showEditStyleModal && (
        <OnboardingModal
          isEditing={true}
          onClose={() => setShowEditStyleModal(false)}
          onComplete={() => {
            setShowEditStyleModal(false);
            refreshData();
            alert('Perfil de estilo atualizado com sucesso!');
          }}
        />
      )}

      {showReactivationModal && (
        <ReactivationModal
          currentPlan={user.subscription?.plan ?? null}
          currentCycle={user.subscription?.frequency ?? null}
          hasStripeCustomer={hasStripeCustomer}
          onClose={() => setShowReactivationModal(false)}
        />
      )}
    </div>
  );
};

export default SubscriptionManagement;
