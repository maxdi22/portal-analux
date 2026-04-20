import React, { useRef, useEffect, useState, useLayoutEffect } from 'react';
import { motion, useScroll, useTransform, useSpring, animate, useMotionValue, AnimatePresence } from 'framer-motion';
import { ArrowRight, Sparkles, ChevronDown, MousePointer2 } from 'lucide-react';

interface ScrollVideoHeroProps {
  onJoin?: () => void;
}

const HOOKS = [
  { range: [0, 0.12], text: "Assinatura de semijoias que entrega elegância na sua rotina.", highlight: "semijoias" },
  { range: [0.22, 0.40], text: "Exclusividade, curadoria de estilo feita para o seu momento.", highlight: "feita para o seu momento" },
  { range: [0.50, 0.70], text: "Sua próxima edição já pode estar a caminho.", highlight: "caminho" }
];

const SLIDER_IMAGES = [
  "/media/c-hook-1/2009598018-d01-20251212143817-1765561099.1112.webp",
  "/media/c-hook-1/59105797c-d01-20251127154356-1764269039.4537.webp",
  "/media/c-hook-1/59105818c-d01-20251127151913-1764267555.9137.webp",
  "/media/c-hook-1/59107129-d01-20251111113350-1762871633.7075.webp",
  "/media/c-hook-1/59107133-d01-20251111110355-1762869837.37.webp",
  "/media/c-hook-1/59107626-d01-20260131114857-1769870938.2966.webp",
  "/media/c-hook-1/78040316-d01-20251215170404-1765829045.365.webp",
  "/media/c-hook-1/806068009m-d01-20251204142855-1764869338.7903.webp",
  "/media/c-hook-1/9004070-d01-20251210172813-1765398494.9576.webp"
];

const INTRO_SECONDS_TARGET = 1.9;

const ScrollVideoHero: React.FC<ScrollVideoHeroProps> = ({ onJoin }) => {
  const containerRef = useRef<HTMLDivElement>(null);
  const videoRef = useRef<HTMLVideoElement>(null);
  
  const [isReady, setIsReady] = useState(false);
  const [videoDuration, setVideoDuration] = useState(0);
  const [introFinished, setIntroFinished] = useState(false);
  const [currentImageIndex, setCurrentImageIndex] = useState(0);
  
  const introProgress = useMotionValue(0);

  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start start", "end end"]
  });

  const smoothProgress = useSpring(scrollYProgress, {
    stiffness: 100, 
    damping: 30,
    restDelta: 0.001
  });

  useLayoutEffect(() => {
    const video = videoRef.current;
    if (!video) return;

    const onMetadata = () => {
      setVideoDuration(video.duration);
      setIsReady(true);
    };

    if (video.readyState >= 1) onMetadata();
    else video.addEventListener('loadedmetadata', onMetadata);

    return () => video.removeEventListener('loadedmetadata', onMetadata);
  }, []);

  // 1.5. IMAGE SLIDER MOTOR
  useEffect(() => {
    const timer = setInterval(() => {
      setCurrentImageIndex((prev) => (prev + 1) % SLIDER_IMAGES.length);
    }, 3000); // 3 segundos por imagem
    return () => clearInterval(timer);
  }, []);

  // 2. AUTO-INTRO MOTOR
  useEffect(() => {
    if (!isReady || !videoRef.current || videoDuration === 0) return;
    const video = videoRef.current;

    let introFrameId: number;

    const runIntro = () => {
      video.currentTime = 0;
      video.playbackRate = 0.85; 
      video.play().catch(() => {});

      // Animação da intro um pouco mais longa para o texto respirar
      animate(0, 1, {
        duration: 2.8,
        ease: [0.33, 1, 0.68, 1], 
        onUpdate: (latest) => introProgress.set(latest)
      });

      const monitor = () => {
        if (video.currentTime >= INTRO_SECONDS_TARGET) {
          video.pause();
          video.playbackRate = 1.0;
          setIntroFinished(true);
          return;
        }
        introFrameId = requestAnimationFrame(monitor);
      };

      introFrameId = requestAnimationFrame(monitor);
    };

    const delay = setTimeout(runIntro, 800);
    return () => {
      clearTimeout(delay);
      cancelAnimationFrame(introFrameId);
    };
  }, [isReady, videoDuration]);

  // 3. MAIN TRIGGER MOTOR
  useEffect(() => {
    const video = videoRef.current;
    if (!video || videoDuration === 0 || !introFinished) return;

    let frameId: number;
    const update = () => {
      const latestScroll = scrollYProgress.get();
      const targetTime = INTRO_SECONDS_TARGET + (latestScroll * (videoDuration - INTRO_SECONDS_TARGET));
      const current = video.currentTime;
      const diff = targetTime - current;

      const isBackwards = diff < -0.01;

      if (diff > 0.01) {
        // MOVIMENTO PARA FRENTE (Fluidez via Playback Rate)
        let rate = 1.0;
        if (diff > 0.8) rate = 2.0;
        else if (diff > 0.3) rate = 1.4;
        else if (diff < 0.08) rate = 0.5;
        
        video.playbackRate = rate;
        if (video.paused) video.play().catch(() => {});
      } 
      else if (isBackwards) {
        // MOVIMENTO PARA TRÁS (Scrubbing Otimizado)
        // Pausamos imediatamente para evitar conflito de buffers
        if (!video.paused) video.pause();
        
        // Só atualizamos se o salto for relevante para evitar "stutter" do decoder
        if (Math.abs(diff) > 0.015) {
            video.currentTime = targetTime;
        }
      }
      else {
        // ESTACIONÁRIO
        if (!video.paused) video.pause();
      }
      frameId = requestAnimationFrame(update);
    };
    frameId = requestAnimationFrame(update);
    return () => cancelAnimationFrame(frameId);
  }, [videoDuration, scrollYProgress, introFinished]);

  // UI Transforms refinadas para entrada 'Cinemática' do primeiro Hook
  const introEntryY = useTransform(introProgress, [0, 0.4, 1], [60, 0, 0]);
  const introEntryOpacity = useTransform(introProgress, [0, 0.3, 1], [0, 1, 1]);

  const introExitY = useTransform(smoothProgress, [0, 0.15], [0, -40]);
  const introExitOpacity = useTransform(smoothProgress, [0, 0.15], [1, 0]);

  const ctaOpacity = useTransform(smoothProgress, [0.75, 0.85], [0, 1]);
  const ctaScale = useSpring(useTransform(smoothProgress, [0.75, 0.85], [0.8, 1]), { stiffness: 100, damping: 20 });
  const indicatorScrollOpacity = useTransform(smoothProgress, [0.70, 0.78], [1, 0]);
  const bgOverlayOpacity = useTransform(smoothProgress, [0.70, 0.85], [0, 0.75]);

  return (
    <div ref={containerRef} className="relative w-full bg-[#0a0508]" style={{ height: '400vh' }}>
      <div className="sticky top-0 left-0 h-screen w-full overflow-hidden flex items-center justify-center">
        
        <div className="absolute inset-0 z-0 bg-black">
          <video
            ref={videoRef}
            src="/videos/hero-scroll.mp4"
            className="h-full w-full object-cover pointer-events-none"
            muted playsInline preload="auto"
            style={{ 
              opacity: isReady ? 0.9 : 0, 
              transition: 'opacity 2s ease',
              filter: 'contrast(1.05) brightness(0.9)'
            }}
          />
          <div className="absolute inset-0 bg-gradient-to-r from-black via-[#1a0a12]/80 to-transparent z-10 w-3/4" />
          <div className="absolute inset-0 bg-gradient-to-t from-[#0a0508]/90 via-transparent to-transparent z-10" />
          
          {/* Overlay extra para destacar o CTA no final */}
          <motion.div 
            className="absolute inset-0 bg-black/80 z-15 pointer-events-none"
            style={{ opacity: bgOverlayOpacity }}
          />
        </div>

        <div className="relative z-20 w-full h-full max-w-7xl mx-auto px-10 flex items-center pb-96">
            <div className="w-full lg:w-1/2 h-full relative flex items-center">
                <div className="w-full relative">
                    {HOOKS.map((hook, i) => {
                        // eslint-disable-next-line react-hooks/rules-of-hooks
                        const scrollOpacity = useTransform(smoothProgress, [hook.range[0]-0.05, hook.range[0], hook.range[1], hook.range[1]+0.05], [0, 1, 1, 0]);
                        // eslint-disable-next-line react-hooks/rules-of-hooks
                        const scrollY = useTransform(smoothProgress, [hook.range[0]-0.05, hook.range[0], hook.range[1], hook.range[1]+0.05], [40, 0, 0, -40]);
                        
                        // Lógica especial para o Primeiro Hook (Intro automatizada)
                        const opacity = i === 0 
                          ? (introFinished ? scrollOpacity : introEntryOpacity)
                          : scrollOpacity;
                        
                        const y = i === 0 
                          ? (introFinished ? scrollY : introEntryY)
                          : scrollY;

                        return (
                            <motion.div key={i} style={{ opacity, y }} className="absolute inset-x-0 top-[5%] -mt-[60px]">
                                <h2 className="text-5xl md:text-8xl font-display text-white leading-[1.1] drop-shadow-2xl overflow-hidden max-w-2xl">
                                    {hook.text.split(hook.highlight).map((part, idx, arr) => (
                                        <React.Fragment key={idx}>
                                            <motion.span 
                                                className="opacity-95 inline-block"
                                                initial={i === 0 ? { y: '100%' } : false}
                                                animate={i === 0 ? { y: 0 } : false}
                                                transition={{ duration: 1.2, ease: [0.22, 1, 0.36, 1], delay: 0.2 }}
                                            >
                                                {part}
                                            </motion.span>
                                            {idx < arr.length - 1 && (
                                              <motion.span 
                                                className="text-analux-gold-strong italic inline-block mx-3"
                                                initial={i === 0 ? { opacity: 0, scale: 0.9 } : false}
                                                animate={i === 0 ? { opacity: 1, scale: 1 } : false}
                                                transition={{ duration: 1.5, delay: 0.6 }}
                                              >
                                                {hook.highlight}
                                              </motion.span>
                                            )}
                                        </React.Fragment>
                                    ))}
                                </h2>

                                {/* CTA CARD FOR FIRST HOOK */}
                                {i === 0 && (
                                  <motion.div 
                                    className="max-w-md mt-10 p-5 bg-analux-plum/90 backdrop-blur-3xl border border-analux-gold-strong/30 rounded-[2.5rem] flex gap-6 items-center shadow-[0_30px_60px_-15px_rgba(0,0,0,0.7),0_0_40px_rgba(212,175,55,0.1)]"
                                    initial={{ opacity: 0, y: 30 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 1, delay: 1.2, ease: "easeOut" }}
                                  >
                                    <div className="w-24 h-24 shrink-0 rounded-2xl overflow-hidden border border-white/10 shadow-2xl group relative">
                                      <div className="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent z-10" />
                                      <AnimatePresence>
                                        <motion.img 
                                          key={currentImageIndex}
                                          src={SLIDER_IMAGES[currentImageIndex]} 
                                          alt="AnaLux Jewelry" 
                                          className="h-full w-full object-cover absolute inset-0"
                                          initial={{ x: "100%", scale: 1.1, opacity: 0 }}
                                          animate={{ x: 0, scale: 1, opacity: 1 }}
                                          exit={{ x: "-100%", scale: 0.9, opacity: 0 }}
                                          transition={{ 
                                            x: { type: "spring", stiffness: 300, damping: 30 },
                                            opacity: { duration: 0.8 },
                                            scale: { duration: 1.2 }
                                          }}
                                        />
                                      </AnimatePresence>
                                    </div>
                                    <div className="flex flex-col gap-3">
                                      <h4 className="text-white font-display text-xl leading-tight">
                                        Assine agora e entre para a experiência <span className="text-analux-gold-soft italic">premium</span> da Analux.
                                      </h4>
                                      <button 
                                        onClick={onJoin}
                                        className="w-full bg-white text-analux-plum px-8 py-3 rounded-full text-[10px] font-black uppercase tracking-[0.2em] hover:bg-analux-gold-strong hover:text-white transition-all shadow-xl active:scale-95 flex items-center justify-center gap-3 group/btn"
                                      >
                                        Assinar agora
                                        <ArrowRight size={14} className="group-hover/btn:translate-x-1 transition-transform" />
                                      </button>
                                    </div>
                                  </motion.div>
                                )}
                            </motion.div>
                        );
                    })}

                    <motion.div 
                        style={{ opacity: ctaOpacity, scale: ctaScale }} 
                        className="absolute inset-x-0 top-1/2 -translate-y-1/2 z-30"
                    >
                        <div className="flex flex-col items-start gap-10 w-full">
                            {/* Elegant Label */}
                            <div className="flex flex-col gap-6">
                                <div className="flex items-center gap-4">
                                    <div className="h-px w-12 bg-analux-gold-strong" />
                                    <span className="text-analux-gold-strong uppercase tracking-[0.5em] text-[10px] font-bold font-body">
                                        Experiência Analux
                                    </span>
                                </div>
                                
                                <h3 className="text-4xl md:text-6xl font-display text-white leading-[1.1] max-w-2xl mb-2">
                                    Sua jornada extraordinária começa com o <span className="text-analux-gold-soft italic">próximo ritual.</span>
                                </h3>

                                <span className="text-analux-gold-soft/60 uppercase tracking-[0.5em] text-[11px] font-black">
                                    Membro Exclusive
                                </span>
                            </div>
                            
                            {/* Refined Brand Button */}
                            <button 
                                onClick={onJoin} 
                                className="relative group overflow-hidden bg-gradient-to-r from-[#49172d] to-[#2d1435] border border-analux-gold-strong/30 rounded-full px-20 py-8 shadow-[0_20px_50px_rgba(73,23,45,0.6)] hover:shadow-analux-gold-strong/30 transition-all duration-500"
                            >
                                <div className="relative z-10 flex items-center gap-10">
                                    <span className="text-analux-gold-soft uppercase tracking-[0.3em] text-lg font-black">
                                        Quero Minha Box
                                    </span>
                                    <div className="w-14 h-14 rounded-full bg-analux-gold-strong/10 flex items-center justify-center border border-analux-gold-strong/20 group-hover:bg-analux-gold-strong/20 transition-colors">
                                        <ArrowRight size={24} className="text-analux-gold-strong transition-transform group-hover:translate-x-1" />
                                    </div>
                                </div>
                                
                                {/* Glow Effect */}
                                <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000" />
                            </button>

                            <p className="text-[10px] text-white/40 uppercase tracking-[0.4em] pl-2">
                                Assinatura Ana Lux • Edição Limitada
                            </p>
                        </div>
                    </motion.div>
                </div>
            </div>
        </div>

        <AnimatePresence>
          {introFinished && (
            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              exit={{ opacity: 0 }}
              className="absolute bottom-16 left-1/2 -translate-x-1/2 z-30 flex flex-col items-center gap-3"
              style={{ opacity: indicatorScrollOpacity }}
            >
              <span className="text-[9px] text-analux-gold-soft uppercase tracking-[0.6em] font-bold mb-1">
                Role para ver mais
              </span>
              <motion.div 
                animate={{ 
                    y: [0, 10, 0],
                    opacity: [0.4, 1, 0.4]
                }}
                transition={{ repeat: Infinity, duration: 1.5, ease: "easeInOut" }}
                className="flex flex-col items-center"
              >
                  <ChevronDown className="text-analux-gold-strong" size={24} />
                  <div className="w-px h-8 bg-gradient-to-b from-analux-gold-strong to-transparent -mt-2" />
              </motion.div>
            </motion.div>
          )}
        </AnimatePresence>

        <div 
          className="absolute bottom-12 right-10 z-30 flex items-center gap-6 opacity-30 hover:opacity-100 transition-opacity duration-1000 cursor-pointer group" 
          style={{ opacity: introFinished ? 0.3 : 0 }}
          onClick={() => {
            if (containerRef.current) {
              const bottom = containerRef.current.getBoundingClientRect().bottom + window.scrollY;
              window.scrollTo({ top: bottom, behavior: 'smooth' });
            }
          }}
        >
            <div className="flex items-center gap-4">
                <div className="w-16 h-px bg-white/40 group-hover:bg-analux-gold-strong transition-colors" />
                <span className="text-[7px] text-white uppercase tracking-[0.4em] font-black group-hover:text-analux-gold-soft transition-colors">Pular Abertura</span>
            </div>
        </div>
      </div>
    </div>
  );
};

export default ScrollVideoHero;
