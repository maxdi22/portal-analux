import React, { useState } from 'react';
import { Save, X, Eye, Edit3, Settings, Image as ImageIcon, Type, Layout, CheckCircle2 } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

interface LPAdminToolbarProps {
  isEditing: boolean;
  onToggleEdit: () => void;
  onPublish: () => Promise<void>;
  onReset: () => void;
  isSaving: boolean;
  hasChanges: boolean;
  onOpenSettings: () => void;
}

const LPAdminToolbar: React.FC<LPAdminToolbarProps> = ({
  isEditing,
  onToggleEdit,
  onPublish,
  onReset,
  isSaving,
  hasChanges,
  onOpenSettings
}) => {
  const [showPublishSuccess, setShowPublishSuccess] = useState(false);

  const handlePublish = async () => {
    await onPublish();
    setShowPublishSuccess(true);
    setTimeout(() => setShowPublishSuccess(false), 3000);
  };

  return (
    <>
      <div className="fixed bottom-8 left-1/2 -translate-x-1/2 z-[100] flex items-center gap-2 p-2 bg-slate-900/90 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl scale-90 sm:scale-100 transition-all hover:bg-slate-900">
        <motion.button
          onClick={onToggleEdit}
          animate={!isEditing ? { scale: [1, 1.02, 1] } : {}}
          transition={!isEditing ? { duration: 2, repeat: Infinity, ease: "easeInOut" } : {}}
          className={`flex items-center gap-2 px-4 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all ${
            isEditing 
            ? 'bg-analux-gold-strong text-slate-900 shadow-lg shadow-analux-gold-strong/20' 
            : 'text-white hover:bg-white/10'
          }`}
        >
          {isEditing ? <Eye size={16} /> : <Edit3 size={16} />}
          {isEditing ? 'Ver como Visitante' : 'Editar Página'}
        </motion.button>

        <div className="w-px h-6 bg-white/10 mx-1" />

        {isEditing && (
          <div className="flex items-center gap-1">
            <button
              onClick={onOpenSettings}
              className="p-2.5 text-white/70 hover:text-white hover:bg-white/10 rounded-xl transition-all"
              title="Configurações de Layout"
            >
              <Settings size={18} />
            </button>
            
            <button
              onClick={onReset}
              disabled={!hasChanges || isSaving}
              className="p-2.5 text-white/70 hover:text-white hover:bg-red-500/20 disabled:opacity-30 rounded-xl transition-all"
              title="Descartar Alterações"
            >
              <X size={18} />
            </button>

            <button
              onClick={handlePublish}
              disabled={!hasChanges || isSaving}
              className="ml-2 flex items-center gap-2 px-5 py-2.5 bg-white text-slate-900 rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-100 disabled:opacity-50 disabled:cursor-not-allowed transition-all"
            >
              {isSaving ? (
                <div className="w-4 h-4 border-2 border-slate-900 border-t-transparent rounded-full animate-spin" />
              ) : <Save size={16} />}
              Publicar Alterações
            </button>
          </div>
        )}

        {!isEditing && (
          <div className="px-4 py-2 text-white/40 text-[10px] uppercase tracking-widest font-bold">
            Portal Admin v1.0
          </div>
        )}
      </div>

      <AnimatePresence>
        {showPublishSuccess && (
          <motion.div
            initial={{ opacity: 0, y: 50 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: 20 }}
            className="fixed bottom-24 left-1/2 -translate-x-1/2 z-[101] flex items-center gap-3 px-6 py-4 bg-green-500 text-white rounded-2xl shadow-2xl"
          >
            <CheckCircle2 size={24} />
            <div className="text-sm font-bold">Alterações publicadas com sucesso!</div>
          </motion.div>
        )}
      </AnimatePresence>
    </>
  );
};

export default LPAdminToolbar;
