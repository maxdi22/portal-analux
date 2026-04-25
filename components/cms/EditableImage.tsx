import React, { useState, useRef, useEffect } from 'react';
import { createPortal } from 'react-dom';
import { ImageIcon, Link as LinkIcon, X, Upload, Loader2, Check } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { supabase } from '../../services/supabase';
import { toast } from 'react-hot-toast';

interface EditableImageProps {
  src: string;
  onChange: (newSrc: string) => void;
  isEditing: boolean;
  className?: string;
  alt?: string;
}

const EditableImage: React.FC<EditableImageProps> = ({ 
  src, 
  onChange, 
  isEditing, 
  className = "", 
  alt = "" 
}) => {
  const [showPopup, setShowPopup] = useState(false);
  const [tempSrc, setTempSrc] = useState(src);
  const [isUploading, setIsUploading] = useState(false);
  const [popoverPos, setPopoverPos] = useState({ top: 0, left: 0 });
  const triggerRef = useRef<HTMLDivElement>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  if (!isEditing) {
    return <img src={src} className={className} alt={alt} />;
  }

  const handleOpen = () => {
    if (triggerRef.current) {
      const rect = triggerRef.current.getBoundingClientRect();
      // Position logic: Try to place it to the right, if not enough space, use bottom or center
      const spaceRight = window.innerWidth - rect.right;
      let left = rect.right + 20;
      let top = rect.top;

      if (spaceRight < 400) { // Not enough space on right
        left = Math.max(20, rect.left);
        top = rect.bottom + 20;
      }

      // Final viewport constraints
      left = Math.min(left, window.innerWidth - 420);
      top = Math.max(20, Math.min(top, window.innerHeight - 500));

      setPopoverPos({ top, left });
    }
    setTempSrc(src);
    setShowPopup(true);
  };

  const handleApply = () => {
    onChange(tempSrc);
    setShowPopup(false);
  };

  const handleFileUpload = async (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (!file) return;

    if (!file.type.startsWith('image/')) {
      toast.error('Por favor, selecione uma imagem.');
      return;
    }

    setIsUploading(true);
    const fileExt = file.name.split('.').pop();
    const fileName = `${Math.random().toString(36).substring(2)}-${Date.now()}.${fileExt}`;
    const filePath = `cms/${fileName}`;

    try {
      const { error } = await supabase.storage
        .from('cms-assets')
        .upload(filePath, file);

      if (error) throw error;

      const { data: { publicUrl } } = supabase.storage
        .from('cms-assets')
        .getPublicUrl(filePath);

      setTempSrc(publicUrl);
      toast.success('Upload concluído!');
    } catch (error: any) {
      console.error('Error uploading image:', error);
      toast.error('Erro no upload: ' + error.message);
    } finally {
      setIsUploading(false);
    }
  };

  const PopoverUI = (
    <div className="fixed inset-0 z-[999] pointer-events-none">
      {/* Backdrop for closing */}
      <div className="absolute inset-0 bg-black/10 backdrop-blur-[2px] pointer-events-auto" onClick={() => setShowPopup(false)} />
      
      <motion.div
        initial={{ opacity: 0, scale: 0.95, y: 10 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        exit={{ opacity: 0, scale: 0.95, y: 10 }}
        style={{ top: popoverPos.top, left: popoverPos.left }}
        className="absolute pointer-events-auto bg-white rounded-3xl p-6 w-[380px] shadow-[0_20px_50px_rgba(0,0,0,0.3)] border border-slate-100 font-body flex flex-col gap-5"
        onClick={e => e.stopPropagation()}
      >
        <div className="flex justify-between items-center">
          <h3 className="text-lg font-display font-bold text-slate-900 leading-none">Mídia Contextual</h3>
          <button onClick={() => setShowPopup(false)} className="p-1 hover:bg-slate-50 rounded-lg text-slate-400">
            <X size={20} />
          </button>
        </div>

        <div className="space-y-4">
          <div 
            className={`border-2 border-dashed rounded-2xl p-6 flex flex-col items-center justify-center gap-2 transition-all cursor-pointer ${
              isUploading ? 'border-analux-gold-strong bg-analux-gold-soft/5' : 'border-slate-100 hover:border-analux-gold-strong hover:bg-slate-50'
            }`}
            onClick={() => fileInputRef.current?.click()}
          >
            <input type="file" ref={fileInputRef} className="hidden" accept="image/*" onChange={handleFileUpload} />
            {isUploading ? (
              <Loader2 size={24} className="text-analux-gold-strong animate-spin" />
            ) : (
              <div className="p-2 bg-analux-gold-soft/20 rounded-full text-analux-gold-strong">
                <Upload size={18} />
              </div>
            )}
            <div className="text-center">
              <p className="text-xs font-bold text-slate-700">{isUploading ? 'Enviando...' : 'Fazer Upload'}</p>
              <p className="text-[9px] text-slate-400 uppercase tracking-widest mt-0.5">JPG, PNG ou WebP</p>
            </div>
          </div>

          <div className="flex items-center gap-3">
             <div className="h-px flex-1 bg-slate-100" />
             <span className="text-[9px] uppercase tracking-widest text-slate-300 font-bold">ou link</span>
             <div className="h-px flex-1 bg-slate-100" />
          </div>

          <div>
            <div className="relative">
              <LinkIcon size={14} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
              <input
                type="text"
                value={tempSrc}
                onChange={e => setTempSrc(e.target.value)}
                placeholder="https://..."
                className="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-100 rounded-xl focus:ring-1 focus:ring-analux-gold-strong outline-none text-xs transition-all"
              />
            </div>
          </div>

          <div className="p-3 bg-slate-50 rounded-xl border border-slate-100">
             <p className="text-[9px] text-slate-400 uppercase tracking-widest font-bold mb-2">Prévia</p>
             {tempSrc ? (
               <img src={tempSrc} className="max-h-32 w-full object-cover rounded-lg" alt="Preview" />
             ) : (
               <div className="h-20 flex items-center justify-center text-slate-300 italic text-[10px]">Sem imagem</div>
             )}
          </div>
        </div>

        <div className="flex gap-2">
          <button
            onClick={() => setShowPopup(false)}
            className="flex-1 py-3 text-slate-400 font-bold uppercase tracking-widest text-[10px] hover:bg-slate-50 rounded-xl"
          >
            Cancelar
          </button>
          <button
            onClick={handleApply}
            disabled={isUploading || !tempSrc}
            className="flex-1 py-3 bg-analux-plum text-white font-bold uppercase tracking-widest text-[10px] rounded-xl shadow-lg hover:brightness-110 disabled:opacity-30 flex items-center justify-center gap-2"
          >
            <Check size={14} /> Aplicar
          </button>
        </div>
      </motion.div>
    </div>
  );

  return (
    <div className="relative group" ref={triggerRef}>
      <img src={src} className={className} alt={alt} />
      
      <div className="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center rounded-[inherit] cursor-pointer" onClick={handleOpen}>
        <div className="flex items-center gap-2 bg-white text-slate-900 px-4 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest shadow-xl transform scale-90 group-hover:scale-100 transition-all font-body">
          <ImageIcon size={14} />
          Trocar
        </div>
      </div>

      {showPopup && createPortal(
        <AnimatePresence>
          {showPopup && PopoverUI}
        </AnimatePresence>,
        document.getElementById('portal-root') || document.body
      )}
    </div>
  );
};

export default EditableImage;
