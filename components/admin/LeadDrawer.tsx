import React, { useState, useEffect } from 'react';
import { 
    X, 
    Phone, 
    Mail, 
    MessageCircle, 
    Calendar, 
    Trash2, 
    Loader2, 
    ExternalLink, 
    Plus, 
    History, 
    UserPlus, 
    MoreVertical,
    TrendingDown,
    TrendingUp,
    Zap,
    MapPin,
    AlertCircle
} from 'lucide-react';
import { supabase } from '../../services/supabase';
import { LeadData, LeadInteraction, LeadStage } from '../../types';

interface LeadDrawerProps {
    leadId: string;
    onClose: () => void;
    onUpdate: () => void;
}

const LeadDrawer: React.FC<LeadDrawerProps> = ({ leadId, onClose, onUpdate }) => {
    const [activeTab, setActiveTab] = useState<'info' | 'timeline' | 'tasks'>('info');
    const [loading, setLoading] = useState(true);
    const [saving, setSaving] = useState(false);
    const [lead, setLead] = useState<LeadData | null>(null);
    const [interactions, setInteractions] = useState<LeadInteraction[]>([]);
    const [newNote, setNewNote] = useState('');

    useEffect(() => {
        if (leadId) fetchLeadDetails();
    }, [leadId]);

    const fetchLeadDetails = async () => {
        try {
            setLoading(true);
            const { data, error } = await supabase
                .from('leads')
                .select('*')
                .eq('id', leadId)
                .single();

            if (data) {
                setLead({
                    id: data.id,
                    name: data.name,
                    email: data.email,
                    phone: data.phone,
                    stage: data.stage as LeadStage,
                    source: data.source,
                    interestLevel: data.interest_level as any,
                    expectedValue: Number(data.expected_value),
                    createdAt: data.created_at,
                    lastContact: data.last_contact,
                    notes: data.notes
                });

                const { data: interactionData } = await supabase
                    .from('lead_interactions')
                    .select('*')
                    .eq('lead_id', leadId)
                    .order('created_at', { ascending: false });
                
                if (interactionData) setInteractions(interactionData);
            }
        } catch (err) {
            console.error('Error fetching lead:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleAddInteraction = async (type: string, content: string) => {
        setSaving(true);
        try {
            const { data: userData } = await supabase.auth.getUser();
            await supabase.from('lead_interactions').insert({
                lead_id: leadId,
                admin_id: userData.user?.id,
                type,
                content
            });
            
            if (type === 'note') setNewNote('');
            await fetchLeadDetails();
        } finally {
            setSaving(false);
        }
    };

    const updateLeadStage = async (newStage: LeadStage) => {
        await supabase.from('leads').update({ stage: newStage }).eq('id', leadId);
        await handleAddInteraction('stage_change', `Etapa alterada para: ${newStage}`);
        onUpdate();
    };

    const handleWhatsApp = () => {
        if (!lead?.phone) return;
        const phone = lead.phone.replace(/\D/g, '');
        const text = encodeURIComponent(`Olá ${lead.name}, aqui é a equipe Analux! Vimos que você tem interesse no nosso clube...`);
        window.open(`https://wa.me/55${phone}?text=${text}`, '_blank');
        handleAddInteraction('whatsapp', 'Iniciou contato via WhatsApp');
    };

    if (loading && !lead) {
        return (
            <div className="fixed inset-0 z-[110] bg-black/10 backdrop-blur-sm flex justify-end">
                <div className="w-full max-w-xl bg-white shadow-2xl h-full flex items-center justify-center">
                    <Loader2 className="animate-spin text-amber-500" size={32} />
                </div>
            </div>
        );
    }

    return (
        <div className="fixed inset-0 z-[110] bg-black/30 backdrop-blur-sm flex justify-end animate-in fade-in duration-300">
            <div className="w-full max-w-xl bg-slate-50 shadow-2xl h-full flex flex-col animate-in slide-in-from-right duration-500">
                
                {/* Header (Premium Lead Info) */}
                <div className="bg-slate-900 p-8 text-white relative overflow-hidden">
                    <div className="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full -mr-32 -mt-32 blur-[80px]"></div>
                    <button onClick={onClose} className="absolute right-6 top-6 p-2 hover:bg-white/10 rounded-full transition-all">
                        <X size={24} />
                    </button>

                    <div className="flex items-center gap-5 relative z-10">
                        <div className="w-20 h-20 rounded-3xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-500 font-serif text-3xl italic shadow-inner">
                            {lead?.name.charAt(0)}
                        </div>
                        <div>
                            <div className="flex items-center gap-3">
                                <h2 className="text-2xl font-serif italic">{lead?.name}</h2>
                                <span className={`px-2 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-wider border ${
                                    lead?.interestLevel === 'Hot' ? 'bg-red-500/20 text-red-400 border-red-500/30' :
                                    lead?.interestLevel === 'Warm' ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' :
                                    'bg-rose-500/20 text-rose-400 border-rose-500/30'
                                }`}>
                                    {lead?.interestLevel} Lead
                                </span>
                            </div>
                            <p className="text-white/40 text-xs mt-1 font-medium tracking-wide">Origem: <span className="text-white/70">{lead?.source}</span></p>
                            
                            <div className="flex items-center gap-4 mt-4 text-xs font-bold uppercase tracking-widest">
                                <button onClick={handleWhatsApp} className="flex items-center gap-2 px-4 py-2 bg-green-500/20 text-green-400 border border-green-500/20 rounded-xl hover:bg-green-500/30 transition-all">
                                    <MessageCircle size={14} /> WhatsApp
                                </button>
                                 <button className="flex items-center gap-2 px-4 py-2 bg-rose-500/20 text-rose-400 border border-rose-500/20 rounded-xl hover:bg-rose-500/30 transition-all">
                                    <Mail size={14} /> E-mail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Status Progress Bar */}
                <div className="px-8 py-4 bg-white border-b border-slate-100 flex items-center justify-between">
                    <div className="flex-1 flex items-center gap-1">
                        {(['NEW', 'CONTACTED', 'QUALIFIED', 'NEGOTIATION', 'CONVERTED'] as LeadStage[]).map((st, i) => {
                            const isPast = ['NEW', 'CONTACTED', 'QUALIFIED', 'NEGOTIATION', 'CONVERTED', 'LOST'].indexOf(lead?.stage || 'NEW') >= i;
                            const isCurrent = lead?.stage === st;
                            return (
                                <div key={st} className="flex-1 flex flex-col gap-1 items-center">
                                    <div className={`h-1.5 w-full rounded-full transition-all duration-700 ${
                                        isPast ? 'bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.4)]' : 'bg-slate-100'
                                    }`} />
                                    {isCurrent && <span className="text-[8px] font-bold text-amber-600 uppercase tracking-tighter absolute mt-3">{st}</span>}
                                </div>
                            );
                        })}
                    </div>
                    <div className="ml-6 flex-shrink-0">
                         <select 
                            className="text-[10px] font-bold bg-slate-50 border-none rounded-lg py-1 px-3 text-slate-500 focus:ring-0 cursor-pointer uppercase tracking-widest"
                            value={lead?.stage}
                            onChange={(e) => updateLeadStage(e.target.value as any)}
                        >
                            <option value="NEW">Mudar Etapa</option>
                            <option value="NEW">Novo Lead</option>
                            <option value="CONTACTED">Contatado</option>
                            <option value="QUALIFIED">Qualificado</option>
                            <option value="NEGOTIATION">Em Negociação</option>
                            <option value="CONVERTED">CONVERTIDO</option>
                            <option value="LOST">Perdido</option>
                        </select>
                    </div>
                </div>

                {/* Tabs */}
                <div className="flex bg-white px-2 border-b border-slate-100">
                    {(['info', 'timeline', 'tasks'] as const).map(tab => (
                        <button
                            key={tab}
                            onClick={() => setActiveTab(tab)}
                            className={`px-8 py-4 text-[10px] font-bold uppercase tracking-[0.2em] relative transition-all ${
                                activeTab === tab ? 'text-amber-600' : 'text-slate-400 hover:text-slate-600'
                            }`}
                        >
                            {tab === 'info' && 'Informações'}
                            {tab === 'timeline' && 'Histórico'}
                            {tab === 'tasks' && 'Tarefas / Follow-up'}
                            {activeTab === tab && <div className="absolute bottom-0 left-8 right-8 h-0.5 bg-amber-500 rounded-full" />}
                        </button>
                    ))}
                </div>

                {/* Content Area */}
                <div className="flex-1 overflow-y-auto p-8 space-y-8">
                    {activeTab === 'info' && (
                        <div className="space-y-8 animate-in fade-in duration-500">
                            {/* Value & Pipeline Card */}
                            <div className="grid grid-cols-2 gap-4">
                                <div className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                                    <div className="text-[9px] font-bold text-slate-300 uppercase tracking-widest mb-1">Valor Potencial</div>
                                    <div className="text-2xl font-bold text-slate-800">R$ {lead?.expectedValue || '0'}</div>
                                    <div className="mt-2 flex items-center gap-1 text-[10px] font-bold text-green-500">
                                        <TrendingUp size={12} /> Prob. Alta
                                    </div>
                                </div>
                                <div className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm">
                                    <div className="text-[9px] font-bold text-slate-300 uppercase tracking-widest mb-1">Dias no Funil</div>
                                    <div className="text-2xl font-bold text-slate-800">12 dias</div>
                                    <div className="mt-2 flex items-center gap-1 text-[10px] font-bold text-amber-500">
                                        <Zap size={12} fill="currentColor" /> Seguir Vendas
                                    </div>
                                </div>
                            </div>

                            {/* Details List */}
                            <div className="bg-white p-8 rounded-[40px] border border-slate-100 shadow-sm space-y-6">
                                <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-300 flex items-center gap-2">
                                    <AlertCircle size={14} className="text-amber-500" /> Detalhes do Contato
                                </h3>
                                
                                <div className="grid grid-cols-1 gap-6">
                                    <div className="flex items-center gap-4">
                                        <div className="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400"><MapPin size={20} /></div>
                                        <div>
                                            <div className="text-[10px] font-bold uppercase text-slate-300 tracking-widest">Cidade/Região</div>
                                            <div className="text-sm font-medium text-slate-600">São Paulo, SP</div>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-4">
                                        <div className="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400"><Phone size={20} /></div>
                                        <div>
                                            <div className="text-[10px] font-bold uppercase text-slate-300 tracking-widest">Telefone</div>
                                            <div className="text-sm font-medium text-slate-600">{lead?.phone || 'Não informado'}</div>
                                        </div>
                                    </div>
                                    <div className="flex items-center gap-4">
                                        <div className="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400"><Mail size={20} /></div>
                                        <div>
                                            <div className="text-[10px] font-bold uppercase text-slate-300 tracking-widest">E-mail</div>
                                            <div className="text-sm font-medium text-slate-600">{lead?.email || 'Não informado'}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {/* Sales Actions */}
                            <div className="space-y-4 pt-4">
                                <h3 className="text-xs font-bold uppercase tracking-[0.3em] text-slate-300 ml-2">Ações de Venda</h3>
                                <div className="grid grid-cols-1 gap-2">
                                    <button 
                                        className="w-full flex items-center justify-between p-5 bg-amber-500 text-white rounded-[24px] hover:shadow-lg hover:shadow-amber-500/30 transition-all group overflow-hidden relative"
                                        onClick={() => updateLeadStage('CONVERTED')}
                                    >
                                        <div className="absolute top-0 right-0 w-24 h-24 bg-white/20 rounded-full blur-2xl -mr-12 -mt-12 group-hover:bg-white/30 transition-all"></div>
                                        <div className="flex items-center gap-4 relative z-10">
                                            <div className="p-2 bg-white/20 rounded-xl"><UserPlus size={20} /></div>
                                            <div className="text-left">
                                                <div className="font-bold text-sm tracking-wide">CONVERTER EM MUSA</div>
                                                <div className="text-[9px] font-bold text-white/70 uppercase">Mover para Ativos & Criar Perfil</div>
                                            </div>
                                        </div>
                                        <TrendingUp size={20} className="relative z-10 group-hover:translate-x-1 transition-transform" />
                                    </button>
                                    
                                    <button className="w-full flex items-center justify-between p-5 bg-white border border-slate-100 rounded-[24px] hover:border-red-500/30 transition-all group">
                                        <div className="flex items-center gap-4">
                                            <div className="p-2 bg-red-50 text-red-500 rounded-xl"><TrendingDown size={20} /></div>
                                            <div className="text-left">
                                                <div className="font-bold text-sm tracking-wide text-slate-700">MARCAR COMO PERDIDO</div>
                                                <div className="text-[9px] font-bold text-slate-300 uppercase">Lead Desistiu / Irrelevante</div>
                                            </div>
                                        </div>
                                        <X size={20} className="text-slate-200 group-hover:text-red-300" />
                                    </button>
                                </div>
                            </div>
                        </div>
                    )}

                    {activeTab === 'timeline' && (
                        <div className="space-y-8 animate-in fade-in duration-500">
                            {/* Add Quick Interaction */}
                            <div className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm space-y-4">
                                <textarea 
                                    className="w-full bg-slate-50 rounded-2xl p-4 text-sm border-none focus:ring-1 focus:ring-amber-500/20 outline-none placeholder:text-slate-300 resize-none h-24 font-medium text-slate-600"
                                    placeholder="Registrar nota deste contato..."
                                    value={newNote}
                                    onChange={e => setNewNote(e.target.value)}
                                />
                                <div className="flex justify-end">
                                    <button 
                                        onClick={() => handleAddInteraction('note', newNote)}
                                        className="bg-slate-900 text-white px-6 py-2.5 rounded-full text-[10px] font-bold uppercase tracking-widest hover:scale-105 active:scale-95 transition-all"
                                    >
                                        Adicionar Registro
                                    </button>
                                </div>
                            </div>

                            {/* Interactions List */}
                            <div className="relative pl-8 space-y-8 before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-slate-100">
                                {interactions.map(item => (
                                    <div key={item.id} className="relative">
                                        <div className="absolute -left-8 mt-1.5 w-6 h-6 rounded-full bg-white border-2 border-slate-100 flex items-center justify-center z-10">
                                            {item.type === 'stage_change' ? <TrendingUp size={10} className="text-amber-500" /> :
                                             item.type === 'whatsapp' ? <MessageCircle size={10} className="text-green-500" /> :
                                             <History size={10} className="text-slate-400" />}
                                        </div>
                                        <div>
                                            <div className="text-[9px] font-bold text-slate-300 uppercase tracking-[0.2em] mb-1">
                                                {new Date(item.createdAt).toLocaleString('pt-BR')}
                                            </div>
                                            <div className="bg-white p-5 rounded-[24px] border border-slate-100 shadow-sm">
                                                <p className="text-sm text-slate-600 font-medium leading-relaxed">{item.content}</p>
                                            </div>
                                        </div>
                                    </div>
                                ))}

                                {interactions.length === 0 && (
                                    <div className="text-center py-20 text-slate-300">
                                        <History size={40} className="mx-auto mb-4 opacity-10" />
                                        <p className="text-xs uppercase tracking-[0.3em] font-bold">Nenhum registro ainda</p>
                                    </div>
                                )}
                            </div>
                        </div>
                    )}

                    {activeTab === 'tasks' && (
                        <div className="flex flex-col items-center justify-center py-24 animate-in zoom-in duration-500">
                            <div className="w-24 h-24 bg-white rounded-full border-4 border-slate-50 flex items-center justify-center text-slate-100 mb-6 shadow-xl shadow-slate-200/50">
                                <Zap size={40} fill="currentColor" />
                            </div>
                            <h3 className="text-sm font-bold text-slate-800 tracking-wider">Módulo de Automação Analux</h3>
                            <p className="text-xs text-slate-400 mt-2 max-w-[240px] text-center leading-relaxed">
                                Em breve, o sistema agendará contatos automáticos baseados no comportamento da musa.
                            </p>
                            <button className="mt-8 px-8 py-3 bg-slate-50 text-slate-400 rounded-full border border-slate-100 text-[10px] font-bold uppercase tracking-widest cursor-not-allowed">
                                Ativar Follow-up Inteligente
                            </button>
                        </div>
                    )}
                </div>

                {/* Footer Quick Context Menu */}
                <div className="p-8 bg-white border-t border-slate-100 flex gap-4">
                    <button onClick={handleWhatsApp} className="flex-1 flex items-center justify-center gap-3 bg-green-50 text-green-700 py-3.5 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-green-100 transition-all border border-green-100 shadow-sm">
                        <MessageCircle size={18} /> Chamar no Whats
                    </button>
                    <button onClick={onClose} className="px-6 py-3.5 bg-slate-50 text-slate-400 border border-slate-100 rounded-2xl text-[10px] font-bold uppercase tracking-widest hover:bg-slate-100 transition-all">
                        Fechar
                    </button>
                </div>
            </div>
        </div>
    );
};

export default LeadDrawer;
