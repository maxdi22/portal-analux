import React from 'react';
import { useNavigate } from 'react-router-dom';
import {
  Package,
  Calendar,
  Star,
  ChevronRight,
  Gift,
  ShoppingBag,
  ArrowUpRight,
  Link as LinkIcon,
  ShieldCheck,
  History,
  Sparkles,
  Heart,
  Check,
  Crown,
  CheckCircle
} from 'lucide-react';
import { BoxStatus } from '../types';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';
import OnboardingModal from './OnboardingModal';
import ShareModal from './ShareModal';
import DeliveryTimeline from './DeliveryTimeline';
import DailyMoodCard from './DailyMoodCard';
import ReactivationModal from './ReactivationModal';

const DashboardHome: React.FC = () => {
  const { user, refreshData } = useUser();
  const navigate = useNavigate();
  const [showOnboarding, setShowOnboarding] = React.useState(false);
  const [showShareModal, setShowShareModal] = React.useState(false);
  const [showReactivationModal, setShowReactivationModal] = React.useState(false);
  // Persist referral activation
  const [isReferralActive, setIsReferralActive] = React.useState(() => {
    return localStorage.getItem('analux_referral_active') === 'true';
  });

  const hasStripeCustomer = !!(user as any).stripeCustomerId ||
    !!(user as any).subscription?.stripeCustomerId;

  React.useEffect(() => {
    // Only show if we have a loaded user (with ID) and they explicitly haven't completed onboarding
    if (user && user.id && user.onboardingCompleted === false) {
      // Small delay for better UX
      const timer = setTimeout(() => setShowOnboarding(true), 1000);
      return () => clearTimeout(timer);
    }
  }, [user]);

  const statusSteps = [
    BoxStatus.ORDERED,
    BoxStatus.PREPARING,
    BoxStatus.CURATING,
    BoxStatus.SHIPPED,
    BoxStatus.DELIVERED
  ];

  React.useEffect(() => {
    const query = new URLSearchParams(window.location.search);
    const sessionId = query.get('session_id');

    if (sessionId) {
      // VISIBLE FEEDBACK
      const statusDiv = document.createElement('div');
      statusDiv.style.position = 'fixed';
      statusDiv.style.top = '0';
      statusDiv.style.left = '0';
      statusDiv.style.width = '100%';
      statusDiv.style.backgroundColor = '#9d4edd';
      statusDiv.style.color = 'white';
      statusDiv.style.textAlign = 'center';
      statusDiv.style.padding = '10px';
      statusDiv.style.zIndex = '9999';
      statusDiv.innerText = 'Processando sua assinatura... Aguarde.';
      document.body.appendChild(statusDiv);

      console.log('Finalizing checkout...', sessionId);
      supabase.functions.invoke('stripe-proxy', {
        body: { action: 'finalize_checkout', session_id: sessionId }
      })
        .then(({ data, error }) => {
          if (!error && data?.success) {
            statusDiv.innerText = 'Sucesso! Atualizando...';
            statusDiv.style.backgroundColor = '#2ecc71';

            // Clean URL and Reaload
            window.history.replaceState({}, document.title, window.location.pathname);
            setTimeout(() => window.location.reload(), 1500);
          } else {
            console.error('Finalize failed', error, data);
            const errorMsg = error?.message || data?.error || 'Erro desconhecido';
            const errorDetails = data?.details || '';
            statusDiv.innerText = `Erro ao finalizar: ${errorMsg} ${errorDetails ? `(${errorDetails})` : ''}`;
            statusDiv.style.backgroundColor = '#e74c3c';

            // Auto-hide after 10s to not block screen forever
            setTimeout(() => statusDiv.remove(), 10000);
          }
        });
    } else if (user.email) {
      // Normal sync
      supabase.functions.invoke('stripe-proxy', { body: { action: 'sync_subscription' } })
        .then(({ error }) => { if (!error) refreshData(); });
    }
  }, [user.email]);

  return (
    <div className="space-y-5 animate-fadeIn pb-20">

      {/* ── Mobile Hero Header ── */}
      <header className="md:flex hidden flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
          <h1 className="text-3xl font-serif text-analux-primary mb-1">Olá, {user.name}</h1>
          <p className="text-gray-500 text-sm">Seu brilho Analux está a caminho.</p>
        </div>
        <div className="flex items-center gap-4 bg-white p-2 pr-6 rounded-full shadow-sm border border-analux-secondary/20">
          <img src={user.avatar} alt="Avatar" className="w-10 h-10 rounded-full border-2 border-analux-secondary object-cover" />
          <div>
            <p className="text-xs font-semibold text-analux-primary leading-tight">{user.name}</p>
            <p className="text-[10px] text-analux-secondary uppercase tracking-wider">{user.level}</p>
          </div>
        </div>
      </header>

      {/* ── Mobile-only greeting ── */}
      <div className="md:hidden flex items-center justify-between">
        <div>
          <p className="text-[11px] text-gray-400 uppercase tracking-widest font-bold">Bem-vinda de volta</p>
          <h1 className="text-2xl font-serif text-analux-primary leading-tight mt-0.5">
            {user.name.split(' ')[0]} ✨
          </h1>
        </div>
        <div className="flex items-center gap-2 bg-white rounded-2xl px-3 py-2 shadow-sm border border-gray-100">
          <img src={user.avatar} alt="Avatar" className="w-8 h-8 rounded-xl object-cover" />
          <div>
            <p className="text-[10px] font-bold text-analux-primary leading-none">{user.level}</p>
            <p className="text-[9px] text-analux-secondary uppercase tracking-wider mt-0.5">Musa</p>
          </div>
        </div>
      </div>

      {/* ── Stats Cards: horizontal scroll on mobile, grid on desktop ── */}
      <div className="-mx-4 md:mx-0 px-4 md:px-0">
        <div className="flex md:grid md:grid-cols-2 lg:grid-cols-12 gap-3 md:gap-4 overflow-x-auto pb-2 snap-x snap-mandatory no-scrollbar">

          {/* Subscription Card */}
          <div
            onClick={() => !user.subscription || user.subscription.status !== 'ACTIVE'
              ? setShowReactivationModal(true)
              : navigate('/box')}
            className={`snap-start shrink-0 w-[160px] md:w-auto lg:col-span-2 p-4 md:p-5 rounded-2xl md:rounded-3xl flex flex-col justify-between cursor-pointer active:scale-95 transition-all duration-200 min-h-[130px] md:min-h-[140px] shadow-sm ${
              user.subscription?.status === 'ACTIVE'
                ? 'bg-gradient-to-br from-analux-primary to-analux-dark text-white'
                : user.subscription?.status === 'PAUSED'
                  ? 'bg-gradient-to-br from-amber-500 to-orange-600 text-white'
                  : 'bg-white border border-gray-100'
            }`}
          >
            <div className="flex justify-between items-start">
              <div className={`w-9 h-9 flex items-center justify-center rounded-xl ${
                user.subscription?.status === 'ACTIVE'
                  ? 'bg-white/15'
                  : user.subscription?.status === 'PAUSED'
                    ? 'bg-white/20'
                    : 'bg-gray-100'
              }`}>
                <Package size={18} className={
                  user.subscription?.status ? 'text-white' : 'text-gray-400'
                } />
              </div>
              {user.subscription?.status === 'ACTIVE' && (
                <span className="text-[8px] font-bold bg-white/20 text-white px-2 py-0.5 rounded-full uppercase tracking-wider">Ativa</span>
              )}
              {user.subscription?.status === 'PAUSED' && (
                <span className="text-[8px] font-bold bg-white/20 text-white px-2 py-0.5 rounded-full uppercase tracking-wider">Pausada</span>
              )}
            </div>
            <div>
              <p className={`text-[10px] font-bold uppercase tracking-wider mb-0.5 ${
                user.subscription?.status ? 'text-white/70' : 'text-gray-400'
              }`}>Assinatura</p>
              <p className={`text-sm font-bold leading-tight ${
                user.subscription?.status ? 'text-white' : 'text-analux-primary'
              }`}>
                {user.subscription?.status === 'ACTIVE'
                  ? `Plano ${user.subscription.plan}`
                  : user.subscription?.status === 'PAUSED'
                    ? 'Toque p/ reativar'
                    : 'Toque p/ assinar'}
              </p>
            </div>
          </div>

          {/* Cashback Card */}
          <div
            onClick={() => navigate('/shop')}
            className="snap-start shrink-0 w-[160px] md:w-auto lg:col-span-3 bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-gray-50 shadow-sm flex flex-col justify-between cursor-pointer active:scale-95 transition-all min-h-[130px] md:min-h-[140px]"
          >
            <div className="flex justify-between items-start">
              <div>
                <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Cashback</span>
                {!user.isStoreConnected ? (
                  <div className="flex flex-col mt-1">
                    <p className="text-2xl font-serif text-gray-300">R$ 0</p>
                    <span className="text-[8px] text-red-400 font-bold flex items-center gap-1 mt-0.5 animate-pulse">
                      <LinkIcon size={7} /> Conectar loja
                    </span>
                  </div>
                ) : (
                  <p className="text-2xl font-serif text-analux-primary mt-1">R$ {user.cashback.toFixed(2)}</p>
                )}
              </div>
              <div className="p-2 bg-analux-secondary/10 rounded-xl text-analux-secondary">
                <ShoppingBag size={16} />
              </div>
            </div>
            <button className="text-[9px] font-bold text-analux-secondary uppercase flex items-center gap-1">
              Usar na Loja <ArrowUpRight size={9} />
            </button>
          </div>

          {/* Points Card */}
          <div className="snap-start shrink-0 w-[160px] md:w-auto lg:col-span-3 bg-white p-4 md:p-6 rounded-2xl md:rounded-3xl border border-gray-50 shadow-sm flex flex-col justify-between min-h-[130px] md:min-h-[140px]">
            <div className="flex justify-between items-start w-full">
              <div className="w-full mr-3">
                <span className="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Pontos Club</span>
                {!user.isStoreConnected ? (
                  <div className="mt-1 mb-1">
                    <p className="text-2xl font-serif text-gray-300">0</p>
                    <span className="text-[8px] text-red-400 font-bold flex items-center gap-1">
                      <LinkIcon size={7} /> Pendente
                    </span>
                  </div>
                ) : (
                  <>
                    <p className="text-2xl font-serif text-analux-primary mt-1">{user.points}</p>
                    <div className="w-full bg-gray-100 h-1 rounded-full mt-2 overflow-hidden">
                      <div className="bg-analux-secondary h-full" style={{ width: '65%' }} />
                    </div>
                  </>
                )}
                <p className="text-[8px] text-gray-400 mt-1.5 font-medium leading-tight">Troque por produtos e experiências</p>
              </div>
              <div className="p-2 bg-analux-secondary/10 rounded-xl text-analux-secondary shrink-0">
                <Crown size={16} />
              </div>
            </div>
          </div>

          {/* Mood Card — horizontal scroll only on mobile */}
          <div className="snap-start shrink-0 w-[160px] md:w-auto lg:col-span-2">
            <DailyMoodCard />
          </div>

          {/* Store Connection Card */}
          <div
            onClick={() => navigate('/shop')}
            className={`snap-start shrink-0 w-[160px] md:w-auto lg:col-span-2 p-4 md:p-5 rounded-2xl md:rounded-3xl border flex flex-col justify-between transition-all duration-500 cursor-pointer active:scale-95 min-h-[130px] md:min-h-[140px] ${
              user.isStoreConnected
                ? 'bg-analux-secondary/10 border-analux-secondary/20'
                : 'bg-white border-dashed border-gray-200'
            }`}
          >
            <div className="flex justify-between items-start">
              <div className={`w-9 h-9 flex items-center justify-center rounded-xl ${user.isStoreConnected ? 'bg-white text-analux-secondary shadow-sm' : 'bg-gray-100 text-gray-400'}`}>
                <ShoppingBag size={18} />
              </div>
              {user.isStoreConnected && <Check size={14} className="text-analux-secondary" />}
            </div>
            <div>
              <p className="text-[10px] text-gray-400 uppercase tracking-widest font-bold mb-0.5">Loja</p>
              <p className="text-sm font-bold text-analux-primary leading-tight">
                {user.isStoreConnected ? 'Conectada ✓' : 'Vincular conta'}
              </p>
            </div>
          </div>

        </div>
      </div>



      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Main Subscription Card */}
        <div className="lg:col-span-2 bg-gradient-to-br from-analux-primary via-analux-dark to-black rounded-[40px] p-10 text-white relative overflow-hidden shadow-2xl group">
          <div className="absolute top-0 right-0 w-[400px] h-[400px] bg-analux-secondary/10 rounded-full -mr-40 -mt-40 blur-[100px] group-hover:bg-analux-secondary/20 transition-all duration-700"></div>
          <div className="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div className="space-y-6">
              <span className="bg-white/10 backdrop-blur-md text-analux-secondary px-4 py-1.5 rounded-full text-[10px] font-bold tracking-[0.2em] border border-white/10 uppercase">
                Plano {user.subscription?.plan}
              </span>
              <div className="space-y-2">
                <h2 className="text-5xl font-serif">Próximo Envio</h2>
                <p className="text-analux-contrast/60 text-sm font-light">Prepare o coração para uma nova curadoria exclusiva.</p>
              </div>
              <div className="flex flex-wrap gap-6 pt-4">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10">
                    <Calendar size={18} className="text-analux-secondary" />
                  </div>
                  <div className="flex flex-col">
                    <span className="text-[10px] text-white/40 uppercase font-bold">Previsão</span>
                    <span className="text-sm font-semibold">{user.subscription?.nextBoxDate}</span>
                  </div>
                </div>
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10">
                    <Gift size={18} className="text-analux-secondary" />
                  </div>
                  <div className="flex flex-col">
                    <span className="text-[10px] text-white/40 uppercase font-bold">Vantagem</span>
                    <span className="text-sm font-semibold">Mimo VIP Disponível</span>
                  </div>
                </div>
              </div>
            </div>
            <div className="relative group cursor-pointer" onClick={() => navigate('/box')}>
              <div className="absolute inset-0 bg-analux-secondary blur-2xl opacity-20 group-hover:opacity-40 transition-opacity"></div>
              <img
                src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=300&h=300"
                className="w-48 h-48 object-cover rounded-3xl border-4 border-white/10 shadow-2xl rotate-3 group-hover:rotate-0 transition-transform duration-500"
                alt="Próxima Box"
              />
            </div>
          </div>
        </div>

        {/* Referrals Section */}
        <section className="bg-white rounded-[40px] p-8 border border-gray-50 flex flex-col justify-between shadow-sm relative overflow-hidden group">
          <div className="absolute -right-4 -top-4 opacity-[0.03] group-hover:scale-110 transition-transform duration-700">
            <ShieldCheck size={160} className="rotate-12" />
          </div>
          <div className="relative z-10">
            <div className="bg-analux-contrast w-14 h-14 rounded-2xl flex items-center justify-center text-analux-primary mb-8 shadow-inner">
              <Star size={28} />
            </div>
            <h3 className="text-2xl font-serif text-analux-primary mb-3">Indique & Brilhe</h3>
            <p className="text-gray-400 text-xs leading-relaxed mb-6">
              Você tem <b className="text-analux-primary">{user.invitesAvailable} convites</b>. Ganhe <b className="text-analux-secondary">R$ 20,00</b> de crédito para cada indicação.
            </p>

            {/* Referral Content - Revealed on Activation */}
            {isReferralActive ? (
              <div className="animate-in fade-in slide-in-from-bottom-4 duration-700">
                <div className="grid grid-cols-3 gap-2 mb-6">
                  <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                    <p className="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Cliques</p>
                    <p className="text-lg font-bold text-slate-700 leading-none">{user.referralStats?.totalClicks || 0}</p>
                  </div>
                  <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                    <p className="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Online</p>
                    <div className="flex items-center justify-center gap-1">
                      {user.referralStats?.activeNow && user.referralStats.activeNow > 0 && <span className="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>}
                      <p className="text-lg font-bold text-slate-700 leading-none">{user.referralStats?.activeNow || 0}</p>
                    </div>
                  </div>
                  <div className="bg-gray-50 rounded-xl p-3 text-center border border-gray-100">
                    <p className="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Vendas</p>
                    <p className="text-lg font-bold text-analux-secondary leading-none">{user.referralStats?.totalInvited || 0}</p>
                  </div>
                </div>

                <div className="bg-gray-50 p-3 rounded-xl border border-dashed border-gray-200 mb-6 flex justify-between items-center group/code cursor-pointer"
                  onClick={() => {
                    const code = user.referralCode || 'VIP123';
                    navigator.clipboard.writeText(code);
                    alert('Código copiado!');
                  }}>
                  <span className="text-[10px] uppercase font-bold text-gray-400 tracking-widest">Seu Código:</span>
                  <span className="text-analux-primary font-bold font-mono tracking-wider group-hover/code:text-analux-secondary transition-colors">{user.referralCode || 'VIP123'}</span>
                  <div className="p-1 bg-white rounded shadow-sm opacity-0 group-hover/code:opacity-100 transition-opacity"><Check size={12} className="text-green-500" /></div>
                </div>

                <button
                  onClick={() => setShowShareModal(true)}
                  className="w-full py-2.5 bg-analux-primary text-white rounded-xl font-bold uppercase text-[9px] tracking-widest flex items-center justify-center gap-3 hover:bg-analux-dark transition-all shadow-xl shadow-analux-primary/20 mt-auto"
                >
                  Compartilhar Link VIP
                </button>
              </div>
            ) : (
              <div className="mt-8">
                <button
                  onClick={() => {
                    // Simulate generation delay
                    const btn = document.activeElement as HTMLButtonElement;
                    if (btn) btn.innerHTML = "Gerando seu link...";
                    setTimeout(() => {
                      setIsReferralActive(true);
                      localStorage.setItem('analux_referral_active', 'true');
                    }, 800);
                  }}
                  className="w-full py-4 bg-analux-secondary text-white rounded-2xl font-bold uppercase text-[10px] tracking-widest flex items-center justify-center gap-3 hover:scale-[1.02] transition-all shadow-xl shadow-analux-secondary/20"
                >
                  <Sparkles size={16} /> Gerar Link VIP
                </button>
              </div>
            )}

          </div>
        </section>
      </div>

      {/* Onboarding Modal */}
      {showOnboarding && (
        <OnboardingModal
          onClose={() => { setShowOnboarding(false); }}
          onComplete={() => {
            setShowOnboarding(false);
            refreshData();
          }}
        />
      )}

      {/* NEW RESTORED SECTIONS */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Box History Summary */}
        <section className="lg:col-span-2 bg-white rounded-[40px] p-8 shadow-sm border border-gray-50">
          <div className="flex justify-between items-center mb-8">
            <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
              <History size={20} className="text-analux-secondary" /> Sua Jornada de Brilho
            </h3>
            <button onClick={() => navigate('/box')} className="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-analux-secondary transition-colors">Ver histórico completo</button>
          </div>
          <div className="flex gap-6 overflow-x-auto pb-4 no-scrollbar px-2">
            {/* 1. Past Boxes (History) */}
            {user.boxHistory.map((box) => (
              <div key={box.id} className="shrink-0 group cursor-pointer w-48 opacity-60 hover:opacity-100 transition-opacity">
                <div className="aspect-square rounded-3xl overflow-hidden mb-3 relative shadow-sm grayscale group-hover:grayscale-0 transition-all">
                  <img src={box.image} alt={box.name} className="w-full h-full object-cover" />
                  <div className="absolute inset-0 bg-black/10"></div>
                  <div className="absolute bottom-2 left-2 bg-white/90 backdrop-blur-md px-2 py-1 rounded-lg">
                    <span className="text-[9px] font-bold text-gray-500 uppercase tracking-wider flex items-center gap-1">
                      <CheckCircle size={10} className="text-green-500" /> Recebida
                    </span>
                  </div>
                </div>
                <p className="text-[11px] font-bold text-gray-400 group-hover:text-analux-primary transition-colors">{box.name}</p>
                <p className="text-[9px] text-gray-300 uppercase tracking-tighter">{box.date}</p>
              </div>
            ))}

            {/* 2. Current Month Box (Active/Target) */}
            <div className="shrink-0 group cursor-pointer w-48 relative" onClick={() => navigate('/box')}>
              <div className="absolute -top-3 left-1/2 -translate-x-1/2 z-20">
                <span className="bg-analux-secondary text-white text-[9px] font-bold uppercase tracking-widest px-3 py-1 rounded-full shadow-md animate-bounce">
                  Sua Edição
                </span>
              </div>
              <div className="aspect-square rounded-3xl overflow-hidden mb-3 relative shadow-xl ring-4 ring-analux-secondary/20 scale-105 transition-transform">
                <img
                  src={user.subscription?.currentBox?.coverImage || "https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&q=80&w=300&h=300"}
                  alt="Edição Atual"
                  className="w-full h-full object-cover"
                />
                {/* Progress Overlay */}
                <div className="absolute bottom-0 left-0 w-full p-3 bg-gradient-to-t from-black/80 to-transparent">
                  <p className="text-white text-xs font-serif mb-1">{user.subscription?.currentBox?.name || "Aurora Floral"}</p>
                  <div className="w-full bg-white/20 h-1 rounded-full overflow-hidden">
                    <div className="bg-analux-secondary h-full w-[60%]"></div>
                  </div>
                  <p className="text-[8px] text-white/80 mt-1 uppercase tracking-wider text-right">
                    {user.subscription?.currentBox?.status === 'PLANNING' ? 'Planejamento' :
                      user.subscription?.currentBox?.status === 'PRODUCTION' ? 'Em Produção' :
                        user.subscription?.currentBox?.status || "Em Preparação"}
                  </p>
                </div>
              </div>
            </div>

            {/* 3. Next Box (The "Vem Aí") */}
            {(() => {
              const today = new Date();
              const currentDay = today.getDate();
              const isEarlyMonth = currentDay <= 15; // Cutoff rule

              return (
                <div className="shrink-0 w-48 relative">
                  <div className={`aspect-square rounded-3xl overflow-hidden mb-3 relative border-2 border-dashed border-gray-200 bg-gray-50 flex items-center justify-center
                            ${isEarlyMonth ? 'cursor-help' : 'cursor-wait'}
                        `}>
                    <img
                      src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?auto=format&fit=crop&q=80&w=300&h=300"
                      alt="Spoiler"
                      className={`w-full h-full object-cover transition-all duration-700
                                    ${isEarlyMonth ? 'blur-xl opacity-80 scale-110' : 'opacity-40 grayscale'}
                                `}
                    />

                    <div className="absolute inset-0 flex flex-col items-center justify-center p-4 text-center z-10">
                      {isEarlyMonth ? (
                        <>
                          <Sparkles size={24} className="text-analux-secondary mb-2 animate-pulse" />
                          <p className="font-serif text-gray-800 text-lg">Vem aí...</p>
                          <p className="text-[9px] text-gray-500 uppercase tracking-widest mt-1">Spoiler dia 15</p>
                        </>
                      ) : (
                        <>
                          <Calendar size={24} className="text-gray-400 mb-2" />
                          <p className="font-serif text-gray-400 text-lg">Dezembro</p>
                          <p className="text-[9px] text-gray-400 uppercase tracking-widest mt-1">Em breve</p>
                        </>
                      )}
                    </div>
                  </div>
                  <p className="text-[11px] font-bold text-gray-300 text-center uppercase tracking-widest">
                    {isEarlyMonth ? 'Próxima Edição' : 'Futura Edição'}
                  </p>
                </div>
              );
            })()}
          </div>
        </section>

        {/* Style DNA Summary Card */}
        <section className="bg-analux-contrast rounded-[40px] p-8 border border-analux-secondary/10 relative overflow-hidden group">
          <div className="absolute top-0 right-0 p-4">
            <Sparkles size={40} className="text-analux-secondary/10 group-hover:rotate-12 transition-transform" />
          </div>
          <div className="relative z-10 space-y-6">
            <h3 className="text-xl font-serif text-analux-primary">Seu DNA de Estilo</h3>
            <div className="space-y-4">
              <div>
                <p className="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Metais Preferidos</p>
                <div className="flex flex-wrap gap-2">
                  {user.stylePrefs.metals.map(metal => (
                    <span key={metal} className="px-3 py-1 bg-white rounded-full text-[9px] font-bold text-analux-primary shadow-sm border border-gray-50">{metal}</span>
                  ))}
                </div>
              </div>
              <div>
                <p className="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2">Vibe Atual</p>
                <div className="flex flex-wrap gap-2">
                  {user.stylePrefs.vibe.map(v => (
                    <span key={v} className="px-3 py-1 bg-analux-secondary text-white rounded-full text-[9px] font-bold shadow-md">{v}</span>
                  ))}
                </div>
              </div>
            </div>
            <button
              onClick={() => navigate('/style')}
              className="w-full py-3 bg-white text-analux-primary border border-analux-secondary/20 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-analux-primary hover:text-white transition-all shadow-sm"
            >
              Refinar com Style AI
            </button>
          </div>
        </section>
      </div>

      {/* Box Status Tracker (Moved to Bottom) */}
      {user.subscription?.status === 'ACTIVE' ? (
        <section className="bg-white rounded-[40px] p-8 shadow-sm border border-gray-50">

          <div className="flex justify-between items-center mb-10">
            <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
              <Package size={22} className="text-analux-secondary" /> Jornada da sua Box
            </h3>
            <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-[0.2em] bg-analux-secondary/10 px-4 py-1.5 rounded-full">
              {user.subscription?.currentBox?.month || 'Edição Atual'}
            </span>
          </div>

          <div className="relative pt-4 pb-2 max-w-4xl mx-auto">
            {user.subscription?.currentBox ? (
              <DeliveryTimeline
                // Logic: Cycle starts on subscription day of current/last month
                cycleStartDate={(() => {
                  // Demo: Assume subscription day is 10th
                  const now = new Date();
                  const subDay = 10;
                  let cycleDate = new Date(now.getFullYear(), now.getMonth(), subDay);
                  if (now.getDate() < subDay) {
                    // If today is 5th, cycle was last month 10th
                    cycleDate.setMonth(cycleDate.getMonth() - 1);
                  }
                  return cycleDate.toISOString();
                })()}
                status={user.subscription.isDelivered ? 'delivered' : undefined}
                onConfirmReceipt={() => {
                  // In a real app, this would verify with backend
                  alert("Que bom que chegou! Aproveite seu ritual ✨");
                  // Ideally trigger a context update or mock update here
                }}
              />
            ) : (
              <p className="text-center text-gray-400 py-8">Nenhuma box ativa no momento.</p>
            )}
          </div>
        </section>
      ) : (
        <section className="bg-amber-50 rounded-[40px] p-8 border border-amber-100 flex items-center justify-between">
          <div className="flex items-center gap-4">
            <div className="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-500">
              <Package size={24} />
            </div>
            <div>
              <h3 className="text-lg font-serif text-amber-700">Assinatura Pausada</h3>
              <p className="text-xs text-amber-600">Regularize para voltar a receber suas boxes exclusivas.</p>
            </div>
          </div>
          <button
            onClick={() => setShowReactivationModal(true)}
            className="px-6 py-3 bg-amber-500 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-amber-600 transition-all shadow-md"
          >
            Reativar Agora
          </button>
        </section>
      )}
      {/* Share Modal */}
      {showShareModal && (
        <ShareModal
          referralCode={user.referralCode || 'VIP123'}
          onClose={() => setShowShareModal(false)}
        />
      )}

      {/* Reactivation Modal */}
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

export default DashboardHome;
