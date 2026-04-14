import React, { useState, useEffect } from 'react';
import { 
    Plus, 
    Search, 
    Filter, 
    Trello, 
    List, 
    TrendingUp, 
    Loader2, 
    Users, 
    Target, 
    Zap,
    MessageCircle,
    MoreHorizontal,
    ChevronRight,
    SearchX,
    X
} from 'lucide-react';
import { supabase } from '../../services/supabase';
import { LeadData, LeadStage } from '../../types';
import LeadDrawer from './LeadDrawer';

const AdminLeads: React.FC = () => {
    const [viewMode, setViewMode] = useState<'kanban' | 'list'>('kanban');
    const [leads, setLeads] = useState<LeadData[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedLeadId, setSelectedLeadId] = useState<string | null>(null);
    const [showAddModal, setShowAddModal] = useState(false);
    
    // Drag and Drop States
    const [draggedLeadId, setDraggedLeadId] = useState<string | null>(null);
    const [dragOverStage, setDragOverStage] = useState<LeadStage | null>(null);
    const [dragCoords, setDragCoords] = useState({ x: 0, y: 0 });

    // New Lead State
    const [newLead, setNewLead] = useState({
        name: '',
        email: '',
        phone: '',
        source: 'Instagram',
        interest_level: 'Warm'
    });

    useEffect(() => {
        fetchLeads();
    }, []);

    const fetchLeads = async () => {
        try {
            setLoading(true);
            const { data, error } = await supabase
                .from('leads')
                .select('*')
                .order('created_at', { ascending: false });
            
            if (data) {
                setLeads(data.map(l => ({
                    id: l.id,
                    name: l.name,
                    email: l.email,
                    phone: l.phone,
                    stage: l.stage as LeadStage,
                    source: l.source,
                    interestLevel: l.interest_level as any,
                    expectedValue: Number(l.expected_value),
                    createdAt: l.created_at,
                    lastContact: l.last_contact,
                    notes: l.notes
                })));
            }
        } finally {
            setLoading(false);
        }
    };

    const handleAddLead = async (e: React.FormEvent) => {
        e.preventDefault();
        try {
            const { error } = await supabase.from('leads').insert([newLead]);
            if (error) throw error;
            setShowAddModal(false);
            setNewLead({ name: '', email: '', phone: '', source: 'Instagram', interest_level: 'Warm' });
            fetchLeads();
        } catch (err) {
            console.error('Error adding lead:', err);
        }
    };

    // Drag & Drop Handlers
    const handleDragStart = (e: React.DragEvent, leadId: string) => {
        setDraggedLeadId(leadId);
        e.dataTransfer.setData('leadId', leadId);
        e.dataTransfer.effectAllowed = 'move';
        
        // Hide default ghost image
        const img = new Image();
        img.src = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // transparent
        e.dataTransfer.setDragImage(img, 0, 0);
        
        setDragCoords({ x: e.clientX, y: e.clientY });
    };

    const handleDrag = (e: React.DragEvent) => {
        if (e.clientX === 0 && e.clientY === 0) return; // Ignore final frame at 0,0
        setDragCoords({ x: e.clientX, y: e.clientY });
    };

    const handleDragOver = (e: React.DragEvent, stageId: LeadStage) => {
        e.preventDefault();
        setDragOverStage(stageId);
    };

    const handleDragLeave = () => {
        setDragOverStage(null);
    };

    const handleDrop = async (e: React.DragEvent, stageId: LeadStage) => {
        e.preventDefault();
        const leadId = e.dataTransfer.getData('leadId');
        
        if (!leadId) return;
        
        setDragOverStage(null);
        setDraggedLeadId(null);

        const lead = leads.find(l => l.id === leadId);
        if (lead && lead.stage !== stageId) {
            // Optimistic Update
            setLeads(prev => prev.map(l => l.id === leadId ? { ...l, stage: stageId } : l));

            try {
                const { error } = await supabase
                    .from('leads')
                    .update({ stage: stageId })
                    .eq('id', leadId);
                
                if (error) throw error;
            } catch (err) {
                console.error('Error updating status:', err);
                // Rollback if failed
                fetchLeads();
            }
        }
    };

    // Kanban Logic
    const stages: { id: LeadStage; label: string; color: string }[] = [
        { id: 'NEW', label: 'Novos', color: 'bg-rose-600' },
        { id: 'CONTACTED', label: 'Em Contato', color: 'bg-amber-500' },
        { id: 'QUALIFIED', label: 'Qualificados', color: 'bg-emerald-500' },
        { id: 'NEGOTIATION', label: 'Negociação', color: 'bg-purple-500' },
        { id: 'CONVERTED', label: 'Convertidos', color: 'bg-analux-secondary' }
    ];

    const leadsByStage = (stage: LeadStage) => 
        leads.filter(l => l.stage === stage && l.name.toLowerCase().includes(searchTerm.toLowerCase()));

    return (
        <div className="space-y-8 animate-in fade-in duration-500">
            {/* Upper Header & Funnel KPIs */}
            <div className="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
                <div>
                    <h1 className="text-3xl font-bold text-slate-800 tracking-tight">Lead CRM Engine</h1>
                    <p className="text-slate-500 text-sm mt-1">Gerencie seu funil de vendas e converta novas Musas.</p>
                </div>
                
                <div className="flex flex-wrap gap-4">
                    <div className="flex bg-white p-1 rounded-2xl border border-slate-100 shadow-sm">
                        <button 
                            onClick={() => setViewMode('kanban')}
                            className={`flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all ${
                                viewMode === 'kanban' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600'
                            }`}
                        >
                            <Trello size={16} /> Kanban
                        </button>
                        <button 
                            onClick={() => setViewMode('list')}
                            className={`flex items-center gap-2 px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-wider transition-all ${
                                viewMode === 'list' ? 'bg-slate-900 text-white shadow-lg' : 'text-slate-400 hover:text-slate-600'
                            }`}
                        >
                            <List size={16} /> Lista
                        </button>
                    </div>
                    
                    <button 
                        onClick={() => setShowAddModal(true)}
                        className="bg-amber-500 text-white px-6 py-3 rounded-2xl font-bold text-sm flex items-center gap-2 hover:bg-amber-600 active:scale-95 transition-all shadow-lg shadow-amber-500/20"
                    >
                        <Plus size={18} /> Novo Lead
                    </button>
                </div>
            </div>

            {/* Quick KPIs */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                {[
                    { label: 'Leads Ativos', value: leads.length, icon: <Users />, color: 'text-rose-600', bg: 'bg-rose-50' },
                    { label: 'Qualificados', value: leads.filter(l => l.stage === 'QUALIFIED').length, icon: <Target />, color: 'text-emerald-500', bg: 'bg-emerald-50' },
                    { label: 'Conversão', value: '4.2%', icon: <TrendingUp />, color: 'text-amber-500', bg: 'bg-amber-50' },
                    { label: 'Tempo Médio', value: '8d', icon: <Zap />, color: 'text-purple-500', bg: 'bg-purple-50' }
                ].map((kpi, idx) => (
                    <div key={idx} className="bg-white p-6 rounded-[32px] border border-slate-100 shadow-sm flex items-center gap-4">
                        <div className={`p-3 ${kpi.bg} ${kpi.color} rounded-2xl`}>{React.cloneElement(kpi.icon as any, { size: 20 })}</div>
                        <div>
                            <div className="text-[10px] font-bold text-slate-300 uppercase tracking-widest">{kpi.label}</div>
                            <div className="text-xl font-bold text-slate-800">{kpi.value}</div>
                        </div>
                    </div>
                ))}
            </div>

            {/* Content Filtering */}
            <div className="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex gap-4 items-center">
                <div className="relative flex-1">
                    <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300" size={18} />
                    <input 
                        type="text" 
                        placeholder="Buscar por nome, e-mail, telefone ou origem..."
                        className="w-full pl-12 pr-4 py-3 bg-slate-50 border-none rounded-xl text-sm focus:ring-2 focus:ring-amber-500/20 outline-none placeholder:text-slate-300 font-medium text-slate-600"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                    />
                </div>
                <button className="p-3 bg-slate-50 text-slate-400 rounded-xl hover:text-slate-600 transition-colors border border-slate-100">
                    <Filter size={20} />
                </button>
            </div>

            {/* Kanban View */}
            {viewMode === 'kanban' && (
                <div 
                    className="flex gap-6 overflow-x-auto pb-8 snap-x scrollbar-hide min-h-[600px] relative"
                    onDragOver={(e) => {
                        handleDrag(e); // Sync position while moving over container
                    }}
                >
                    {stages.map(stage => (
                        <div 
                            key={stage.id} 
                            className={`flex-shrink-0 w-80 flex flex-col gap-4 snap-start transition-all duration-300 ${
                                dragOverStage === stage.id ? 'scale-[1.02]' : ''
                            }`}
                            onDragOver={(e) => handleDragOver(e, stage.id)}
                            onDragLeave={handleDragLeave}
                            onDrop={(e) => handleDrop(e, stage.id)}
                        >
                            <div className="flex items-center justify-between px-2">
                                <div className="flex items-center gap-3">
                                    <div className={`w-2 h-2 rounded-full ${stage.color} shadow-[0_0_8px_rgba(0,0,0,0.1)]`} />
                                    <h3 className="text-xs font-bold uppercase underline-offset-8 tracking-[0.2em] text-slate-400">
                                        {stage.label}
                                    </h3>
                                </div>
                                <span className="bg-slate-100 text-slate-400 text-[10px] font-bold px-2 py-0.5 rounded-full">
                                    {leadsByStage(stage.id).length}
                                </span>
                            </div>

                            <div className={`flex-1 space-y-4 rounded-[32px] p-2 min-h-[400px] transition-colors duration-300 ${
                                dragOverStage === stage.id ? 'bg-amber-500/10' : 'bg-slate-100/30'
                            }`}>
                                {leadsByStage(stage.id).map(lead => (
                                    <div 
                                        key={lead.id}
                                        draggable
                                        onDragStart={(e) => handleDragStart(e, lead.id)}
                                        onDrag={(e) => handleDrag(e)}
                                        onDragEnd={() => {
                                            setDraggedLeadId(null);
                                            setDragOverStage(null);
                                        }}
                                        onClick={() => setSelectedLeadId(lead.id)}
                                        className={`bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 hover:border-amber-500/30 transition-all cursor-grab active:cursor-grabbing group active:scale-[0.98] animate-in slide-in-from-bottom-2 ${
                                            draggedLeadId === lead.id ? 'opacity-40 grayscale-[0.5] scale-95 border-dashed border-amber-300' : ''
                                        }`}
                                    >
                                        <div className="flex justify-between items-start mb-4">
                                            <div className="flex items-center gap-3">
                                                <div className="w-8 h-8 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 font-serif text-sm italic border border-slate-100 group-hover:bg-amber-50 group-hover:text-amber-500 transition-colors">
                                                    {lead.name.charAt(0)}
                                                </div>
                                                <div className="font-bold text-slate-900 text-sm truncate max-w-[140px]">{lead.name}</div>
                                            </div>
                                            <button className="text-slate-200 hover:text-slate-400"><MoreHorizontal size={14} /></button>
                                        </div>

                                        <p className="text-[10px] text-slate-400 font-medium mb-4 line-clamp-1">{lead.email || 'Sem email vinculado'}</p>
                                        
                                        <div className="flex items-center justify-between pt-4 border-t border-slate-50">
                                            <div className="flex items-center gap-1.5 p-1 bg-green-50 rounded-lg">
                                                <MessageCircle className="text-green-500" size={12} />
                                                <span className="text-[8px] font-bold text-green-700 uppercase tracking-tighter">Whats</span>
                                            </div>
                                            <div className="text-[10px] font-bold text-slate-300 uppercase tracking-widest">
                                                {new Date(lead.createdAt).toLocaleDateString('pt-BR', { day: '2-digit', month: 'short' })}
                                            </div>
                                        </div>
                                    </div>
                                ))}
                                
                                {leadsByStage(stage.id).length === 0 && (
                                    <div className="h-full border-2 border-dashed border-slate-100 rounded-[28px] flex items-center justify-center text-slate-200">
                                        <Plus size={24} />
                                    </div>
                                )}
                            </div>
                        </div>
                    ))}
                </div>
            )}

            {/* List View */}
            {viewMode === 'list' && (
                <div className="bg-white rounded-[32px] border border-slate-100 shadow-sm overflow-hidden">
                    <table className="w-full text-left">
                        <thead className="bg-slate-50 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400">
                            <tr>
                                <th className="px-8 py-5">Prospecto</th>
                                <th className="px-8 py-5">Estágio</th>
                                <th className="px-8 py-5">Origem</th>
                                <th className="px-8 py-5">Interesse</th>
                                <th className="px-8 py-5">Valor Est.</th>
                                <th className="px-8 py-5 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-50">
                            {leads.filter(l => l.name.toLowerCase().includes(searchTerm.toLowerCase())).map(lead => (
                                <tr 
                                    key={lead.id} 
                                    className="hover:bg-slate-50 cursor-pointer group transition-all"
                                    onClick={() => setSelectedLeadId(lead.id)}
                                >
                                    <td className="px-8 py-5">
                                        <div className="font-bold text-slate-800">{lead.name}</div>
                                        <div className="text-xs text-slate-400">{lead.email || lead.phone}</div>
                                    </td>
                                    <td className="px-8 py-5">
                                        <span className={`px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest border ${
                                            lead.stage === 'NEW' ? 'bg-rose-50 text-rose-600 border-rose-100' :
                                            lead.stage === 'CONVERTED' ? 'bg-analux-contrast text-analux-primary border-analux-secondary/20' :
                                            'bg-amber-50 text-amber-600 border-amber-100'
                                        }`}>
                                            {lead.stage}
                                        </span>
                                    </td>
                                    <td className="px-8 py-5 text-sm text-slate-500 font-medium">{lead.source}</td>
                                    <td className="px-8 py-5">
                                        <div className={`flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-widest ${
                                            lead.interestLevel === 'Hot' ? 'text-red-500' : 'text-slate-400'
                                        }`}>
                                            <Zap size={14} fill={lead.interestLevel === 'Hot' ? 'currentColor' : 'none'} /> {lead.interestLevel}
                                        </div>
                                    </td>
                                    <td className="px-8 py-5 text-sm font-bold text-slate-700">R$ {lead.expectedValue}</td>
                                    <td className="px-8 py-5 text-right">
                                        <button className="text-slate-300 group-hover:text-amber-500 transition-colors"><ChevronRight size={20} /></button>
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                    
                    {leads.length === 0 && !loading && (
                        <div className="p-20 text-center text-slate-300">
                             <SearchX size={48} className="mx-auto mb-4 opacity-10" />
                             <p className="text-xs uppercase tracking-widest font-bold">Nenhum prospecto encontrado</p>
                        </div>
                    )}
                </div>
            )}

            {/* Add Lead Modal */}
            {showAddModal && (
                <div className="fixed inset-0 z-[150] bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4 animate-in fade-in duration-300">
                    <div className="bg-white w-full max-w-lg rounded-[48px] p-10 shadow-2xl animate-in zoom-in-95 duration-300 relative overflow-hidden">
                        <div className="absolute top-0 right-0 w-32 h-32 bg-amber-500/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>
                        
                        <div className="flex justify-between items-center mb-8">
                            <h3 className="text-2xl font-serif italic text-slate-900">Novo Prospecto</h3>
                            <button onClick={() => setShowAddModal(false)} className="p-2 hover:bg-slate-50 rounded-full"><X size={20} /></button>
                        </div>

                        <form onSubmit={handleAddLead} className="space-y-6">
                            <div className="space-y-2">
                                <label className="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 ml-1">Nome Completo</label>
                                <input 
                                    required 
                                    className="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" 
                                    placeholder="Ex: Maria Silvia"
                                    value={newLead.name}
                                    onChange={e => setNewLead({...newLead, name: e.target.value})}
                                />
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <label className="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 ml-1">E-mail</label>
                                    <input 
                                        type="email" 
                                        className="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" 
                                        placeholder="maria@email.com"
                                        value={newLead.email}
                                        onChange={e => setNewLead({...newLead, email: e.target.value})}
                                    />
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 ml-1">WhatsApp</label>
                                    <input 
                                        className="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" 
                                        placeholder="(11) 99999-9999"
                                        value={newLead.phone}
                                        onChange={e => setNewLead({...newLead, phone: e.target.value})}
                                    />
                                </div>
                            </div>
                            <div className="grid grid-cols-2 gap-4">
                                <div className="space-y-2">
                                    <label className="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 ml-1">Origem</label>
                                    <select 
                                        className="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-amber-500/20 outline-none cursor-pointer"
                                        value={newLead.source}
                                        onChange={e => setNewLead({...newLead, source: e.target.value})}
                                    >
                                        <option>Instagram</option>
                                        <option>TikTok</option>
                                        <option>Anúncios Google</option>
                                        <option>Indicação</option>
                                        <option>Site</option>
                                    </select>
                                </div>
                                <div className="space-y-2">
                                    <label className="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 ml-1">Temperatura</label>
                                    <select 
                                        className="w-full bg-slate-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-amber-500/20 outline-none cursor-pointer"
                                        value={newLead.interest_level}
                                        onChange={e => setNewLead({...newLead, interest_level: e.target.value})}
                                    >
                                        <option value="Cold">Gelado (Cold)</option>
                                        <option value="Warm">Morno (Warm)</option>
                                        <option value="Hot">Quente (Hot)</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" className="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold uppercase tracking-widest hover:bg-slate-800 transition-all mt-4">
                                Cadastrar Lead
                            </button>
                        </form>
                    </div>
                </div>
            )}

            {/* Lead Detail Drawer */}
            {selectedLeadId && (
                <LeadDrawer 
                    leadId={selectedLeadId} 
                    onClose={() => setSelectedLeadId(null)} 
                    onUpdate={fetchLeads} 
                />
            )}
            {/* Custom Drag Layer */}
            {draggedLeadId && (
                <div 
                    className="fixed pointer-events-none z-[100] transition-transform duration-75 ease-out"
                    style={{ 
                        left: 0, 
                        top: 0, 
                        transform: `translate3d(${dragCoords.x - 160}px, ${dragCoords.y - 40}px, 0) rotate(3deg) scale(1.05)`,
                        width: '320px'
                    }}
                >
                    {leads.find(l => l.id === draggedLeadId) && (
                        <div className="bg-white p-6 rounded-[28px] border border-amber-500/30 shadow-2xl shadow-amber-500/20">
                            <div className="flex justify-between items-start mb-4">
                                <div className="flex items-center gap-3">
                                    <div className="w-10 h-10 rounded-2xl bg-slate-50 flex items-center justify-center font-bold text-slate-400">
                                        {leads.find(l => l.id === draggedLeadId)!.name.charAt(0)}
                                    </div>
                                    <div>
                                        <div className="font-bold text-slate-800 text-sm">{leads.find(l => l.id === draggedLeadId)!.name}</div>
                                        <div className="text-[10px] text-slate-400 uppercase tracking-wider">{leads.find(l => l.id === draggedLeadId)!.source}</div>
                                    </div>
                                </div>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                <span className="bg-amber-50 text-amber-600 px-3 py-1 rounded-full text-[10px] font-bold">
                                    {leads.find(l => l.id === draggedLeadId)!.interest_level}
                                </span>
                            </div>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default AdminLeads;
