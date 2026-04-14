import React, { useState, useEffect } from 'react';
import { 
    X, 
    Calendar, 
    DollarSign, 
    Activity, 
    Heart, 
    MessageCircle, 
    Package, 
    Plus, 
    Trash2,
    Loader2,
    ExternalLink,
    ChevronRight,
    TrendingUp,
    Shield,
    Clock,
    Zap
} from 'lucide-react';
import { supabase } from '../../services/supabase';
import { SubscriberNote, SubscriberInteraction } from '../../types';

interface Subscriber360DrawerProps {
    subscriberId: string;
    onClose: () => void;
}

const Subscriber360Drawer: React.FC<Subscriber360DrawerProps> = ({ subscriberId, onClose }) => {
    const [activeTab, setActiveTab] = useState<'overview' | 'timeline' | 'style' | 'notes'>('overview');
    const [loading, setLoading] = useState(true);
    const [savingNote, setSavingNote] = useState(false);
    const [subscriber, setSubscriber] = useState<any>(null);
    const [notes, setNotes] = useState<SubscriberNote[]>([]);
    const [interactions, setInteractions] = useState<SubscriberInteraction[]>([]);
    const [newNote, setNewNote] = useState('');
    const [noteCategory, setNoteCategory] = useState<SubscriberNote['category']>('Geral');

    useEffect(() => {
        if (subscriberId) {
            fetchFullProfile();
            fetchNotes();
        }
    }, [subscriberId]);

    const fetchFullProfile = async () => {
        try {
            setLoading(true);
            const { data, error } = await supabase
                .from('profiles')
                .select(`
                    *,
                    subscriptions (*)
                `)
                .eq('id', subscriberId)
                .single();

            if (data) {
                setSubscriber(data);
                
                // Build Timeline from various sources
                const tl: SubscriberInteraction[] = [
                    {
                        id: 'joined',
                        type: 'rate', // Using rate icon for joins
                        content: 'Tornou-se uma Musa Analux',
                        date: data.created_at,
                    }
                ];

                // Add forum posts
                const { data: posts } = await supabase
                    .from('forum_posts')
                    .select('*')
                    .eq('author_id', subscriberId);
                
                if (posts) {
                    posts.forEach(p => tl.push({
                        id: p.id,
                        type: 'post',
                        content: `Postou: "${p.title}"`,
                        date: p.created_at
                    }));
                }

                // Sort by date desc
                setInteractions(tl.sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime()));
            }
        } catch (err) {
            console.error('Error fetching 360 data:', err);
        } finally {
            setLoading(false);
        }
    };

    const fetchNotes = async () => {
        const { data } = await supabase
            .from('subscriber_notes')
            .select('*')
            .eq('subscriber_id', subscriberId)
            .order('created_at', { ascending: false });
        
        if (data) {
            setNotes(data.map(n => ({
                id: n.id,
                subscriberId: n.subscriber_id,
                adminId: n.admin_id,
                content: n.content,
                category: n.category as any,
                createdAt: n.created_at
            })));
        }
    };

    const handleAddNote = async () => {
        if (!newNote.trim()) return;
        setSavingNote(true);
        try {
            const { data: userData } = await supabase.auth.getUser();
            const { error } = await supabase.from('subscriber_notes').insert({
                subscriber_id: subscriberId,
                admin_id: userData.user?.id,
                content: newNote,
                category: noteCategory
            });

            if (error) throw error;
            setNewNote('');
            await fetchNotes();
        } catch (err) {
            console.error('Error adding note:', err);
        } finally {
            setSavingNote(false);
        }
    };

    const handleDeleteNote = async (id: string) => {
        if (!confirm('Excluir esta nota?')) return;
        await supabase.from('subscriber_notes').delete().eq('id', id);
        fetchNotes();
    };

    if (loading && !subscriber) {
        return (
            <div className="fixed inset-0 z-[100] bg-black/5backdrop-blur-sm flex justify-end">
                <div className="w-full max-w-2xl bg-white shadow-2xl h-full flex items-center justify-center">
                    <Loader2 className="animate-spin text-amber-500" size={32} />
                </div>
            </div>
        );
    }

    return (
        <div className="fixed inset-0 z-[100] bg-black/20 backdrop-blur-sm flex justify-end animate-in fade-in duration-300">
            <div className="w-full max-w-2xl bg-slate-50 shadow-2xl h-full flex flex-col animate-in slide-in-from-right duration-500">
                {/* Header */}
                <div className="bg-analux-primary p-8 text-white relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>
                    <button 
                        onClick={onClose}
                        className="absolute right-6 top-6 p-2 hover:bg-white/10 rounded-full transition-colors"
                    >
                        <X size={24} />
                    </button>

                    <div className="flex items-center gap-6 relative z-10">
                        <div className="w-24 h-24 rounded-[32px] bg-white/10 border-2 border-white/20 p-1 flex-shrink-0">
                            <img 
                                src={subscriber?.avatar || `https://i.pravatar.cc/150?u=${subscriber?.id}`} 
                                className="w-full h-full object-cover rounded-[28px]" 
                                alt={subscriber?.name} 
                            />
                        </div>
                        <div className="space-y-1">
                            <div className="flex items-center gap-3">
                                <h2 className="text-3xl font-serif italic">{subscriber?.name}</h2>
                                {subscriber?.role === 'superadmin' && (
                                    <span className="px-2 py-0.5 bg-rose-500 text-white text-[10px] font-bold rounded-lg flex items-center gap-1 shadow-lg shadow-rose-900/20">
                                        <Shield size={10} fill="currentColor" /> MASTER
                                    </span>
                                )}
                                {subscriber?.role === 'admin' && (
                                    <span className="px-2 py-0.5 bg-amber-400 text-analux-primary text-[10px] font-bold rounded-lg flex items-center gap-1 shadow-lg shadow-amber-900/20">
                                        <Shield size={10} fill="currentColor" /> ADMIN
                                    </span>
                                )}
                            </div>
                            <p className="text-white/60 font-medium tracking-wide">{subscriber?.email}</p>
                            <div className="flex items-center gap-2 mt-2">
                                <span className="bg-white/10 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border border-white/10">
                                    {subscriber?.subscriptions?.plan || 'Musa Bronze'}
                                </span>
                                <span className="flex items-center gap-1 text-[10px] font-bold text-amber-400 uppercase tracking-widest ml-2">
                                    <Zap size={10} fill="currentColor" /> {subscriber?.points || 0} Pontos
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Tabs Navigation */}
                <div className="flex border-b border-slate-200 bg-white sticky top-0 z-10 px-4">
                    {(['overview', 'timeline', 'style', 'notes'] as const).map(tab => (
                        <button
                            key={tab}
                            onClick={() => setActiveTab(tab)}
                            className={`px-6 py-4 text-[10px] font-bold uppercase tracking-[0.2em] transition-all relative ${
                                activeTab === tab ? 'text-analux-primary' : 'text-slate-400 hover:text-slate-600'
                            }`}
                        >
                            {tab === 'overview' && 'Visão Geral'}
                            {tab === 'timeline' && 'Linha do Tempo'}
                            {tab === 'style' && 'Estilo'}
                            {tab === 'notes' && 'Notas (CRM)'}
                            {activeTab === tab && (
                                <div className="absolute bottom-0 left-6 right-6 h-0.5 bg-analux-primary rounded-full" />
                            )}
                        </button>
                    ))}
                </div>

                {/* Content Area */}
                <div className="flex-1 overflow-y-auto p-8 space-y-8">
                    {activeTab === 'overview' && (
                        <div className="space-y-8 animate-in fade-in duration-500">
                            {/* KPI Grid */}
                            <div className="grid grid-cols-2 gap-4">
                                <div className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                                    <div className="flex items-center justify-between mb-2">
                                        <div className="p-2 bg-green-50 text-green-600 rounded-xl"><DollarSign size={18} /></div>
                                        <span className="text-[10px] font-bold text-green-600 uppercase tracking-widest">LTV Real</span>
                                    </div>
                                    <div className="text-2xl font-bold text-slate-800">R$ {subscriber?.lifetime_points || 0}</div>
                                    <div className="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Estimativa de Gastos</div>
                                </div>
                                <div className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                                    <div className="flex items-center justify-between mb-2">
                                        <div className="p-2 bg-rose-50 text-rose-600 rounded-xl"><Activity size={18} /></div>
                                        <span className="text-[10px] font-bold text-rose-600 uppercase tracking-widest">Score Eng.</span>
                                    </div>
                                    <div className="text-2xl font-bold text-slate-800">8.4 / 10</div>
                                    <div className="text-[9px] text-slate-400 uppercase tracking-widest mt-1">Membro Ativo</div>
                                </div>
                            </div>

                            {/* Status Section */}
                            <div className="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm space-y-6">
                                <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-400">Assinatura Atual</h3>
                                <div className="flex items-center justify-between">
                                    <div className="flex items-center gap-4">
                                        <div className="w-12 h-12 bg-analux-contrast rounded-2xl flex items-center justify-center text-analux-primary">
                                            <Package size={24} />
                                        </div>
                                        <div>
                                            <div className="font-bold text-slate-800">{subscriber?.subscriptions?.plan || 'Musa Ouro'}</div>
                                            <div className="text-xs text-slate-400">Cobrança Mensal</div>
                                        </div>
                                    </div>
                                    <span className="bg-green-100 text-green-700 px-4 py-1.5 rounded-full text-[10px] font-bold border border-green-200 uppercase tracking-widest">
                                        {subscriber?.subscriptions?.status || 'Active'}
                                    </span>
                                </div>
                                
                                <div className="grid grid-cols-2 gap-8 pt-4 border-t border-slate-50">
                                    <div>
                                        <div className="text-[10px] font-bold uppercase text-slate-300 tracking-widest mb-1">Próxima Box</div>
                                        <div className="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                            <Calendar size={14} /> {subscriber?.subscriptions?.next_box_date || '10/05/2026'}
                                        </div>
                                    </div>
                                    <div>
                                        <div className="text-[10px] font-bold uppercase text-slate-300 tracking-widest mb-1">Musa Desde</div>
                                        <div className="flex items-center gap-2 text-sm text-slate-600 font-medium">
                                            <Clock size={14} /> {new Date(subscriber?.created_at).toLocaleDateString('pt-BR')}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Quick Actions */}
                            <div className="space-y-4">
                                <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-400 ml-2">Ações Sugeridas</h3>
                                <div className="grid grid-cols-1 gap-2">
                                    <button className="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:border-amber-400 transition-all group">
                                        <div className="flex items-center gap-3">
                                            <Plus className="text-amber-500" size={18} />
                                            <span className="text-sm font-medium text-slate-700">Bonificar com Pontos</span>
                                        </div>
                                        <ChevronRight size={16} className="text-slate-300 group-hover:translate-x-1" />
                                    </button>
                                    <button className="flex items-center justify-between p-4 bg-white border border-slate-100 rounded-2xl hover:border-rose-400 transition-all group">
                                        <div className="flex items-center gap-3">
                                            <ExternalLink className="text-rose-600" size={18} />
                                            <span className="text-sm font-medium text-slate-700">Ver Perfil no Stripe</span>
                                        </div>
                                        <ChevronRight size={16} className="text-slate-300 group-hover:translate-x-1" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}

                    {activeTab === 'timeline' && (
                        <div className="space-y-6 animate-in fade-in duration-500">
                             <div className="relative pl-8 space-y-12 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100">
                                {interactions.map((event) => (
                                    <div key={event.id} className="relative">
                                        <div className="absolute -left-8 mt-1.5 w-6 h-6 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center z-10 shadow-sm">
                                            {event.type === 'post' && <MessageCircle size={12} className="text-rose-600" />}
                                            {event.type === 'rate' && <Heart size={12} fill="currentColor" className="text-rose-500" />}
                                            {event.type === 'comment' && <ChevronRight size={12} className="text-emerald-500" />}
                                            {event.type === 'payment_success' && <DollarSign size={12} className="text-green-500" />}
                                        </div>
                                        <div>
                                            <div className="text-[10px] font-bold text-slate-300 uppercase tracking-widest mb-1">
                                                {new Date(event.date).toLocaleDateString('pt-BR', { day: '2-digit', month: 'long', year: 'numeric' })}
                                            </div>
                                            <div className="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm">
                                                <p className="text-sm text-slate-700 font-medium leading-relaxed">{event.content}</p>
                                            </div>
                                        </div>
                                    </div>
                                ))}

                                {interactions.length === 0 && (
                                    <div className="text-center py-12 text-slate-400">
                                        <Loader2 className="animate-spin mx-auto mb-4 opacity-20" size={32} />
                                        <p className="text-sm">Buscando rastros digitais...</p>
                                    </div>
                                )}
                             </div>
                        </div>
                    )}

                    {activeTab === 'style' && (
                        <div className="space-y-8 animate-in fade-in duration-500">
                            {/* Visual Plating Prefs */}
                            <div className="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm flex items-center justify-between">
                                <div>
                                    <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-400 mb-4">Preferência de Banho</h3>
                                    <div className="flex gap-3">
                                        <div className={`w-16 h-16 rounded-2xl border-2 flex flex-col items-center justify-center gap-1 ${
                                            subscriber?.style_profile?.plating === 'Dourado' || subscriber?.style_profile?.plating === 'Todos'
                                            ? 'border-amber-400 bg-amber-50 text-amber-700'
                                            : 'border-slate-50 text-slate-300'
                                        }`}>
                                            <TrendingUp size={18} />
                                            <span className="text-[8px] font-bold uppercase">Gold</span>
                                        </div>
                                        <div className={`w-16 h-16 rounded-2xl border-2 flex flex-col items-center justify-center gap-1 ${
                                            subscriber?.style_profile?.plating === 'Prateado' || subscriber?.style_profile?.plating === 'Todos'
                                            ? 'border-slate-400 bg-slate-50 text-slate-700'
                                            : 'border-slate-50 text-slate-300'
                                        }`}>
                                            <TrendingUp size={18} />
                                            <span className="text-[8px] font-bold uppercase">Silver</span>
                                        </div>
                                    </div>
                                </div>
                                <div className="text-right">
                                    <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-400 mb-2">Aro do Anel</h3>
                                    <div className="text-3xl font-serif text-analux-primary">{subscriber?.style_profile?.ringSize || '--'}</div>
                                </div>
                            </div>

                            {/* Tags de Estilos */}
                            <div className="space-y-4">
                                <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-400 ml-2">Assinaturas de Estilo</h3>
                                <div className="flex flex-wrap gap-2">
                                    {(subscriber?.style_profile?.styles || ['Clássico', 'Minimalista', 'Sofisticado']).map((tag: string) => (
                                        <span key={tag} className="px-5 py-2.5 bg-white border border-slate-100 rounded-full text-xs font-medium text-slate-600 shadow-sm">
                                            {tag}
                                        </span>
                                    ))}
                                </div>
                            </div>

                            {/* Full Detailed JSON Table if needed or visual list */}
                            <div className="bg-slate-100/50 p-6 rounded-3xl space-y-4">
                                <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-500 mb-2">Resumo da Curadoria</h3>
                                <div className="grid grid-cols-2 gap-4">
                                    <div className="text-xs"><span className="text-slate-400">Furos: </span> <span className="text-slate-700 font-bold">{subscriber?.style_profile?.earringHoles || '1'}</span></div>
                                    <div className="text-xs"><span className="text-slate-400">Signo: </span> <span className="text-slate-700 font-bold">{subscriber?.style_profile?.sign || '--'}</span></div>
                                    <div className="text-xs text-wrap"><span className="text-slate-400">Pets: </span> <span className="text-slate-700 font-bold">{subscriber?.style_profile?.pets || 'Não informado'}</span></div>
                                </div>
                            </div>
                        </div>
                    )}

                    {activeTab === 'notes' && (
                        <div className="space-y-6 animate-in fade-in duration-500 pb-20">
                            {/* Add Note Input */}
                            <div className="bg-white p-6 rounded-[32px] border border-rose-100 shadow-md shadow-rose-500/5 space-y-4">
                                <div className="flex gap-2">
                                    {(['Geral', 'Suporte', 'Logística', 'Financeiro'] as const).map(cat => (
                                        <button 
                                            key={cat}
                                            onClick={() => setNoteCategory(cat)}
                                            className={`px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border transition-all ${
                                                noteCategory === cat ? 'bg-analux-primary text-white border-analux-primary' : 'bg-slate-50 text-slate-400 border-slate-200'
                                            }`}
                                        >
                                            {cat}
                                        </button>
                                    ))}
                                </div>
                                <textarea
                                    className="w-full h-24 p-4 text-sm bg-slate-50 rounded-2xl border-none focus:ring-2 focus:ring-analux-primary/20 resize-none outline-none text-slate-700 placeholder:text-slate-300 transition-all font-medium"
                                    placeholder="Escrever algo importante sobre esta musa..."
                                    value={newNote}
                                    onChange={e => setNewNote(e.target.value)}
                                />
                                <div className="flex justify-end">
                                    <button
                                        onClick={handleAddNote}
                                        disabled={savingNote || !newNote.trim()}
                                        className="bg-analux-primary text-white px-6 py-2.5 rounded-full text-xs font-bold flex items-center gap-2 hover:bg-black active:scale-95 transition-all disabled:opacity-50"
                                    >
                                        {savingNote ? <Loader2 size={16} className="animate-spin" /> : <Plus size={16} />}
                                        Salvar Nota
                                    </button>
                                </div>
                            </div>

                            {/* Notes List */}
                            <div className="space-y-4">
                                {notes.map(note => (
                                    <div key={note.id} className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm group hover:shadow-md transition-all">
                                        <div className="flex justify-between items-start mb-3">
                                            <div className="flex items-center gap-2">
                                                <span className={`px-2 py-0.5 rounded-full text-[8px] font-bold uppercase tracking-[0.2em] border ${
                                                    note.category === 'Suporte' ? 'bg-cyan-50 text-cyan-700 border-cyan-100' :
                                                    note.category === 'Logística' ? 'bg-orange-50 text-orange-700 border-orange-100' :
                                                    note.category === 'Financeiro' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' :
                                                    'bg-slate-50 text-slate-500 border-slate-100'
                                                }`}>
                                                    {note.category}
                                                </span>
                                                <span className="text-[9px] font-bold text-slate-300 uppercase tracking-widest">{new Date(note.createdAt).toLocaleString('pt-BR')}</span>
                                            </div>
                                            <button 
                                                onClick={() => handleDeleteNote(note.id)}
                                                className="text-slate-200 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 p-1"
                                            >
                                                <Trash2 size={12} />
                                            </button>
                                        </div>
                                        <p className="text-sm text-slate-600 leading-relaxed font-medium">{note.content}</p>
                                    </div>
                                ))}
                                
                                {notes.length === 0 && (
                                    <div className="text-center py-20">
                                        <div className="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <MessageCircle size={32} />
                                        </div>
                                        <p className="text-sm text-slate-400 italic">Nenhuma nota interna registrada ainda.</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}
                </div>

                {/* Footer Controls */}
                <div className="p-8 bg-white border-t border-slate-100 grid grid-cols-2 gap-4">
                    <button className="flex items-center justify-center gap-2 py-3 bg-analux-secondary text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:scale-105 transition-all shadow-lg shadow-analux-secondary/20">
                        <Zap size={16} /> Ver Fluxo do Mês
                    </button>
                    <button 
                        onClick={onClose}
                        className="flex items-center justify-center gap-2 py-3 bg-slate-100 text-slate-600 rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-200 transition-all">
                        Fechar Painel
                    </button>
                </div>
            </div>
        </div>
    );
};

export default Subscriber360Drawer;
