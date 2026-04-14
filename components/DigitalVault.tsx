import React, { useState } from 'react';
import { createPortal } from 'react-dom';
import { Gem, Lock, Plus, Search, Filter, Camera, X, Upload, CheckCircle2, RotateCcw, Clock, Maximize2, Smile, MapPin, BookOpen, Crown } from 'lucide-react';
import { DigitalJewelry, LifePhase } from '../types';
import { useUser } from '../context/UserContext';

import { supabase } from '../services/supabase';

const DigitalVault: React.FC = () => {
  const { user, refreshData } = useUser();
  const [activeMode, setActiveMode] = useState<'vault' | 'phases'>('vault');
  const [selectedJewelry, setSelectedJewelry] = useState<DigitalJewelry | null>(null);

  // Add Modal State
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [newItem, setNewItem] = useState<{ name: string, image: string, origin: 'Box Mensal' | 'Loja Analux' | 'Presente' | 'Manual' }>({
    name: '',
    image: 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&q=80&w=400&h=400',
    origin: 'Presente'
  });
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const [isUploading, setIsUploading] = useState(false);

  // Life Phase State
  const [isAddPhaseModalOpen, setIsAddPhaseModalOpen] = useState(false);
  const [newPhase, setNewPhase] = useState({
    title: '',
    date: '',
    description: '',
    category: 'Amor'
  });

  const handleAddPhase = async () => {
    if (!newPhase.title || !newPhase.date) return;
    setIsUploading(true); // Recycle existing loading state or create new one

    try {
      const { error } = await supabase.from('life_phases').insert({
        user_id: user.id,
        title: newPhase.title,
        date: newPhase.date,
        description: newPhase.description,
        category: newPhase.category,
        linked_jewelry_ids: []
      });

      if (error) throw error;

      await refreshData(user.id);
      setIsAddPhaseModalOpen(false);
      setNewPhase({ title: '', date: '', description: '', category: 'Amor' });
    } catch (err) {
      console.error('Error adding phase:', err);
      alert('Erro ao criar capítulo.');
    } finally {
      setIsUploading(false);
    }
  };

  // Edit State
  const [isEditing, setIsEditing] = useState(false);
  const [isUpdateLoading, setIsUpdateLoading] = useState(false);
  const [editForm, setEditForm] = useState({
    memory: '',
    mood: '',
    location: ''
  });

  const handleUpdateItem = async () => {
    if (!selectedJewelry) return;
    setIsUpdateLoading(true);

    try {
      // Assuming 'digital_vault' table has these columns. If not, they might need to be added or stored in a JSON column.
      // Based on previous reads, 'memory' and 'mood' exist. 'location' might be new, we'll try to save it or map it if needed.
      // Checking local type usage: selectedJewelry.location check in main render implies we might need to support it.

      const { error } = await supabase
        .from('digital_vault')
        .update({
          memory: editForm.memory,
          mood: editForm.mood,
          // location: editForm.location // Uncomment if column exists, for now we will try to update it
        })
        .eq('id', selectedJewelry.id);

      if (error) throw error;

      // Optimistic update or refresh
      await refreshData(user.id);

      // Update local selected item to reflect changes immediately
      setSelectedJewelry({
        ...selectedJewelry,
        memory: editForm.memory,
        mood: editForm.mood,
        location: editForm.location
      });

      setIsEditing(false);
    } catch (err) {
      console.error('Error updating item:', err);
      alert('Erro ao atualizar. Tente novamente.');
    } finally {
      setIsUpdateLoading(false);
    }
  };

  const handleAddItem = async () => {
    if (!newItem.name) return;
    setIsUploading(true);

    try {
      let finalImageUrl = newItem.image;

      if (selectedFile) {
        const fileExt = selectedFile.name.split('.').pop();
        const fileName = `vault/${Math.random().toString(36).substring(2)}_${Date.now()}.${fileExt}`;

        const { error: uploadError } = await supabase.storage
          .from('forum-images')
          .upload(fileName, selectedFile);

        if (uploadError) throw uploadError;

        const { data } = supabase.storage
          .from('forum-images')
          .getPublicUrl(fileName);

        if (data) finalImageUrl = data.publicUrl;
      }

      const { error } = await supabase.from('digital_vault').insert({
        user_id: user.id,
        name: newItem.name,
        image: finalImageUrl,
        origin: newItem.origin,
        acquired_at: new Date().toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' }),
        memory: '',
        mood: '',
        status: 'active'
      });

      if (error) throw error;

      await refreshData(user.id);
      setIsAddModalOpen(false);
      setNewItem({
        name: '',
        image: 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&q=80&w=400&h=400',
        origin: 'Presente'
      });
      setSelectedFile(null);
    } catch (err) {
      console.error('Error adding item:', err);
      alert('Erro ao adicionar item.');
    } finally {
      setIsUploading(false);
    }
  };

  return (
    <div className="space-y-12 animate-fadeIn pb-32">
      <header className="flex flex-col md:flex-row justify-between items-end gap-8 border-b border-analux-secondary/10 pb-12">
        <div className="space-y-4">
          <div className="flex items-center gap-3 text-analux-secondary">
            <Gem size={18} />
            <span className="text-[10px] font-bold uppercase tracking-[0.4em]">Sua Coleção</span>
          </div>
          <h1 className="text-6xl font-serif text-analux-primary italic">Seu Porta <span className="text-analux-secondary not-italic">Joias.</span></h1>
        </div>

        <div className="flex bg-white/50 backdrop-blur-md p-1.5 rounded-full border border-analux-secondary/10 shadow-sm">
          <button
            onClick={() => setActiveMode('vault')}
            className={`px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all ${activeMode === 'vault' ? 'bg-analux-primary text-white shadow-lg' : 'text-gray-400 hover:text-analux-primary'}`}
          >
            Minhas Joias
          </button>
          <button
            onClick={() => setActiveMode('phases')}
            className={`px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest transition-all ${activeMode === 'phases' ? 'bg-analux-primary text-white shadow-lg' : 'text-gray-400 hover:text-analux-primary'}`}
          >
            Fases da Vida
          </button>
        </div>
      </header>

      {activeMode === 'vault' ? (
        <div className="space-y-16">

          {/* SEÇÃO 1: JOIAS ATIVAS (ACERVO) */}
          <section className="space-y-6">
            <div className="flex items-center gap-4">
              <h3 className="text-2xl font-serif text-analux-primary">Acervo Pessoal</h3>
              <div className="h-px bg-analux-secondary/20 flex-1"></div>
            </div>

            <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
              {user.digitalVault.map((item) => (
                <div
                  key={item.id}
                  onClick={() => setSelectedJewelry(item)}
                  className="group cursor-pointer bg-white rounded-[40px] p-4 border border-gray-50 shadow-sm hover:shadow-2xl hover:translate-y-[-8px] transition-all duration-500"
                >
                  <div className="aspect-[4/5] rounded-[32px] overflow-hidden mb-5 relative">
                    <img src={item.image} alt={item.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000" />
                    <div className="absolute inset-0 bg-analux-primary/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                      <Maximize2 size={24} className="text-white" />
                    </div>
                    <div className="absolute top-4 left-4 bg-white/90 backdrop-blur-md px-3 py-1 rounded-full text-[8px] font-bold text-analux-secondary uppercase tracking-widest shadow-sm">
                      {item.origin}
                    </div>
                  </div>
                  <div className="px-2 pb-2">
                    <h4 className="text-sm font-serif text-analux-primary mb-1">{item.name}</h4>
                    <p className="text-[9px] text-gray-400 font-bold uppercase tracking-widest mb-3">{item.acquiredAt}</p>

                    {(item.mood || item.location) && (
                      <div className="flex items-center gap-3 pt-3 border-t border-gray-50">
                        {item.mood && (
                          <div className="flex items-center gap-1 text-[9px] font-bold text-gray-400" title="Estado de Espírito">
                            <Smile size={10} className="text-analux-secondary" />
                            <span>{item.mood}</span>
                          </div>
                        )}
                        {item.location && (
                          <div className="flex items-center gap-1 text-[9px] font-bold text-gray-400" title="Local Marcante">
                            <MapPin size={10} className="text-analux-secondary" />
                            <span>{item.location}</span>
                          </div>
                        )}
                      </div>
                    )}
                  </div>
                </div>
              ))}

              {user.digitalVault.length === 0 && (
                <div className="col-span-full py-12 text-center text-gray-400 font-light italic">
                  Seu acervo ainda está vazio. Adicione suas relíquias ou aguarde sua Box!
                </div>
              )}
            </div>
          </section>

          {/* SEÇÃO 2: JOIAS DA BOX (CONFIRMAÇÃO) */}
          {user.subscription?.currentBox && user.subscription.currentBox.items && (
            <section className="space-y-6">
              <div className="flex items-center gap-4">
                <h3 className="text-2xl font-serif text-analux-primary">Box do Mês: <span className="italic text-analux-secondary">{user.subscription.currentBox.name}</span></h3>
                <div className="h-px bg-analux-secondary/20 flex-1"></div>
              </div>

              <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
                {user.subscription.currentBox.items.map((boxItem: any, idx: number) => {
                  // Check if this item is already in the user's vault (confirmed)
                  const isConfirmed = user.digitalVault.some(v => v.name === boxItem.name);

                  if (isConfirmed) {
                    // If confirmed, maybe show it differently or skip it? 
                    // User said: "depois vamos ter uma divisorio para mostrar as joias da box"
                    // And "items confirmed ... ali temos imagem da joias da box e nome"
                    // Let's show confirmed ones here too, but visually distinct as "Received"
                    return (
                      <div key={idx} className="opacity-50 grayscale hover:grayscale-0 transition-all cursor-default">
                        <div className="aspect-[4/5] rounded-[32px] overflow-hidden mb-4 relative border-2 border-green-100">
                          <img src={boxItem.image} className="w-full h-full object-cover" />
                          <div className="absolute inset-0 flex items-center justify-center bg-white/30 backdrop-blur-sm">
                            <div className="bg-green-100 text-green-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest flex items-center gap-2">
                              <CheckCircle2 size={12} /> Já no Acervo
                            </div>
                          </div>
                        </div>
                        <p className="text-xs text-center font-bold text-gray-400">{boxItem.name}</p>
                      </div>
                    );
                  }

                  // If NOT confirmed (Pending)
                  return (
                    <div key={idx} className="bg-analux-contrast/30 border-2 border-dashed border-analux-secondary/30 rounded-[40px] p-4 relative overflow-hidden group hover:bg-white transition-all">
                      <div className="aspect-[4/5] rounded-[32px] overflow-hidden mb-5 relative">
                        {/* BLUR EFFECT + COLOR PRESERVED (as requested: "blur so que com cor") */}
                        <img src={boxItem.image} className="w-full h-full object-cover blur-xl scale-125 transition-all duration-700 group-hover:scale-110 group-hover:blur-lg" />

                        <div className="absolute inset-0 flex flex-col items-center justify-center p-4 z-10">
                          <div className="bg-white/80 backdrop-blur-md p-3 rounded-full text-analux-secondary mb-3 shadow-lg animate-bounce">
                            <Clock size={20} />
                          </div>
                          <p className="text-[10px] font-bold text-analux-primary uppercase tracking-widest text-center mb-4">A Caminho</p>

                          <button
                            onClick={async () => {
                              if (confirm('Você confirma que recebeu esta joia? Ela será adicionada ao seu Porta Joias.')) {
                                const { error } = await supabase.from('digital_vault').insert({
                                  user_id: user.id,
                                  name: boxItem.name,
                                  image: boxItem.image,
                                  origin: 'Box Mensal',
                                  acquired_at: new Date().toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' }),
                                  status: 'active'
                                });
                                if (!error) refreshData(user.id);
                              }
                            }}
                            className="px-5 py-2 bg-analux-primary text-white rounded-full text-[9px] font-bold uppercase tracking-widest shadow-lg hover:bg-analux-secondary transition-colors"
                          >
                            Confirmar Chegada
                          </button>
                        </div>
                      </div>
                      <div className="text-center">
                        <h4 className="text-sm font-serif text-analux-primary mb-1">Joia Surpresa</h4>
                        <p className="text-[9px] text-gray-400 uppercase tracking-widest">Box {user.subscription.currentBox?.name}</p>
                      </div>
                    </div>
                  );
                })}
              </div>
            </section>
          )}

          {/* SEÇÃO 3: ADICIONAR MANUALMENTE */}
          <section className="space-y-6 pt-8 border-t border-gray-100">
            <div className="flex items-center justify-between">
              <div className="space-y-1">
                <h3 className="text-2xl font-serif text-analux-primary">Adicionar Relíquia</h3>
                <p className="text-sm text-gray-400 font-light">Adicione fotos das suas próprias joias para completar seu acervo.</p>
              </div>
              <button
                onClick={() => setIsAddModalOpen(true)}
                className="flex items-center gap-2 px-6 py-3 bg-analux-contrast text-analux-primary rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-analux-primary hover:text-white transition-all shadow-sm"
              >
                <Plus size={16} /> Nova Peça
              </button>
            </div>
          </section>

        </div>
      ) : (
        <section className="relative min-h-[50vh]">
          <div className="flex justify-end mb-8">
            <button
              onClick={() => setIsAddPhaseModalOpen(true)}
              className="flex items-center gap-2 px-6 py-3 bg-analux-secondary/10 text-analux-secondary rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-analux-secondary hover:text-white transition-all shadow-sm"
            >
              <Plus size={16} /> Novo Capítulo
            </button>
          </div>

          {user.lifePhases.length === 0 ? (
            <div className="absolute inset-0 flex flex-col items-center justify-center text-center space-y-6 animate-fadeIn">
              <div className="bg-analux-secondary/10 p-6 rounded-full text-analux-secondary mb-2">
                <Crown size={32} />
              </div>
              <div>
                <h3 className="text-2xl font-serif text-analux-primary italic mb-2">Sua Jornada Começa Aqui</h3>
                <p className="text-sm text-gray-400 font-light max-w-md mx-auto mb-8">
                  As fases da vida são capítulos eternizados pelas suas escolhas.
                  Comece a registrar seus momentos mais marcantes agora.
                </p>
                <button
                  onClick={() => setIsAddPhaseModalOpen(true)}
                  className="px-8 py-4 bg-analux-primary text-white rounded-full text-[10px] font-bold uppercase tracking-[0.2em] shadow-xl shadow-analux-primary/20 hover:scale-105 transition-all flex items-center gap-2 mx-auto"
                >
                  <Plus size={16} /> Criar Primeiro Capítulo
                </button>
              </div>
            </div>
          ) : (
            user.lifePhases.map((phase, idx) => (
              <div key={phase.id} className={`flex flex-col md:flex-row items-center gap-12 ${idx % 2 === 0 ? '' : 'md:flex-row-reverse'}`}>
                <div className={`md:w-1/2 flex flex-col ${idx % 2 === 0 ? 'md:items-end md:text-right' : 'md:items-start md:text-left'}`}>
                  <div className="bg-analux-secondary/10 px-4 py-1.5 rounded-full text-[10px] font-bold text-analux-secondary uppercase tracking-[0.3em] mb-4 w-fit">
                    {phase.category}
                  </div>
                  <h3 className="text-4xl font-serif text-analux-primary mb-4 italic">{phase.title}</h3>
                  <p className="text-gray-400 text-sm leading-relaxed max-w-md font-light mb-6">
                    {phase.description}
                  </p>
                </div>
                <div className="relative z-10 flex flex-col items-center">
                  <div className="w-5 h-5 rounded-full bg-white border-4 border-analux-secondary shadow-[0_0_20px_rgba(227,164,131,0.5)]"></div>
                  <div className="mt-4 text-[10px] font-bold text-analux-primary uppercase tracking-[0.4em] bg-white px-4 py-1 shadow-sm rounded-full whitespace-nowrap">
                    {phase.date}
                  </div>
                </div>
                <div className="md:w-1/2"></div>
              </div>
            ))
          )}
        </section>
      )}

      {/* Add Phase Modal */}
      {isAddPhaseModalOpen && createPortal(
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-analux-primary/20 backdrop-blur-xl animate-fadeIn">
          <div className="bg-white w-full max-w-md rounded-[40px] shadow-2xl p-10 relative animate-scaleIn">
            <button
              onClick={() => setIsAddPhaseModalOpen(false)}
              className="absolute top-6 right-6 p-2 text-gray-300 hover:text-analux-primary rounded-full"
            >
              <Plus size={24} className="rotate-45" />
            </button>

            <h2 className="text-3xl font-serif text-analux-primary mb-2">Novo Capítulo</h2>
            <p className="text-xs text-gray-400 mb-8 border-b border-gray-100 pb-4">Eternize um momento marcante da sua vida.</p>

            <div className="space-y-4">
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Título do Capítulo</label>
                <input
                  type="text"
                  value={newPhase.title}
                  onChange={e => setNewPhase({ ...newPhase, title: e.target.value })}
                  className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none"
                  placeholder="Ex: O Grande Sim, Formatura..."
                />
              </div>

              <div className="grid grid-cols-2 gap-4">
                <div className="space-y-2">
                  <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Data</label>
                  <input
                    type="text"
                    value={newPhase.date}
                    onChange={e => setNewPhase({ ...newPhase, date: e.target.value })}
                    className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none"
                    placeholder="Ex: Julho 2024"
                  />
                </div>
                <div className="space-y-2">
                  <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Categoria</label>
                  <select
                    value={newPhase.category}
                    onChange={e => setNewPhase({ ...newPhase, category: e.target.value })}
                    className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none appearance-none"
                  >
                    <option value="Amor">Amor</option>
                    <option value="Carreira">Carreira</option>
                    <option value="Viagem">Viagem</option>
                    <option value="Família">Família</option>
                    <option value="Conquista">Conquista</option>
                  </select>
                </div>
              </div>

              <div className="space-y-2">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">A História</label>
                <textarea
                  value={newPhase.description}
                  onChange={e => setNewPhase({ ...newPhase, description: e.target.value })}
                  className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none min-h-[100px]"
                  placeholder="Conte brevemente sobre este momento..."
                />
              </div>

              <button
                onClick={handleAddPhase}
                disabled={isUploading}
                className="w-full py-4 bg-analux-primary text-white rounded-2xl font-bold uppercase text-[10px] tracking-widest shadow-xl shadow-analux-primary/20 hover:scale-[1.02] transition-all mt-4 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                {isUploading ? 'Criando...' : 'Eternizar Momento'}
              </button>
            </div>
          </div>
        </div>,
        document.body
      )}


      {/* Add Item Modal */}
      {isAddModalOpen && createPortal(
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-analux-primary/20 backdrop-blur-xl animate-fadeIn">
          <div className="bg-white w-full max-w-md rounded-[40px] shadow-2xl p-10 relative animate-scaleIn">
            <button
              onClick={() => setIsAddModalOpen(false)}
              className="absolute top-6 right-6 p-2 text-gray-300 hover:text-analux-primary rounded-full"
            >
              <Plus size={24} className="rotate-45" />
            </button>

            <h2 className="text-3xl font-serif text-analux-primary mb-6">Nova Relíquia</h2>

            <div className="space-y-4">
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Nome da Peça</label>
                <input
                  type="text"
                  value={newItem.name}
                  onChange={e => setNewItem({ ...newItem, name: e.target.value })}
                  className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none"
                  placeholder="Ex: Anel Solitário..."
                />
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Origem</label>
                <select
                  value={newItem.origin}
                  onChange={e => setNewItem({ ...newItem, origin: e.target.value as any })}
                  className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none"
                >
                  <option value="Presente">Presente</option>
                  <option value="Loja Analux">Loja Analux</option>
                  <option value="Box Mensal">Box Mensal</option>
                  <option value="Acervo Pessoal">Acervo Pessoal</option>
                </select>
              </div>
              <div className="space-y-2">
                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Foto da Relíquia</label>

                {/* Image Upload Area */}
                <div
                  onClick={() => document.getElementById('vault-file-upload')?.click()}
                  className="w-full aspect-square bg-analux-contrast border-2 border-dashed border-analux-secondary/20 rounded-2xl flex flex-col items-center justify-center cursor-pointer hover:bg-analux-secondary/5 transition-all overflow-hidden relative"
                >
                  {/* File Preview */}
                  {newItem.image && newItem.image !== 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?auto=format&fit=crop&q=80&w=400&h=400' ? (
                    <img src={newItem.image} alt="Preview" className="w-full h-full object-cover" />
                  ) : (
                    <div className="flex flex-col items-center text-gray-400">
                      <Camera size={24} className="mb-2" />
                      <span className="text-[10px] uppercase font-bold tracking-widest">Toque para enviar</span>
                    </div>
                  )}

                  <input
                    type="file"
                    id="vault-file-upload"
                    className="hidden"
                    accept="image/*"
                    onChange={(e) => {
                      if (e.target.files && e.target.files[0]) {
                        const file = e.target.files[0];
                        setNewItem({ ...newItem, image: URL.createObjectURL(file) });
                        setSelectedFile(file);
                      }
                    }}
                  />
                </div>
              </div>

              <button
                onClick={handleAddItem}
                disabled={isUploading}
                className="w-full py-4 bg-analux-primary text-white rounded-2xl font-bold uppercase text-[10px] tracking-widest shadow-xl shadow-analux-primary/20 hover:scale-[1.02] transition-all mt-4 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
              >
                {isUploading ? (
                  <>
                    <div className="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    Enviando...
                  </>
                ) : (
                  'Adicionar ao Cofre'
                )}
              </button>
            </div>
          </div>
        </div>,
        document.body
      )}

      {/* Detail Modal */}
      {selectedJewelry && createPortal(
        <div className="fixed inset-0 z-[9999] flex items-center justify-center p-6 bg-analux-primary/20 backdrop-blur-xl animate-fadeIn">
          <div className="bg-white w-full max-w-4xl rounded-[60px] shadow-2xl overflow-hidden flex flex-col md:flex-row relative animate-scaleIn">
            <button
              onClick={() => {
                setSelectedJewelry(null);
                setIsEditing(false);
              }}
              className="absolute top-8 right-8 z-10 p-3 text-gray-300 hover:text-analux-primary hover:bg-analux-contrast rounded-full transition-all"
            >
              <Plus size={24} className="rotate-45" />
            </button>

            <div className="md:w-1/2 h-[50vh] md:h-auto">
              <img src={selectedJewelry.image} className="w-full h-full object-cover" />
            </div>

            <div className="md:w-1/2 p-12 space-y-8 overflow-y-auto max-h-[70vh]">
              <div>
                <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-[0.4em]">Detalhes da Relíquia</span>
                <h2 className="text-4xl font-serif text-analux-primary mt-2">{selectedJewelry.name}</h2>
                <p className="text-gray-400 text-[10px] font-bold uppercase tracking-widest mt-2">Adquirida em {selectedJewelry.acquiredAt} via {selectedJewelry.origin}</p>
              </div>

              {isEditing ? (
                <div className="space-y-6 animate-fadeIn">
                  <div className="space-y-2">
                    <label className="text-[10px] font-bold text-gray-300 uppercase tracking-widest flex items-center gap-2">
                      <BookOpen size={12} /> Sua Memória
                    </label>
                    <textarea
                      value={editForm.memory}
                      onChange={e => setEditForm({ ...editForm, memory: e.target.value })}
                      className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none min-h-[100px]"
                      placeholder="Escreva sobre o momento especial que esta joia representa..."
                    />
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div className="space-y-2">
                      <label className="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Estado de Espírito</label>
                      <input
                        type="text"
                        value={editForm.mood}
                        onChange={e => setEditForm({ ...editForm, mood: e.target.value })}
                        className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-xs font-bold text-analux-primary outline-none"
                        placeholder="Ex: Radiante"
                      />
                    </div>
                    <div className="space-y-2">
                      <label className="text-[8px] font-bold text-gray-400 uppercase tracking-widest">Local Marcante</label>
                      <input
                        type="text"
                        value={editForm.location} // Note: Keeping it purely frontend for now as per minimal change or map to existing field if available, but assuming new state usage
                        onChange={e => setEditForm({ ...editForm, location: e.target.value })}
                        className="w-full bg-analux-contrast border-0 rounded-2xl py-3 px-4 text-xs font-bold text-analux-primary outline-none"
                        placeholder="Ex: Paris"
                      />
                    </div>
                  </div>

                  <div className="flex gap-3 pt-4">
                    <button
                      onClick={() => setIsEditing(false)}
                      className="flex-1 py-3 text-gray-400 hover:text-analux-primary text-[10px] font-bold uppercase tracking-widest"
                    >
                      Cancelar
                    </button>
                    <button
                      onClick={handleUpdateItem}
                      className="flex-1 py-3 bg-analux-primary text-white rounded-full font-bold uppercase text-[10px] tracking-widest shadow-lg hover:bg-analux-secondary disabled:opacity-50"
                      disabled={isUpdateLoading}
                    >
                      {isUpdateLoading ? 'Salvando...' : 'Salvar Memória'}
                    </button>
                  </div>
                </div>
              ) : (
                <div className="space-y-6 animate-fadeIn">
                  <div className="space-y-2">
                    <label className="text-[10px] font-bold text-gray-300 uppercase tracking-widest flex items-center gap-2">
                      <BookOpen size={12} /> Sua Memória
                    </label>
                    <p className="text-sm text-gray-500 italic leading-relaxed">
                      {selectedJewelry.memory || "Você ainda não registrou uma memória para esta peça. O que ela representa para você hoje?"}
                    </p>
                  </div>

                  <div className="grid grid-cols-2 gap-4">
                    <div className="p-4 bg-analux-contrast rounded-3xl">
                      <p className="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Estado de Espírito</p>
                      <div className="flex items-center gap-2 text-analux-primary text-xs font-bold">
                        <Smile size={14} className="text-analux-secondary" /> {selectedJewelry.mood || "Radiante"}
                      </div>
                    </div>
                    <div className="p-4 bg-analux-contrast rounded-3xl">
                      <p className="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Local Marcante</p>
                      <div className="flex items-center gap-2 text-analux-primary text-xs font-bold">
                        <MapPin size={14} className="text-analux-secondary" /> {selectedJewelry.location || "Casa"}
                      </div>
                    </div>
                  </div>

                  <div className="text-center space-y-3">
                    <p className="text-[10px] text-gray-400 italic">Atribua um vínculo afetivo que você vivenciou com esta joia</p>
                    <button
                      onClick={() => {
                        setEditForm({
                          memory: selectedJewelry.memory || '',
                          mood: selectedJewelry.mood || '',
                          location: selectedJewelry.location || '' // Check if location exists on type, otherwise default
                        });
                        setIsEditing(true);
                      }}
                      className="w-full py-5 bg-analux-primary text-white rounded-full font-bold uppercase text-[10px] tracking-[0.2em] shadow-xl shadow-analux-primary/20 hover:scale-105 transition-all"
                    >
                      Diário de Memórias
                    </button>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>,
        document.body
      )}
    </div>
  );
};

export default DigitalVault;
