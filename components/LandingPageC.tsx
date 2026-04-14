import React, { useState } from 'react';
import {
  Sparkles,
  ArrowRight,
  CheckCircle2,
  ShieldCheck,
  Gem,
  Plus,
  Minus,
  Instagram,
  ChevronRight,
  Smartphone,
  Calendar,
  Layers,
  Search,
  Zap
} from 'lucide-react';
import { useUser } from '../context/UserContext';
import AuthModal from './AuthModal';
import SubscriptionReviewModal from './SubscriptionReviewModal';

const LandingPageC: React.FC = () => {
  const { setIsMember, isMember } = useUser();
  const [openFaq, setOpenFaq] = useState<number | null>(null);
  const [billingCycle, setBillingCycle] = useState<'monthly' | 'quarterly' | 'semiannual'>('monthly');
  const [isLoginModalOpen, setIsLoginModalOpen] = useState<boolean | 'signup'>(false);
  const [reviewModalData, setReviewModalData] = useState<any>(null);

  const onJoin = () => setIsLoginModalOpen(true);
  const scrollToPlans = () => document.getElementById('plans')?.scrollIntoView({ behavior: 'smooth' });

  const faqItems = [
    {
      question: "Por que a Analux é considerada uma assinatura premium?",
      answer: "Diferente de boxes comuns que focam em volume de peças, a Analux foca em curadoria estratégica. Nossas peças são selecionadas para mulheres que buscam presença e sofisticação no dia a dia, com qualidade de joalheria (banho ouro 18k e ródio branco) e uma experiência digital exclusiva no Portal Analux."
    },
    {
      question: "Como funciona a personalização do Style DNA?",
      answer: "No momento da adesão, você preenche um perfil detalhado de estilo. Nossa curadoria usa esses dados para garantir que as peças enviadas façam sentido com o seu guarda-roupa e sua rotina, reduzindo significativamente a taxa de devolução e aumentando sua satisfação."
    },
    {
      question: "O acesso ao Portal Analux está incluso em todos os planos?",
      answer: "Sim. Todas as nossas assinantes têm acesso imediato ao Portal Analux, onde gerenciam seu acervo digital, acompanham o status do ritual mensal e participam da nossa comunidade exclusiva."
    },
    {
      question: "Posso cancelar ou pausar a assinatura?",
      answer: "Sem burocracias. No Portal Analux, você tem total autonomia para pausar sua box (caso vá viajar, por exemplo) ou cancelar renovações futuras com apenas um clique."
    }
  ];

  const plans = [
    {
      name: 'Essencial',
      desc: 'Para quem busca elegância no cotidiano.',
      featured: false,
      monthly: { price: '147', perks: ['2 peças curatoriais/mês', 'Acesso ao Portal Analux', 'Style DNA Inicial', 'Cashback 2%'] },
      quarterly: { price: '127', perks: ['2 peças curatoriais/mês', 'Acesso ao Portal Analux', 'Style DNA Inicial', 'Cashback 4%'] },
      semiannual: { price: '117', perks: ['2 peças curatoriais/mês', 'Acesso ao Portal Analux', 'Style DNA Inicial', 'Cashback 6%'] },
    },
    {
      name: 'Ritual',
      desc: 'O equilíbrio perfeito entre presença e variedade.',
      featured: true,
      monthly: { price: '297', perks: ['3 peças exclusivas/mês', 'Mimos trimestrais', 'Digital Vault Completo', 'Cashback 5%'] },
      quarterly: { price: '267', perks: ['3 peças exclusivas/mês', 'Mimos trimestrais', 'Digital Vault Completo', 'Cashback 8%'] },
      semiannual: { price: '247', perks: ['3 peças exclusivas/mês', 'Mimos trimestrais', 'Digital Vault Completo', 'Cashback 10%'] },
    },
    {
      name: 'Premium',
      desc: 'A experiência máxima de curadoria e luxo.',
      featured: false,
      monthly: { price: '497', perks: ['4 peças selecionadas/mês', 'Styling personalizado', 'Frete Grátis Brasil', 'Cashback 15%'] },
      quarterly: { price: '447', perks: ['4 peças selecionadas/mês', 'Styling personalizado', 'Frete Grátis Brasil', 'Cashback 18%'] },
      semiannual: { price: '397', perks: ['4 peças selecionadas/mês', 'Styling personalizado', 'Frete Grátis Brasil', 'Cashback 20%'] },
    }
  ];

  return (
    <div className="bg-[#FFFCF9] text-[#49172d] selection:bg-analux-secondary selection:text-white font-sans overflow-x-hidden">
      
      {/* Auth & Review Modals placeholders */}
      {isLoginModalOpen && <AuthModal onClose={() => setIsLoginModalOpen(false)} onAuthSuccess={() => window.location.href = '/dashboard'} />}
      
      {/* 1. Header Editorial */}
      <nav className="fixed top-0 w-full z-50 bg-white/60 backdrop-blur-xl border-b border-analux-secondary/10 px-6 py-4">
        <div className="max-w-7xl mx-auto flex justify-between items-center">
          <div className="flex flex-col items-center">
            <span className="text-2xl font-serif font-bold tracking-widest text-[#49172d]">ANALUX</span>
            <span className="text-[9px] uppercase tracking-[0.4em] text-analux-secondary -mt-1 font-bold">The Editorial Club</span>
          </div>
          <div className="hidden lg:flex items-center gap-10 text-[10px] font-bold uppercase tracking-[0.2em] text-[#49172d]/60">
            <a href="#diferencial" className="hover:text-analux-secondary transition-colors">A Diferença</a>
            <a href="#portal" className="hover:text-analux-secondary transition-colors">Experiência Digital</a>
            <a href="#plans" className="hover:text-analux-secondary transition-colors">Planos</a>
          </div>
          <button
            onClick={onJoin}
            className="border border-[#49172d]/20 px-6 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-[#49172d] hover:text-white transition-all"
          >
            Acessar Club
          </button>
        </div>
      </nav>

      {/* 2. Hero Section: O Objeto de Desejo */}
      <section className="relative pt-44 pb-32 px-6">
        <div className="max-w-6xl mx-auto text-center space-y-10">
          <div className="flex justify-center items-center gap-3 text-analux-secondary animate-fadeIn">
            <div className="h-[1px] w-8 bg-analux-secondary"></div>
            <span className="text-[10px] font-bold uppercase tracking-[0.3em]">Curadoria Inteligente & Status</span>
            <div className="h-[1px] w-8 bg-analux-secondary"></div>
          </div>
          
          <h1 className="text-6xl md:text-8xl font-serif leading-[1.1] animate-slideUp">
            A curadoria que traduz a sua <br />
            <span className="italic text-analux-secondary">próxima fase</span> em elegância.
          </h1>
          
          <p className="max-w-xl mx-auto text-lg text-[#49172d]/70 font-light leading-relaxed animate-fadeIn" style={{ animationDelay: '0.4s' }}>
            Muito mais que uma box. Uma seleção estratégica de semijoias premium para mulheres que comunicam sucesso sem precisar dizer uma palavra.
          </p>

          <div className="flex flex-col sm:flex-row gap-5 justify-center pt-8 animate-fadeIn" style={{ animationDelay: '0.6s' }}>
            <button
              onClick={scrollToPlans}
              className="bg-[#49172d] text-white px-10 py-6 rounded-2xl font-bold flex items-center justify-center gap-3 shadow-2xl hover:bg-black transition-all group"
            >
              Iniciar meu Ritual de Estilo <ArrowRight size={18} className="group-hover:translate-x-1 transition-transform" />
            </button>
            <div className="flex -space-x-3 items-center">
              {[1,2,3,4].map(i => (
                <div key={i} className="w-10 h-10 rounded-full border-2 border-white bg-slate-200 overflow-hidden">
                   <img src={`https://i.pravatar.cc/100?img=${i+10}`} alt="Musa" />
                </div>
              ))}
              <p className="pl-6 text-[10px] font-bold uppercase tracking-wider text-slate-400">Join +500 Musas Analux</p>
            </div>
          </div>
        </div>
      </section>

      {/* 3. Diferenciação: Por que não é uma box comum */}
      <section id="diferencial" className="py-32 bg-white px-6">
        <div className="max-w-7xl mx-auto">
          <div className="grid grid-cols-1 lg:grid-cols-2 gap-24 items-center">
            <div className="relative">
              <div className="aspect-[4/5] rounded-[60px] overflow-hidden shadow-3xl">
                <img 
                  src="https://images.unsplash.com/photo-1576426863848-c21f53c60b19?auto=format&fit=crop&q=80&w=800" 
                  className="w-full h-full object-cover" 
                  alt="Elegância Analux" 
                />
              </div>
              <div className="absolute -bottom-10 -left-10 bg-[#FFFCF9] p-10 rounded-[40px] shadow-xl border border-slate-50 max-w-sm hidden md:block">
                 <p className="text-xl font-serif text-[#49172d] mb-2 italic">"Não é sobre volume, é sobre as peças certas."</p>
                 <p className="text-[10px] font-bold uppercase tracking-widest text-analux-secondary">Fundadora Analux</p>
              </div>
            </div>

            <div className="space-y-12">
              <h2 className="text-5xl font-serif leading-tight">Esqueça as boxes de volume. <br /><span className="text-analux-secondary">Assine inteligência.</span></h2>
              
              <div className="space-y-8">
                {[
                  { title: "Curadoria sobre catálogo", desc: "Nós não enviamos o que sobrou no estoque. Escolhemos o que elevará sua imagem de acordo com o seu Style DNA." },
                  { title: "Joalheria sobre bijuteria", desc: "Peças com banho intenso de Ouro 18k e Ródio, preparadas para acompanhar sua rotina executiva com brilho duradouro." },
                  { title: "Comunidade sobre transação", desc: "A Analux é um ecossistema de mulheres empresárias que compartilham o mesmo repertório estético." }
                ].map((item, idx) => (
                  <div key={idx} className="flex gap-6 items-start">
                    <div className="mt-1 p-2 bg-analux-secondary/10 rounded-xl text-analux-secondary">
                      <Gem size={20} />
                    </div>
                    <div>
                      <h4 className="text-xl font-bold font-serif mb-2">{item.title}</h4>
                      <p className="text-[#49172d]/60 leading-relaxed font-light">{item.desc}</p>
                    </div>
                  </div>
                ))}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* 4. O Ritual (Como Funciona) */}
      <section className="py-32 bg-[#49172d] text-white px-6 relative overflow-hidden">
        <div className="max-w-7xl mx-auto relative z-10">
          <div className="text-center mb-24">
            <h2 className="text-5xl font-serif mb-6">O Ritual em 3 Atos</h2>
            <p className="text-white/40 max-w-lg mx-auto uppercase tracking-widest text-xs font-bold font-sans">Simplicidade, elegância e previsibilidade.</p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-16">
            {[
              { step: "01", title: "Mapeamento DNA", desc: "Você define suas preferências, banhos e estilo. Nosso algoritmo cria seu perfil estético exclusivo." },
              { step: "02", title: "Curadoria & Envio", desc: "Nossa equipe seleciona as peças do mês. Você recebe um unboxing sensorial, editorial e inesquecível." },
              { step: "03", title: "Acervo Digital", desc: "Suas peças são registradas no seu Digital Vault no Portal Analux. Gerencie seu patrimônio de estilo num só lugar." }
            ].map((item, i) => (
              <div key={i} className="space-y-6 relative">
                 <span className="text-7xl font-serif text-white/10 absolute -top-10 -left-6">{item.step}</span>
                 <h4 className="text-2xl font-serif text-analux-secondary">{item.title}</h4>
                 <p className="text-white/60 leading-relaxed font-light">{item.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* 5. Portal Analux: O Seu Quarto de Vestir Digital */}
      <section id="portal" className="py-32 bg-white px-6">
        <div className="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
          <div className="order-2 lg:order-1 space-y-10">
            <div className="inline-block px-4 py-2 bg-slate-100 rounded-full text-[10px] font-bold uppercase tracking-widest text-slate-500">
              Technology meets Luxury
            </div>
            <h2 className="text-5xl font-serif leading-tight">O seu espelho digital. <br />Disponível <span className="text-analux-secondary italic">24/7.</span></h2>
            <p className="text-[#49172d]/60 text-lg leading-relaxed font-light">
              O Portal Analux não é uma área de membros comum. É o cérebro da sua caixa. Um espaço para gerenciar sua imagem, registrar memórias e se conectar com outras musas.
            </p>
            
            <div className="grid grid-cols-1 sm:grid-cols-2 gap-8">
              {[
                { icon: <Smartphone />, title: "Digital Vault", desc: "Veja todas as peças que você já recebeu." },
                { icon: <Search />, title: "Style DNA", desc: "Configurações precisas de banho e arquétipos." },
                { icon: <Calendar />, title: "Ritual Path", desc: "Contagem regressiva e timeline de cada box." },
                { icon: <Layers />, title: "Memórias", desc: "Registre cliques usando suas joias favoritas." }
              ].map((item, i) => (
                <div key={i} className="flex gap-4 p-6 bg-[#FFFCF9] rounded-2xl hover:shadow-lg transition-all cursor-default group">
                   <div className="text-analux-secondary group-hover:scale-110 transition-transform">{item.icon}</div>
                   <div>
                     <h5 className="font-bold text-sm mb-1">{item.title}</h5>
                     <p className="text-[11px] text-[#49172d]/50 leading-tight">{item.desc}</p>
                   </div>
                </div>
              ))}
            </div>
          </div>

          <div className="order-1 lg:order-2 relative">
             <div className="bg-analux-secondary/10 absolute inset-0 rounded-[50px] -rotate-6 scale-95 translate-x-10"></div>
             <img 
              src="https://images.unsplash.com/photo-1556656793-062ff9878258?auto=format&fit=crop&q=80&w=800" 
              className="relative rounded-[50px] shadow-2xl border-8 border-white"
              alt="Portal App"
             />
          </div>
        </div>
      </section>

      {/* 6. Planos com Framing Premium */}
      <section id="plans" className="py-32 px-6 bg-[#FFFCF9]">
        <div className="max-w-5xl mx-auto text-center mb-24">
          <h2 className="text-6xl font-serif mb-8">Escolha seu nível de acesso</h2>
          <p className="text-[#49172d]/50 max-w-lg mx-auto leading-relaxed">Não estamos vendendo pacotes, estamos vendendo curadoria. Escolha o plano que melhor se adapta ao seu momento.</p>
          
          <div className="flex justify-center mt-12">
            <div className="flex bg-white p-1 rounded-full border border-slate-100 shadow-sm">
              {['monthly', 'quarterly', 'semiannual'].map(c => (
                <button 
                  key={c}
                  onClick={() => setBillingCycle(c as any)}
                  className={`px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all ${billingCycle === c ? 'bg-[#49172d] text-white shadow-xl' : 'text-slate-400'}`}
                >
                  {c === 'monthly' ? 'Mensal' : c === 'quarterly' ? 'Trimestral' : 'Semestral'}
                </button>
              ))}
            </div>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-7xl mx-auto">
          {plans.map((plan, i) => {
            const data = (plan as any)[billingCycle];
            return (
              <div key={i} className={`relative p-10 rounded-[40px] border flex flex-col justify-between transition-all hover:translate-y-[-10px] ${plan.featured ? 'bg-[#49172d] text-white border-none shadow-3xl scale-105 z-10' : 'bg-white border-slate-100 shadow-sm'}`}>
                {plan.featured && <div className="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-analux-secondary px-6 py-2 rounded-full text-[10px] font-bold uppercase tracking-[0.2em] shadow-lg">Mais Assinado</div>}
                
                <div>
                   <h3 className={`text-2xl font-serif mb-2 ${plan.featured ? 'text-analux-secondary' : 'text-[#49172d]'}`}>{plan.name}</h3>
                   <p className={`text-xs mb-8 ${plan.featured ? 'text-white/60' : 'text-slate-400'}`}>{plan.desc}</p>
                   
                   <div className="mb-10">
                     <span className="text-sm font-bold opacity-40 uppercase mr-1">R$</span>
                     <span className="text-5xl font-serif font-bold">{data.price}</span>
                     <span className="text-xs opacity-40 uppercase ml-1">/mês</span>
                   </div>

                   <div className="space-y-4 mb-12">
                     {data.perks.map((p: string, j: number) => (
                       <div key={j} className="flex items-center gap-3 text-sm">
                          <CheckCircle2 size={16} className={plan.featured ? 'text-analux-secondary' : 'text-[#49172d]'} />
                          <span className="opacity-80">{p}</span>
                       </div>
                     ))}
                   </div>
                </div>

                <button 
                  onClick={onJoin}
                  className={`w-full py-5 rounded-2xl font-bold text-[10px] uppercase tracking-[0.3em] transition-all ${plan.featured ? 'bg-analux-secondary text-white hover:bg-white hover:text-[#49172d]' : 'bg-[#49172d] text-white hover:bg-analux-secondary'}`}
                >
                   Garantir meu acesso
                </button>
              </div>
            );
          })}
        </div>
      </section>

      {/* 7. FAQ Estratégico & Objeções */}
      <section className="py-32 px-6 max-w-4xl mx-auto">
        <h2 className="text-4xl font-serif text-center mb-20 italic">Esclarecendo Suas Dúvidas</h2>
        <div className="space-y-4">
           {faqItems.map((item, idx) => (
             <div key={idx} className="bg-white rounded-3xl overflow-hidden border border-slate-100">
               <button 
                onClick={() => setOpenFaq(openFaq === idx ? null : idx)}
                className="w-full p-8 flex items-center justify-between text-left group"
               >
                 <span className="text-lg font-serif font-bold text-[#49172d]/80 group-hover:text-analux-secondary transition-colors">{item.question}</span>
                 <div className="text-analux-secondary">{openFaq === idx ? <Minus /> : <Plus />}</div>
               </button>
               {openFaq === idx && <div className="px-8 pb-8 text-[#49172d]/60 font-light leading-relaxed animate-fadeIn">{item.answer}</div>}
             </div>
           ))}
        </div>
      </section>

      {/* 8. Manifesto de Fechamento */}
      <footer className="py-32 bg-[#49172d] text-white px-6 text-center">
        <div className="max-w-4xl mx-auto space-y-12">
          <div className="flex flex-col items-center">
            <span className="text-4xl font-serif font-bold tracking-widest text-analux-secondary">ANALUX</span>
            <span className="text-xs uppercase tracking-[0.5em] text-white/40 -mt-1 font-bold">Club Exclusive</span>
          </div>

          <h2 className="text-4xl md:text-6xl font-serif italic max-w-2xl mx-auto leading-tight">"A moda é o que você compra, estilo é o que você faz com ela."</h2>
          
          <div className="space-y-4 font-light text-white/60">
             <p>A Analux é para mulheres que sabem aonde querem chegar.</p>
             <p>É o detalhe que traz intenção. É a peça que vira herança.</p>
             <p>Junte-se à nossa comunidade de musas hoje.</p>
          </div>

          <button 
            onClick={scrollToPlans}
            className="inline-flex items-center gap-4 bg-analux-secondary text-white px-12 py-6 rounded-2xl font-bold hover:scale-105 transition-all shadow-2xl shadow-black/40"
          >
            Escolher meu Nível de Acesso <ArrowRight />
          </button>

          <div className="pt-24 grid grid-cols-1 md:grid-cols-3 gap-8 text-[10px] font-bold uppercase tracking-widest text-white/20">
             <div className="flex flex-col items-center gap-2">
                <ShieldCheck />
                <span>Sigilo & Segurança</span>
             </div>
             <div className="flex flex-col items-center gap-2">
                <Zap />
                <span>Expedição Prioritária</span>
             </div>
             <div className="flex flex-col items-center gap-2">
                <Instagram />
                <span>@analuxbox</span>
             </div>
          </div>
          <p className="text-[10px] text-white/10 pt-10">© 2024 Analux Club. Direitos Reservados à quem brilha.</p>
        </div>
      </footer>
    </div>
  );
};

export default LandingPageC;
