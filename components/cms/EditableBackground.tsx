import React, { useState, useRef } from 'react';
import { createPortal } from 'react-dom';
import { Settings2, Upload, Link as LinkIcon, Loader2, X, Check, Eye, Trash2 } from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import { supabase } from '../../services/supabase';
import { toast } from 'react-hot-toast';

interface BackgroundConfig {
  type: 'image' | 'video';
  url: string;
  fit: 'cover' | 'contain' | 'auto';
  repeat: 'no-repeat' | 'repeat' | 'repeat-x' | 'repeat-y';
  opacity: number;
}

interface EditableBackgroundProps {
  config: BackgroundConfig;
  onChange: (newConfig: BackgroundConfig) => void;
  isEditing: boolean;
  children: React.ReactNode;
  className?: string;
}

const EditableBackground: React.FC<EditableBackgroundProps> = ({
  config,
  onChange,
  isEditing,
  children,
  className = ""
}) => {
  const [showPopup, setShowPopup] = useState(false);
  const [tempConfig, setTempConfig] = useState<BackgroundConfig>(config);
  const [isUploading, setIsUploading] = useState(false);
  const [popoverPos, setPopoverPos] = useState({ top: 0, left: 0 });
  
  const triggerRef = useRef<HTMLDivElement>(null);
  const fileInputRef = useRef<HTMLInputElement>(null);

  const handleOpen = (e: React.MouseEvent) => {
    e.stopPropagation();
    if (triggerRef.current) {
      const rect = triggerRef.current.getBoundingClientRect();
      let left = rect.right + 20;
      let top = rect.top;

      if (window.innerWidth - rect.right < 420) {
        left = Math.max(20, rect.left - 420);
      }
      
      left = Math.max(20, Math.min(left, window.innerWidth - 420));
      top = Math.max(20, Math.min(top, window.innerHeight - 600));

      setPopoverPos({ top, left });
    }
    setTempConfig(config);
    setShowPopup(true);
  };

  const handleFileUpload = async (event: React.ChangeEvent<HTMLInputElement>) => {
    const file = event.target.files?.[0];
    if (!file) return;

    const isVideo = file.type.startsWith('video/');
    const isImage = file.type.startsWith('image/');

    if (!isImage && !isVideo) {
      toast.error('Selecione uma imagem ou vídeo válido.');
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

      setTempConfig(prev => ({
        ...prev,
        url: publicUrl,
        type: isVideo ? 'video' : 'image'
      }));
      toast.success('Upload concluído!');
    } catch (error: any) {
      console.error('Error uploading background:', error);
      toast.error('Erro no upload: ' + error.message);
    } finally {
      setIsUploading(false);
    }
  };

  const handleSave = () => {
    onChange(tempConfig);
    setShowPopup(false);
  };

  const popoverContent = (
    <div className="fixed inset-0 z-[1000] pointer-events-none">
      <div className="absolute inset-0 bg-black/10 backdrop-blur-[2px] pointer-events-auto" onClick={() => setShowPopup(false)} />
      
      <motion.div
        initial={{ opacity: 0, scale: 0.95, y: 10 }}
        animate={{ opacity: 1, scale: 1, y: 0 }}
        exit={{ opacity: 0, scale: 0.95, y: 10 }}
        style={{ top: popoverPos.top, left: popoverPos.left }}
        className="absolute pointer-events-auto bg-white rounded-[2.5rem] p-8 w-[400px] shadow-[0_30px_70px_rgba(0,0,0,0.4)] border border-slate-100 font-body flex flex-col gap-6"
        onClick={e => e.stopPropagation()}
      >
        <div className="flex justify-between items-center">
          <div>
            <h3 className="text-xl font-display font-bold text-slate-900 leading-tight">Plano de Fundo</h3>
            <p className="text-[10px] uppercase tracking-widest text-slate-400 font-bold mt-1">Configurações Avançadas</p>
          </div>
          <button onClick={() => setShowPopup(false)} className="p-2 hover:bg-slate-50 rounded-full text-slate-400 transition-colors">
            <X size={20} />
          </button>
        </div>

        <div className="space-y-6 overflow-y-auto max-h-[70vh] pr-2 custom-scrollbar">
          {/* Mídia Select */}
          <div className="space-y-3">
             <label className="text-[10px] uppercase tracking-widest font-bold text-slate-400">Origem da Mídia</label>
             <div 
               className={`border-2 border-dashed rounded-3xl p-8 flex flex-col items-center justify-center gap-3 transition-all cursor-pointer ${
                 isUploading ? 'border-analux-gold-strong bg-analux-gold-soft/5' : 'border-slate-100 hover:border-analux-gold-strong hover:bg-slate-50'
               }`}
               onClick={() => fileInputRef.current?.click()}
             >
               <input type="file" ref={fileInputRef} className="hidden" accept="image/*,video/*" onChange={handleFileUpload} />
               {isUploading ? (
                 <Loader2 size={32} className="text-analux-gold-strong animate-spin" />
               ) : (
                 <div className="p-3 bg-analux-gold-soft/20 rounded-2xl text-analux-gold-strong">
                   <Upload size={24} />
                 </div>
               )}
               <div className="text-center">
                 <p className="text-xs font-bold text-slate-700">{isUploading ? 'Processando...' : 'Fazer Upload'}</p>
                 <p className="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Imagens ou Vídeos (MP4)</p>
               </div>
             </div>

             <div className="relative mt-2">
                <LinkIcon size={14} className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                <input 
                  type="text"
                  value={tempConfig.url}
                  onChange={e => setTempConfig(prev => ({ ...prev, url: e.target.value }))}
                  placeholder="URL direta da mídia..."
                  className="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-2xl text-xs outline-none focus:ring-1 focus:ring-analux-gold-strong transition-all"
                />
             </div>
          </div>

          <div className="h-px bg-slate-100" />

          {/* Ajustes Layout */}
          <div className="grid grid-cols-2 gap-4">
             <div className="space-y-2">
                <label className="text-[10px] uppercase tracking-widest font-bold text-slate-400">Enquadramento</label>
                <select 
                  value={tempConfig.fit}
                  onChange={e => setTempConfig(prev => ({ ...prev, fit: e.target.value as any }))}
                  className="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none focus:ring-1 focus:ring-analux-gold-strong"
                >
                  <option value="cover">Preencher (Cover)</option>
                  <option value="contain">Conter (Contain)</option>
                  <option value="auto">Automático</option>
                </select>
             </div>
             <div className="space-y-2">
                <label className="text-[10px] uppercase tracking-widest font-bold text-slate-400">Repetição</label>
                <select 
                  value={tempConfig.repeat}
                  onChange={e => setTempConfig(prev => ({ ...prev, repeat: e.target.value as any }))}
                  className="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl text-xs outline-none focus:ring-1 focus:ring-analux-gold-strong"
                >
                  <option value="no-repeat">Não repetir</option>
                  <option value="repeat">Repetir tudo</option>
                  <option value="repeat-x">Repetir X</option>
                  <option value="repeat-y">Repetir Y</option>
                </select>
             </div>
          </div>

          {/* Opacidade */}
          <div className="space-y-3">
             <div className="flex justify-between items-center">
                <label className="text-[10px] uppercase tracking-widest font-bold text-slate-400">Opacidade do Conteúdo</label>
                <span className="text-xs font-bold text-slate-900">{tempConfig.opacity}%</span>
             </div>
             <input 
               type="range"
               min="0"
               max="100"
               value={tempConfig.opacity}
               onChange={e => setTempConfig(prev => ({ ...prev, opacity: parseInt(e.target.value) }))}
               className="w-full accent-analux-plum h-1.5 bg-slate-100 rounded-full appearance-none cursor-pointer"
             />
          </div>

          {/* Prévia Mídia Ativa */}
          <div className="p-4 bg-slate-50 rounded-3xl border border-slate-100">
             <div className="flex justify-between items-center mb-3">
               <p className="text-[10px] text-slate-400 uppercase tracking-widest font-bold">Preview Ativo</p>
               <div className={`px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-widest ${tempConfig.type === 'video' ? 'bg-blue-100 text-blue-600' : 'bg-emerald-100 text-emerald-600'}`}>
                 {tempConfig.type}
               </div>
             </div>
             <div className="relative aspect-video rounded-2xl overflow-hidden bg-white border border-slate-100">
                {tempConfig.url ? (
                  tempConfig.type === 'video' ? (
                    <video src={tempConfig.url} autoPlay muted loop className="w-full h-full object-cover" />
                  ) : (
                    <img src={tempConfig.url} className="w-full h-full object-cover" alt="Preview" />
                  )
                ) : (
                  <div className="h-full flex items-center justify-center text-slate-300 italic text-[10px]">Sem mídia</div>
                )}
             </div>
          </div>
        </div>

        <div className="flex gap-3 pt-2">
          <button
            onClick={() => setShowPopup(false)}
            className="flex-1 py-4 text-slate-400 font-bold uppercase tracking-widest text-[10px] hover:bg-slate-50 rounded-2xl transition-colors"
          >
            Cancelar
          </button>
          <button
            onClick={handleSave}
            disabled={isUploading || !tempConfig.url}
            className="flex-1 py-4 bg-analux-plum text-white font-bold uppercase tracking-widest text-[10px] rounded-2xl shadow-xl hover:shadow-analux-plum/20 hover:brightness-110 disabled:opacity-30 flex items-center justify-center gap-2 transition-all"
          >
            <Check size={14} /> Salvar Alterações
          </button>
        </div>
      </motion.div>
    </div>
  );

  return (
    <div className={`relative ${className}`} ref={triggerRef}>
      {/* Background Layer */}
      <div className="absolute inset-0 pointer-events-none overflow-hidden rounded-[inherit]">
        {config.url && (
           config.type === 'video' ? (
             <video 
               src={config.url} 
               autoPlay 
               muted 
               loop 
               playsInline
               className="w-full h-full"
               style={{ 
                 objectFit: config.fit as any,
                 opacity: config.opacity / 100
               }}
             />
           ) : (
             <div 
               className="w-full h-full"
               style={{ 
                 backgroundImage: `url(${config.url})`,
                 backgroundSize: config.fit === 'auto' ? 'auto' : config.fit,
                 backgroundRepeat: config.repeat,
                 opacity: config.opacity / 100
               }}
             />
           )
        )}
      </div>

      {/* Content Layer */}
      <div className="relative z-10 h-full">
        {children}
      </div>

      {/* Editor Overlay */}
      {isEditing && (
        <div 
          className="absolute inset-0 z-20 border-2 border-dashed border-analux-gold-strong/0 hover:border-analux-gold-strong/40 bg-analux-gold-strong/0 hover:bg-analux-gold-strong/5 transition-all cursor-pointer rounded-[inherit]"
          onClick={handleOpen}
        >
          <div className="absolute top-4 right-4 bg-white/90 backdrop-blur shadow-xl p-2 rounded-full text-analux-plum opacity-0 group-hover:opacity-100">
             <Settings2 size={16} />
          </div>
        </div>
      )}

      {showPopup && createPortal(
        <AnimatePresence>
          {showPopup && popoverContent}
        </AnimatePresence>,
        document.getElementById('portal-root') || document.body
      )}
    </div>
  );
};

export default EditableBackground;
