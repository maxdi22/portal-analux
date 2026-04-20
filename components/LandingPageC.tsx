import React, { useState, useEffect, useCallback, useRef } from 'react';
import Lenis from 'lenis';
import {
  ArrowRight,
  Check,
  ChevronDown,
  CirclePlay,
  Gift,
  Lock,
  ShieldCheck,
  Sparkles,
  Star,
  Plus,
  Minus,
  Facebook,
  Gem,
  CheckCircle2,
  Pointer,
  ChevronUp,
  ChevronLeft,
  ChevronRight,
  Play
} from 'lucide-react';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';
import AuthModal from './AuthModal';
import SubscriptionReviewModal from './SubscriptionReviewModal';
import { useLPConfig } from '../hooks/useLPConfig';
import ScrollVideoHero from './ScrollVideoHero';
import * as Icons from 'lucide-react';

const SectionEyebrow = ({ children }: { children: React.ReactNode }) => (
    <span className="inline-flex items-center gap-2 rounded-full border border-analux-plum/10 bg-white/70 px-4 py-2 text-[0.65rem] font-bold uppercase tracking-[0.25em] text-analux-plum/70 shadow-sm backdrop-blur">
      <span className="h-1.5 w-1.5 rounded-full bg-analux-gold-strong" />
      {children}
    </span>
  );

const HeroCardSlider = () => {
  const images = [
    '/media/c-hook-1/2009598018-d01-20251212143817-1765561099.1112.webp',
    '/media/c-hook-1/59105797c-d01-20251127154356-1764269039.4537.webp',
    '/media/c-hook-1/59105818c-d01-20251127151913-1764267555.9137.webp',
    '/media/c-hook-1/59107129-d01-20251111113350-1762871633.7075.webp',
    '/media/c-hook-1/59107133-d01-20251111110355-1762869837.37.webp',
    '/media/c-hook-1/59107626-d01-20260131114857-1769870938.2966.webp',
    '/media/c-hook-1/78040316-d01-20251215170404-1765829045.365.webp',
    '/media/c-hook-1/806068009m-d01-20251204142855-1764869338.7903.webp',
    '/media/c-hook-1/9004070-d01-20251210172813-1765398494.9576.webp',
  ];

  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentIndex((prev) => (prev + 1) % images.length);
    }, 2500);
    return () => clearInterval(timer);
  }, [images.length]);

  return (
    <div className="relative w-full aspect-square overflow-hidden rounded-[2rem] bg-analux-plum/5">
      <div 
        className="flex transition-transform duration-700 ease-in-out h-full w-full"
        style={{ transform: `translateX(-${currentIndex * 100}%)` }}
      >
        {images.map((img, i) => (
          <img 
            key={i}
            src={img}
            className="w-full h-full flex-shrink-0 object-cover"
            alt={`Slide ${i}`}
          />
        ))}
      </div>
      
      {/* Dynamic Overlay Gradient */}
      <div className="absolute inset-0 bg-gradient-to-t from-analux-plum/20 to-transparent pointer-events-none" />
    </div>
  );
};

const LandingPageC: React.FC = () => {
  const { user } = useUser();
  const { 
    config, 
    loading
  } = useLPConfig();

  const [introMobileFinished, setIntroMobileFinished] = useState(false);
  const [isAuthModalOpen, setIsAuthModalOpen] = useState(false);
  const [isSubscriptionReviewModalOpen, setIsSubscriptionReviewModalOpen] = useState(false);
  const [authMode, setAuthMode] = useState<'login' | 'signup'>('login');
   
  const categoryScrollRef = useRef<HTMLDivElement>(null);
  const [scrollProgress, setScrollProgress] = useState(0);

  const handleCategoryScroll = () => {
    if (categoryScrollRef.current) {
      const { scrollLeft, scrollWidth, clientWidth } = categoryScrollRef.current;
      const progress = (scrollLeft / (scrollWidth - clientWidth)) * 100;
      setScrollProgress(isNaN(progress) ? 0 : progress);
    }
  };

  const scrollCategories = (direction: 'left' | 'right') => {
    if (categoryScrollRef.current) {
      const { clientWidth } = categoryScrollRef.current;
      const scrollAmount = clientWidth * 0.6;
      categoryScrollRef.current.scrollBy({
        left: direction === 'left' ? -scrollAmount : scrollAmount,
        behavior: 'smooth'
      });
    }
  };
  const [showBackToTop, setShowBackToTop] = useState(false);
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const [isLoginModalOpen, setIsLoginModalOpen] = useState<boolean | 'signup'>(false);
  const [headerTheme, setHeaderTheme] = useState<'transparent' | 'plum'>('transparent');
  const [billingCycle, setBillingCycle] = useState<'monthly' | 'quarterly' | 'semiannual'>('monthly');
  const [reviewModalData, setReviewModalData] = useState<{
    planName: string;
    cycle: 'monthly' | 'quarterly' | 'semiannual';
    price: string;
    perks: string[];
  } | null>(null);
  const [pendingSubscription, setPendingSubscription] = useState<{ plan: string, cycle: 'monthly' | 'quarterly' | 'semiannual' } | null>(null);
  const headerThemeRef = useRef(headerTheme);

  useEffect(() => {
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

  const executeCheckout = async (planName: string, cycle: 'monthly' | 'quarterly' | 'semiannual') => {
    const priceMap: Record<string, string> = {
      'Essencial-monthly':    'price_1TO5KbDWlGIoBgz1i51a05jy',
      'Essencial-quarterly':  'price_1TO5KjDWlGIoBgz1s62Byfac',
      'Essencial-semiannual': 'price_1TO5KsDWlGIoBgz1UswFpM6W',
      'Signature-monthly':    'price_1TO5L0DWlGIoBgz1DdgWR0U2',
      'Signature-quarterly':  'price_1TO5L8DWlGIoBgz1Qz4ljzWb',
      'Signature-semiannual': 'price_1TO5LFDWlGIoBgz1yz5RVb37',
    };
    const key = `${planName}-${cycle}`;
    const priceId = priceMap[key];
    if (!priceId) {
      alert('Este plano ainda não está configurado. Por favor, tente novamente.');
      return;
    }
    try {
      const { data, error } = await supabase.functions.invoke('stripe-proxy', {
        body: {
          action: 'create_checkout_session',
          priceId: priceId,
          returnUrl: window.location.origin + '/dashboard'
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
    const details = plan[cycle];
    if (!user?.id) {
      setPendingSubscription({ plan: plan.name, cycle });
      setIsLoginModalOpen('signup');
      return;
    }
    const data = {
      planName: plan.name,
      cycle,
      price: details.price,
      perks: details.perks
    };
    setReviewModalData(data);
    localStorage.setItem('sub_draft_active_plan', JSON.stringify(data));
  };

  // BACK TO TOP LOGIC
  useEffect(() => {
    const handleScroll = () => {
      const isDesktop = window.innerWidth >= 1024;
      // No desktop, o botão só deve aparecer após o ScrollVideoHero (que tem 400vh)
      // Definimos um threshold maior para garantir que o vídeo já tenha passado.
      const threshold = isDesktop ? 4000 : 700;
      
      if (window.scrollY > threshold) {
        setShowBackToTop(true);
      } else {
        setShowBackToTop(false);
      }
    };

    window.addEventListener('scroll', handleScroll);
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const scrollToTop = () => {
    window.scrollTo({
      top: 0,
      behavior: 'smooth'
    });
  };

  // Smooth Scroll (Lenis) Initialization
  useEffect(() => {
    const lenis = new Lenis({
      duration: 1.2,
      easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
      orientation: 'vertical',
      gestureOrientation: 'vertical',
      smoothWheel: true,
      wheelMultiplier: 1,
      touchMultiplier: 2,
      infinite: false,
    });

    function raf(time: number) {
      lenis.raf(time);
      requestAnimationFrame(raf);
    }

    requestAnimationFrame(raf);

    // Link anchor smooth scroll integration
    const handleAnchorClick = (e: MouseEvent) => {
      const target = e.target as HTMLElement;
      const anchor = target.closest('a');
      if (anchor && anchor.getAttribute('href')?.startsWith('#')) {
        const id = anchor.getAttribute('href')?.substring(1);
        const element = document.getElementById(id || '');
        if (element) {
          e.preventDefault();
          lenis.scrollTo(element, {
            offset: -100, // Adjust for floating header
            duration: 1.5,
          });
        }
      }
    };

    document.addEventListener('click', handleAnchorClick);

    return () => {
      lenis.destroy();
      document.removeEventListener('click', handleAnchorClick);
    };
  }, []);

  // DYNAMIC SOCIAL PROOF STATE
  const [socialProof, setSocialProof] = useState<{ avatars: string[], count: number }>({
    avatars: [],
    count: 12
  });

  useEffect(() => {
    const avatarPool = [
      'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1520813792240-56fc4a3765a7?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=80&h=80&fit=crop&crop=face',
      'https://images.unsplash.com/photo-1531123897727-8f129e16fd3c?w=80&h=80&fit=crop&crop=face'
    ];
    
    // Pick 4 random avatars
    const shuffled = [...avatarPool].sort(() => 0.5 - Math.random());
    const selected = shuffled.slice(0, 4);
    
    // Pick random count between 8 and 32
    const count = Math.floor(Math.random() * (32 - 8 + 1)) + 8;
    
    setSocialProof({ avatars: selected, count });
  }, []);

  // Scroll-based section detection — runs AFTER content loads
  useEffect(() => {
    if (loading || !config) return; // Don't attach until DOM is ready

    const HEADER_PROBE_Y = 120; // The Y-coordinate to probe (middle of the floating header)

    const detectTheme = () => {
      const sections = document.querySelectorAll('[data-header-theme]');
      let newTheme: 'transparent' | 'plum' = 'transparent';

      sections.forEach((section) => {
        const rect = section.getBoundingClientRect();
        // Check if this section's vertical range covers the header probe point
        if (rect.top <= HEADER_PROBE_Y && rect.bottom >= HEADER_PROBE_Y) {
          const theme = section.getAttribute('data-header-theme');
          newTheme = theme === 'light' ? 'plum' : 'transparent';
        }
      });

      // Only update state if theme actually changed (avoids re-renders)
      if (headerThemeRef.current !== newTheme) {
        headerThemeRef.current = newTheme;
        setHeaderTheme(newTheme);
      }
    };

    // Run once immediately to set initial state
    detectTheme();

    // Attach scroll listener
    window.addEventListener('scroll', detectTheme, { passive: true });
    return () => window.removeEventListener('scroll', detectTheme);
  }, [loading, config]);

  if (loading || !config) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-analux-cream">
        <div className="w-12 h-12 border-4 border-analux-gold-strong border-t-transparent rounded-full animate-spin"></div>
      </div>
    );
  }

  const { assets, content, styling } = config;

  const scrollToPlans = () => {
    document.getElementById('planos')?.scrollIntoView({ behavior: 'smooth' });
  };
  const onJoin = () => setIsLoginModalOpen('signup'); // Kept as fallback

  const plans = [
    {
      name: 'Essencial',
      desc: 'Plano de entrada premium. Vivencie a experiência Analux com uma curadoria essencial.',
      featured: false,
      monthly: {
        price: '189',
        perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Cancele quando quiser']
      },
      quarterly: {
        price: '179',
        perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Permanência (3 meses)', 'Pouch de Proteção', 'Cancele após 3 meses']
      },
      semiannual: {
        price: '169',
        perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Permanência (6 meses)', 'Pouch Dust Bag Premium de Adesão']
      }
    },
    {
      name: 'Signature',
      desc: 'Plano aspiracional. Curadoria superior, experiência rica e o maior valor percebido.',
      featured: true,
      monthly: {
        price: '239',
        perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Cancele quando quiser']
      },
      quarterly: {
        price: '229',
        perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Permanência (3 meses)', 'Pouch de Proteção Premium', 'Cancele após 3 meses']
      },
      semiannual: {
        price: '219',
        perks: ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Permanência (6 meses)', 'Porta-Joias Exclusivo de Adesão']
      }
    }
  ];


  return (
    <div className="relative bg-analux-cream text-analux-plum font-body selection:bg-analux-gold-soft selection:text-analux-plum">
      <div className="pointer-events-none absolute inset-0 opacity-[0.2] analux-noise" />

      {/* Review Modal */}
      {reviewModalData && (
        <SubscriptionReviewModal
          planName={reviewModalData.planName}
          cycle={reviewModalData.cycle}
          price={reviewModalData.price}
          perks={reviewModalData.perks}
          onClose={() => {
            setReviewModalData(null);
            localStorage.removeItem('sub_draft_active_plan');
          }}
          onConfirm={() => {
            localStorage.removeItem('sub_draft_active_plan'); 
            return executeCheckout(reviewModalData.planName, reviewModalData.cycle);
          }}
        />
      )}

      {/* Auth Modal */}
      {isLoginModalOpen && (
        <AuthModal 
          onClose={() => setIsLoginModalOpen(false)} 
          initialMode={isLoginModalOpen === 'signup' ? 'REGISTER' : 'LOGIN'}
          customWelcomeMessage={isLoginModalOpen === 'signup' ? 'Crie seu login e senha para prosseguir para a contratação.' : undefined}
          onAuthSuccess={() => {
            if (pendingSubscription) {
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
              setPendingSubscription(null);
            } else {
              window.location.href = '/dashboard';
            }
          }}
        />
      )}

      {/* Header Editorial - CUSTOM FLOATING PILL */}
      <header className={`fixed top-4 lg:top-8 inset-x-0 z-50 px-4 lg:px-6 transition-all duration-500 ease-in-out`}>
        <div className={`max-w-7xl mx-auto backdrop-blur-xl border rounded-[2.5rem] h-16 lg:h-20 flex items-center px-4 md:px-6 lg:px-10 shadow-2xl transition-all duration-500 ${
          headerTheme === 'plum'
            ? 'bg-analux-plum/95 border-white/10 shadow-[0_20px_50px_rgba(26,11,16,0.5)]' 
            : 'bg-white/5 border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.3)]'
        }`}>
          {/* LEFT: LOGO */}
          <div className="flex shrink-0">
            <button onClick={scrollToTop} className="shrink-0 transition-opacity hover:opacity-80">
              <img 
                  src={assets.logoDark} 
                  alt="Analux" 
                  className={`h-8 w-auto transition-all duration-500 ${headerTheme === 'plum' ? 'brightness-100 opacity-100' : 'brightness-0 invert opacity-80'}`} 
              />
            </button>
          </div>

          <div className="flex-1" /> {/* Spacer 1 */}
          
          {/* CENTER: NAVIGATION PILL */}
          <div className="flex-initial hidden lg:flex">
            <nav className="flex items-center bg-white/90 backdrop-blur-md rounded-full p-1 shadow-lg border border-white/20 whitespace-nowrap">
              <a href="#experiencia" className="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-[0.2em] text-analux-plum/60 hover:text-analux-plum transition-all hover:bg-black/5">
                Experiência
              </a>
              <a href="#box" className="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-[0.2em] text-analux-plum/60 hover:text-analux-plum transition-all hover:bg-black/5">
                O que chega
              </a>
              <a href="#ritual" className="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-[0.2em] text-analux-plum/60 hover:text-analux-plum transition-all hover:bg-black/5">
                Ritual
              </a>
              <a href="#planos" className="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-[0.2em] text-analux-plum/60 hover:text-analux-plum transition-all hover:bg-black/5">
                Planos
              </a>
              <a href="#faq" className="px-5 py-2 rounded-full text-xs font-bold uppercase tracking-[0.2em] text-analux-plum/60 hover:text-analux-plum transition-all hover:bg-black/5">
                Dúvidas
              </a>
            </nav>
          </div>

          <div className="flex-1" /> {/* Spacer 2 */}

          {/* RIGHT: AVATARES + PORTAL */}
          <div className="flex shrink-0 items-center justify-end gap-3 lg:gap-6">
            {/* SOCIAL PROOF: Avatar Stack */}
            <div className="hidden xl:flex items-center gap-3 border-r border-white/10 pr-6 shrink-0">
              <div className="flex -space-x-2 shrink-0">
                {socialProof.avatars.map((url, i) => (
                  <img
                    key={i}
                    className="h-6 w-6 rounded-full ring-2 ring-white/20 object-cover"
                    src={url}
                    alt="Assinante"
                  />
                ))}
              </div>
              <p className={`text-[10px] leading-tight transition-colors duration-500 ${headerTheme === 'plum' ? 'text-white/50' : 'text-white/40'}`}>
                <strong className={`font-bold block ${headerTheme === 'plum' ? 'text-analux-gold-soft' : 'text-analux-gold-strong'}`}>
                  +{socialProof.count} novas
                </strong>
                assinaturas
              </p>
            </div>

            <button
                onClick={() => setIsLoginModalOpen(true)}
                className={`px-4 py-2 lg:px-8 lg:py-3 rounded-full text-[8px] lg:text-[9px] font-bold uppercase tracking-widest transition-all shadow-xl whitespace-nowrap ${
                  headerTheme === 'plum'
                    ? 'bg-analux-gold-strong text-white hover:bg-white hover:text-analux-plum shadow-analux-gold-strong/20'
                    : 'bg-analux-plum text-white hover:bg-analux-gold-strong hover:text-white shadow-analux-plum/20'
                }`}
            >
                Acessar Portal
            </button>
          </div>
        </div>
      </header>

      <main>
        {/* MOBILE CINEMATIC HERO */}
        <section data-header-theme="dark" className="relative lg:hidden h-[100dvh] w-full overflow-hidden bg-analux-plum">
          {/* VIDEO 2: BACKGROUND LOOPING (Starts immediately but is behind Video 1) */}
          <video 
            src="/loop.mp4" 
            autoPlay 
            loop 
            muted 
            playsInline 
            poster="/cover2.jpg"
            className="absolute inset-0 w-full h-full object-cover"
          />
          {/* VIDEO 1: INTRO (Fades out when finished) */}
          <video 
            src="/freepik_ultrarealistic-cinematic-_2816098516.mp4" 
            autoPlay 
            muted 
            playsInline 
            onEnded={() => setIntroMobileFinished(true)}
            poster="/cover1.jpg"
            className={`absolute inset-0 w-full h-full object-cover transition-opacity duration-[2000ms] ease-in-out z-10 ${introMobileFinished ? 'opacity-0 pointer-events-none' : 'opacity-100'}`}
          />
          {/* Subtle gradient just to ensure the white text is readable */}
          <div className="absolute inset-0 bg-gradient-to-b from-analux-plum/40 via-transparent to-transparent pointer-events-none z-20" />
          
          {introMobileFinished && (
            <div 
              className="absolute inset-x-0 top-[18%] px-10 flex flex-col items-center text-center animate-slideUp z-20"
            >
              <h2 className="text-[1.75rem] leading-[1.3] font-display text-white mb-10 drop-shadow-2xl">
                <span className="italic text-analux-gold-soft">Assinatura</span> de <span className="italic text-analux-gold-soft">semijoias</span> feita para mulheres que fazem do seu estilo a <span className="italic text-analux-gold-soft">sua marca</span>.
              </h2>

              <button 
                onClick={scrollToPlans} 
                className="bg-analux-plum/90 backdrop-blur-md border border-white/10 px-8 py-5 rounded-full font-bold uppercase tracking-[0.15em] text-[10px] flex items-center justify-center gap-3 shadow-2xl text-white hover:bg-analux-gold-strong transition-all duration-300"
              >
                Conhecer os Planos <ArrowRight size={16} />
              </button>
            </div>
          )}

          {/* SCROLL INDICATOR */}
          {introMobileFinished && (
            <div 
              className="absolute inset-x-0 bottom-12 flex flex-col items-center animate-fadeIn z-20"
            >
              <div className="flex flex-col items-center gap-3">
                <span className="text-[9px] text-white/50 uppercase tracking-[0.3em] font-bold">Deslize para baixo</span>
                
                <div className="relative h-16 w-8 flex justify-center overflow-hidden">
                  <div className="absolute top-2 bottom-0 w-px bg-gradient-to-b from-white/10 to-transparent pointer-events-none" />
                  <div className="absolute top-0 text-analux-gold-soft animate-bounce">
                      <Pointer size={22} className="opacity-80 drop-shadow-lg" />
                  </div>
                </div>
              </div>
            </div>
          )}
        </section>

        {/* CINEMATIC INTRO */}
        <section data-header-theme="dark" className="hidden lg:block">
          <ScrollVideoHero onJoin={scrollToPlans} />
        </section>

        {/* HERO SECTION */}
        <section 
          id="top" 
          data-header-theme="light"
          className="relative px-4 lg:px-6 pt-24 pb-20 lg:pt-[12rem] lg:pb-[14rem]"
        >
          <div 
            className="absolute inset-0 bg-cover bg-center pointer-events-none transition-opacity duration-700" 
            style={{ 
                backgroundImage: `url(${assets.heroBg})`,
                opacity: styling.heroBgOpacity / 100
            }}
          />
          <div className="absolute inset-0 bg-gradient-to-b from-analux-cream via-analux-cream/95 to-analux-cream-deep/50 pointer-events-none" />
          
          <div className="max-w-7xl mx-auto relative grid lg:grid-cols-2 gap-16 items-center">
            <div className="space-y-10 animate-slideUp">
              <SectionEyebrow>
                {content.hero.eyebrow}
              </SectionEyebrow>
              
              <h1 className="text-4xl md:text-6xl xl:text-8xl font-display leading-[0.9] text-analux-plum">
                {content.hero.titlePart1} <br />
                <span className="italic text-analux-gold-strong">
                    {content.hero.titleItalic}
                </span>
                {content.hero.titlePart2}
              </h1>
              
              <div className="space-y-6 max-w-xl">
                <div className="text-xl leading-relaxed text-analux-plum/80 font-light">
                   {content.hero.description1}
                </div>
                <div className="text-lg text-analux-plum/60 font-light">
                    {content.hero.description2}
                </div>
              </div>

              <div className="flex flex-col sm:flex-row gap-4 lg:gap-5">
                <button
                  onClick={scrollToPlans}
                  className="analux-button-primary px-6 py-4 lg:px-10 lg:py-6 rounded-full font-bold flex items-center justify-center gap-3 group text-xs lg:text-sm uppercase tracking-widest"
                >
                  {content.hero.primaryCTA}
                  <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
                </button>
                <a href="#experiencia" className="analux-button-secondary px-6 py-4 lg:px-10 lg:py-6 rounded-full font-bold flex items-center justify-center gap-3 text-xs lg:text-sm uppercase tracking-widest">
                   <CirclePlay size={18} /> 
                   {content.hero.secondaryCTA}
                </a>
              </div>
            </div>

            <div className="relative">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="space-y-4 pt-12">
                  <div className="analux-hero-card aspect-[3/4] overflow-hidden group">
                    <img 
                      src={content.hero.card1Image || assets.heroModel || assets.productModel} 
                      alt="Analux Box" 
                      className="w-full h-full object-cover rounded-[3rem] transition-transform duration-1000 group-hover:scale-105" 
                    />
                    <div className="absolute top-6 left-6 z-20">
                      <p className="text-[10px] font-bold uppercase tracking-widest text-white/90 drop-shadow-md">Analux Box</p>
                    </div>
                    <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-analux-plum/80 to-transparent p-6 text-left">
                      <p className="text-[9px] uppercase tracking-widest text-white">CURADORIA PREMIUM</p>
                      <p className="text-white font-display text-xl mt-1 italic">Para o seu DNA de Estilo</p>
                    </div>
                  </div>
                  <div className="analux-floating-note mx-auto max-w-[90%]">
                    <Sparkles size={14} className="text-analux-gold-strong" />
                    <p className="text-[11px] font-medium leading-[1.4]">Peças escolhidas para valorizar imagem, estilo e presença.</p>
                  </div>
                </div>
                <div className="space-y-4">
                   <div className="analux-hero-card bg-white/40 p-3">
                     <HeroCardSlider />
                     <div className="px-3 pb-3 space-y-2">
                        <p className="text-[8px] uppercase tracking-[0.3em] font-bold text-analux-gold-strong">
                           {content.hero.card2Eyebrow || "A Experiência"}
                        </p>
                        <p className="font-display text-xl leading-tight">
                           {content.hero.card2Title || "Uma assinatura pensada para quem quer exclusividade com mais intenção."}
                        </p>
                     </div>
                   </div>
                </div>
              </div>
            </div>
          </div>

          <div className="mt-20 lg:mt-32 pt-16 border-t border-analux-plum/10 max-w-7xl mx-auto px-4 lg:px-0">
            <div className="text-center space-y-6 mb-16 lg:mb-24">
              <SectionEyebrow>
                {content.howItWorks?.eyebrow || "A Jornada"}
              </SectionEyebrow>
              <h2 className="text-4xl md:text-5xl lg:text-7xl font-display leading-[1.1] text-analux-plum opacity-100 max-w-4xl mx-auto">
                Entenda como <span className="text-analux-gold-strong italic opacity-100">funciona</span>
              </h2>
              <p className="text-analux-plum opacity-80 text-sm lg:text-base max-w-2xl mx-auto font-medium leading-relaxed">
                {content.howItWorks?.description || "Na Analux Box, você recebe até 5 semijoias todos os meses, escolhidas a dedo de acordo com o seu perfil de acessórios."}
              </p>
            </div>

            <div className="relative">
              {/* Connecting Line (Desktop) */}
              <div className="absolute top-10 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[80%] h-px bg-gradient-to-r from-transparent via-analux-gold-strong/30 to-transparent hidden lg:block" />

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-12 lg:gap-8">
                {(content.howItWorks?.steps || [
                    { id: '1', number: '01', icon: 'Pointer', title: "Assine o Clube", desc: "Escolha o plano que mais combina com seu estilo e rotina." },
                    { id: '2', number: '02', icon: 'CheckCircle2', title: "Defina seu Perfil", desc: "Conte-nos suas preferências para uma curadoria personalizada." },
                    { id: '3', number: '03', icon: 'Sparkles', title: "Curadoria Única", desc: "Nossa inteligência e time de estilo selecionam as peças perfeitas." },
                    { id: '4', number: '04', icon: 'Gift', title: "Receba sua Box", desc: "Sua box chega em sua casa com embalagem exclusiva." },
                    { id: '5', number: '05', icon: 'Gem', title: "Porta-Joias Digital", desc: "Gerencie sua coleção e organize suas peças favoritas em seu painel exclusivo." }
                  ]).map((step, i) => {
                    const IconComponent = (Icons as any)[step.icon] || Icons.HelpCircle;
                    return (
                      <div key={step.id || i} className="relative group text-center lg:text-left flex flex-col items-center lg:items-start animate-fadeIn" style={{ animationDelay: `${i * 150}ms` }}>
                        <div className="relative mb-8">
                          {/* Step Circle */}
                          <div className="w-20 h-20 rounded-full bg-white shadow-xl shadow-analux-plum/5 flex items-center justify-center text-analux-gold-strong relative z-10 border border-analux-plum/5 group-hover:scale-110 transition-transform duration-500">
                            <IconComponent size={24} />
                          </div>
                          
                          {/* Floating Number Bubble */}
                          <div className="absolute -top-1 -right-1 w-8 h-8 rounded-full bg-analux-plum text-white text-[10px] font-bold flex items-center justify-center shadow-lg border-2 border-white z-20">
                            {step.number}
                          </div>
                          
                          {/* Pulse Effect */}
                          <div className="absolute inset-0 rounded-full bg-analux-gold-soft/20 animate-ping opacity-0 group-hover:opacity-100 transition-opacity" />
                        </div>

                        <h4 className="text-[11px] lg:text-[12px] font-bold uppercase tracking-[0.2em] text-analux-plum mb-3 px-4 lg:px-0">
                          {step.title}
                        </h4>
                        <p className="text-analux-plum/50 text-[13px] font-light leading-relaxed max-w-[240px] px-4 lg:px-0">
                          {step.desc}
                        </p>
                      </div>
                    );
                  })}
              </div>
            </div>
          </div>
        </section>

        <section data-header-theme="light" className="border-y border-analux-plum/5 bg-white/40 py-8 lg:py-10 select-none relative z-[100] overflow-y-visible overflow-x-clip">
          <div className="animate-marquee flex items-center group/marquee hover:[animation-play-state:paused]">
            {/* Double the items for seamless loop */}
            {[...Array(2)].map((_, loopIdx) => (
              <div key={loopIdx} className="flex items-center">
                {/* Repeat the set multiple times to ensure full width coverage */}
                {[...Array(4)].map((_, setIdx) => (
                  <div key={setIdx} className="flex items-center">
                    {(content.marquee?.items || [
                      { title: "Semijoias novas todos os meses", desc: "Receba novos acessórios no conforto do seu lar. Selecionados especialmente para você" },
                      { title: "Peças exclusivas", desc: "Cada caixinha receberá semijoias diferentes de acordo com o gosto de cada uma" },
                      { title: "Semijoias de qualidade", desc: "Todas as nossas semijoias são banhadas, hipoalergicas e com 1 ano de garantia. Assinando a box, você tem garantia de estar investindo em acessórios duradouros." },
                      { title: "Frete fixo", desc: "Assine pagando frete de apenas R$9,9 independente da região." }
                    ]).map((item, i) => (
                      <div key={`${loopIdx}-${setIdx}-${i}`} className="flex items-center gap-10 lg:gap-20 px-5 lg:px-10">
                        <div className="group/item relative flex items-center gap-4 whitespace-nowrap cursor-help py-4">
                          <Star size={12} className="text-analux-gold-strong transition-transform duration-300 group-hover/item:scale-125" />
                          <span className="text-[10px] lg:text-[11px] uppercase tracking-[0.3em] font-bold text-analux-plum group-hover/item:text-analux-gold-strong transition-colors duration-300">
                            {item.title}
                          </span>
                          
                          <div className="absolute bottom-full left-1/2 -translate-x-1/2 mb-4 w-64 p-4 rounded-2xl bg-white/90 backdrop-blur-xl border border-analux-plum/10 shadow-2xl opacity-0 translate-y-2 pointer-events-none group-hover/item:opacity-100 group-hover/item:translate-y-0 transition-all duration-300 z-30">
                            <div className="relative text-center">
                              <p className="text-[10px] uppercase tracking-widest font-bold text-analux-gold-strong mb-2">{item.title}</p>
                              <p className="text-[11px] leading-relaxed font-medium text-analux-plum/90 whitespace-normal">
                                {item.desc}
                              </p>
                              <div className="absolute -bottom-6 left-1/2 -translate-x-1/2 w-4 h-4 bg-white/90 border-r border-b border-analux-plum/10 rotate-45" />
                            </div>
                          </div>
                        </div>
                      </div>
                    ))}
                  </div>
                ))}
              </div>
            ))}
          </div>
        </section>

        {/* DIFFERENTIATION SECTION */}
        <section id="experiencia" data-header-theme="light" className="py-24 lg:py-32 bg-white/40 px-6">
          <div className="max-w-7xl mx-auto flex flex-col lg:grid lg:grid-cols-[0.8fr_1.2fr] gap-16 lg:gap-24 items-start">
            <div className="space-y-10 lg:sticky lg:top-48 w-full text-center lg:text-left flex flex-col items-center lg:items-start">
              <SectionEyebrow>
                {content.differentiation.eyebrow}
              </SectionEyebrow>
              <h2 className="text-4xl md:text-5xl lg:text-7xl font-display leading-[1.1] lg:leading-[0.95] max-w-lg">
                {content.differentiation.title}
              </h2>
            </div>
            <div className="flex flex-col gap-8 w-full">
               <div className="p-10 lg:p-14 rounded-[3rem] border border-analux-plum/5 bg-white shadow-3xl shadow-analux-plum/5 backdrop-blur-xl group hover:shadow-analux-plum/10 transition-all duration-500">
                 <div className="text-xl lg:text-2xl leading-relaxed font-light text-analux-plum/90">
                   {content.differentiation.text1}
                 </div>
                 <div className="mt-10 text-lg leading-relaxed text-analux-plum/60 font-light italic">
                    {content.differentiation.text2}
                 </div>

                 <div className="mt-12 flex flex-col sm:flex-row gap-4">
                    <button onClick={scrollToPlans} className="analux-button-primary px-8 py-5 rounded-full font-bold uppercase tracking-widest text-[11px] flex items-center justify-center gap-3">
                      Quero viver a experiência analux <ArrowRight size={18} />
                    </button>
                    <button className="analux-button-secondary px-8 py-5 rounded-full font-bold uppercase tracking-widest text-[11px] flex items-center justify-center gap-3">
                      <CirclePlay size={18} /> Ver o que torna a box exclusiva
                    </button>
                 </div>
               </div>
               <div className="relative rounded-[3rem] overflow-hidden group shadow-3xl bg-white border border-analux-plum/5">
                   <div className="relative z-10 p-5 lg:p-10 text-analux-plum/90">
                      {(content.differentiation.card?.items || [
                          { id: '1', icon: 'Gift', title: "Mais que joias", desc: "Uma entrega pensada para ter atmosfera de edição especial." },
                          { id: '2', icon: 'Sparkles', title: "Mais que surpresa", desc: "Descoberta recorrente com linguagem visual e intenção de marca." },
                          { id: '3', icon: 'ShieldCheck', title: "Mais que recorrência", desc: "Uma forma elegante de manter desejo, vínculo e estilo." }
                        ]).map((item) => {
                          const IconComponent = (Icons as any)[item.icon] || Icons.HelpCircle;
                          return (
                            <div key={item.id} className="flex gap-5 mb-5 last:mb-0">
                               <div className="text-analux-gold-soft">
                                  <IconComponent size={24} />
                               </div>
                               <div>
                                 <h4 className="font-display text-2xl lg:text-3xl leading-none text-analux-plum">{item.title}</h4>
                                 <p className="text-analux-plum/60 text-[13px] lg:text-sm mt-3 leading-relaxed max-w-sm">{item.desc}</p>
                               </div>
                            </div>
                          );
                        })}
                   </div>
                </div>

                {/* CATEGORY CAROUSEL BLOCK - 3rd element of the Right Column */}
                <div className="mt-16 w-full group/carousel">
                  <div className="flex items-end justify-between mb-10">
                    <div className="text-center lg:text-left">
                      <SectionEyebrow>Categorias</SectionEyebrow>
                      <h3 className="mt-4 text-2xl lg:text-4xl font-display text-analux-plum flex items-center justify-center lg:justify-start gap-4">
                        Explore nossos acessórios <ArrowRight size={20} className="text-analux-gold-strong" />
                      </h3>
                    </div>
                    
                    {/* Discrete Arrows */}
                    <div className="hidden lg:flex gap-3 pr-2">
                       <button 
                         onClick={() => scrollCategories('left')}
                         className="w-10 h-10 rounded-full border border-analux-plum/10 flex items-center justify-center text-analux-plum/40 hover:text-analux-gold-strong hover:border-analux-gold-strong/30 hover:bg-analux-gold-soft/5 transition-all"
                       >
                         <ChevronLeft size={20} />
                       </button>
                       <button 
                         onClick={() => scrollCategories('right')}
                         className="w-10 h-10 rounded-full border border-analux-plum/10 flex items-center justify-center text-analux-plum/40 hover:text-analux-gold-strong hover:border-analux-gold-strong/30 hover:bg-analux-gold-soft/5 transition-all"
                       >
                         <ChevronRight size={20} />
                       </button>
                    </div>
                  </div>

                  <div className="relative">
                    <div 
                      ref={categoryScrollRef}
                      onScroll={handleCategoryScroll}
                      className="flex overflow-x-auto pb-10 gap-6 snap-x snap-mandatory scrollbar-hide -mx-2 px-2 scroll-smooth"
                    >
                      {[
                        { title: "Pulseiras & Braceletes", image: "/media/categories/pulseiras.png" },
                        { title: "Brincos", image: "/media/categories/brincos.png" },
                        { title: "Colares", image: "/media/categories/colares.png" },
                        { title: "Tornezeleiras", image: "/media/categories/tornezeleiras.png" },
                        { title: "Anéis", image: "/media/categories/aneis.png" },
                      ].map((cat, idx) => (
                        <div 
                          key={idx} 
                          className="relative min-w-[75%] sm:min-w-[48%] aspect-[4/5] rounded-[2rem] overflow-hidden group snap-start shadow-xl cursor-pointer"
                        >
                          <img 
                            src={cat.image} 
                            alt={cat.title} 
                            className="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-110"
                          />
                          <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/10 to-transparent opacity-80 group-hover:opacity-100 transition-opacity" />
                          <div className="absolute inset-0 p-6 flex flex-col justify-end items-start transform translate-y-2 group-hover:translate-y-0 transition-transform duration-500">
                             <h4 className="text-white font-display text-xl lg:text-2xl leading-tight mb-4">{cat.title.toUpperCase()}</h4>
                             <button className="bg-analux-gold-strong hover:bg-analux-gold-strong/90 text-white px-5 py-2.5 rounded-full text-[9px] font-bold uppercase tracking-[0.2em] transition-all flex items-center gap-2">
                               Veja mais <Plus size={12} />
                             </button>
                          </div>
                        </div>
                      ))}
                    </div>

                    {/* Mimetized Scroll Progress Indicator */}
                    <div className="mt-4 flex items-center justify-center lg:justify-start">
                      <div className="h-[2px] w-32 lg:w-48 bg-analux-plum/5 relative rounded-full overflow-hidden">
                        <div 
                          className="absolute h-full bg-analux-gold-strong transition-all duration-300 ease-out rounded-full"
                          style={{ 
                            width: '40%', 
                            left: `${scrollProgress * 0.6}%` 
                          }}
                        />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
           </div>
         </section>

        {/* WHAT'S INSIDE SECTION */}
        <section id="box" data-header-theme="light" className="py-20 lg:py-32 bg-analux-cream-deep/20 px-4 lg:px-6">
          <div className="max-w-7xl mx-auto grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div className="relative order-2 lg:order-1">
              <div className="analux-hero-card p-3 rotate-[-2deg] scale-95 opacity-50 absolute inset-0"></div>
              <div className="analux-hero-card p-4 relative bg-white shadow-2xl">
                 <img 
                    src="/media/recebe-box-01.jpg" 
                    className="rounded-[2rem] aspect-[4/5] object-cover" 
                    alt="Box Detail" 
                  />
              </div>
            </div>
            <div className="order-1 lg:order-2 space-y-10">
              <SectionEyebrow>
                {content.box?.eyebrow || "O que você recebe"}
              </SectionEyebrow>
              <h2 className="text-4xl md:text-5xl lg:text-7xl font-display leading-[1.1] lg:leading-[0.95]">
                {content.box?.title || "O que chega na sua Box"}
              </h2>
              <p className="text-lg lg:text-xl text-analux-plum/60 font-light max-w-xl">
                 {content.box?.description || "Uma curadoria exclusiva, enviada mensalmente com todo o carinho que você merece."}
              </p>
              
              <div className="space-y-4">
                {(content.box?.items || [
                    { title: "Joias com direção estética", desc: "Peças escolhidas para conversar entre si e valorizar suas composições." },
                    { title: "Uma entrega que parece presente", desc: "Box desenhada para criar expectativa real e exclusividade." },
                    { title: "Elementos surpresa", desc: "Detalhes e atmosfera que fazem do recebimento um ritual de luxo." }
                  ]).map((item, i) => {
                  const IconComp = (Icons as any)[item.icon] || Icons.Check;
                  return (
                    <div key={i} className="flex items-start gap-4 p-6 rounded-[2rem] bg-white/40 border border-analux-plum/5 hover:bg-white/60 transition-all">
                      <div className="shrink-0 p-3 bg-analux-gold-soft/20 text-analux-gold-strong rounded-2xl">
                        <IconComp size={24} />
                      </div>
                      <div>
                        <h4 className="font-display text-xl mb-1">{item.title}</h4>
                        <p className="text-sm font-light text-analux-plum/60 leading-relaxed">{item.desc}</p>
                      </div>
                    </div>
                  );
                })}
              </div>
            </div>
          </div>
        </section>

        {/* WHO IT'S FOR SECTION */}
        <section data-header-theme="light" className="py-20 lg:py-32 px-4 lg:px-6">
          <div className="max-w-7xl mx-auto grid lg:grid-cols-[1fr_0.8fr] gap-12 lg:gap-20 items-center">
             <div className="space-y-10">
                <SectionEyebrow>
                  {content.whoItsFor?.eyebrow || "Para quem é"}
                </SectionEyebrow>
                <h2 className="text-3xl md:text-5xl lg:text-6xl font-display leading-[0.95]">
                   {content.whoItsFor?.title || "Para mulheres que sentem que o detalhe muda tudo."}
                </h2>
                <div className="space-y-6 text-lg lg:text-xl font-light text-analux-plum/70 leading-relaxed whitespace-pre-line">
                   {content.whoItsFor?.description || "A Analux Box é para quem entende que joia não é excesso. É assinatura. É para mulheres que gostam de se montar com mais intenção, se presentear com elegância e construir presença.\n\nSe você valoriza beleza, curadoria e a sensação de receber algo pensado exclusivamente para você, essa experiência foi criada no seu ritmo."}
                </div>
                <button onClick={scrollToPlans} className="analux-button-primary w-full sm:w-auto px-8 sm:px-10 py-5 sm:py-6 rounded-full font-bold uppercase tracking-widest text-xs flex justify-center items-center gap-3">
                  Iniciar minha Assinatura <ArrowRight size={18} />
                </button>
             </div>
             <div className="grid grid-cols-2 gap-4">
                <div className="analux-hero-card overflow-hidden h-full min-h-[300px] lg:h-[500px]">
                  <img 
                    src={content.whoItsFor?.image1 || assets.productModel} 
                    className="w-full h-full object-cover" 
                    alt="Model" 
                  />
                </div>
                <div className="space-y-4">
                  <div className="analux-stat-card bg-[#281119] text-white p-8 h-fit">
                    <p className="text-[9px] uppercase tracking-widest opacity-40 mb-3">Filosofia</p>
                    <p className="font-display text-2xl italic">
                       {content.whoItsFor?.quote || "\"Joias que não competem com você. Elas refinam o que você já transmite.\""}
                    </p>
                  </div>
                  <div className="analux-hero-card overflow-hidden h-64 relative group">
                     <img 
                      src={content.whoItsFor?.image2 || assets.lifestyle} 
                      className="w-full h-full object-cover" 
                      alt="Lifestyle" 
                    />
                  </div>
                </div>
             </div>
           </div>
         </section>

        {/* RITUAL SECTION (HOW IT WORKS) */}
        <section data-header-theme="dark" className="py-20 lg:py-32 bg-analux-plum relative overflow-hidden px-4 lg:px-6">
           <div className="absolute inset-0 opacity-20">
             <img src={assets.goldPanel} className="w-full h-full object-cover" alt="Gold Panel" />
           </div>
           
           <div className="max-w-7xl mx-auto relative z-10 space-y-16 lg:space-y-24">
              <div className="text-center space-y-6">
                <SectionEyebrow light>
                  {content.ritual?.eyebrow || "O Ritual"}
                </SectionEyebrow>
                <h2 className="text-4xl md:text-6xl font-display text-white italic">
                  {content.ritual?.title || "Sua casa, seu santuário."}
                </h2>
              </div>
 
             <div className="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <div className="space-y-10 lg:space-y-12">
                   {(content.ritual?.steps || [
                       { id: "1", n: "01", t: "O Unboxing", d: "A caixa é apenas o começo. Aroma, toque e visual criam a antecipação necessária para o que vem a seguir." },
                       { id: "2", n: "02", t: "A Descoberta", d: "Cada joia é apresentada com direção de estilo, sugerindo como ela pode transformar seu look do dia." },
                       { id: "3", n: "03", t: "A Pose", d: "Conecte-se com sua nova versão. Joias têm o poder de mudar como você se sente no espelho." }
                     ]).map((item: any, i: number) => (
                        <div key={item.id || i} className="flex gap-6 lg:gap-8 group">
                          <span className="text-3xl lg:text-4xl font-display text-analux-gold-strong leading-none">{item.n}</span>
                          <div className="space-y-2">
                            <h4 className="text-xl lg:text-2xl font-display text-white tracking-wide">{item.t}</h4>
                            <p className="text-sm lg:text-base text-white/50 font-light leading-relaxed max-w-md">{item.d}</p>
                          </div>
                        </div>
                    ))}
                </div>

                <div className="relative group aspect-square lg:aspect-video rounded-[3rem] overflow-hidden shadow-2xl border border-white/10">
                   <video 
                    className="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" 
                    autoPlay 
                    loop 
                    muted 
                    playsInline 
                    poster={assets.heroBg}
                  >
                    <source src={assets.editorialVideo} type="video/mp4" />
                  </video>
                  <div className="absolute inset-0 bg-black/20 flex items-center justify-center">
                    <div className="text-center p-4 lg:p-6 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                       <p className="text-[10px] font-bold uppercase tracking-widest text-analux-gold-soft">Curadoria em Movimento</p>
                       <p className="font-display text-xl lg:text-2xl text-white mt-1 italic">Presença editorial premium</p>
                    </div>
                  </div>
                </div>
             </div>
          </div>
        </section>


        {/* PORTAL ECOSYSTEM SHOWCASE - DNA & COFRE */}
        {/* PORTAL ECOSYSTEM SHOWCASE - DNA & COFRE */}
        <section data-header-theme="light" className="py-20 lg:py-32 bg-white relative overflow-hidden px-4 lg:px-6 border-y border-analux-plum/5">
           {/* Background Accents - Now relative to the full section */}
           <div className="absolute top-0 right-0 w-full lg:w-1/2 h-full bg-analux-cream-deep/20 pointer-events-none" />
           <div className="absolute -bottom-24 -right-24 w-64 lg:w-96 h-64 lg:h-96 bg-analux-gold-soft/10 rounded-full blur-3xl pointer-events-none" />

           <div className="max-w-7xl mx-auto relative z-10">
             <div className="text-center mb-16 lg:mb-20 space-y-6">
               <SectionEyebrow>Ecossistema Digital</SectionEyebrow>
               <h2 className="text-4xl md:text-5xl lg:text-7xl font-display text-analux-plum leading-tight">
                 Beleza que vai além <br className="hidden md:block" />
                 <span className="italic text-analux-gold-strong">do acessório físico.</span>
               </h2>
             </div>

             <div className="grid lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-analux-plum/5 relative overflow-hidden rounded-[3rem] lg:rounded-[4rem]">
                
                {/* Column 1: MEU DNA */}
                <div className="p-10 lg:p-20 flex flex-col group/dna relative overflow-hidden">
                  <div className="absolute -top-10 -left-10 w-40 h-40 bg-analux-gold-soft/5 rounded-full blur-3xl group-hover/dna:bg-analux-gold-soft/10 transition-all duration-700" />
                  
                  <div className="relative z-10 space-y-8">
                    <div className="w-16 h-16 lg:w-20 lg:h-20 rounded-3xl bg-analux-gold-strong text-white flex items-center justify-center shadow-2xl shadow-analux-gold-strong/20 group-hover/dna:scale-110 group-hover/dna:rotate-6 transition-transform duration-500">
                      <Sparkles size={32} />
                    </div>
                    
                    <div className="space-y-4">
                      <h3 className="text-3xl lg:text-5xl font-display text-analux-plum">
                        {content.portal?.dna?.title || "O Seu DNA"}
                        <br/>
                        <span className="italic text-analux-gold-strong">
                          {content.portal?.dna?.titleItalic || "de Estilo"}
                        </span>
                      </h3>
                      <p className="text-lg lg:text-xl text-analux-plum/60 font-light leading-relaxed">
                        {content.portal?.dna?.desc || "Onde a ciência do estilo encontra sua intuição. Nosso portal utiliza inteligência de curadoria para mapear sua essência estética."}
                      </p>
                    </div>

                    <div className="p-6 rounded-2xl bg-analux-cream/30 border border-analux-plum/5">
                      <p className="text-sm lg:text-base text-analux-plum/80 leading-relaxed italic">
                        {content.portal?.dna?.quote || "\"Ao assinar, você desbloqueia uma jornada de autoconhecimento que garante que cada peça Analux seja a moldura perfeita para quem você é hoje.\""}
                      </p>
                    </div>
                  </div>
                </div>

                {/* Column 2: COFRE DE JOIAS */}
                <div className="p-10 lg:p-20 flex flex-col group/vault bg-analux-plum/[0.02] relative overflow-hidden">
                  <div className="absolute -bottom-10 -right-10 w-40 h-40 bg-analux-plum/5 rounded-full blur-3xl group-hover/vault:bg-analux-plum/10 transition-all duration-700" />
                  
                  <div className="relative z-10 space-y-8">
                    <div className="w-16 h-16 lg:w-20 lg:h-20 rounded-3xl bg-analux-plum text-white flex items-center justify-center shadow-2xl shadow-analux-plum/20 group-hover/vault:scale-110 group-hover/vault:-rotate-6 transition-transform duration-500">
                      <Gem size={32} />
                    </div>
                    
                    <div className="space-y-4">
                      <h3 className="text-3xl lg:text-5xl font-display text-analux-plum">
                        {content.portal?.vault?.title || "O Seu Cofre"}
                        <br/>
                        <span className="italic">
                          {content.portal?.vault?.titleItalic || "de Joias"}
                        </span>
                      </h3>
                      <p className="text-lg lg:text-xl text-analux-plum/60 font-light leading-relaxed">
                        {content.portal?.vault?.desc || "O santuário digital da sua coleção. Visualize suas peças, acompanhe sua trajetória e gerencie seu portfólio de acessórios com segurança total."}
                      </p>
                    </div>

                    <div className="p-6 rounded-2xl bg-white/60 border border-analux-plum/5">
                      <p className="text-sm lg:text-base text-analux-plum/80 leading-relaxed italic">
                        {content.portal?.vault?.quote || "\"Mais do que organizar, o Cofre de Joias eterniza suas conquistas e permite vislumbrar novas combinações em um ambiente de elegância digital.\""}
                      </p>
                    </div>
                  </div>
                </div>
              </div>

              {/* Bottom Unified CTA */}
              <div className="relative z-10 bg-white/50 backdrop-blur-md p-8 lg:p-12 border-t border-analux-plum/5 text-center">
                <button 
                  onClick={scrollToPlans} 
                  className="analux-button-primary w-full max-w-2xl py-6 lg:py-10 rounded-3xl font-bold uppercase tracking-[0.3em] text-sm group flex items-center justify-center gap-6 mx-auto shadow-2xl shadow-analux-plum/20 hover:-translate-y-1 transition-all"
                >
                  Quero entrar para o clube e descobrir meu DNA
                  <ArrowRight className="group-hover:translate-x-3 transition-transform" />
                </button>
                <p className="mt-6 text-[10px] uppercase tracking-widest font-bold text-analux-plum/40">
                  Acesso imediato ao portal após a confirmação da assinatura
                </p>
              </div>
          </div>
        </section>

        {/* PLANOS SECTION */}
        <section id="planos" data-header-theme="light" className="py-20 lg:py-32 px-4 lg:px-6 border-y border-analux-plum/5" style={{ backgroundColor: '#fcf8f3' }}>
          <div className="max-w-7xl mx-auto">
            <div className="text-center space-y-6 lg:space-y-8 mb-16 lg:mb-24">
              <SectionEyebrow>
                {content.plans?.eyebrow || "Assinatura Analux"}
              </SectionEyebrow>
              <h2 className="text-4xl md:text-5xl lg:text-6xl font-display text-analux-plum">
                 {content.plans?.title || "Escolha seu Plano"}
              </h2>

              <div className="flex flex-col items-center gap-6">
                <div className="flex items-center gap-2 bg-white p-1.5 rounded-full border border-analux-plum/10 shadow-sm relative w-fit mx-auto overflow-hidden sm:overflow-visible scale-90 sm:scale-100">
                  <div
                    className={`absolute top-1.5 bottom-1.5 rounded-full bg-analux-plum transition-all duration-500 ease-in-out w-28 sm:w-32 ${billingCycle === 'monthly' ? 'left-1.5' : billingCycle === 'quarterly' ? 'left-[126px] sm:left-[142px]' : 'left-[246px] sm:left-[278px]'
                      }`}
                  ></div>
                  <button
                    onClick={() => setBillingCycle('monthly')}
                    className={`relative z-10 px-2 sm:px-6 py-3 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 w-28 sm:w-32 ${billingCycle === 'monthly' ? 'text-white' : 'text-analux-plum/40 hover:text-analux-plum'}`}
                  >
                    Flex
                  </button>
                  <button
                    onClick={() => setBillingCycle('quarterly')}
                    className={`relative z-10 px-2 sm:px-6 py-3 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 w-28 sm:w-32 ${billingCycle === 'quarterly' ? 'text-white' : 'text-analux-plum/40 hover:text-analux-plum'}`}
                  >
                    Trimestral
                  </button>
                  <button
                    onClick={() => setBillingCycle('semiannual')}
                    className={`relative z-10 px-2 sm:px-6 py-3 text-[9px] sm:text-[10px] font-bold uppercase tracking-widest transition-colors duration-300 w-28 sm:w-32 ${billingCycle === 'semiannual' ? 'text-white' : 'text-analux-plum/40 hover:text-analux-plum'}`}
                  >
                     Semestral
                  </button>
                </div>
                {billingCycle !== 'monthly' && (
                  <div className="flex items-center gap-2 text-analux-gold-strong font-bold text-[10px] uppercase tracking-widest animate-fadeIn">
                    <Gem size={12} /> Acesso a Benefício Exclusivo de Adesão
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
                    className={`relative p-12 rounded-[3.5rem] flex flex-col justify-between ${plan.featured ? '' : 'overflow-hidden'} transition-all duration-500 hover:translate-y-[-10px] animate-fadeIn ${plan.featured
                      ? 'bg-analux-plum text-white shadow-2xl scale-105 z-10'
                      : 'text-analux-plum border border-analux-plum/5 shadow-3xl'
                      }`}
                  >
                    {!plan.featured && (
                      <>
                        <img src={assets.heroBg} className="absolute inset-0 w-full h-full object-cover opacity-60" alt="BG" />
                        <div className="absolute inset-0 bg-gradient-to-br from-analux-cream via-analux-cream-deep/90 to-analux-sand/80" />
                      </>
                    )}

                    {plan.featured && billingCycle !== 'monthly' && (
                      <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-analux-gold-strong text-white px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-lg z-20">
                        {billingCycle === 'quarterly' ? 'Melhor Adesão' : 'Recomendado'}
                      </div>
                    )}

                    <div className="relative z-10 flex flex-col justify-between h-full">
                      <div>
                        <div className="flex justify-between items-start mb-6">
                          <p className={`text-xs font-bold uppercase tracking-[0.3em] ${plan.featured ? 'text-analux-gold-soft' : 'text-analux-plum/40'}`}>Plano {plan.name}</p>
                          {billingCycle !== 'monthly' && (
                            <span className={`${plan.featured ? 'bg-white/20 text-white' : 'bg-analux-gold-strong/20 text-analux-gold-strong'} px-2 py-1 rounded text-[9px] font-bold uppercase`}>Save Deal</span>
                          )}
                        </div>
                        <div className="mb-8">
                          <p className={`text-xl font-light leading-relaxed mb-6 ${plan.featured ? 'text-white/70' : 'text-analux-plum/70'}`}>{plan.desc}</p>
                          <div className="flex items-baseline gap-2">
                            <span className="text-5xl font-display">R$ {currentData.price}</span>
                          </div>
                          <span className={`text-xs block mt-1 ${plan.featured ? 'text-white/50' : 'text-analux-plum/40'}`}>
                            {billingCycle === 'monthly' ? 'Sem permanência mínima' : `Compromisso de ${billingCycle === 'quarterly' ? '3' : '6'} meses`}
                          </span>
                        </div>

                        <div className="space-y-5 mb-12">
                          {currentData.perks.map((perk: string, j: number) => (
                            <div key={j} className="flex items-center gap-4">
                              <CheckCircle2 size={18} className={plan.featured ? 'text-analux-gold-soft' : 'text-analux-plum'} />
                              <span className="text-sm font-medium">{perk}</span>
                            </div>
                          ))}
                        </div>
                      </div>

                      <button
                        onClick={() => handleSubscribe(plan, billingCycle)}
                        className={plan.featured
                          ? 'w-full py-6 rounded-3xl font-bold text-xs uppercase tracking-widest transition-all bg-analux-gold-strong text-white shadow-lg hover:bg-white hover:text-analux-plum'
                          : 'analux-button-primary w-full py-7 rounded-full font-bold uppercase tracking-[0.3em] text-xs group'
                        }
                      >
                        Assinar Agora
                      </button>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>
        </section>

        {/* FAQ SECTION */}
        <section id="faq" data-header-theme="light" className="py-20 lg:py-32 px-4 lg:px-6">
          <div className="max-w-4xl mx-auto space-y-12 lg:space-y-20">
            <div className="text-center space-y-6 px-4">
              <SectionEyebrow>Dúvidas Frequentes</SectionEyebrow>
              <h2 className="text-3xl md:text-5xl lg:text-6xl font-display italic">Clareza também faz parte da experiência.</h2>
            </div>
            
            <div className="space-y-4">
              {(content.faqItems || [
                { question: "Como funciona a curadoria?", answer: "Ao assinar, você preencherá um perfil de estilo. Nossa curadoria editorial seleciona as peças que melhor combinam com sua essência e banhos preferidos." },
                { question: "O que recebo exatamente?", answer: "Você receberá uma box luxuosa contendo até 5 semijoias de alta qualidade, embaladas com todo o ritual de beleza Analux." },
                { question: "Posso cancelar ou pausar?", answer: "Sim. No plano Flex você tem total liberdade. Nos planos com fidelidade, você pode gerenciar sua assinatura pelo portal após o período mínimo." },
                { question: "As peças têm garantia?", answer: "Sim. Todas as peças Analux acompanham certificado de garantia e instruções de cuidado para sua longevidade." }
              ]).map((item: any, idx: number) => (
                <div key={idx} className="bg-white rounded-[2rem] overflow-hidden border border-analux-plum/5 shadow-sm transition-all hover:shadow-md">
                  <button 
                    onClick={() => setOpenFaq(openFaq === idx ? null : idx)}
                    className="w-full p-6 lg:p-8 flex items-center justify-between text-left group"
                  >
                    <span className="text-lg lg:text-xl pr-4 font-display font-medium text-analux-plum group-hover:text-analux-gold-strong transition-colors">
                      {item.question}
                    </span>
                    <div className="text-analux-gold-strong">
                      {openFaq === idx ? <Minus size={20} /> : <Plus size={20} />}
                    </div>
                  </button>
                  {openFaq === idx && (
                    <div className="px-6 pb-6 lg:px-8 lg:pb-8 text-sm lg:text-base text-analux-plum/60 font-light leading-relaxed animate-fadeIn">
                       {item.answer}
                    </div>
                  )}
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* FOOTER MANIFESTO */}
        <footer data-header-theme="dark" className="py-24 lg:py-40 bg-analux-plum text-white px-4 lg:px-6 text-center relative overflow-hidden">
           <img src={assets.goldPanel} className="absolute inset-0 w-full h-full object-cover opacity-10 pointer-events-none" alt="Footer Texture" />
           <div className="max-w-4xl mx-auto relative z-10 space-y-12 lg:space-y-16">
              <div className="flex justify-center mb-10 lg:mb-16">
                <img 
                  src="/media/logo03.png" 
                  alt="Analux Logo" 
                  className="h-10 lg:h-14 w-auto opacity-100" 
                />
              </div>
              
              <h2 className="text-3xl md:text-5xl lg:text-7xl font-display italic leading-tight px-2">
                {content.footer?.manifestoTitle || "\"A moda é o que você compra, estilo é o que você faz com ela.\""}
              </h2>
              
              <div className="space-y-4 text-lg lg:text-xl font-light text-white/50 px-4">
                {(content.footer?.paragraphs || [
                    "A Analux é para mulheres que sabem aonde querem chegar.",
                    "É o detalhe que traz intenção. É a peça que vira herança."
                  ]).map((p: string, i: number) => (
                    <p key={i}>{p}</p>
                  ))}
              </div>

              <div className="pt-6 lg:pt-10">
                <button onClick={scrollToPlans} className="analux-button-primary-light px-8 py-5 lg:px-12 lg:py-7 w-full sm:w-auto rounded-full text-[10px] sm:text-xs lg:text-sm font-bold uppercase tracking-widest shadow-2xl">
                  Escolher meu Plano e Iniciar Ritual
                </button>
              </div>

              <div className="pt-20 lg:pt-32 flex flex-wrap justify-center gap-8 md:gap-12 text-[9px] md:text-[10px] font-bold uppercase tracking-[0.2em] md:tracking-[0.3em] opacity-30">
                 <div className="flex flex-col items-center gap-3 lg:gap-4 w-[40%] sm:w-auto">
                    <ShieldCheck size={24} /> <span className="text-center">Sigilo & <br className="sm:hidden"/>Segurança</span>
                 </div>
                 <div className="flex flex-col items-center gap-3 lg:gap-4 w-[40%] sm:w-auto">
                    <Sparkles size={24} /> <span className="text-center">Curadoria <br className="sm:hidden"/>Certificada</span>
                 </div>
                 <div className="flex flex-col items-center gap-3 lg:gap-4 w-[40%] sm:w-auto mt-4 sm:mt-0">
                    <Star size={24} /> <span className="text-center">Clube <br className="sm:hidden"/>Exclusivo</span>
                 </div>
              </div>
              
              <p className="text-[9px] opacity-20 pt-20 tracking-widest uppercase">© 2024 Analux Club. Direitos Reservados à quem brilha.</p>
           </div>
        </footer>
      </main>

      {/* BACK TO TOP BUTTON */}
      <button
        onClick={scrollToTop}
        className={`fixed bottom-8 right-8 z-[100] p-4 rounded-full bg-analux-plum/90 text-white shadow-2xl transition-all duration-500 backdrop-blur-md border border-white/10 hover:bg-analux-gold-strong hover:scale-110 active:scale-95 group ${
          showBackToTop ? 'opacity-100 translate-y-0 pointer-events-auto' : 'opacity-0 translate-y-10 pointer-events-none'
        }`}
        aria-label="Voltar para o topo"
      >
        <ChevronUp size={24} className="group-hover:animate-bounce" />
      </button>
    </div>
  );
};

export default LandingPageC;
