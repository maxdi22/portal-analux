import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import {
    ArrowRight,
    Star,
    Check,
    Instagram,
    Facebook,
    Twitter,
    Play,
    ShoppingBag,
    Plus
} from 'lucide-react';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';
import AuthModal from './AuthModal';
import SubscriptionReviewModal from './SubscriptionReviewModal';

const LandingPageB: React.FC = () => {
    const navigate = useNavigate();
    const { user } = useUser();
    const [showAuthModal, setShowAuthModal] = useState(false);
    const [authMode, setAuthMode] = useState<'LOGIN' | 'REGISTER'>('REGISTER');

    const [reviewModalData, setReviewModalData] = useState<{
        planName: string;
        cycle: 'monthly' | 'semiannual';
        price: string;
        perks: string[];
    } | null>(null);
    const [pendingSubscription, setPendingSubscription] = useState<{ planName: string, cycle: 'monthly' | 'semiannual', price: string, perks: string[] } | null>(null);

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

    const executeCheckout = async (planName: string, cycle: 'monthly' | 'semiannual') => {
        const priceMap: Record<string, string> = {
            'Essencial-monthly':    'price_1TNjcPDWlGIoBgz1T0SlVW6H',
            'Essencial-semiannual': 'price_1TNjcPDWlGIoBgz1kexIZLN2',
            'Signature-monthly':    'price_1TNjcQDWlGIoBgz1jtpNxY9l',
            'Signature-semiannual': 'price_1TNjcQDWlGIoBgz1RidzBHQW',
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

    const handleSubscribePlan = (planName: string, cycle: 'monthly' | 'semiannual', price: string, perks: string[]) => {
        if (!user?.id) {
            setPendingSubscription({ planName, cycle, price, perks });
            setAuthMode('REGISTER');
            setShowAuthModal(true);
            return;
        }
        const data = { planName, cycle, price, perks };
        setReviewModalData(data);
        localStorage.setItem('sub_draft_active_plan', JSON.stringify(data));
    };

    const handleSubscribe = () => {
        document.getElementById('planos')?.scrollIntoView({ behavior: 'smooth' });
    };

    const handleLogin = () => {
        setAuthMode('LOGIN');
        setShowAuthModal(true);
    };

    return (
        <div className="bg-[#FFFCF9] min-h-screen font-sans selection:bg-analux-secondary/30 text-analux-primary overflow-x-hidden">

            {/* Navbar Minimalista */}
            <nav className="fixed top-0 w-full z-50 bg-[#FFFCF9]/80 backdrop-blur-md border-b border-analux-primary/5">
                <div className="max-w-[1400px] mx-auto px-6 h-20 flex items-center justify-between">
                    <div className="text-2xl font-serif font-bold tracking-widest text-analux-primary">
                        ANALUX
                    </div>
                    <div className="flex items-center gap-8">
                        <button onClick={() => navigate('/')} className="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-analux-primary transition-colors">
                            Versão A
                        </button>
                        <button onClick={handleLogin} className="text-[10px] font-bold uppercase tracking-widest text-analux-primary hover:text-analux-secondary transition-colors">
                            Login
                        </button>
                        <button
                            onClick={handleSubscribe}
                            className="bg-analux-primary text-white px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-analux-secondary transition-colors shadow-lg shadow-analux-primary/20"
                        >
                            Assinar
                        </button>
                    </div>
                </div>
            </nav>

            {/* HERO SECTION - Split Layout */}
            <header className="pt-20 min-h-screen flex flex-col md:flex-row relative">
                {/* Text Side (Left) */}
                <div className="w-full md:w-1/2 p-10 md:p-20 flex flex-col justify-center relative z-10">
                    <p className="text-[10px] font-bold tracking-[0.3em] text-analux-secondary uppercase mb-6 flex items-center gap-3">
                        <span className="w-8 h-[1px] bg-analux-secondary"></span>
                        Clube de Assinatura
                    </p>
                    <h1 className="text-6xl md:text-8xl font-serif leading-[0.9] text-analux-primary mb-8 text-balance">
                        SEMIJOIAS<br />
                        <span className="italic font-light text-analux-secondary">com alma</span><br />
                        E ELEGÂNCIA.
                    </h1>
                    <p className="text-gray-500 text-sm md:text-base max-w-md leading-relaxed mb-10">
                        Uma curadoria mensal de peças banhadas a ouro e prata, selecionadas por estilistas para elevar sua autoconfiança e transformar seu visual.
                    </p>
                    <div className="flex flex-col sm:flex-row gap-4">
                        <button
                            onClick={handleSubscribe}
                            className="bg-analux-primary text-white px-10 py-5 rounded-none text-xs font-bold uppercase tracking-widest hover:bg-analux-dark transition-all flex items-center justify-center gap-3 md:w-auto w-full group"
                        >
                            Começar Experiência <ArrowRight size={16} className="group-hover:translate-x-1 transition-transform" />
                        </button>
                    </div>

                    {/* Footer note inside Hero Left */}
                    <div className="mt-20 flex items-center gap-4 text-[9px] font-bold text-gray-400 uppercase tracking-wider">
                        <span>Frete Grátis</span>
                        <div className="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <span>Cancelamento Flexível</span>
                        <div className="w-1 h-1 bg-gray-300 rounded-full"></div>
                        <span>Curadoria Premium</span>
                    </div>
                </div>

                {/* Image Side (Right) - Abstract Shapes */}
                <div className="w-full md:w-1/2 relative min-h-[50vh] md:min-h-auto bg-[#f8f5f2] overflow-hidden flex items-center justify-center">
                    {/* Abstract Arch Shape */}
                    <div className="absolute w-[80%] h-[90%] bg-[#e8e4df] rounded-t-[500px] bottom-0 overflow-hidden">
                        <img
                            src="https://images.unsplash.com/photo-1576409545582-74d12579b295?q=80&w=2787&auto=format&fit=crop"
                            alt="Mulher elegante com semijoias"
                            className="w-full h-full object-cover opacity-90 hover:scale-105 transition-transform duration-[2s] ease-out mix-blend-multiply"
                        />
                    </div>
                    {/* Floating Circle */}
                    <div className="absolute top-20 right-10 w-40 h-40 bg-analux-secondary rounded-full mix-blend-multiply opacity-80 backdrop-blur-3xl animate-blob"></div>
                </div>
            </header>

            {/* SECTIONS GRID - 4 Columns */}
            <section className="py-24 px-6 max-w-[1400px] mx-auto">
                <div className="mb-10 text-center">
                    <p className="text-[9px] uppercase tracking-[0.3em] text-gray-400 mb-2">Coleção Outono/Inverno</p>
                    <h2 className="text-3xl font-serif text-analux-primary">Destaques da Temporada</h2>
                </div>

                <div className="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    {[
                        { title: "Colar Aurora", price: "Assinatura", img: "https://images.unsplash.com/photo-1599643478518-17488fbbcd75?q=80&w=800&auto=format&fit=crop" },
                        { title: "Brincos Luna", price: "Assinatura", img: "https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=800&auto=format&fit=crop" },
                        { title: "Anel Solitare", price: "Assinatura", img: "https://images.unsplash.com/photo-1605100804763-247f67b3557e?q=80&w=800&auto=format&fit=crop" },
                        { title: "Pulseira Elo", price: "Assinatura", img: "https://images.unsplash.com/photo-1611591437281-460bfbe1220a?q=80&w=800&auto=format&fit=crop" }
                    ].map((item, idx) => (
                        <div key={idx} className="group cursor-pointer">
                            <div className="bg-gray-100 aspect-[3/4] mb-4 overflow-hidden relative">
                                <img src={item.img} alt={item.title} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                <div className="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-colors"></div>
                                <div className="absolute bottom-4 left-0 w-full text-center opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-4 group-hover:translate-y-0">
                                    <span className="bg-white px-4 py-2 text-[10px] uppercase font-bold tracking-widest text-analux-primary">
                                        Ver Detalhes
                                    </span>
                                </div>
                            </div>
                            <h3 className="text-sm font-serif font-bold text-gray-800">{item.title}</h3>
                            <div className="flex justify-between items-center mt-1 border-t border-gray-100 pt-2">
                                <span className="text-[10px] font-bold text-gray-400 uppercase tracking-wider">{item.price}</span>
                                <button className="text-[10px] font-bold uppercase text-analux-secondary hover:text-analux-primary transition-colors">Adicionar</button>
                            </div>
                        </div>
                    ))}
                </div>

                <div className="flex justify-between items-center mt-12 px-4 border-t border-gray-100 pt-8">
                    <div className="w-3 h-3 bg-analux-primary rounded-full"></div>
                    <p className="text-[10px] uppercase tracking-[0.4em] text-gray-400">Ver Coleção Completa</p>
                    <div className="w-3 h-3 bg-analux-primary rounded-full"></div>
                </div>
            </section>

            {/* BRAND STORY - Text Left, Shapes Right */}
            <section className="py-24 bg-[#F5F2EF]">
                <div className="max-w-[1400px] mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-20 items-center">
                    <div>
                        <h2 className="text-5xl md:text-6xl font-serif leading-tight mb-8">
                            A BELEZA QUE<br /> EMERGE DO <span className="text-analux-secondary italic">cuidado</span>
                        </h2>
                        <div className="prose prose-sm text-gray-500 mb-10">
                            <p>
                                A AnaLux não é apenas sobre semijoias; é sobre o ritual de se sentir preciosa.
                                Nossas peças são desenhadas para mulheres contemporâneas que buscam
                                expressar sua individualidade sem abrir mão da elegância clássica.
                            </p>
                            <p>
                                Cada box é uma narrativa, cada peça um capítulo da sua história de estilo.
                                Permita-se brilhar com nossa curadoria exclusiva.
                            </p>
                        </div>
                        <div className="flex gap-4">
                            <div className="w-16 h-16 rounded-full bg-analux-secondary/20 flex items-center justify-center text-analux-secondary transform hover:scale-110 transition-transform cursor-pointer">
                                <Play fill="currentColor" size={24} />
                            </div>
                            <div className="flex flex-col justify-center">
                                <span className="text-xs font-bold uppercase tracking-widest text-analux-primary">Assista ao Manifesto</span>
                                <span className="text-[10px] text-gray-400">01:30 min</span>
                            </div>
                        </div>
                    </div>

                    <div className="relative h-[600px] flex items-center justify-center">
                        {/* Big abstract shape */}
                        <div className="absolute right-0 w-[400px] h-[550px] bg-analux-primary rounded-[200px] rotate-6 overflow-hidden">
                            <img
                                src="https://images.unsplash.com/photo-1531995811006-35cb42e1a022?q=80&w=2670&auto=format&fit=crop"
                                className="w-full h-full object-cover opacity-80 mix-blend-luminosity hover:mix-blend-normal transition-all duration-700"
                                alt="Manifesto"
                            />
                        </div>
                        {/* Small circle shape */}
                        <div className="absolute bottom-20 left-10 w-48 h-48 bg-white rounded-full p-2 shadow-2xl animate-float">
                            <img
                                src="https://images.unsplash.com/photo-1512163143273-bde0e3cc540f?q=80&w=600&auto=format&fit=crop"
                                className="w-full h-full object-cover rounded-full"
                                alt="Detail"
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* VALUE LADDER - Escalada de Valor */}
            <section className="py-24 bg-analux-primary text-[#FFFCF9]">
                <div className="max-w-[1400px] mx-auto px-6">
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-20 items-center">
                        <div className="relative">
                            <div className="absolute -top-10 -left-10 text-[200px] font-serif opacity-5 leading-none select-none">3x</div>
                            <h2 className="text-5xl font-serif leading-tight mb-8 relative z-10">
                                MAIS QUE UMA CAIXA.<br />
                                <span className="text-analux-secondary italic">Um investimento em você.</span>
                            </h2>
                            <p className="text-white/70 max-w-md mb-12 font-light leading-relaxed">
                                Se você for comprar cada item separadamente, pagaria muito mais.
                                A Analux Box traz curadoria de luxo por uma fração do preço,
                                garantindo que seu acervo de semijoias cresça com inteligência.
                            </p>

                            <div className="space-y-6">
                                <div className="flex items-center justify-between border-b border-white/10 pb-4">
                                    <span className="text-sm uppercase tracking-widest text-white/60">3 Semijoias Premium</span>
                                    <span className="font-serif text-xl text-white/40 line-through">R$ 389,00</span>
                                </div>
                                <div className="flex items-center justify-between border-b border-white/10 pb-4">
                                    <span className="text-sm uppercase tracking-widest text-white/60">Curadoria & Frete</span>
                                    <span className="font-serif text-xl text-white/40 line-through">R$ 59,90</span>
                                </div>
                                <div className="flex items-center justify-between border-b border-white/10 pb-4">
                                    <span className="text-sm uppercase tracking-widest text-white/60">Conteúdo Editorial</span>
                                    <span className="font-serif text-xl text-white/40 line-through">R$ 29,90</span>
                                </div>
                                <div className="flex items-center justify-between pt-4">
                                    <span className="text-lg font-bold uppercase tracking-widest text-analux-secondary">Valor Total Real</span>
                                    <span className="font-serif text-3xl text-white/60 line-through">R$ 478,80</span>
                                </div>
                                <div className="mt-8 bg-white/5 p-8 rounded-none border border-analux-secondary/30 flex items-center justify-between relative overflow-hidden group hover:bg-white/10 transition-colors cursor-default">
                                    <div className="absolute top-0 right-0 w-20 h-20 bg-analux-secondary blur-[50px] opacity-20 group-hover:opacity-40 transition-opacity"></div>
                                    <div>
                                        <p className="text-[10px] uppercase tracking-[0.3em] text-analux-secondary mb-1">Você paga a partir de</p>
                                        <p className="text-5xl font-serif text-white">R$ 109,90</p>
                                    </div>
                                    <div className="h-12 w-[1px] bg-white/20"></div>
                                    <div className="text-right">
                                        <p className="text-[10px] uppercase tracking-widest text-white/60">Economia de</p>
                                        <p className="text-2xl font-serif text-green-400">75%</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="relative h-[600px] bg-[#f4f4f4]">
                            <img
                                src="https://images.unsplash.com/photo-1615655406736-b37c4fabf923?q=80&w=2670&auto=format&fit=crop"
                                className="w-full h-full object-cover mix-blend-multiply opacity-80"
                                alt="Semijoias Comparison"
                            />
                            <div className="absolute inset-0 flex items-center justify-center">
                                <div className="w-64 h-64 border border-white/30 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <div className="text-center">
                                        <p className="text-5xl font-serif text-white mb-2">3.5x</p>
                                        <p className="text-[10px] uppercase tracking-widest text-white">Retorno sobre<br />Investimento</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* PLANS SECTION */}
            <section className="py-32 px-6 max-w-[1400px] mx-auto" id="planos">
                <div className="text-center mb-20">
                    <p className="text-[9px] uppercase tracking-[0.4em] text-gray-400 mb-4">Membership</p>
                    <h2 className="text-5xl md:text-7xl font-serif text-analux-primary mb-12">Escolha seu legado</h2>

                    {/* Minimalist Toggle */}
                    <div className="inline-flex items-center gap-8 border-b border-gray-200 pb-4">
                        {['Flex', 'Semestral'].map((cycle) => (
                            <button
                                key={cycle}
                                className="text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:text-analux-primary focus:text-analux-primary focus:outline-none transition-colors relative group"
                            >
                                {cycle}
                                <span className="absolute -bottom-[17px] left-0 w-full h-[1px] bg-analux-primary scale-x-0 group-focus:scale-x-100 transition-transform"></span>
                            </button>
                        ))}
                    </div>
                    {/* Nota: A lógica de estado do ciclo (billingCycle) precisaria ser implementada aqui igual à LandingPage original se quisermos funcionalidade real de toggle visual rápido. Por enquanto é visual. */}
                </div>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                    {/* Plan 1 */}
                    <div className="border border-gray-200 p-12 hover:border-analux-primary transition-colors duration-500 group relative">
                        <h3 className="text-4xl font-serif text-analux-primary mb-2">Essencial</h3>
                        <p className="text-xs uppercase tracking-widest text-gray-400 mb-8">Para começar a brilhar</p>
                        <div className="mb-8">
                            <span className="text-5xl font-serif text-analux-primary">R$ 189</span>
                            <span className="text-sm text-gray-400 ml-2">/mês</span>
                        </div>
                        <ul className="space-y-4 mb-12 border-t border-gray-100 pt-8">
                            <li className="flex items-center gap-3 text-sm text-gray-600"><Check size={14} className="text-analux-secondary" /> Acesso ao Portal Analux</li>
                            <li className="flex items-center gap-3 text-sm text-gray-600"><Check size={14} className="text-analux-secondary" /> Cobrança Mensal</li>
                            <li className="flex items-center gap-3 text-sm text-gray-600"><Check size={14} className="text-analux-secondary" /> Frete Fixo Nacional</li>
                        </ul>
                        <button
                            onClick={() => handleSubscribePlan('Essencial', 'monthly', '189', ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional'])}
                            className="w-full py-4 border border-analux-primary text-analux-primary text-[10px] font-bold uppercase tracking-widest hover:bg-analux-primary hover:text-white transition-colors"
                        >
                            Selecionar
                        </button>
                    </div>

                    {/* Plan 2 - Featured */}
                    <div className="bg-analux-primary text-white p-12 -mt-8 shadow-2xl relative overflow-hidden group">
                        <div className="absolute top-0 right-0 bg-analux-secondary text-white text-[9px] font-bold uppercase tracking-widest px-4 py-2">Premium</div>
                        <h3 className="text-4xl font-serif mb-2">Signature</h3>
                        <p className="text-xs uppercase tracking-widest text-white/50 mb-8">A experiência completa</p>
                        <div className="mb-8">
                            <span className="text-5xl font-serif">R$ 239</span>
                            <span className="text-sm text-white/50 ml-2">/mês</span>
                        </div>
                        <ul className="space-y-4 mb-12 border-t border-white/10 pt-8">
                            <li className="flex items-center gap-3 text-sm text-white/90"><Check size={14} className="text-analux-secondary" /> Acesso ao Portal Analux</li>
                            <li className="flex items-center gap-3 text-sm text-white/90"><Check size={14} className="text-analux-secondary" /> Cobrança Mensal</li>
                            <li className="flex items-center gap-3 text-sm text-white/90"><Check size={14} className="text-analux-secondary" /> Frete Fixo Nacional</li>
                            <li className="flex items-center gap-3 text-sm text-white/90"><Check size={14} className="text-analux-secondary" /> Curadoria Superior</li>
                        </ul>
                        <button
                            onClick={() => handleSubscribePlan('Signature', 'monthly', '239', ['Acesso ao Portal Analux', 'Cobrança Mensal', 'Frete Fixo Nacional', 'Curadoria Superior'])}
                            className="w-full py-4 bg-white text-analux-primary text-[10px] font-bold uppercase tracking-widest hover:bg-analux-secondary hover:text-white transition-colors"
                        >
                            Assinar Signature
                        </button>
                    </div>
                </div>
            </section>

            {/* FAQ SECTION */}
            <section className="py-24 bg-[#F9F9F9]">
                <div className="max-w-3xl mx-auto px-6">
                    <h2 className="text-4xl font-serif text-center mb-16 text-analux-primary">Perguntas Frequentes</h2>
                    <div className="space-y-4">
                        {[
                            { q: "As semijoias escurecem?", a: "Não. Todas as peças possuem banho de alta qualidade com verniz de proteção e garantia de 1 ano." },
                            { q: "Posso cancelar quando quiser?", a: "Sim. A assinatura mensal não possui fidelidade. Você tem total liberdade no seu painel." },
                            { q: "E se eu não gostar?", a: "Temos um sistema de trocas facilitado. Sua satisfação é nossa prioridade." }
                        ].map((faq, i) => (
                            <div key={i} className="bg-white border border-gray-200 p-6 cursor-pointer hover:border-analux-secondary transition-colors group">
                                <h4 className="text-sm font-bold uppercase tracking-wider text-analux-primary mb-2 flex justify-between items-center">
                                    {faq.q}
                                    <Plus size={16} className="text-analux-secondary group-hover:rotate-45 transition-transform" />
                                </h4>
                                <p className="text-xs text-gray-500 leading-relaxed font-light hidden group-hover:block animate-fadeIn">
                                    {faq.a}
                                </p>
                            </div>
                        ))}
                    </div>
                </div>
            </section>

            {/* FINAL CTA */}
            <section className="py-40 bg-analux-primary text-white text-center relative overflow-hidden">
                <div className="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div className="relative z-10 max-w-4xl mx-auto px-6">
                    <p className="text-[10px] uppercase tracking-[0.5em] text-analux-secondary mb-6 animate-pulse">Últimas vagas do mês</p>
                    <h2 className="text-6xl md:text-9xl font-serif leading-none mb-10">
                        TORNE-SE<br />
                        <span className="text-analux-secondary italic">inesquecível.</span>
                    </h2>
                    <button
                        onClick={handleSubscribe}
                        className="bg-white text-analux-primary px-16 py-6 text-xs font-bold uppercase tracking-widest hover:bg-analux-secondary hover:text-white transition-all transform hover:scale-105 shadow-2xl"
                    >
                        Garantir Minha Box Agora
                    </button>
                    <p className="mt-8 text-[9px] text-white/40 uppercase tracking-widest">Garantia de 7 dias ou seu dinheiro de volta</p>
                </div>
            </section>

            {/* NEWSLETTER - Split */}
            <section className="bg-gray-100 py-20 px-6">
                <div className="max-w-4xl mx-auto bg-white p-12 md:p-16 flex flex-col md:flex-row gap-12 shadow-xl items-center relative overflow-hidden">
                    <div className="w-full md:w-1/2 relative z-10">
                        <h3 className="text-3xl font-serif text-analux-primary leading-tight mb-4">
                            ENTRE PARA O CLUBE<br />
                            <span className="text-gray-300 italic">e receba spoilers</span>
                        </h3>
                        <p className="text-xs text-gray-400 max-w-xs leading-relaxed">
                            Assine nossa newsletter e ganhe 10% OFF na sua primeira box, além de acesso antecipado aos lançamentos.
                        </p>
                    </div>
                    <div className="w-full md:w-1/2 relative z-10">
                        <div className="border border-gray-200 p-1 flex">
                            <input
                                type="email"
                                placeholder="Seu melhor e-mail"
                                className="flex-1 p-3 text-sm outline-none placeholder:text-gray-300 placeholder:text-xs placeholder:uppercase placeholder:tracking-widest"
                            />
                            <button className="bg-analux-primary text-white px-8 text-[10px] font-bold uppercase tracking-widest hover:bg-analux-secondary transition-colors">
                                Enviar
                            </button>
                        </div>
                        <p className="text-[9px] text-gray-300 mt-3 text-center uppercase tracking-wider">
                            Ao assinar você concorda com nossa política de privacidade.
                        </p>
                    </div>
                    {/* Decorative background element */}
                    <div className="absolute -top-20 -right-20 w-64 h-64 bg-analux-secondary/5 rounded-full blur-3xl pointer-events-none"></div>
                </div>
            </section>

            {/* INSTAGRAM FEED PLACEHOLDER */}
            <div className="py-20 text-center">
                <h4 className="text-2xl font-serif text-analux-primary mb-2">@ANALUX.CLUB</h4>
                <p className="text-[10px] uppercase tracking-[0.3em] text-gray-400 mb-10 flex items-center justify-center gap-2">
                    Siga nosso universo <ArrowRight size={12} />
                </p>
                <div className="grid grid-cols-2 md:grid-cols-4 md:gap-4 gap-2 px-4 max-w-[1400px] mx-auto">
                    {/* 4 grayscale placeholders */}
                    {[1, 2, 3, 4].map(i => (
                        <div key={i} className="aspect-square bg-gray-200 relative overflow-hidden group">
                            <div className="absolute inset-0 flex items-center justify-center bg-gray-300/50 group-hover:bg-transparent transition-colors duration-500">
                                <Instagram className="text-white opacity-50 group-hover:opacity-0 transition-opacity" size={32} />
                            </div>
                            {/* Placeholder generic images */}
                            <img src={`https://source.unsplash.com/random/400x400?jewelry&sig=${i}`} className="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700" />
                        </div>
                    ))}
                </div>
            </div>


            {/* FOOTER */}
            <footer className="bg-[#f2efeb] pt-20 pb-10 px-6 text-[10px] tracking-wider uppercase text-gray-500 font-bold border-t border-gray-200">
                <div className="max-w-[1400px] mx-auto grid grid-cols-1 md:grid-cols-4 gap-12 mb-20">
                    <div>
                        <h5 className="text-analux-primary text-lg font-serif normal-case mb-6">Analux</h5>
                        <p className="normal-case font-normal leading-relaxed max-w-xs mb-4">
                            Elevando a autoestima de mulheres através de semijoias com alma e design exclusivo.
                        </p>
                    </div>
                    <div>
                        <h6 className="text-black mb-6">Menu</h6>
                        <ul className="space-y-4">
                            <li><a href="#" className="hover:text-analux-primary">Assinatura</a></li>
                            <li><a href="#" className="hover:text-analux-primary">Coleções</a></li>
                            <li><a href="#" className="hover:text-analux-primary">Manifesto</a></li>
                            <li><a href="#" className="hover:text-analux-primary">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h6 className="text-black mb-6">Endereço</h6>
                        <p className="normal-case font-normal leading-relaxed">
                            Rua Oscar Freire, 1234<br />
                            Jardins, São Paulo - SP<br />
                            CEP 01419-001
                        </p>
                        <p className="mt-4 normal-case font-normal">contato@analux.com.br</p>
                    </div>
                    <div>
                        <h6 className="text-black mb-6">Social</h6>
                        <div className="flex gap-4 text-analux-primary">
                            <Instagram size={18} className="hover:scale-110 transition-transform cursor-pointer" />
                            <Facebook size={18} className="hover:scale-110 transition-transform cursor-pointer" />
                            <Twitter size={18} className="hover:scale-110 transition-transform cursor-pointer" />
                        </div>
                    </div>
                </div>
                <div className="max-w-[1400px] mx-auto flex flex-col md:flex-row justify-between items-center border-t border-gray-300/50 pt-10">
                    <p>© 2026 Analux Club. All rights reserved.</p>
                    <div className="flex gap-8 mt-4 md:mt-0">
                        <a href="#" className="hover:text-analux-primary">Privacy Policy</a>
                        <a href="#" className="hover:text-analux-primary">Terms of Service</a>
                    </div>
                </div>
            </footer>

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

            {showAuthModal && (
                <AuthModal
                    onClose={() => setShowAuthModal(false)}
                    initialMode={authMode}
                    customWelcomeMessage={authMode === 'REGISTER' ? 'Crie seu login e senha para prosseguir para a contratação.' : undefined}
                    onAuthSuccess={() => {
                        if (pendingSubscription) {
                            setReviewModalData(pendingSubscription);
                            setPendingSubscription(null);
                            setShowAuthModal(false);
                        } else {
                            navigate('/dashboard');
                        }
                    }}
                />
            )}
        </div>
    );
};

export default LandingPageB;
