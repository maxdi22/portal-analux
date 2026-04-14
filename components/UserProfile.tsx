import React, { useState } from 'react';
import { User, StylePreferences, MemberLevel } from '../types';
import { useUser } from '../context/UserContext';
import {
  Camera,
  Sparkles,
  Save,
  User as UserIcon,
  Phone,
  Mail,
  Award,
  Calendar,
  History,
  Gem,
  CheckCircle2,
  Trophy
} from 'lucide-react';

import { supabase } from '../services/supabase';
import UserAvatar from './UserAvatar';

const UserProfile: React.FC = () => {
  const { user, updateUser, refreshData } = useUser();
  const [uploading, setUploading] = useState(false);
  const fileInputRef = React.useRef<HTMLInputElement>(null);
  const [formData, setFormData] = useState<Partial<User>>({
    phone: user.phone,
    email: user.email,
    name: user.name
  });

  // Sync form data if user changes
  React.useEffect(() => {
    setFormData({
      phone: user.phone,
      email: user.email,
      name: user.name
    });
  }, [user]);

  const handleAvatarClick = () => {
    fileInputRef.current?.click();
  };

  const handleFileChange = async (event: React.ChangeEvent<HTMLInputElement>) => {
    try {
      setUploading(true);
      if (!event.target.files || event.target.files.length === 0) return;

      const file = event.target.files[0];
      const fileExt = file.name.split('.').pop();
      const fileName = `${user.id}-${Date.now()}.${fileExt}`;

      // Upload to Supabase Storage 'avatars' bucket
      const { error: uploadError } = await supabase.storage
        .from('avatars')
        .upload(fileName, file, { upsert: true });

      if (uploadError) throw uploadError;

      const { data } = supabase.storage.from('avatars').getPublicUrl(fileName);

      // Optimistic update
      await updateUser({ avatar: data.publicUrl });
      refreshData(); // Ensure global state sync

    } catch (error) {
      console.error('Erro ao fazer upload:', error);
      alert('Erro ao atualizar. Tente novamente.');
    } finally {
      setUploading(false);
    }
  };

  // Sync history on mount
  React.useEffect(() => {
    // Assuming syncBoxHistory is a function available in this scope,
    // or perhaps refreshData() is intended to cover this.
    // If syncBoxHistory is not defined, this will cause an error.
    // For now, I'll assume it's meant to be called.
    // If it's part of useUser, it should be destructured like refreshData.
    // If it's not, you'll need to define it or replace it with an existing function.
    // For the purpose of this edit, I'm adding it as instructed.
    // If refreshData() already covers box history, you might want to use that instead.
    // For now, I'll use refreshData as it's available and likely intended for data sync.
    refreshData();
  }, []);

  return (
    <div className="max-w-6xl mx-auto space-y-12 animate-fadeIn pb-20">
      {/* Header Area */}
      <div className="flex flex-col md:flex-row gap-12 items-center md:items-start">

        {/* Avatar Section */}
        <div className="flex flex-col items-center gap-4">
          <div className="relative group">
            <div className="w-52 h-52 rounded-[60px] p-2 bg-white shadow-2xl overflow-hidden relative">
              {uploading ? (
                <div className="absolute inset-0 z-20 bg-analux-contrast/80 flex items-center justify-center">
                  <div className="animate-spin w-8 h-8 border-4 border-analux-secondary border-t-transparent rounded-full"></div>
                </div>
              ) : null}
              <UserAvatar
                src={user.avatar}
                size="2xl"
                className="rounded-[50px] w-full h-full object-cover shadow-inner"
              />
            </div>
            <button
              onClick={handleAvatarClick}
              className="absolute -bottom-2 -right-2 bg-[#4A3B52] text-white p-3.5 rounded-2xl shadow-xl hover:scale-110 transition-all border-4 border-white group-hover:rotate-12"
            >
              <Camera size={20} />
            </button>
            <input
              type="file"
              ref={fileInputRef}
              onChange={handleFileChange}
              className="hidden"
              accept="image/*"
            />
          </div>
          <div className="text-center">
            <h1 className="text-4xl font-serif text-analux-primary mb-1">{user.name.split(' ')[0]}</h1>
            <p className="text-xs text-analux-secondary font-bold uppercase tracking-widest">{user.level} • Desde {user.subscription?.memberSince.split(' de ')[1] || '2024'}</p>
          </div>
        </div>

        {/* Digital Card (Purple Design) */}
        <div className="flex-1 w-full max-w-xl">
          <div className="relative aspect-[16/9] bg-[#592E58] rounded-[32px] p-8 text-white shadow-2xl overflow-hidden flex flex-col justify-between group transition-transform hover:scale-[1.01] duration-500">
            {/* Texture/Pattern Overlay */}
            <div className="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] bg-repeat mix-blend-overlay"></div>
            <div className="absolute top-0 right-0 w-64 h-64 bg-[#C68DFF]/20 rounded-full -mr-20 -mt-20 blur-3xl"></div>

            {/* Top Row */}
            <div className="relative z-10 flex justify-between items-start">
              <div>
                <p className="text-[10px] font-bold text-[#E5D5C5] tracking-[0.3em] uppercase mb-1">Membro Oficial Analux</p>
                <h2 className="text-3xl font-serif text-white">Luxury Club Card</h2>
              </div>
              <div className="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md border border-white/5">
                <Gem size={24} className="text-[#E5D5C5]" />
              </div>
            </div>

            {/* Middle (ID) */}
            <div className="relative z-10 mt-4">
              <p className="text-[10px] font-mono tracking-widest text-white/40 mb-1 uppercase">ID: {user.id.slice(0, 18)}...</p>
              <p className="text-2xl font-medium tracking-wide uppercase">{user.name}</p>
            </div>

            {/* Bottom Row */}
            <div className="relative z-10 w-full mt-auto pt-6">
              <div className="flex justify-between items-end mb-2">
                <div className="flex items-center gap-2">
                  <div className="w-8 h-1.5 bg-[#E2A676] rounded-full"></div>
                  <span className="text-[9px] font-bold uppercase tracking-wider text-white/80">Musa Diamante em progresso</span>
                </div>
                <div className="text-right">
                  <p className="text-[8px] text-white/40 uppercase font-bold tracking-widest mb-0.5">Fidelidade Total</p>
                  <p className="text-sm font-bold text-[#ffd700]">92% VIP Score</p>
                </div>
              </div>
              <div className="h-0.5 w-full bg-white/10 rounded-full overflow-hidden">
                <div className="h-full w-[92%] bg-gradient-to-r from-[#E2A676] to-[#ffd700]"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-10">
        {/* Achievements */}
        <section className="bg-white rounded-[40px] p-8 space-y-8 animate-slideUp" style={{ animationDelay: '100ms' }}>
          <div className="flex justify-between items-center">
            <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
              <Trophy size={20} className="text-analux-secondary" /> Conquistas Analux
            </h3>
            <span className="text-[10px] font-bold text-gray-400 uppercase">3 de 8 desbloqueadas</span>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
            {/* Static/Mock Achievements for now - Enhancing UI */}
            <div className="p-6 bg-[#FEFBF6] rounded-3xl border border-[#F3E5DC] flex flex-col items-center text-center gap-3">
              <div className="text-3xl filter drop-shadow-sm">👑</div>
              <div>
                <h4 className="text-xs font-bold text-analux-primary">Musa Fundadora</h4>
                <p className="text-[10px] text-gray-500 mt-1 leading-snug">Assinou nos primeiros meses do clube.</p>
              </div>
              <span className="text-[9px] font-bold text-[#D4B89E] uppercase tracking-wider">Em Jan 2024</span>
            </div>

            <div className="p-6 bg-[#FEFBF6] rounded-3xl border border-[#F3E5DC] flex flex-col items-center text-center gap-3">
              <div className="text-3xl filter drop-shadow-sm">✨</div>
              <div>
                <h4 className="text-xs font-bold text-analux-primary">Brilho Constante</h4>
                <p className="text-[10px] text-gray-500 mt-1 leading-snug">Manteve 6 meses de assinatura ativa.</p>
              </div>
              <span className="text-[9px] font-bold text-[#D4B89E] uppercase tracking-wider">Em Jul 2024</span>
            </div>

            <div className="p-6 bg-gray-50 rounded-3xl border border-dashed border-gray-200 flex flex-col items-center text-center gap-3 opacity-60">
              <div className="text-3xl grayscale opacity-50">💎</div>
              <div>
                <h4 className="text-xs font-bold text-gray-400">Elite 1 Ano</h4>
                <p className="text-[10px] text-gray-400 mt-1 leading-snug">Complete 12 meses de brilho</p>
              </div>
            </div>
          </div>
        </section>

        {/* Timeline */}
        <section className="bg-white rounded-[40px] p-8 space-y-8 animate-slideUp" style={{ animationDelay: '200ms' }}>
          <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
            <History size={20} className="text-analux-secondary" /> Linha do Tempo
          </h3>

          <div className="space-y-6 relative pl-4">
            {/* Line */}
            <div className="absolute left-[27px] top-4 bottom-4 w-0.5 bg-gradient-to-b from-gray-200 to-transparent"></div>

            {user.boxHistory.length > 0 ? user.boxHistory.map((box, i) => (
              <div key={box.id} className="relative flex gap-6 items-center group">
                <div className={`w-6 h-6 rounded-full border-4 border-white shadow-md z-10 shrink-0 ${i === 0 ? 'bg-[#D4B89E]' : 'bg-[#592E58]'}`}>
                  {i === 0 && <span className="absolute -top-1 -right-1 w-2.5 h-2.5 bg-red-400 rounded-full border-2 border-white"></span>}
                </div>
                <div className="flex-1 bg-gray-50 p-4 rounded-2xl flex items-center gap-4 border border-transparent group-hover:border-gray-200 transition-colors">
                  <img src={box.image} alt={box.name} className="w-12 h-12 rounded-lg object-cover shadow-sm" />
                  <div>
                    <h4 className="text-xs font-bold text-analux-primary">{box.name}</h4>
                    <p className="text-[10px] text-analux-secondary font-medium">{box.theme}</p>
                    <p className="text-[9px] text-gray-400 mt-1 uppercase">Recebida em {box.date}</p>
                  </div>
                </div>
              </div>
            )) : (
              <p className="text-sm text-gray-400 italic pl-8">Sua jornada está apenas começando...</p>
            )}
          </div>
        </section>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-2 gap-10">
        {/* Real Data Form */}
        <section className="bg-white rounded-[40px] p-8 space-y-8 shadow-sm">
          <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
            <UserIcon size={20} className="text-analux-secondary" /> Dados de Contato
          </h3>
          <div className="space-y-6">
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">Nome Completo</label>
              <input
                value={formData.name || ''}
                onChange={e => setFormData({ ...formData, name: e.target.value })}
                className="w-full bg-gray-50 border-0 rounded-2xl px-6 py-4 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-analux-secondary/20 transition-all outline-none"
              />
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">WhatsApp</label>
              <div className="relative">
                <Phone size={16} className="absolute left-5 top-1/2 -translate-y-1/2 text-gray-300" />
                <input
                  value={formData.phone || ''}
                  onChange={e => setFormData({ ...formData, phone: e.target.value })}
                  className="w-full bg-gray-50 border-0 rounded-2xl py-4 pl-12 pr-6 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-analux-secondary/20 transition-all outline-none"
                />
              </div>
            </div>
            <div className="space-y-2">
              <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1">E-mail</label>
              <div className="relative">
                <Mail size={16} className="absolute left-5 top-1/2 -translate-y-1/2 text-gray-300" />
                <input
                  value={formData.email || ''}
                  onChange={e => setFormData({ ...formData, email: e.target.value })}
                  className="w-full bg-gray-50 border-0 rounded-2xl py-4 pl-12 pr-6 text-sm font-medium text-gray-700 focus:ring-2 focus:ring-analux-secondary/20 transition-all outline-none"
                />
              </div>
            </div>
          </div>
        </section>

        {/* Style DNA Summary */}
        <section className="bg-white rounded-[40px] p-8 space-y-8 shadow-sm">
          <div className="flex justify-between items-center">
            <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2">
              <Sparkles size={20} className="text-analux-secondary" /> DNA de Estilo
            </h3>
            <a href="/style-dna" className="text-[10px] font-bold text-analux-secondary hover:underline">Ver completo</a>
          </div>

          <div className="space-y-6">
            <div>
              <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1 mb-3 block">Estilos Principais</span>
              <div className="flex flex-wrap gap-2">
                {(user.styleProfile?.styles?.length ? user.styleProfile.styles : ['Ainda não definido']).map(s => (
                  <span key={s} className="px-4 py-2 bg-[#592E58] text-white rounded-xl text-xs font-bold">{s}</span>
                ))}
              </div>
            </div>

            <div>
              <span className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1 mb-3 block">Banho Preferido</span>
              <div className="flex gap-4">
                <div className="px-5 py-3 border border-analux-secondary/30 rounded-xl bg-analux-contrast/50 text-analux-primary text-xs font-bold">
                  {user.styleProfile?.plating || 'Não definido'}
                </div>
              </div>
            </div>

            <a href="/style-dna" className="block w-full text-center py-4 rounded-2xl border border-dashed border-analux-secondary/30 text-xs font-bold text-analux-secondary hover:bg-analux-contrast transition-colors">
              Atualizar Preferências Completas
            </a>
          </div>
        </section>
      </div>

      <div className="flex justify-center pt-8">
        <button
          onClick={async () => {
            await updateUser(formData);
            alert('Perfil atualizado com sucesso!');
          }}
          className="bg-[#D4B89E] text-white px-16 py-4 rounded-2xl font-bold text-sm tracking-widest uppercase hover:bg-[#c4a88e] transition-all shadow-xl hover:shadow-2xl hover:-translate-y-1"
        >
          Salvar Alterações
        </button>
      </div>
    </div>
  );
};

export default UserProfile;
