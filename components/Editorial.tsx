
import React from 'react';
import { Play, Music, Camera, Heart, Sparkles, Book, Wind, Coffee } from 'lucide-react';

const Editorial: React.FC = () => {
  return (
    <div className="max-w-5xl mx-auto space-y-20 animate-fadeIn pb-24">
      {/* Cover Section */}
      <section className="relative h-[70vh] rounded-[60px] overflow-hidden group shadow-2xl">
        <img 
          src="https://images.unsplash.com/photo-1596566111082-d5df24433bdc?auto=format&fit=crop&q=80&w=1200&h=800" 
          className="w-full h-full object-cover transition-transform duration-[3000ms] group-hover:scale-110"
          alt="Aurora Floral Cover"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-analux-primary via-analux-primary/20 to-transparent"></div>
        <div className="absolute inset-0 flex flex-col justify-end p-12 md:p-20 space-y-4">
          <span className="text-analux-secondary font-bold tracking-[0.4em] uppercase text-xs">Edição Outubro 2024</span>
          <h1 className="text-6xl md:text-8xl font-serif text-white leading-tight">Aurora Floral</h1>
          <p className="text-white/70 max-w-xl text-lg font-light leading-relaxed">
            Uma ode ao renascimento. Descubra como as formas orgânicas da natureza inspiraram nossa curadoria mais feminina do ano.
          </p>
        </div>
      </section>

      {/* Manifesto Section */}
      <section className="grid grid-cols-1 md:grid-cols-2 gap-16 items-center px-4">
        <div className="space-y-8">
          <div className="inline-flex p-3 bg-analux-secondary/10 text-analux-secondary rounded-full">
            <Sparkles size={24} />
          </div>
          <h2 className="text-4xl md:text-5xl font-serif text-analux-primary leading-tight">O Manifesto do Brilho</h2>
          <div className="space-y-6 text-gray-600 leading-relaxed italic text-lg">
            <p>"Acreditamos que uma semijoia não é apenas um adorno, mas um amuleto de autoconfiança. A Analux nasceu para celebrar a mulher que encontra beleza no detalhe, força na delicadeza e luxo na sua própria essência."</p>
            <p>Neste mês, convidamos você a florescer em sua melhor versão.</p>
          </div>
        </div>
        <div className="relative">
          <div className="aspect-[4/5] rounded-[40px] overflow-hidden shadow-xl rotate-2">
            <img src="https://images.unsplash.com/photo-1601121141461-9d6647bca1ed?auto=format&fit=crop&q=80&w=600&h=800" className="w-full h-full object-cover" alt="Manifesto Visual" />
          </div>
          <div className="absolute -bottom-6 -left-6 w-32 h-32 bg-analux-secondary rounded-[32px] p-4 text-white flex flex-col justify-center items-center shadow-lg -rotate-12">
            <Heart size={24} className="fill-white mb-2" />
            <span className="text-[10px] font-bold uppercase tracking-widest text-center">Feito para Você</span>
          </div>
        </div>
      </section>

      {/* The Ritual Section */}
      <section className="bg-analux-primary rounded-[60px] p-12 md:p-20 text-white space-y-12 relative overflow-hidden">
        <div className="absolute top-0 right-0 w-96 h-96 bg-analux-secondary/10 rounded-full -mr-32 -mt-32 blur-3xl"></div>
        <div className="text-center space-y-4 relative z-10">
          <h2 className="text-4xl md:text-5xl font-serif">O Ritual da Box</h2>
          <p className="text-white/60 max-w-2xl mx-auto">Não é apenas abrir uma caixa, é um momento seu. Siga estes passos para uma experiência completa.</p>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
          <div className="bg-white/5 backdrop-blur-md p-8 rounded-[40px] border border-white/10 space-y-4 hover:bg-white/10 transition-all">
            <div className="w-12 h-12 bg-analux-secondary rounded-2xl flex items-center justify-center text-white">
              <Wind size={24} />
            </div>
            <h4 className="text-xl font-serif">A Atmosfera</h4>
            <p className="text-sm text-white/50 leading-relaxed">Escolha um lugar calmo. Acenda sua vela Analux ou use seu aroma favorito para despertar os sentidos.</p>
          </div>
          <div className="bg-white/5 backdrop-blur-md p-8 rounded-[40px] border border-white/10 space-y-4 hover:bg-white/10 transition-all">
            <div className="w-12 h-12 bg-analux-secondary rounded-2xl flex items-center justify-center text-white">
              <Music size={24} />
            </div>
            <h4 className="text-xl font-serif">O Som</h4>
            <p className="text-sm text-white/50 leading-relaxed">Dê play na nossa curadoria musical do mês. Deixe que as notas suaves acompanhem o brilho das suas novas semijoias.</p>
          </div>
          <div className="bg-white/5 backdrop-blur-md p-8 rounded-[40px] border border-white/10 space-y-4 hover:bg-white/10 transition-all">
            <div className="w-12 h-12 bg-analux-secondary rounded-2xl flex items-center justify-center text-white">
              <Coffee size={24} />
            </div>
            <h4 className="text-xl font-serif">A Presença</h4>
            <p className="text-sm text-white/50 leading-relaxed">Respire fundo. Sinta a textura de cada peça. Este é o presente que você deu a si mesma.</p>
          </div>
        </div>
      </section>

      {/* Playlist & Content */}
      <section className="grid grid-cols-1 lg:grid-cols-3 gap-12 px-4">
        <div className="lg:col-span-2 space-y-10">
          <div className="flex items-center gap-4 mb-8">
            <div className="w-1 bg-analux-secondary h-8"></div>
            <h3 className="text-3xl font-serif text-analux-primary">Conteúdos do Mês</h3>
          </div>
          
          <div className="space-y-12">
            <article className="group cursor-pointer">
              <div className="aspect-video rounded-[40px] overflow-hidden mb-6 shadow-lg">
                <img src="https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=800&h=450" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Trends" />
              </div>
              <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-widest">Estilo & Tendência</span>
              <h4 className="text-2xl font-serif text-analux-primary mt-2 mb-4 group-hover:text-analux-secondary transition-colors">Como combinar o banho de Ouro com tons de Primavera</h4>
              <p className="text-gray-500 text-sm leading-relaxed">Nesta edição, exploramos como as cores pastel da estação harmonizam perfeitamente com nossas peças cravejadas...</p>
            </article>

            <article className="group cursor-pointer">
              <div className="aspect-video rounded-[40px] overflow-hidden mb-6 shadow-lg">
                <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?auto=format&fit=crop&q=80&w=800&h=450" className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="Care" />
              </div>
              <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-widest">Cuidado Especial</span>
              <h4 className="text-2xl font-serif text-analux-primary mt-2 mb-4 group-hover:text-analux-secondary transition-colors">Guia de Conservação: Pérolas e Cristais</h4>
              <p className="text-gray-500 text-sm leading-relaxed">Aprenda a manter o brilho eterno de suas semijoias Analux com dicas simples de limpeza e armazenamento.</p>
            </article>
          </div>
        </div>

        {/* Sidebar: Playlist & BTS */}
        <div className="space-y-12">
          {/* Playlist Widget */}
          <div className="bg-white p-8 rounded-[40px] border border-gray-100 shadow-xl space-y-6">
            <div className="flex items-center justify-between">
              <Music size={24} className="text-analux-secondary" />
              <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mood do Mês</span>
            </div>
            <div className="aspect-square bg-analux-primary rounded-3xl overflow-hidden relative group">
              <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&q=80&w=400&h=400" className="w-full h-full object-cover opacity-60 group-hover:scale-110 transition-transform duration-700" alt="Playlist Cover" />
              <div className="absolute inset-0 flex items-center justify-center">
                <button className="w-16 h-16 bg-white rounded-full flex items-center justify-center text-analux-primary shadow-2xl hover:scale-110 transition-transform">
                  <Play size={24} className="fill-analux-primary ml-1" />
                </button>
              </div>
            </div>
            <div className="text-center">
              <h4 className="text-lg font-serif text-analux-primary">Analux Sessions: Spring Bloom</h4>
              <p className="text-xs text-gray-400 mt-1 italic">Jazz, Soul & Chill Lofi</p>
            </div>
            <button className="w-full py-4 bg-analux-contrast text-analux-primary rounded-2xl text-xs font-bold hover:bg-analux-secondary hover:text-white transition-all">
              Abrir no Spotify
            </button>
          </div>

          {/* Behind the Scenes Preview */}
          <div className="space-y-6">
            <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
              <Camera size={20} className="text-analux-secondary" /> Bastidores
            </h3>
            <div className="grid grid-cols-2 gap-4">
              <div className="aspect-square rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1573408302354-014545c92523?auto=format&fit=crop&q=80&w=200&h=200" className="w-full h-full object-cover" alt="BTS 1" />
              </div>
              <div className="aspect-square rounded-2xl overflow-hidden bg-gray-100 shadow-sm">
                <img src="https://images.unsplash.com/photo-1626784215021-2e39ccf971cd?auto=format&fit=crop&q=80&w=200&h=200" className="w-full h-full object-cover" alt="BTS 2" />
              </div>
            </div>
            <p className="text-[10px] text-gray-400 italic leading-relaxed">Um olhar exclusivo sobre a curadoria manual de cada peça deste mês no Ateliê Analux.</p>
          </div>
        </div>
      </section>

      {/* Editorial Footer Quote */}
      <section className="text-center py-20 px-4 border-t border-analux-secondary/10">
        <Book size={40} className="mx-auto text-analux-secondary/20 mb-8" />
        <h3 className="text-3xl md:text-4xl font-serif text-analux-primary mb-6 italic">"O brilho que você usa é o reflexo da luz que você carrega."</h3>
        <p className="text-[10px] font-bold text-analux-secondary uppercase tracking-[0.4em]">Time Editorial Analux</p>
      </section>
    </div>
  );
};

export default Editorial;
