
import React, { useState } from 'react';
import {
  Sparkles,
  ArrowRight,
  CheckCircle2,
  Heart,
  Star,
  ShieldCheck,
  Zap,
  Gift,
  ShoppingBag,
  Users,
  Instagram,
  ChevronDown,
  RefreshCw,
  Plus,
  Minus,
  Gem
} from 'lucide-react';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';
import AuthModal from './AuthModal';
import SubscriptionReviewModal from './SubscriptionReviewModal';
import { useParams } from 'react-router-dom';

const LandingPage: React.FC = () => {
  const { setIsMember, isMember } = useUser();
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const [billingCycle, setBillingCycle] = useState<'monthly' | 'quarterly' | 'semiannual'>('monthly');
  const [isLoginModalOpen, setIsLoginModalOpen] = useState<boolean | 'signup'>(false);

  // State for Review Modal (Plan details)
  const [reviewModalData, setReviewModalData] = useState<{
    planName: string;
    cycle: 'monthly' | 'quarterly' | 'semiannual';
    price: string;
    perks: string[];
  } | null>(null);

  const [pendingSubscription, setPendingSubscription] = useState<{ plan: string, cycle: 'monthly' | 'quarterly' | 'semiannual' } | null>(null);

  // Restore state on mount - Enabled for better UX
  React.useEffect(() => {
    const savedPlan = localStorage.getItem('sub_draft_active_plan');
    if (savedPlan) {
      try {
        const parsed = JSON.parse(savedPlan);
        setReviewModalData(parsed);
      } catch (e) {
        localStorage.removeItem('sub_draft_active_plan');
      }
    }
  }, []);

  /* New Logic for Referral Tracking */
  const { code } = useParams();

  React.useEffect(() => {
    if (code) {
      // 1. Store locally for Signup attribution
      localStorage.setItem('analux_referral_source', code);

      // 2. Increment Click Count (server-side function safer)
      const incrementClick = async () => {
        // Attempt to update by finding profile with this code
        // Since we can't easily write to other profiles with RLS, we ideally use an RPC.
        // For now, let's try calling an RPC we will create.
        await supabase.rpc('increment_referral_click', { ref_code: code });
      };
      incrementClick();

      // 3. Realtime Presence (I am watching this code)
      const channel = supabase.channel(`referral:${code}`, {
        config: {
          presence: {
            key: 'visitor',
          },
        },
      });

      channel.subscribe(async (status) => {
        if (status === 'SUBSCRIBED') {
          await channel.track({ online_at: new Date().toISOString() });
        }
      });

      return () => {
        supabase.removeChannel(channel);
      }
    }
  }, [code]);

  const onJoin = () => {
    setIsLoginModalOpen(true);
  };

  const scrollToPlans = () => {
    document.getElementById('planos')?.scrollIntoView({ behavior: 'smooth' });
  };

  const executeCheckout = async (planName: string, cycle: 'monthly' | 'quarterly' | 'semiannual') => {
    // Map plans to Stripe Price IDs
    const priceMap: any = {
      'Essencial-monthly': 'price_1StgSxDiv2beAnVS5JvJEGRr', // TEST ID PROVIDED BY USER
      // Add others here later
    };

    const key = `${planName}-${cycle}`;
    const priceId = priceMap[key];

    if (!priceId) {
      alert('Este plano ainda não está configurado no ambiente de teste. Tente o Essencial Mensal.');
      return;
    }

    try {
      const { data, error } = await supabase.functions.invoke('stripe-proxy', {
        body: {
          action: 'create_checkout_session',
          priceId: priceId,
          returnUrl: window.location.origin + '/dashboard' // or /box
        }
      });

      if (error) throw error;
      if (data?.url) {
        window.location.href = data.url;
      }
    } catch (err) {
      console.error('Erro no checkout:', err);
      alert('Erro ao iniciar checkout. Tente novamente.');
    }
  };

  const handleSubscribe = async (plan: any, cycle: 'monthly' | 'quarterly' | 'semiannual') => {
    // Helper to extract correct price/perks
    const details = plan[cycle];

    if (!isMember) {
      // Store intention for AFTER login
      setPendingSubscription({ plan: plan.name, cycle });
      setIsLoginModalOpen('signup');
      return;
    }

    // If logged in, show review modal
    const data = {
      planName: plan.name,
      cycle,
      price: details.price,
      perks: details.perks
    };
    // @ts-ignore
    setReviewModalData(data);
    localStorage.setItem('sub_draft_active_plan', JSON.stringify(data));
  };

  const faqItems = [
    {
      question: "Como as peças são escolhidas para a minha Box?",
      answer: "Nossa curadoria utiliza o seu 'DNA de Estilo' preenchido no momento da adesão. Levamos em conta suas preferências de banho (Ouro ou Ródio), estilo (Minimalista, Romântico, etc) e as tendências do mês para garantir que cada box seja um reflexo da sua personalidade."
    },
    {
      question: "Qual a qualidade e garantia das semijoias?",
      answer: "Todas as peças Analux possuem banho de alta qualidade (Ouro 18k ou Ródio Branco), são hipoalergênicas e possuem garantia de 1 ano. Nosso padrão de acabamento é de joalheria, garantindo brilho e durabilidade excepcionais."
    },
    {
      question: "Posso cancelar ou pausar minha assinatura quando quiser?",
      answer: "Sim! Não acreditamos em fidelidade forçada. Você pode pausar sua box por um mês ou cancelar a renovação diretamente pelo seu Portal Analux com apenas um clique, sem burocracias ou taxas escondidas."
    },
    {
      question: "E se eu não gostar de alguma peça enviada?",
      answer: "Embora nossa taxa de assertividade seja de 98%, oferecemos um sistema de troca facilitada para assinantes. Você tem até 7 dias após o recebimento para solicitar a troca de uma peça da box por créditos na loja oficial."
    },
    {
      question: "Como funciona o frete da Box?",
      answer: "O frete é calculado no checkout. Assinantes do plano Premium possuem Frete Grátis para todo o Brasil. Para os demais planos, temos tarifas fixas reduzidas graças às nossas parcerias logísticas exclusivas."
    }
  ];

  const plans = [
    {
      name: 'Essencial',
      desc: 'Receba de 2 a 3 semijoias por mês!',
      featured: false,
      monthly: {
        price: '129,90',
        perks: ['2 a 3 Joias Exclusivas', 'Box Temática Mensal', 'Renovação Automática', 'Cashback 2%']
      },
      quarterly: {
        price: '119,90',
        perks: ['2 a 3 Joias Exclusivas', 'Box Temática Mensal', 'Renovação Trimestral', 'Cashback 3%']
      },
      semiannual: {
        price: '109,90',
        perks: ['2 a 3 Joias Exclusivas', 'Box Temática Mensal', 'Renovação Semestral', 'Cashback 5%']
      }
    },
    {
      name: 'Premium',
      desc: 'Receba de 4 a 5 semijoias por mês!',
      featured: true,
      monthly: {
        price: '179,90',
        perks: ['4 a 5 Joias Exclusivas', 'Box Temática Mensal', 'Mimo Extra', 'Cashback 5%']
      },
      quarterly: {
        price: '169,90',
        perks: ['4 a 5 Joias Exclusivas', 'Box Temática Mensal', 'Mimo Extra', 'Cashback 8%']
      },
      semiannual: {
        price: '159,90',
        perks: ['4 a 5 Joias Exclusivas', 'Box Temática Mensal', 'Mimo Extra', 'Cashback 10%']
      }
    }
  ];

  return (
    <div className="bg-analux-contrast text-analux-primary selection:bg-analux-secondary selection:text-white relative">

      {/* Review Modal */}
      {reviewModalData && (
        <SubscriptionReviewModal
          planName={reviewModalData.planName}
          // @ts-ignore
          cycle={reviewModalData.cycle}
          price={reviewModalData.price}
          perks={reviewModalData.perks}
          onClose={() => {
            setReviewModalData(null);
            localStorage.removeItem('sub_draft_active_plan');
          }}
          onConfirm={() => {
            // Proceed to Checkout
            localStorage.removeItem('sub_draft_active_plan'); // Clear draft so it doesn't reopen on return
            return executeCheckout(reviewModalData.planName, reviewModalData.cycle);
          }}
        />
      )}

      {/* Login Modal */}
      {isLoginModalOpen && (
        <AuthModal
          onClose={() => setIsLoginModalOpen(false)}
          initialMode={isLoginModalOpen === 'signup' ? 'REGISTER' : 'LOGIN'}
          customWelcomeMessage={isLoginModalOpen === 'signup' ? 'Crie seu login e senha para prosseguir para a contratação.' : undefined}
          onAuthSuccess={() => {
            if (pendingSubscription) {
              // Instead of executing checkout, Show Review Modal
              // Find details
              const selectedPlan = plans.find(p => p.name === pendingSubscription.plan);
              if (selectedPlan) {
                // @ts-ignore
                const details = selectedPlan[pendingSubscription.cycle];
                setReviewModalData({
                  planName: selectedPlan.name,
                  cycle: pendingSubscription.cycle,
                  price: details.price,
                  perks: details.perks
                });
              }
              setPendingSubscription(null); // clear pending
            } else {
              window.location.href = '/dashboard';
            }
          }}
        />
      )}

      {/* Navigation */}
      <nav className="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md border-b border-analux-secondary/10 px-6 py-4">
        <div className="max-w-7xl mx-auto flex justify-between items-center">
          <div className="flex flex-col items-center">
            <span className="text-2xl font-serif font-bold tracking-widest text-analux-primary">ANALUX</span>
            <span className="text-[8px] uppercase tracking-[0.3em] text-analux-secondary -mt-1">Club Exclusive</span>
          </div>
          <div className="hidden md:flex items-center gap-10 text-[10px] font-bold uppercase tracking-widest text-gray-500">
            <a href="/lp-b" className="text-analux-primary hover:text-analux-secondary transition-colors border border-analux-primary/10 px-2 py-1 rounded">Versão B</a>
            <a href="#experiencia" className="hover:text-analux-secondary transition-colors">A Experiência</a>
            <a href="#ritual" className="hover:text-analux-secondary transition-colors">O Ritual</a>
            <a href="#comunidade" className="hover:text-analux-secondary transition-colors">Comunidade</a>
            <a href="#planos" className="hover:text-analux-secondary transition-colors">Planos</a>
          </div>
          <button
            onClick={onJoin}
            className="bg-analux-primary text-white px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-analux-primary/20"
          >
            Acessar Portal
          </button>
        </div>
      </nav>

      {/* Hero Section */}
      <section className="relative min-h-screen flex items-center justify-center pt-20 overflow-hidden px-6">
        <div className="absolute inset-0 z-0">
          <img
            src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=1920&h=1080"
            className="w-full h-full object-cover opacity-10"
            alt="Hero Background"
          />
          <div className="absolute inset-0 bg-gradient-to-b from-analux-contrast via-transparent to-analux-contrast"></div>
        </div>

        <div className="relative z-10 text-center space-y-8 max-w-4xl animate-fadeIn">
          <div className="inline-flex items-center gap-2 bg-analux-secondary/10 text-analux-secondary px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] border border-analux-secondary/20">
            <Sparkles size={14} /> Mais que uma Box, uma Identidade
          </div>
          <h1 className="text-6xl md:text-8xl font-serif leading-tight">
            Todo mês, um ritual.<br />
            <span className="text-analux-secondary">Toda fase, uma nova você.</span>
          </h1>
          <p className="text-gray-500 text-lg md:text-xl max-w-2xl mx-auto font-light leading-relaxed">
            Uma box de joias criada para marcar fases, sentimentos e movimentos da sua vida. Mais do que receber peças, você recebe experiências exclusivas.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6">
            <button
              onClick={scrollToPlans}
              className="w-full sm:w-auto bg-analux-primary text-white px-12 py-6 rounded-3xl font-bold flex items-center justify-center gap-3 shadow-2xl shadow-analux-primary/30 hover:scale-105 transition-all group"
            >
              Quero entrar para a Analux Box <ArrowRight size={20} className="group-hover:translate-x-1 transition-transform" />
            </button>
            <button className="text-xs font-bold uppercase tracking-widest text-analux-secondary px-8 py-6 flex items-center gap-2 hover:opacity-70">
              Ver como funciona <ChevronDown size={16} className="animate-bounce" />
            </button>
          </div>
        </div>
      </section>

      {/* Bloco 2 - O Que é a Box */}
      <section id="experiencia" className="py-32 px-6 max-w-7xl mx-auto">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
          <div className="relative group">
            <div className="absolute inset-0 bg-analux-secondary/20 rounded-[60px] rotate-3 -z-10 group-hover:rotate-0 transition-transform duration-700"></div>
            <img
              src="https://images.unsplash.com/photo-1573408302354-014545c92523?auto=format&fit=crop&q=80&w=800&h=1000"
              className="w-full h-full object-cover rounded-[60px] shadow-2xl"
              alt="Curadoria Analux"
            />
            <div className="absolute -bottom-10 -right-10 bg-white p-8 rounded-[40px] shadow-xl space-y-2 hidden md:block">
              <p className="text-4xl font-serif text-analux-primary">98%</p>
              <p className="text-[10px] font-bold uppercase tracking-widest text-analux-secondary">Satisfação nas Curadorias</p>
            </div>
          </div>

          <div className="space-y-10">
            <h2 className="text-5xl font-serif leading-tight">Um convite para<br /><span className="text-analux-secondary">você se sentir.</span></h2>
            <p className="text-gray-500 text-lg leading-relaxed font-light">
              A Analux Box é uma assinatura mensal onde você recebe uma curadoria exclusiva de joias pensadas para refletir momentos, emotions e fases. Cada edição é construída como um ritual: estética, significado e surpresa.
            </p>
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4">
              {[
                { icon: <Star size={20} />, text: 'Curadoria Exclusiva' },
                { icon: <Sparkles size={20} />, text: 'Temas Mensais' },
                { icon: <Heart size={20} />, text: 'Peças com Significado' },
                { icon: <Zap size={20} />, text: 'Experiência Sensorial' },
                { icon: <Users size={20} />, text: 'Comunidade VIP' },
                { icon: <Gift size={20} />, text: 'Mimos Especiais' }
              ].map((item, idx) => (
                <div key={idx} className="flex items-center gap-4 p-4 bg-white rounded-2xl shadow-sm border border-gray-50">
                  <div className="text-analux-secondary">{item.icon}</div>
                  <span className="text-xs font-bold text-analux-primary uppercase tracking-tight">{item.text}</span>
                </div>
              ))}
            </div>
          </div>
        </div>
      </section>

      {/* Bloco 3 - O Que vem na Box */}
      <section className="bg-analux-primary py-32 text-white overflow-hidden relative">
        <div className="absolute top-0 right-0 w-[800px] h-[800px] bg-analux-secondary/5 rounded-full -mr-96 -mt-96 blur-[150px]"></div>
        <div className="max-w-7xl mx-auto px-6 relative z-10">
          <div className="text-center space-y-6 mb-20">
            <h2 className="text-5xl md:text-6xl font-serif">A anatomia de um presente</h2>
            <p className="text-white/60 max-w-xl mx-auto">Você não recebe apenas joias. Você recebe um ecossistema de beleza e bem-estar.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-10">
            {[
              {
                title: 'Joias Premium',
                desc: '2 a 4 peças exclusivas (banho ouro 18k ou ródio) pensadas para durar.',
                img: 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&q=80&w=400&h=300'
              },
              {
                title: 'Ritual Editorial',
                desc: 'Acesso à mini revista digital com textos sensoriais, bastidores e guias de estilo.',
                img: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=400&h=300'
              },
              {
                title: 'Mimos Surpresa',
                desc: 'De aromas exclusivos a acessórios complementares para o seu dia a dia.',
                img: 'https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&q=80&w=400&h=300'
              }
            ].map((item, idx) => (
              <div key={idx} className="group cursor-default">
                <div className="aspect-[4/3] rounded-[40px] overflow-hidden mb-8 relative">
                  <img src={item.img} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" alt={item.title} />
                  <div className="absolute inset-0 bg-analux-primary/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                <h4 className="text-2xl font-serif mb-3 text-analux-secondary">{item.title}</h4>
                <p className="text-white/50 text-sm leading-relaxed">{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Bloco 8 - Planos com Toggle */}
      <section id="planos" className="py-32 px-6 max-w-7xl mx-auto">
        <div className="text-center space-y-8 mb-24">
          <h2 className="text-6xl font-serif text-analux-primary">Escolha seu Plano</h2>

          {/* Chic Toggle - 3 Options */}
          <div className="flex flex-col items-center gap-6">
            <div className="flex items-center gap-4 bg-white p-1.5 rounded-full border border-gray-100 shadow-sm relative w-fit">
              <div
                className={`absolute top-1.5 bottom-1.5 rounded-full bg-analux-primary transition-all duration-500 ease-in-out w-32 ${billingCycle === 'monthly' ? 'left-1.5' :
                  billingCycle === 'quarterly' ? 'left-[134px]' : 'left-[266px]'
                  }`}
              ></div>
              <button
                onClick={() => setBillingCycle('monthly')}
                className={`relative z-10 px-6 py-3 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 w-32 ${billingCycle === 'monthly' ? 'text-white' : 'text-gray-400'}`}
              >
                Mensal
              </button>
              <button
                onClick={() => setBillingCycle('quarterly')}
                className={`relative z-10 px-6 py-3 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 w-32 ${billingCycle === 'quarterly' ? 'text-white' : 'text-gray-400'}`}
              >
                Trimestral
              </button>
              <button
                onClick={() => setBillingCycle('semiannual')}
                className={`relative z-10 px-6 py-3 text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 w-32 ${billingCycle === 'semiannual' ? 'text-white' : 'text-gray-400'}`}
              >
                Semestral
              </button>
            </div>
            {billingCycle !== 'monthly' && (
              <div className="flex items-center gap-2 text-analux-secondary font-bold text-[10px] uppercase tracking-widest animate-fadeIn">
                <Gem size={12} /> {billingCycle === 'semiannual' ? 'Melhor Custo-Benefício' : 'Economia Garantida'}
              </div>
            )}
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-12 max-w-5xl mx-auto">
          {plans.map((plan, i) => {
            // @ts-ignore
            const currentData = plan[billingCycle];
            return (
              <div
                key={`${i}-${billingCycle}`}
                className={`relative p-12 rounded-[60px] flex flex-col justify-between transition-all duration-500 hover:translate-y-[-10px] animate-fadeIn ${plan.featured
                  ? 'bg-analux-primary text-white shadow-2xl scale-105 z-10'
                  : 'bg-white text-analux-primary border border-gray-100 shadow-xl'
                  }`}
              >
                {plan.featured && billingCycle === 'semiannual' && (
                  <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-analux-secondary text-white px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg">
                    Recomendado
                  </div>
                )}

                <div>
                  <div className="flex justify-between items-start mb-6">
                    <p className={`text-xs font-bold uppercase tracking-[0.3em] ${plan.featured ? 'text-analux-secondary' : 'text-gray-400'}`}>Plano {plan.name}</p>
                    {billingCycle !== 'monthly' && (
                      <span className="bg-analux-secondary/20 text-analux-secondary px-2 py-1 rounded text-[9px] font-bold uppercase">Save Deal</span>
                    )}
                  </div>
                  <div className="mb-8">
                    <p className={`text-lg font-serif mb-2 ${plan.featured ? 'text-white' : 'text-analux-primary'}`}>{plan.desc}</p>
                    <div className="flex items-baseline gap-2">
                      <span className="text-5xl font-serif">R$ {currentData.price}</span>
                    </div>
                    <span className={`text-xs block mt-1 ${plan.featured ? 'text-white/50' : 'text-gray-400'}`}>
                      {billingCycle === 'monthly' ? 'Renovado automaticamente' : `Cobrado a cada ${billingCycle === 'quarterly' ? '3' : '6'} meses`}
                    </span>
                  </div>

                  <div className="space-y-5 mb-12">
                    {currentData.perks.map((perk: string, j: number) => (
                      <div key={j} className="flex items-center gap-4">
                        <CheckCircle2 size={18} className={plan.featured ? 'text-analux-secondary' : 'text-analux-primary'} />
                        <span className="text-sm font-medium">{perk}</span>
                      </div>
                    ))}
                  </div>
                </div>

                <button
                  onClick={() => handleSubscribe(plan, billingCycle)}
                  className={`w-full py-6 rounded-2xl font-bold text-xs uppercase tracking-widest transition-all ${plan.featured
                    ? 'bg-analux-secondary text-white shadow-lg hover:bg-white hover:text-analux-primary'
                    : 'bg-analux-primary text-white hover:bg-analux-dark'
                    }`}
                >
                  Assinar Agora
                </button>
              </div>
            );
          })}
        </div>
      </section>

      {/* FAQ Section */}
      <section className="py-32 px-6 max-w-4xl mx-auto">
        <div className="text-center space-y-4 mb-20">
          <h2 className="text-5xl font-serif text-analux-primary">Dúvidas Frequentes</h2>
          <p className="text-gray-400">Tudo o que você precisa saber sobre o universo Analux Box.</p>
        </div>

        <div className="space-y-4">
          {faqItems.map((item, idx) => (
            <div
              key={idx}
              className="bg-white rounded-[32px] overflow-hidden border border-gray-100 shadow-sm transition-all"
            >
              <button
                onClick={() => setOpenFaq(openFaq === idx ? null : idx)}
                className="w-full p-8 flex items-center justify-between text-left hover:bg-analux-contrast/50 transition-colors"
              >
                <span className="text-lg font-serif text-analux-primary">{item.question}</span>
                <div className="text-analux-secondary">
                  {openFaq === idx ? <Minus size={20} /> : <Plus size={20} />}
                </div>
              </button>
              <div
                className={`px-8 transition-all duration-500 ease-in-out ${openFaq === idx ? 'max-h-96 pb-8 opacity-100' : 'max-h-0 opacity-0'
                  }`}
              >
                <p className="text-gray-500 text-sm leading-relaxed border-t border-gray-50 pt-6">
                  {item.answer}
                </p>
              </div>
            </div>
          ))}
        </div>
      </section>

      {/* Bloco 9 - Segurança */}
      <section className="py-20 bg-analux-contrast border-y border-analux-secondary/10">
        <div className="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-12 items-center opacity-40">
          <div className="flex flex-col items-center gap-2">
            <ShieldCheck size={32} />
            <span className="text-[10px] font-bold uppercase">Pagamento Seguro</span>
          </div>
          <div className="flex flex-col items-center gap-2">
            <RefreshCw size={32} className="lucide-refresh-cw" style={{ animation: 'none' }} />
            <span className="text-[10px] font-bold uppercase">Pause quando quiser</span>
          </div>
          <div className="flex flex-col items-center gap-2">
            <ShoppingBag size={32} />
            <span className="text-[10px] font-bold uppercase">Troca Facilitada</span>
          </div>
          <div className="flex flex-col items-center gap-2">
            <Users size={32} />
            <span className="text-[10px] font-bold uppercase">Suporte Exclusivo</span>
          </div>
        </div>
      </section>

      {/* Bloco 10 - Fechamento Emocional */}
      <footer className="py-32 px-6 text-center space-y-12">
        <div className="max-w-3xl mx-auto space-y-8">
          <h2 className="text-5xl md:text-6xl font-serif text-analux-primary leading-tight italic">
            "O brilho que você usa é o reflexo da luz que você carrega."
          </h2>
          <div className="space-y-4 text-gray-500 font-light text-lg">
            <p>A Analux Box não é sobre joias.</p>
            <p>É sobre criar pequenos rituais. É sobre se olhar. É sobre marcar fases.</p>
            <p>É sobre se dar um presente todos os meses.</p>
          </div>
          <button
            onClick={onJoin}
            className="bg-analux-secondary text-white px-16 py-6 rounded-3xl font-bold text-lg shadow-2xl shadow-analux-secondary/30 hover:scale-105 transition-all mt-6"
          >
            Começar meu Ritual agora
          </button>
        </div>

        <div className="pt-24 flex flex-col items-center gap-6 border-t border-gray-100 max-w-7xl mx-auto">
          <div className="flex flex-col items-center">
            <span className="text-2xl font-serif font-bold tracking-widest text-analux-primary">ANALUX</span>
            <span className="text-xs uppercase tracking-[0.3em] text-analux-secondary -mt-1">Club Exclusive</span>
          </div>
          <div className="flex gap-8 text-[10px] font-bold uppercase tracking-widest text-gray-400">
            <a href="#" className="hover:text-analux-primary">Privacidade</a>
            <a href="#" className="hover:text-analux-primary">Termos</a>
            <a href="#" className="hover:text-analux-primary">Contato</a>
          </div>
          <div className="flex gap-4">
            <button className="w-10 h-10 rounded-full border border-gray-100 flex items-center justify-center text-gray-400 hover:bg-analux-primary hover:text-white transition-all">
              <Instagram size={18} />
            </button>
          </div>
          <p className="text-[10px] text-gray-300">© 2024 Analux Semijoias. Todos os direitos reservados.</p>
        </div>
      </footer>
    </div>
  );
};

export default LandingPage;
