
import React, { useState, useEffect } from 'react';
import { useNavigate } from 'react-router-dom';
import { Package, Truck, Gift, AlertTriangle, TrendingUp, Users, Calendar, Search, Filter, ArrowLeft, Plus, X, Trash2, Copy, Edit, MoreVertical, Lock } from 'lucide-react';
import { LineChart, Line, XAxis, YAxis, Tooltip, ResponsiveContainer } from 'recharts';
import { BoxEditionStatus, BoxEdition } from '../../../types';
import { supabase } from '../../../services/supabase';

// Mock Data
const mockEditions: BoxEdition[] = [
    {
        id: '1',
        name: 'Aurora Floral',
        theme: 'Renascimento',
        month: 'Novembro/2026',
        status: BoxEditionStatus.PRODUCTION,
        type: 'monthly',
        subscriberCount: 842,
        rating: 4.8,
        estimatedCost: 85.50,
        margin: 62.4
    },
    {
        id: '2',
        name: 'Golden Hour',
        theme: 'Radiância',
        month: 'Dezembro/2026',
        status: BoxEditionStatus.PLANNING,
        type: 'thematic',
        subscriberCount: 1034,
        estimatedCost: 92.00
    }
];

const performanceData = [
    { month: 'Jan', rating: 4.5, retention: 92 },
    { month: 'Fev', rating: 4.6, retention: 93 },
    { month: 'Mar', rating: 4.8, retention: 91 },
    { month: 'Abr', rating: 4.7, retention: 94 },
    { month: 'Mai', rating: 4.9, retention: 95 },
];

const KPICard = ({ title, value, icon, change, type = 'neutral' }: any) => {
    const colors = {
        neutral: 'bg-white text-analux-contrast',
        warning: 'bg-amber-50 text-amber-900 border-amber-100',
        success: 'bg-emerald-50 text-emerald-900 border-emerald-100'
    };

    return (
        <div className={`p-6 rounded-2xl shadow-sm border ${type === 'warning' ? 'border-amber-200' : 'border-gray-100'} bg-white flex items-start justify-between`}>
            <div>
                <p className="text-sm text-gray-500 mb-1">{title}</p>
                <h3 className="text-2xl font-serif text-analux-contrast">{value}</h3>
                {change && (
                    <div className="flex items-center gap-1 mt-2 text-xs font-medium text-emerald-600">
                        <TrendingUp size={12} />
                        {change}
                    </div>
                )}
            </div>
            <div className="p-3 bg-analux-primary/10 rounded-xl text-analux-primary">
                {icon}
            </div>
        </div>
    );
};

const BoxesDashboard = () => {
    const navigate = useNavigate();
    const [editions, setEditions] = useState<BoxEdition[]>([]);
    const [loading, setLoading] = useState(true);
    const [viewMode, setViewMode] = useState<'dashboard' | 'list'>('dashboard');
    const [selectedEdition, setSelectedEdition] = useState<BoxEdition | null>(null);
    const [showDeleteModal, setShowDeleteModal] = useState(false);

    const handleEdit = (edition: BoxEdition) => {
        navigate('/admin/boxes/new', { state: { editionId: edition.id } });
    };

    const handleDelete = async (id: string) => {
        if (!confirm('Tem certeza que deseja excluir esta box?')) return;

        try {
            const { error } = await supabase.from('box_editions').delete().eq('id', id);
            if (error) throw error;
            setEditions(editions.filter(e => e.id !== id));
            setSelectedEdition(null);
        } catch (error) {
            console.error('Error deleting:', error);
            alert('Erro ao excluir');
        }
    };

    const handleClone = async (edition: BoxEdition) => {
        // Implementation for clone would go here - simplified for now
        alert('Funcionalidade de clonar em desenvolvimento');
    };

    useEffect(() => {
        fetchEditions();
    }, []);

    const fetchEditions = async () => {
        try {
            const { data, error } = await supabase
                .from('box_editions')
                .select('*')
                .order('created_at', { ascending: false });

            if (error) throw error;

            if (data) {
                // Map DB snake_case to TS camelCase if needed, or rely on JS flexibility
                // Ideally we should have a mapper. For now assuming keys match or we map manually.
                const mappedData: BoxEdition[] = data.map(item => ({
                    id: item.id,
                    name: item.name,
                    theme: item.theme,
                    month: item.month,
                    status: item.status || BoxEditionStatus.PLANNING,
                    type: item.type,
                    subscriberCount: item.estimated_qty || 0, // Fallback mapping
                    rating: item.rating || 0,
                    coverImage: item.cover_image,
                    items: item.items || [],
                    // Map other fields as necessary from JSONB
                }));
                // Merge with mock for demo purposes if DB is empty, otherwise show DB
                setEditions(mappedData);
            }
        } catch (error) {
            console.error('Error fetching boxes:', error);
            // Show empty on error to avoid confusion, or keep mock ONLY if explicitly desired as demo mode. 
            // User requested to see THEIR box, so removing mock fallback is better.
            setEditions([]);
        } finally {
            setLoading(false);
        }
    };

    const renderDashboardView = () => (
        <div className="space-y-8 animate-fade-in">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-serif text-analux-contrast mb-2">Galeria de Edições</h1>
                    <p className="text-gray-500">Gestão centralizada do ciclo de vida das assinaturas.</p>
                </div>
                <button
                    onClick={() => navigate('/admin/boxes/new')}
                    className="flex items-center gap-2 bg-analux-primary text-white px-4 py-2 rounded-lg hover:bg-analux-primary-dark transition-colors shadow-sm"
                >
                    <Plus size={20} />
                    Nova Edição
                </button>
            </div>

            {/* Quick Stats Grid */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <KPICard
                    title="Boxes em Produção"
                    value={editions.filter(e => e.status === BoxEditionStatus.PRODUCTION).length.toString()}
                    icon={<Package />}
                    change="+1 vs mês anterior"
                />
                <KPICard
                    title="Próximos Envios"
                    value="1.248"
                    icon={<Truck />}
                    change="Dia 15/11"
                />
                <KPICard
                    title="Assinantes Ativas"
                    value="3.842"
                    icon={<Users />}
                    change="+12% este mês"
                />
                <KPICard
                    title="Alertas"
                    value="1"
                    icon={<AlertTriangle />}
                    type="warning"
                />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Main Timeline Chart */}
                <div className="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <div className="flex justify-between items-center mb-6">
                        <h3 className="font-serif text-lg text-analux-contrast">Performance das Edições</h3>
                        <select className="text-sm border-gray-200 rounded-lg text-gray-500">
                            <option>Últimos 6 meses</option>
                            <option>Este ano</option>
                        </select>
                    </div>
                    <div className="h-64">
                        <ResponsiveContainer width="100%" height="100%">
                            <LineChart data={performanceData}>
                                <XAxis dataKey="month" stroke="#9CA3AF" fontSize={12} tickLine={false} axisLine={false} />
                                <YAxis stroke="#9CA3AF" fontSize={12} tickLine={false} axisLine={false} />
                                <Tooltip
                                    contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 4px 6px -1px rgb(0 0 0 / 0.1)' }}
                                />
                                <Line
                                    type="monotone"
                                    dataKey="rating"
                                    stroke="#D4AF37"
                                    strokeWidth={3}
                                    dot={{ fill: '#D4AF37', strokeWidth: 2, r: 4, stroke: '#fff' }}
                                />
                                <Line
                                    type="monotone"
                                    dataKey="retention"
                                    stroke="#E5E7EB"
                                    strokeWidth={3}
                                    dot={false}
                                />
                            </LineChart>
                        </ResponsiveContainer>
                    </div>
                </div>

                {/* Active Editions List */}
                <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 className="font-serif text-lg text-analux-contrast mb-4">Edições Ativas ({editions.length})</h3>
                    <div className="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                        {loading ? (
                            <p className="text-gray-400 text-sm text-center py-4">Carregando galeria...</p>
                        ) : editions.map((edition) => (
                            <div key={edition.id} className="p-4 border border-gray-100 rounded-xl hover:shadow-md transition-shadow cursor-pointer group">
                                <div className="flex justify-between items-start mb-2">
                                    <div className="flex items-center gap-2">
                                        <span className={`w-2 h-2 rounded-full ${[BoxEditionStatus.PRODUCTION, BoxEditionStatus.SHIPPING].includes(edition.status) ? 'bg-purple-500' : 'bg-amber-400'
                                            }`}></span>
                                        <span className="text-xs font-semibold uppercase tracking-wider text-gray-400">{edition.type}</span>
                                    </div>
                                    <span className="text-xs text-gray-500">{edition.month}</span>
                                </div>
                                <h4 className="font-serif text-analux-contrast text-lg group-hover:text-analux-primary transition-colors">{edition.name}</h4>
                                <p className="text-sm text-gray-500 mb-3">{edition.theme}</p>

                                <div className="flex items-center justify-between text-sm">
                                    <span className="flex items-center gap-1 text-gray-600">
                                        <Users size={14} /> {edition.subscriberCount || 0}
                                    </span>
                                    {edition.rating && (
                                        <span className="flex items-center gap-1 text-amber-500 font-medium">
                                            ★ {edition.rating}
                                        </span>
                                    )}
                                </div>
                            </div>
                        ))}

                        <button
                            onClick={() => setViewMode('list')}
                            className="w-full py-3 text-sm text-center text-gray-500 border border-dashed border-gray-200 rounded-xl hover:bg-gray-50 transition-colors"
                        >
                            Ver todas as edições
                        </button>
                    </div>
                </div>
            </div>

        </div>
    );

    const renderListView = () => (
        <div className="p-8">
            <div className="flex justify-between items-center mb-8">
                <div>
                    <button
                        onClick={() => setViewMode('dashboard')}
                        className="flex items-center text-gray-500 hover:text-analux-primary mb-2 transition-colors"
                    >
                        <ArrowLeft size={20} className="mr-2" /> Voltar para Dashboard
                    </button>
                    <h1 className="font-serif text-3xl text-analux-contrast">Todas as Edições</h1>
                    <p className="text-gray-500 mt-2">Gerencie todas as edições de boxes lançadas.</p>
                </div>
                <button
                    onClick={() => navigate('/admin/boxes/new')}
                    className="flex items-center gap-2 bg-analux-primary text-white px-4 py-2 rounded-lg hover:bg-analux-primary-dark transition-colors"
                >
                    <Plus size={20} /> Nova Edição
                </button>
            </div>

            <div className="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                <div className="p-4 flex gap-4">
                    <div className="relative flex-1">
                        <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" size={20} />
                        <input
                            type="text"
                            placeholder="Buscar edição..."
                            className="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-analux-primary/20"
                        />
                    </div>
                    <button className="flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50">
                        <Filter size={20} /> Filtros
                    </button>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                {editions.map((edition) => (
                    <div
                        key={edition.id}
                        onClick={() => setSelectedEdition(edition)}
                        className="relative group rounded-3xl overflow-hidden cursor-pointer shadow-sm hover:shadow-xl transition-all duration-300 h-[420px] bg-gray-900"
                    >
                        {/* Background Image */}
                        <div className="absolute inset-0">
                            {edition.coverImage ? (
                                <img src={edition.coverImage} alt={edition.name} className="w-full h-full object-cover opacity-80 group-hover:scale-105 transition-transform duration-700" />
                            ) : (
                                <div className="w-full h-full bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center">
                                    <Package size={48} className="text-gray-700" />
                                </div>
                            )}
                            <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent" />
                        </div>

                        {/* Status Badge */}
                        <div className="absolute top-4 right-4 z-10">
                            <span className="px-3 py-1 rounded-full text-xs font-semibold tracking-wider bg-white/20 backdrop-blur-md text-white border border-white/10 shadow-lg">
                                {edition.status === 'PLANNING' ? 'EM BREVE' :
                                    edition.status === 'PRODUCTION' ? 'EM PRODUÇÃO' : edition.status}
                            </span>
                        </div>

                        {/* Content Overlay */}
                        <div className="absolute inset-0 p-6 flex flex-col justify-end text-white">
                            <div className="mb-6">
                                <span className="text-amber-400 font-medium tracking-widest text-sm mb-2 block font-serif">
                                    {edition.month?.split('/')[0] || '2026-01'}
                                </span>
                                <h3 className="text-4xl font-serif mb-2 leading-tight">{edition.name}</h3>
                                <p className="text-gray-300 font-light text-lg">{edition.theme}</p>
                            </div>

                            {/* Footer Info Area */}
                            <div className="bg-white rounded-2xl p-4 text-gray-800 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <div className="flex justify-between items-center mb-4">
                                    <div className="flex items-center gap-3">
                                        <div className="p-2 bg-purple-50 rounded-full text-purple-600">
                                            <Calendar size={18} />
                                        </div>
                                        <div>
                                            <span className="text-[10px] uppercase text-gray-400 font-bold tracking-wider block">ENVIO</span>
                                            <span className="font-semibold text-sm">15/{edition.month?.split('/')[0]?.substring(0, 3) || 'Dez'}</span>
                                        </div>
                                    </div>
                                    <div className="w-px h-8 bg-gray-100"></div>
                                    <div className="flex items-center gap-3">
                                        <div className="p-2 bg-amber-50 rounded-full text-amber-600">
                                            <Gift size={18} />
                                        </div>
                                        <div>
                                            <span className="text-[10px] uppercase text-gray-400 font-bold tracking-wider block">SPOILER</span>
                                            <span className="font-semibold text-sm">Liberado</span>
                                        </div>
                                    </div>
                                </div>
                                <button className="w-full py-3 bg-gray-900 text-white rounded-xl text-xs font-bold tracking-widest uppercase flex items-center justify-center gap-2 hover:bg-gray-800 transition-colors">
                                    <Lock size={14} />
                                    Gerenciar Edição
                                </button>
                            </div>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );

    return (
        <>
            {viewMode === 'list' ? renderListView() : renderDashboardView()}

            {/* Details Modal */}
            {selectedEdition && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade-in" onClick={() => setSelectedEdition(null)}>
                    <div className="bg-white rounded-3xl w-full max-w-2xl overflow-hidden shadow-2xl relative" onClick={e => e.stopPropagation()}>
                        <div className="h-48 relative">
                            {selectedEdition.coverImage ? (
                                <img src={selectedEdition.coverImage} className="w-full h-full object-cover" />
                            ) : (
                                <div className="w-full h-full bg-gray-100 flex items-center justify-center">
                                    <Package size={48} className="text-gray-300" />
                                </div>
                            )}
                            <div className="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent" />
                            <button
                                onClick={() => setSelectedEdition(null)}
                                className="absolute top-4 right-4 p-2 bg-black/20 hover:bg-black/40 rounded-full text-white backdrop-blur-md transition-colors"
                            >
                                <X size={20} />
                            </button>
                            <div className="absolute bottom-6 left-6 text-white">
                                <p className="text-amber-400 font-medium tracking-wide text-sm mb-1">{selectedEdition.month}</p>
                                <h2 className="text-3xl font-serif">{selectedEdition.name}</h2>
                            </div>
                        </div>

                        <div className="p-8">
                            <div className="grid grid-cols-2 gap-8 mb-8">
                                <div>
                                    <label className="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Tema</label>
                                    <p className="text-lg text-gray-800 font-serif">{selectedEdition.theme}</p>
                                </div>
                                <div>
                                    <label className="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Status</label>
                                    <span className="px-3 py-1 inline-block rounded-full bg-purple-100 text-purple-700 text-sm font-semibold">
                                        {selectedEdition.status}
                                    </span>
                                </div>
                            </div>

                            {/* Items List */}
                            <div className="mb-8">
                                <label className="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3">Itens da Box ({selectedEdition.items?.length || 0})</label>
                                <div className="space-y-3 max-h-40 overflow-y-auto pr-2 custom-scrollbar">
                                    {selectedEdition.items && selectedEdition.items.length > 0 ? (
                                        selectedEdition.items.map((item, index) => (
                                            <div key={index} className="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-100">
                                                <div className="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center text-xl">
                                                    {item.category === 'jewelry' ? '💎' : item.category === 'gift' ? '🎁' : '📦'}
                                                </div>
                                                <div>
                                                    <p className="font-medium text-gray-800 text-sm">{item.name}</p>
                                                    <p className="text-xs text-gray-500">{item.supplier || 'Fornecedor n/a'}</p>
                                                </div>
                                            </div>
                                        ))
                                    ) : (
                                        <p className="text-sm text-gray-400 italic">Nenhum item cadastrado nesta edição.</p>
                                    )}
                                </div>
                            </div>

                            <div className="flex gap-3 pt-6 border-t border-gray-100">
                                <button
                                    onClick={() => handleEdit(selectedEdition)}
                                    className="flex-1 py-3 bg-analux-primary text-white rounded-xl font-medium hover:bg-analux-primary-dark transition-colors flex items-center justify-center gap-2"
                                >
                                    <Edit size={18} /> Editar
                                </button>
                                <button
                                    onClick={() => handleClone(selectedEdition)}
                                    className="flex-1 py-3 bg-gray-100 text-gray-700 rounded-xl font-medium hover:bg-gray-200 transition-colors flex items-center justify-center gap-2"
                                >
                                    <Copy size={18} /> Clonar
                                </button>
                                <button
                                    onClick={() => handleDelete(selectedEdition.id)}
                                    className="px-4 py-3 bg-red-50 text-red-600 rounded-xl font-medium hover:bg-red-100 transition-colors flex items-center justify-center"
                                >
                                    <Trash2 size={20} />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            )}
        </>
    );
};

export default BoxesDashboard;
