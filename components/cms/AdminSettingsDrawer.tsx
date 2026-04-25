import React from 'react';
import { X, Sliders, Layout, Shield } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

interface AdminSettingsDrawerProps {
  isOpen: boolean;
  onClose: () => void;
  styling: any;
  onUpdateStyling: (field: string, value: any) => void;
}

const AdminSettingsDrawer: React.FC<AdminSettingsDrawerProps> = ({
  isOpen,
  onClose,
  styling,
  onUpdateStyling
}) => {
  return (
    <AnimatePresence>
      {isOpen && (
        <>
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            className="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[150]"
            onClick={onClose}
          />
          <motion.div
            initial={{ x: '100%' }}
            animate={{ x: 0 }}
            exit={{ x: '100%' }}
            transition={{ type: 'spring', damping: 25, stiffness: 200 }}
            className="fixed right-0 top-0 bottom-0 w-80 bg-white shadow-2xl z-[151] overflow-y-auto"
          >
            <div className="p-6 border-b border-slate-100 flex justify-between items-center sticky top-0 bg-white/80 backdrop-blur-md">
              <div className="flex items-center gap-3">
                <div className="p-2 bg-analux-gold-soft/20 rounded-lg text-analux-gold-strong">
                  <Sliders size={20} />
                </div>
                <div>
                  <h3 className="text-lg font-display font-bold">Layout & Estilo</h3>
                  <p className="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Ajustes de proporção</p>
                </div>
              </div>
              <button onClick={onClose} className="p-2 hover:bg-slate-50 rounded-full transition-all">
                <X size={20} className="text-slate-400" />
              </button>
            </div>

            <div className="p-8 space-y-10">
              {/* Hero Section */}
              <div className="space-y-6">
                <div className="flex items-center gap-2 text-analux-plum opacity-40">
                  <Layout size={14} />
                  <span className="text-[10px] font-bold uppercase tracking-widest">Section: Hero</span>
                </div>
                
                <div className="space-y-4">
                  <div>
                    <div className="flex justify-between text-[11px] font-bold mb-2">
                      <span className="text-slate-500 uppercase tracking-wider">Opacidade do Fundo</span>
                      <span className="text-analux-gold-strong">{styling.heroBgOpacity}%</span>
                    </div>
                    <input 
                      type="range" 
                      min="0" 
                      max="100" 
                      value={styling.heroBgOpacity}
                      onChange={(e) => onUpdateStyling('heroBgOpacity', parseInt(e.target.value))}
                      className="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-analux-gold-strong" 
                    />
                  </div>

                  <div>
                    <div className="flex justify-between text-[11px] font-bold mb-2">
                      <span className="text-slate-500 uppercase tracking-wider">Padding Superior</span>
                      <span className="text-analux-gold-strong">{styling.heroSectionPaddingTop}px</span>
                    </div>
                    <input 
                      type="range" 
                      min="0" 
                      max="80" 
                      value={styling.heroSectionPaddingTop}
                      onChange={(e) => onUpdateStyling('heroSectionPaddingTop', parseInt(e.target.value))}
                      className="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-analux-gold-strong" 
                    />
                  </div>

                  <div>
                    <div className="flex justify-between text-[11px] font-bold mb-2">
                      <span className="text-slate-500 uppercase tracking-wider">Padding Inferior</span>
                      <span className="text-analux-gold-strong">{styling.heroSectionPaddingBottom}px</span>
                    </div>
                    <input 
                      type="range" 
                      min="0" 
                      max="80" 
                      value={styling.heroSectionPaddingBottom}
                      onChange={(e) => onUpdateStyling('heroSectionPaddingBottom', parseInt(e.target.value))}
                      className="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-analux-gold-strong" 
                    />
                  </div>
                </div>
              </div>

              {/* Tips */}
              <div className="p-6 bg-slate-50 rounded-2xl border border-slate-100 space-y-3">
                <div className="flex items-center gap-2 text-slate-800">
                  <Shield size={14} className="text-analux-gold-strong" />
                  <span className="text-xs font-bold">Dica de Admin</span>
                </div>
                <p className="text-[11px] text-slate-500 leading-relaxed">
                  Os ajustes de proporção são aplicados instantaneamente na prévia à esquerda, mas só ficam permanentes após clicar em <strong>"Publicar Alterações"</strong> na barra inferior.
                </p>
              </div>
            </div>
          </motion.div>
        </>
      )}
    </AnimatePresence>
  );
};

export default AdminSettingsDrawer;
