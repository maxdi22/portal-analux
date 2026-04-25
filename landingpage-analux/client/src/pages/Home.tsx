/*
Design philosophy for this file:
Editorial couture para semijoias premium, com layout assimétrico, ritmo de revista, atmosfera quente e sofisticada.
A página deve combinar desejo emocional com clareza comercial, usando imagens de close, blocos de manifesto e CTAs elegantes.
Quando houver dúvida, priorizar presença visual, contraste refinado e sensação de curadoria.
*/

import { Button } from "@/components/ui/button";
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
} from "lucide-react";

const assets = {
  logoDark:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/logo-dark_01f782b2.png",
  heroBg:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/analux-hero-editorial-bg-86SZVgBUKWEMqZ3EN73dfL.webp",
  heroModel:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/editorial-02_fd834ba0.jpg",
  productModel:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/editorial-03_90a66980.jpg",
  lifestyle:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/transferir-10_395a7528.jpg",
  boxStill:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/analux-box-luxury-stilllife-Da43zCemUJHdPDWq7zC2gz.webp",
  goldPanel:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/analux-gold-reflections-panel-4oEgjwWQCb7k2jzgcKRevE.webp",
  editorialVideo:
    "https://d2xsxph8kpxj0f.cloudfront.net/310519663402949254/5ac6N248vjtyiXL7nqxERe/short-video3_d16a3bdf.mp4",
};

const highlights = [
  "Curadoria exclusiva com estética autoral",
  "Experiência de unboxing com acabamento premium",
  "Semijoias selecionadas para presença, versatilidade e desejo",
];

const receiveItems = [
  {
    title: "Semijoias com direção estética",
    text: "Peças escolhidas para conversar entre si, valorizar composições e fazer cada edição parecer uma descoberta, não uma compra comum.",
  },
  {
    title: "Uma entrega que parece presente",
    text: "A box é desenhada para criar expectativa real. Cada detalhe amplia a sensação de exclusividade desde o primeiro contato.",
  },
  {
    title: "Elementos surpresa que elevam a experiência",
    text: "Mimos, atmosfera e acabamentos que fazem o recebimento ser lembrado, fotografado e desejado de novo no mês seguinte.",
  },
];

const steps = [
  {
    number: "01",
    title: "Você entra para a experiência",
    text: "Assina a Analux Box e garante acesso às próximas edições com uma jornada simples, elegante e direta.",
  },
  {
    number: "02",
    title: "A curadoria é preparada",
    text: "Cada box nasce com direção visual, seleção de peças e intenção estética para tornar o recebimento mais marcante.",
  },
  {
    number: "03",
    title: "O ritual chega até você",
    text: "Da abertura ao uso, tudo é pensado para transformar o momento em presença, estilo e assinatura pessoal.",
  },
];

const faqs = [
  {
    question: "A Analux Box é para quem prefere semijoias discretas ou marcantes?",
    answer:
      "A proposta é trabalhar elegância, presença e versatilidade. A curadoria busca equilíbrio entre sofisticação, desejo e facilidade de uso em diferentes momentos.",
  },
  {
    question: "Vou receber apenas semijoias?",
    answer:
      "Não. A experiência foi pensada para ir além da peça. A box combina apresentação cuidadosa, atmosfera premium e elementos que tornam o recebimento mais especial.",
  },
  {
    question: "Por que assinar em vez de comprar separadamente?",
    answer:
      "Porque a assinatura entrega algo mais completo: continuidade, surpresa, curadoria e uma relação mais autoral com o seu estilo e com a marca.",
  },
  {
    question: "A decisão é segura?",
    answer:
      "Sim. A página foi construída para comunicar a experiência com clareza, e as condições comerciais podem ser apresentadas no ambiente de contratação com transparência e segurança.",
  },
];

function SectionEyebrow({ children }: { children: React.ReactNode }) {
  return (
    <span className="inline-flex items-center gap-2 rounded-full border border-[rgba(114,77,65,0.14)] bg-white/70 px-4 py-2 text-[0.68rem] font-semibold uppercase tracking-[0.28em] text-foreground/70 shadow-[0_12px_30px_rgba(89,58,45,0.07)] backdrop-blur">
      <span className="h-1.5 w-1.5 rounded-full bg-[--analux-gold-strong]" />
      {children}
    </span>
  );
}

export default function Home() {
  return (
    <div className="relative overflow-x-hidden bg-background text-foreground">
      <div className="pointer-events-none absolute inset-0 opacity-[0.18] analux-noise" />

      <header className="sticky top-0 z-50 border-b border-[rgba(119,83,69,0.08)] bg-[rgba(252,248,243,0.82)] backdrop-blur-xl">
        <div className="container flex items-center justify-between gap-6 py-4">
          <a href="#top" className="shrink-0 transition-opacity duration-300 hover:opacity-80">
            <img
              src={assets.logoDark}
              alt="Analux"
              className="h-10 w-auto sm:h-11"
            />
          </a>

          <nav className="hidden items-center gap-8 lg:flex">
            <a className="analux-nav-link" href="#experiencia">
              Experiência
            </a>
            <a className="analux-nav-link" href="#box">
              O que chega
            </a>
            <a className="analux-nav-link" href="#ritual">
              Ritual
            </a>
            <a className="analux-nav-link" href="#faq">
              Dúvidas
            </a>
          </nav>

          <a href="#cta-final" className="hidden lg:block">
            <Button className="analux-button-primary">Quero entrar para a Box</Button>
          </a>
        </div>
      </header>

      <main id="top">
        <section className="relative isolate overflow-hidden">
          <div
            className="absolute inset-0 bg-cover bg-center opacity-70"
            style={{ backgroundImage: `url(${assets.heroBg})` }}
          />
          <div className="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.9),transparent_48%),linear-gradient(180deg,rgba(252,248,243,0.88),rgba(248,241,234,0.94))]" />

          <div className="container relative grid min-h-[calc(100vh-80px)] items-center gap-12 py-12 lg:grid-cols-[1.08fr_0.92fr] lg:py-20 xl:gap-16">
            <div className="max-w-2xl">
              <SectionEyebrow>Mais que uma box de semijoias</SectionEyebrow>

              <div className="mt-8 space-y-7">
                <h1 className="max-w-[12ch] font-display text-5xl leading-[0.9] text-[--analux-plum] sm:text-6xl xl:text-7xl">
                  Um ritual de presença, estilo e exclusividade.
                </h1>

                <p className="max-w-xl text-lg leading-8 text-foreground/78 sm:text-xl">
                  Todo mês, a <strong>Analux Box</strong> entrega uma curadoria
                  pensada para mulheres que não querem apenas receber peças
                  bonitas, mas viver a sensação de se escolher com mais intenção.
                </p>

                <p className="max-w-lg text-base leading-7 text-foreground/66 sm:text-lg">
                  Receba semijoias selecionadas, experiência editorial e surpresas
                  que transformam o comum em uma assinatura de identidade.
                </p>
              </div>

              <div className="mt-10 flex flex-col gap-4 sm:flex-row sm:items-center">
                <a href="#cta-final">
                  <Button className="analux-button-primary group w-full sm:w-auto">
                    Quero viver a experiência Analux
                    <ArrowRight className="ml-2 size-4 transition-transform duration-300 group-hover:translate-x-1" />
                  </Button>
                </a>
                <a href="#experiencia">
                  <Button variant="outline" className="analux-button-secondary w-full sm:w-auto">
                    <CirclePlay className="mr-2 size-4" />
                    Ver o que torna a Box exclusiva
                  </Button>
                </a>
              </div>

              <div className="mt-10 grid max-w-2xl gap-3 sm:grid-cols-3">
                <div className="analux-stat-card">
                  <span className="analux-stat-number">Edição</span>
                  <p className="analux-stat-text">curada com direção estética e sensação de coleção.</p>
                </div>
                <div className="analux-stat-card">
                  <span className="analux-stat-number">Entrega</span>
                  <p className="analux-stat-text">pensada para parecer presente, não envio comum.</p>
                </div>
                <div className="analux-stat-card">
                  <span className="analux-stat-number">Assinatura</span>
                  <p className="analux-stat-text">que aproxima exclusividade, expectativa e recorrência.</p>
                </div>
              </div>
            </div>

            <div className="relative mx-auto w-full max-w-[40rem] lg:mr-0">
              <div className="absolute -left-8 top-12 hidden h-40 w-40 rounded-full bg-[rgba(208,170,98,0.14)] blur-3xl lg:block" />
              <div className="absolute -right-10 bottom-10 h-48 w-48 rounded-full bg-[rgba(88,39,59,0.12)] blur-3xl" />

              <div className="grid gap-5 lg:grid-cols-[0.52fr_0.48fr]">
                <article className="analux-hero-card relative overflow-hidden lg:translate-y-8">
                  <img
                    src={assets.heroModel}
                    alt="Modelo usando semijoia Analux em close editorial"
                    className="h-full w-full object-cover"
                  />
                  <div className="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[rgba(47,20,28,0.78)] to-transparent p-6 text-white">
                    <p className="text-[0.7rem] uppercase tracking-[0.3em] text-white/70">
                      Curadoria exclusiva
                    </p>
                    <p className="mt-2 max-w-[18ch] font-display text-2xl leading-tight">
                      Desejo com direção.
                    </p>
                  </div>
                </article>

                <div className="flex flex-col gap-5">
                  <article className="analux-floating-note lg:ml-[-2.5rem]">
                    <Sparkles className="size-4 text-[--analux-gold-strong]" />
                    <p>
                      Existe diferença entre comprar uma peça e entrar em uma
                      experiência.
                    </p>
                  </article>

                  <article className="analux-hero-card overflow-hidden bg-[rgba(255,255,255,0.78)] p-4">
                    <img
                      src={assets.boxStill}
                      alt="Still life premium representando a Analux Box"
                      className="h-[16rem] w-full rounded-[1.6rem] object-cover"
                    />
                    <div className="px-2 pb-2 pt-5">
                      <p className="text-[0.7rem] uppercase tracking-[0.28em] text-foreground/52">
                        A experiência
                      </p>
                      <p className="mt-2 font-display text-3xl text-[--analux-plum]">
                        Uma box pensada para ser sentida antes mesmo de ser usada.
                      </p>
                    </div>
                  </article>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="border-y border-[rgba(119,83,69,0.08)] bg-white/50 py-6">
          <div className="container grid gap-4 text-center md:grid-cols-3 md:text-left">
            {highlights.map((item) => (
              <div key={item} className="flex items-center justify-center gap-3 md:justify-start">
                <Star className="size-4 shrink-0 text-[--analux-gold-strong]" />
                <p className="text-sm uppercase tracking-[0.22em] text-foreground/66">{item}</p>
              </div>
            ))}
          </div>
        </section>

        <section id="experiencia" className="relative py-24">
          <div className="container grid gap-14 lg:grid-cols-[0.82fr_1.18fr] lg:items-start">
            <div className="space-y-6">
              <SectionEyebrow>A experiência Analux</SectionEyebrow>
              <h2 className="max-w-[10ch] font-display text-4xl leading-[0.95] text-[--analux-plum] sm:text-5xl">
                Existe uma diferença entre comprar uma peça e entrar em uma experiência.
              </h2>
            </div>

            <div className="grid gap-8 lg:grid-cols-[0.8fr_1fr]">
              <div className="rounded-[2rem] border border-[rgba(119,83,69,0.1)] bg-[rgba(255,255,255,0.68)] p-8 shadow-[0_30px_60px_rgba(103,69,53,0.08)] backdrop-blur-sm">
                <p className="text-lg leading-8 text-foreground/76">
                  Quando a escolha vem sem contexto, você recebe apenas produto.
                  Quando existe <strong>curadoria, intenção e direção estética</strong>,
                  você recebe algo mais difícil de encontrar: coerência,
                  surpresa e presença.
                </p>
                <p className="mt-5 text-lg leading-8 text-foreground/76">
                  A Analux Box nasceu para transformar semijoias em um ritual mensal
                  de estilo, autoestima e descoberta. Cada edição é pensada para
                  que o momento de receber seja tão marcante quanto o momento de usar.
                </p>
              </div>

              <div className="relative overflow-hidden rounded-[2.2rem] border border-[rgba(119,83,69,0.08)] bg-[--analux-plum] p-6 text-white shadow-[0_30px_80px_rgba(65,28,42,0.2)]">
                <img
                  src={assets.goldPanel}
                  alt="Painel editorial dourado abstrato"
                  className="absolute inset-0 h-full w-full object-cover opacity-40"
                />
                <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(57,25,37,0.18),rgba(57,25,37,0.92))]" />
                <div className="relative grid gap-5">
                  <div className="analux-proof-item">
                    <Gift className="size-5 text-[--analux-gold-soft]" />
                    <div>
                      <h3>Mais que semijoias</h3>
                      <p>Uma entrega pensada para ter atmosfera de edição especial.</p>
                    </div>
                  </div>
                  <div className="analux-proof-item">
                    <Sparkles className="size-5 text-[--analux-gold-soft]" />
                    <div>
                      <h3>Mais que surpresa</h3>
                      <p>Uma descoberta recorrente com linguagem visual e intenção de marca.</p>
                    </div>
                  </div>
                  <div className="analux-proof-item">
                    <ShieldCheck className="size-5 text-[--analux-gold-soft]" />
                    <div>
                      <h3>Mais que recorrência</h3>
                      <p>Uma forma elegante de manter desejo, vínculo e continuidade.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section id="box" className="relative overflow-hidden py-24">
          <div className="container grid gap-12 lg:grid-cols-[1.02fr_0.98fr] lg:items-center">
            <div className="relative order-2 lg:order-1">
              <div className="absolute -left-10 top-12 h-44 w-44 rounded-full bg-[rgba(208,170,98,0.18)] blur-3xl" />
              <div className="analux-frame-card relative">
                <img
                  src={assets.boxStill}
                  alt="Representação editorial de uma box premium de semijoias"
                  className="h-[31rem] w-full rounded-[2rem] object-cover"
                />
              </div>
            </div>

            <div className="order-1 lg:order-2">
              <SectionEyebrow>O que você recebe</SectionEyebrow>
              <h2 className="mt-7 max-w-[11ch] font-display text-4xl leading-[0.95] text-[--analux-plum] sm:text-5xl">
                O valor da Analux Box não está em uma única peça.
              </h2>
              <p className="mt-6 max-w-xl text-lg leading-8 text-foreground/74">
                Ele está na experiência completa. A combinação entre semijoias,
                apresentação, atmosfera e surpresa faz o recebimento ter mais
                valor percebido do que uma compra comum e isolada.
              </p>

              <div className="mt-10 grid gap-4">
                {receiveItems.map((item) => (
                  <article key={item.title} className="analux-list-card">
                    <span className="analux-list-icon">
                      <Check className="size-4" />
                    </span>
                    <div>
                      <h3 className="font-display text-2xl text-[--analux-plum]">
                        {item.title}
                      </h3>
                      <p className="mt-2 max-w-[46ch] text-base leading-7 text-foreground/70">
                        {item.text}
                      </p>
                    </div>
                  </article>
                ))}
              </div>
            </div>
          </div>
        </section>

        <section className="py-24">
          <div className="container grid gap-14 lg:grid-cols-[0.88fr_1.12fr] lg:items-center">
            <div>
              <SectionEyebrow>Para quem é</SectionEyebrow>
              <h2 className="mt-7 max-w-[11ch] font-display text-4xl leading-[0.94] text-[--analux-plum] sm:text-5xl">
                Para mulheres que sentem que o detalhe muda tudo.
              </h2>
              <p className="mt-6 max-w-xl text-lg leading-8 text-foreground/74">
                A Analux Box é para quem entende que semijoia não é excesso. É
                assinatura. É para quem gosta de se montar com mais intenção,
                se presentear com mais elegância e construir uma presença que não
                depende de muito para ser percebida.
              </p>
              <p className="mt-5 max-w-xl text-lg leading-8 text-foreground/74">
                Se você valoriza beleza, curadoria e a sensação de receber algo
                pensado para você, essa experiência foi criada no seu ritmo.
              </p>
            </div>

            <div className="grid gap-5 md:grid-cols-[0.55fr_0.45fr]">
              <div className="analux-hero-card overflow-hidden">
                <img
                  src={assets.productModel}
                  alt="Modelo usando semijoias Analux com enquadramento refinado"
                  className="h-full min-h-[31rem] w-full object-cover"
                />
              </div>
              <div className="flex flex-col gap-5">
                <article className="analux-quote-card">
                  <p className="text-[0.68rem] uppercase tracking-[0.28em] text-foreground/52">
                    Presença
                  </p>
                  <p className="mt-4 font-display text-3xl leading-tight text-[--analux-plum]">
                    Semijoias que não competem com você. Elas refinam o que você já transmite.
                  </p>
                </article>
                <article className="analux-hero-card overflow-hidden">
                  <img
                    src={assets.lifestyle}
                    alt="Imagem editorial lifestyle da Analux"
                    className="h-[18rem] w-full object-cover"
                  />
                </article>
              </div>
            </div>
          </div>
        </section>

        <section id="ritual" className="relative overflow-hidden bg-[--analux-plum] py-24 text-white">
          <div className="absolute inset-0 opacity-30" style={{ backgroundImage: `url(${assets.goldPanel})`, backgroundSize: "cover", backgroundPosition: "center" }} />
          <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(63,25,40,0.9),rgba(44,17,28,0.96))]" />

          <div className="container relative grid gap-12 lg:grid-cols-[0.92fr_1.08fr] lg:items-center">
            <div>
              <SectionEyebrow>Como funciona</SectionEyebrow>
              <h2 className="mt-7 max-w-[10ch] font-display text-4xl leading-[0.94] text-white sm:text-5xl">
                Simples por fora. Memorável por dentro.
              </h2>
              <p className="mt-6 max-w-lg text-lg leading-8 text-white/74">
                A jornada é leve de seguir, mas intensa na percepção. Cada etapa
                foi desenhada para transformar recebimento em ritual, e ritual em recorrência desejada.
              </p>

              <div className="mt-10 grid gap-4">
                {steps.map((step) => (
                  <article key={step.number} className="analux-step-card">
                    <span className="analux-step-number">{step.number}</span>
                    <div>
                      <h3 className="font-display text-2xl text-white">{step.title}</h3>
                      <p className="mt-2 text-base leading-7 text-white/70">{step.text}</p>
                    </div>
                  </article>
                ))}
              </div>
            </div>

            <div className="analux-video-shell">
              <div className="flex items-center justify-between gap-4 border-b border-white/10 px-5 py-4 text-sm uppercase tracking-[0.22em] text-white/60">
                <span>Curadoria em movimento</span>
                <span>Editorial premium</span>
              </div>
              <div className="relative aspect-[5/6] overflow-hidden md:aspect-[16/13]">
                <video
                  className="h-full w-full object-cover"
                  autoPlay
                  loop
                  muted
                  playsInline
                  preload="auto"
                >
                  <source src={assets.editorialVideo} type="video/mp4" />
                </video>
                <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(25,11,18,0.12),rgba(25,11,18,0.45))]" />
                <div className="absolute bottom-0 left-0 p-6">
                  <p className="max-w-[22ch] font-display text-3xl leading-tight text-white">
                    Quando a curadoria é bem feita, a semijoia aparece com presença.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </section>

        <section className="py-24">
          <div className="container grid gap-10 lg:grid-cols-[1fr_1fr] lg:items-center">
            <div className="rounded-[2.3rem] border border-[rgba(119,83,69,0.1)] bg-[rgba(255,255,255,0.72)] p-8 shadow-[0_32px_70px_rgba(101,66,52,0.08)] backdrop-blur-sm sm:p-10">
              <SectionEyebrow>Escolha mais inteligente</SectionEyebrow>
              <h2 className="mt-7 max-w-[12ch] font-display text-4xl leading-[0.95] text-[--analux-plum] sm:text-5xl">
                Por que a Analux Box faz mais sentido do que comprar sem direção?
              </h2>
              <div className="mt-8 overflow-hidden rounded-[1.7rem] border border-[rgba(119,83,69,0.1)] bg-white/80">
                <div className="grid grid-cols-2 bg-[rgba(243,233,223,0.72)] px-5 py-4 text-[0.72rem] font-semibold uppercase tracking-[0.22em] text-foreground/60">
                  <span>Compra avulsa</span>
                  <span>Analux Box</span>
                </div>
                {[
                  ["Escolhas isoladas", "Curadoria com coerência estética"],
                  ["Compra sem surpresa", "Experiência mensal com expectativa real"],
                  ["Relação transacional", "Vínculo emocional e recorrente com a marca"],
                  ["Produto solto", "Ritual completo de recebimento e uso"],
                ].map(([left, right]) => (
                  <div key={left} className="grid grid-cols-2 gap-6 border-t border-[rgba(119,83,69,0.08)] px-5 py-5 text-sm leading-7 text-foreground/72 sm:text-base">
                    <span>{left}</span>
                    <span className="font-medium text-[--analux-plum]">{right}</span>
                  </div>
                ))}
              </div>
            </div>

            <div className="relative overflow-hidden rounded-[2.5rem] border border-[rgba(119,83,69,0.1)] bg-[--analux-cream-deep] p-8 sm:p-10">
              <img
                src={assets.heroBg}
                alt="Fundo editorial suave da Analux"
                className="absolute inset-0 h-full w-full object-cover opacity-55"
              />
              <div className="absolute inset-0 bg-[linear-gradient(180deg,rgba(249,242,235,0.8),rgba(249,242,235,0.92))]" />
              <div className="relative">
                <p className="text-[0.7rem] uppercase tracking-[0.3em] text-foreground/52">
                  Decisão com mais valor percebido
                </p>
                <p className="mt-5 max-w-[22ch] font-display text-4xl leading-[1.02] text-[--analux-plum] sm:text-5xl">
                  Entre para a Analux Box e transforme o que você recebe em algo que você sente.
                </p>
                <p className="mt-6 max-w-lg text-lg leading-8 text-foreground/72">
                  Se você quer sair da compra comum e entrar em uma experiência
                  mais bonita, mais autoral e mais memorável, a Analux Box é o
                  próximo passo natural.
                </p>
                <div className="mt-8 flex flex-wrap gap-3 text-sm text-foreground/65">
                  <span className="inline-flex items-center gap-2 rounded-full border border-[rgba(119,83,69,0.12)] bg-white/68 px-4 py-2">
                    <Lock className="size-4 text-[--analux-gold-strong]" />
                    assinatura simples
                  </span>
                  <span className="inline-flex items-center gap-2 rounded-full border border-[rgba(119,83,69,0.12)] bg-white/68 px-4 py-2">
                    <ShieldCheck className="size-4 text-[--analux-gold-strong]" />
                    experiência premium
                  </span>
                </div>
                <a href="#cta-final" className="mt-9 inline-flex">
                  <Button className="analux-button-primary">
                    Quero entrar para a Analux Box
                  </Button>
                </a>
              </div>
            </div>
          </div>
        </section>

        <section id="faq" className="py-24">
          <div className="container grid gap-10 lg:grid-cols-[0.75fr_1.25fr]">
            <div>
              <SectionEyebrow>Dúvidas frequentes</SectionEyebrow>
              <h2 className="mt-7 max-w-[9ch] font-display text-4xl leading-[0.95] text-[--analux-plum] sm:text-5xl">
                Clareza também faz parte da experiência.
              </h2>
            </div>

            <div className="grid gap-4">
              {faqs.map((faq) => (
                <details
                  key={faq.question}
                  className="group rounded-[1.6rem] border border-[rgba(119,83,69,0.1)] bg-[rgba(255,255,255,0.72)] p-6 shadow-[0_18px_40px_rgba(102,69,54,0.06)] backdrop-blur-sm"
                >
                  <summary className="flex list-none items-center justify-between gap-4 font-display text-2xl text-[--analux-plum]">
                    <span>{faq.question}</span>
                    <span className="rounded-full border border-[rgba(119,83,69,0.1)] p-2 text-foreground/60 transition-transform duration-300 group-open:rotate-180">
                      <ChevronDown className="size-4" />
                    </span>
                  </summary>
                  <p className="pt-5 text-base leading-8 text-foreground/72">{faq.answer}</p>
                </details>
              ))}
            </div>
          </div>
        </section>

        <section id="cta-final" className="pb-16 pt-6">
          <div className="container">
            <div className="relative overflow-hidden rounded-[2.8rem] border border-[rgba(119,83,69,0.1)] bg-[--analux-plum] px-7 py-10 text-white shadow-[0_36px_90px_rgba(61,22,36,0.22)] sm:px-10 sm:py-14 lg:px-14">
              <img
                src={assets.goldPanel}
                alt="Reflexos dourados da Analux"
                className="absolute inset-0 h-full w-full object-cover opacity-25"
              />
              <div className="absolute inset-0 bg-[linear-gradient(135deg,rgba(72,30,44,0.82),rgba(45,18,28,0.95))]" />
              <div className="relative grid gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-end">
                <div>
                  <p className="text-[0.72rem] uppercase tracking-[0.3em] text-white/62">
                    Analux Box
                  </p>
                  <h2 className="mt-5 max-w-[12ch] font-display text-4xl leading-[0.94] sm:text-5xl lg:text-6xl">
                    A box que transforma semijoias em experiência recorrente.
                  </h2>
                </div>

                <div className="lg:justify-self-end">
                  <p className="max-w-xl text-lg leading-8 text-white/74">
                    Se o que você procura não é apenas comprar, mas sentir que está entrando em algo mais bonito, mais autoral e mais memorável, a Analux Box é a sua próxima escolha.
                  </p>
                  <div className="mt-8 flex flex-col gap-4 sm:flex-row">
                    <a href="https://portal.analux.shop/" target="_blank" rel="noreferrer">
                      <Button className="analux-button-primary-light w-full sm:w-auto">
                        Assinar agora
                        <ArrowRight className="ml-2 size-4" />
                      </Button>
                    </a>
                    <a href="#top">
                      <Button variant="outline" className="analux-button-outline-light w-full sm:w-auto">
                        Rever a experiência
                      </Button>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  );
}
