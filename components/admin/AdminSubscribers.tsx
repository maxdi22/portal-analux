import React, { useState, useEffect } from 'react';
import { Search, Filter, Shield, AlertTriangle, CheckCircle, Loader2, Users, UserPlus } from 'lucide-react';
import { supabase } from '../../services/supabase';
import { MemberLevel } from '../../types';
import SubscriberActions from './SubscriberActions';
import Subscriber360Drawer from './Subscriber360Drawer';
import CreateSubscriberModal from './CreateSubscriberModal';

interface SubscriberData {
    id: string;
    name: string;
    email: string;
    plan: string;
    status: string;
    joined: string;
    ltv: number;
    risk: string;
    avatar?: string;
    phone?: string;
    address?: any;
    role?: string;
}

const AdminSubscribers: React.FC = () => {
    const [searchTerm, setSearchTerm] = useState('');
    const [statusFilter, setStatusFilter] = useState('all');
    const [subscribers, setSubscribers] = useState<SubscriberData[]>([]);
    const [loading, setLoading] = useState(true);
    const [selectedSubId, setSelectedSubId] = useState<string | null>(null);
    const [showCreateModal, setShowCreateModal] = useState(false);

    useEffect(() => {
        fetchSubscribers();
    }, []);

    const fetchSubscribers = async () => {
        try {
            setLoading(true);
            // Fetch profiles and join with subscriptions
            // Note: This relies on a foreign key relationship between profiles and subscriptions.
            // If explicit FK is missing, we might need to fetch separately, but usually user_id maps.
            const { data, error } = await supabase
                .from('profiles')
                .select(`
                    *,
                    subscriptions (*)
                `);

            if (error) throw error;

            console.log('Raw subscribers data:', data);

            const formattedSubscribers: SubscriberData[] = (data || []).map((profile: any) => {
                // Determine status and plan based on subscription data
                // Since subscriptions might be an array or single object depending on relation type,
                // we handle both (though usually 1:1 for active sub).
                const sub = Array.isArray(profile.subscriptions)
                    ? profile.subscriptions[0]
                    : profile.subscriptions;

                const planName = sub?.plan ?
                    (sub.plan === 'LUXURY' ? 'Musa Diamante' :
                        sub.plan === 'PREMIUM' ? 'Musa Ouro' :
                            sub.plan === 'BASIC' ? 'Musa Bronze' : sub.plan)
                    : 'Sem Plano';

                // Calculate "Since" date
                const joinedDate = sub?.created_at
                    ? new Date(sub.created_at).toLocaleDateString('pt-BR')
                    : new Date(profile.created_at || Date.now()).toLocaleDateString('pt-BR');

                return {
                    id: profile.id,
                    name: profile.name || 'Sem Nome',
                    email: profile.email || '',
                    avatar: profile.avatar,
                    plan: planName,
                    status: sub?.status || 'no_subscription',
                    joined: joinedDate,
                    ltv: profile.lifetime_points || 0,
                    risk: 'low',
                    phone: profile.phone,
                    address: profile.address,
                    role: profile.role
                };
            });

            setSubscribers(formattedSubscribers);
        } catch (error) {
            console.error('Error fetching subscribers:', error);
        } finally {
            setLoading(false);
        }
    };

    const filteredSubscribers = subscribers.filter(sub => {
        const matchesSearch = sub.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
            sub.email.toLowerCase().includes(searchTerm.toLowerCase());

        // Normalize status comparison (API returns 'ACTIVE', UI uses 'active')
        const normalizedSubStatus = (sub.status || '').toLowerCase();
        const matchesStatus = statusFilter === 'all' || normalizedSubStatus === statusFilter;

        return matchesSearch && matchesStatus;
    });

    return (
        <div className="space-y-6">
            <div className="flex justify-between items-center">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900">Gerenciar Assinantes</h1>
                    <p className="text-slate-500">Base total: {subscribers.length} assinantes</p>
                </div>
                <div className="flex gap-3">
                    <button className="px-4 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 font-medium">Exportar CSV</button>
                    <button
                        id="btn-novo-assinante"
                        onClick={() => setShowCreateModal(true)}
                        className="flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 font-bold shadow-sm transition-all"
                    >
                        <UserPlus size={16} /> Novo Assinante
                    </button>
                </div>
            </div>

            {/* Filters */}
            <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex gap-4 items-center">
                <div className="relative flex-1">
                    <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={20} />
                    <input
                        type="text"
                        placeholder="Buscar por nome, email, CPF..."
                        className="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-amber-500/50"
                        value={searchTerm}
                        onChange={(e) => setSearchTerm(e.target.value)}
                        autoComplete="off"
                    />
                </div>

                {/* Status Filter Dropdown */}
                <div className="relative">
                    <select
                        className="appearance-none bg-white border border-slate-200 text-slate-700 py-2 pl-4 pr-10 rounded-lg leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500/50 cursor-pointer text-sm font-medium"
                        value={statusFilter}
                        onChange={(e) => setStatusFilter(e.target.value)}
                    >
                        <option value="all">Todos Status</option>
                        <option value="active">Ativos</option>
                        <option value="paused">Pausados</option>
                        <option value="cancelled">Cancelados</option>
                    </select>
                    <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-500">
                        <Filter size={16} />
                    </div>
                </div>

                {/* Quick Toggle Button */}
                <button
                    onClick={() => setStatusFilter(statusFilter === 'active' ? 'all' : 'active')}
                    className={`nav-btn px-4 py-2 rounded-lg font-bold text-sm transition-colors flex items-center gap-2 ${statusFilter === 'active'
                            ? 'bg-green-100 text-green-700 border border-green-200'
                            : 'bg-white text-slate-600 border border-slate-200 hover:bg-green-50'
                        }`}
                >
                    <CheckCircle size={16} className={statusFilter === 'active' ? 'text-green-600' : 'text-slate-400'} />
                    Apenas Ativos
                </button>
            </div>

            {/* Table */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden min-h-[400px]">
                {loading ? (
                    <div className="flex flex-col items-center justify-center h-[400px] text-slate-400">
                        <Loader2 size={32} className="animate-spin mb-4" />
                        <p>Carregando base de dados...</p>
                    </div>
                ) : (
                    <table className="w-full text-left">
                        <thead className="bg-slate-50 text-slate-500 font-medium text-xs uppercase tracking-wider">
                            <tr>
                                <th className="px-6 py-4">Assinante</th>
                                <th className="px-6 py-4">Plano</th>
                                <th className="px-6 py-4">Status</th>
                                <th className="px-6 py-4">Desde</th>
                                <th className="px-6 py-4">LTV Est.</th>
                                <th className="px-6 py-4">Risco Churn</th>
                                <th className="px-6 py-4 text-center">Role</th>
                                <th className="px-6 py-4 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {filteredSubscribers.map((sub) => (
                                <tr 
                                    key={sub.id} 
                                    className="hover:bg-slate-50 transition-all cursor-pointer group"
                                    onClick={() => setSelectedSubId(sub.id)}
                                >
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500 overflow-hidden">
                                                {sub.avatar ? (
                                                    <img src={sub.avatar} alt={sub.name} className="w-full h-full object-cover" />
                                                ) : (
                                                    sub.name.charAt(0)
                                                )}
                                            </div>
                                            <div>
                                                <div className="flex items-center gap-2">
                                                    <div className="font-bold text-slate-900">{sub.name}</div>
                                                    {sub.role === 'superadmin' && (
                                                        <span className="px-1.5 py-0.5 bg-rose-100 text-rose-700 text-[10px] font-bold rounded border border-rose-200 flex items-center gap-1">
                                                            <Shield size={10} /> MASTER
                                                        </span>
                                                    )}
                                                    {sub.role === 'admin' && (
                                                        <span className="px-1.5 py-0.5 bg-blue-100 text-blue-700 text-[10px] font-bold rounded border border-blue-200">
                                                            ADMIN
                                                        </span>
                                                    )}
                                                </div>
                                                <div className="text-xs text-slate-400">{sub.email}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-600">{sub.plan}</td>
                                    <td className="px-6 py-4">
                                        <StatusBadge status={sub.status || 'no_subscription'} />
                                    </td>
                                    <td className="px-6 py-4 text-sm text-slate-500">{sub.joined}</td>
                                    <td className="px-6 py-4 text-sm font-medium text-slate-700">
                                        {sub.ltv > 0 ? `R$ ${sub.ltv}` : '-'}
                                    </td>
                                    <td className="px-6 py-4">
                                        <RiskBadge risk={sub.risk} />
                                    </td>
                                    <td className="px-6 py-4 text-center">
                                        <RoleBadge role={sub.role || 'member'} />
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <SubscriberActions subscriber={sub} onUpdate={fetchSubscribers} />
                                    </td>
                                </tr>
                            ))}
                        </tbody>
                    </table>
                )}
                {!loading && filteredSubscribers.length === 0 && (
                    <div className="flex flex-col items-center justify-center h-48 text-slate-400">
                        <Users size={48} className="mb-4 opacity-20" />
                        <p>Nenhum assinante encontrado.</p>
                    </div>
                )}
            </div>
            {/* Subscriber 360 Drawer */}
            {selectedSubId && (
                <Subscriber360Drawer 
                    subscriberId={selectedSubId} 
                    onClose={() => setSelectedSubId(null)} 
                />
            )}

            {/* Create Subscriber Modal */}
            {showCreateModal && (
                <CreateSubscriberModal
                    onClose={() => setShowCreateModal(false)}
                    onSuccess={() => {
                        fetchSubscribers();
                        setShowCreateModal(false);
                    }}
                />
            )}
        </div>
    );
};

const StatusBadge = ({ status }: { status: string }) => {
    const styles: Record<string, string> = {
        active: 'bg-green-100 text-green-700 border-green-200',
        paused: 'bg-amber-100 text-amber-700 border-amber-200',
        payment_failed: 'bg-red-100 text-red-700 border-red-200',
        cancelled: 'bg-slate-100 text-slate-600 border-slate-200',
        no_subscription: 'bg-gray-100 text-gray-500 border-gray-200'
    };

    const labels: Record<string, string> = {
        active: 'Ativo',
        paused: 'Pausado',
        payment_failed: 'Pagamento Falhou',
        cancelled: 'Cancelado',
        no_subscription: 'Sem Assinatura'
    }

    return (
        <span className={`px-2.5 py-1 rounded-full text-xs font-bold border whitespace-nowrap ${styles[status.toLowerCase()] || styles.cancelled}`}>
            {labels[status.toLowerCase()] || status}
        </span>
    );
}

const RiskBadge = ({ risk }: { risk: string }) => {
    if (risk === 'low') return <div className="flex items-center gap-1 text-green-600 text-xs font-bold"><Shield size={14} /> Baixo</div>;
    if (risk === 'medium') return <div className="flex items-center gap-1 text-amber-500 text-xs font-bold"><AlertTriangle size={14} /> Médio</div>;
    return <div className="flex items-center gap-1 text-red-500 text-xs font-bold"><AlertTriangle size={14} /> Alto</div>;
}

const RoleBadge = ({ role }: { role: string }) => {
    const styles: Record<string, string> = {
        superadmin: 'bg-rose-50 text-rose-700 border-rose-200',
        admin: 'bg-blue-50 text-blue-700 border-blue-200',
        member: 'bg-slate-50 text-slate-500 border-slate-100'
    };

    const labels: Record<string, string> = {
        superadmin: 'MASTER',
        admin: 'ADMIN',
        member: 'Assinante'
    };

    return (
        <span className={`px-2 py-0.5 rounded-lg text-[10px] font-black border uppercase tracking-widest ${styles[role] || styles.member}`}>
            {labels[role] || role}
        </span>
    );
}

export default AdminSubscribers;
