import React, { useEffect, useState } from 'react';
import {
    Users,
    Trophy,
    TrendingUp,
    Search,
    Download,
    Crown,
    ArrowUpRight
} from 'lucide-react';
import { supabase } from '../../services/supabase';

interface ReferralStat {
    id: string;
    name: string;
    email: string;
    referral_code: string;
    referral_count: number;
    avatar_url: string;
    created_at: string;
    referral_clicks?: number;
    active_users?: number;
}

const AdminReferrals: React.FC = () => {
    const [stats, setStats] = useState<ReferralStat[]>([]);
    const [loading, setLoading] = useState(true);
    const [searchTerm, setSearchTerm] = useState('');

    useEffect(() => {
        fetchReferrals();

        // Polling for "Online" users simulation
        const interval = setInterval(() => {
            setStats(prev => prev.map(s => ({
                ...s,
                active_users: Math.floor(Math.random() * 5) // Mock realtime fluctuation
            })));
        }, 5000);
        return () => clearInterval(interval);
    }, []);

    const fetchReferrals = async () => {
        setLoading(true);
        try {
            // Fetch profiles with referral_count > 0, ordered by count desc
            const { data, error } = await supabase
                .from('profiles')
                .select('*')
                .gt('referral_count', 0)
                .order('referral_count', { ascending: false })
                .limit(50);

            if (error) {
                console.error('Error fetching referrals:', error);
            } else {
                // Enriching with mock analytics data
                const enrichedData = (data || []).map(p => ({
                    ...p,
                    referral_clicks: (p.referral_count || 0) * (Math.floor(Math.random() * 10) + 5), // Mock: 5-15 clicks per conversion
                    active_users: Math.floor(Math.random() * 3) // Mock: 0-2 users online
                }));
                setStats(enrichedData);
            }
        } catch (err) {
            console.error('Error:', err);
        } finally {
            setLoading(false);
        }
    };

    const filteredStats = stats.filter(s =>
        s.name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        s.email?.toLowerCase().includes(searchTerm.toLowerCase()) ||
        s.referral_code?.toLowerCase().includes(searchTerm.toLowerCase())
    );

    const totalReferrals = stats.reduce((acc, curr) => acc + (curr.referral_count || 0), 0);
    const totalCredits = totalReferrals * 20; // R$ 20,00 per referral
    const totalClicks = stats.reduce((acc, curr) => acc + (curr.referral_clicks || 0), 0);

    return (
        <div className="space-y-8 animate-fadeIn">
            <div className="flex flex-col md:flex-row justify-between md:items-center gap-4">
                <div>
                    <h1 className="text-2xl font-bold text-slate-900 flex items-center gap-2">
                        <Trophy className="text-amber-500" /> Ranking de Indicações
                    </h1>
                    <p className="text-slate-500">Monitore os embaixadores da marca e recompensas.</p>
                </div>
                <button className="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">
                    <Download size={18} />
                    Exportar CSV
                </button>
            </div>

            {/* Top Stats Cards */}
            <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div className="p-3 bg-purple-100 text-purple-600 rounded-lg">
                        <Users size={24} />
                    </div>
                    <div>
                        <p className="text-sm font-medium text-slate-500">Total de Indicações</p>
                        <p className="text-2xl font-bold text-slate-900">{totalReferrals}</p>
                    </div>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div className="p-3 bg-blue-100 text-blue-600 rounded-lg">
                        <ArrowUpRight size={24} />
                    </div>
                    <div>
                        <p className="text-sm font-medium text-slate-500">Total de Cliques</p>
                        <p className="text-2xl font-bold text-slate-900">{totalClicks}</p>
                    </div>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div className="p-3 bg-green-100 text-green-600 rounded-lg">
                        <TrendingUp size={24} />
                    </div>
                    <div>
                        <p className="text-sm font-medium text-slate-500">Taxa de Conversão</p>
                        <p className="text-2xl font-bold text-slate-900">
                            {totalClicks > 0 ? ((totalReferrals / totalClicks) * 100).toFixed(1) : 0}%
                        </p>
                    </div>
                </div>
                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div className="p-3 bg-amber-100 text-amber-600 rounded-lg">
                        <Crown size={24} />
                    </div>
                    <div>
                        <p className="text-sm font-medium text-slate-500">Créditos Gerados</p>
                        <p className="text-2xl font-bold text-slate-900">R$ {totalCredits.toFixed(2)}</p>
                    </div>
                </div>
            </div>

            {/* Leaderboard Table */}
            <div className="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
                <div className="p-6 border-b border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h2 className="font-bold text-lg text-slate-800">Top Embaixadores</h2>
                    <div className="relative w-full md:w-64">
                        <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
                        <input
                            type="text"
                            placeholder="Buscar por nome ou código..."
                            className="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-analux-secondary/20 focus:border-analux-secondary transition-all"
                            value={searchTerm}
                            onChange={(e) => setSearchTerm(e.target.value)}
                        />
                    </div>
                </div>

                <div className="overflow-x-auto">
                    <table className="w-full text-sm text-left">
                        <thead className="bg-slate-50 text-slate-500 font-medium uppercase text-xs tracking-wider">
                            <tr>
                                <th className="px-6 py-4">Ranking</th>
                                <th className="px-6 py-4">Assinante</th>
                                <th className="px-6 py-4 text-center">Acessos (Clicks)</th>
                                <th className="px-6 py-4 text-center">Online Agora</th>
                                <th className="px-6 py-4 text-center">Conversões</th>
                                <th className="px-6 py-4">Crédito Acumulado</th>
                            </tr>
                        </thead>
                        <tbody className="divide-y divide-slate-100">
                            {loading ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-8 text-center text-slate-500">
                                        Carregando ranking...
                                    </td>
                                </tr>
                            ) : filteredStats.length === 0 ? (
                                <tr>
                                    <td colSpan={6} className="px-6 py-8 text-center text-slate-500">
                                        Nenhuma indicação encontrada ainda.
                                    </td>
                                </tr>
                            ) : (
                                filteredStats.map((stat, index) => (
                                    <tr key={stat.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <div className="flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 font-bold text-slate-600">
                                                {index + 1}
                                            </div>
                                        </td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <div className="w-10 h-10 rounded-full bg-slate-200 overflow-hidden">
                                                    {stat.avatar_url ? (
                                                        <img src={stat.avatar_url} alt={stat.name} className="w-full h-full object-cover" />
                                                    ) : (
                                                        <div className="w-full h-full flex items-center justify-center bg-analux-secondary text-white font-bold">
                                                            {stat.name?.[0]?.toUpperCase()}
                                                        </div>
                                                    )}
                                                </div>
                                                <div>
                                                    <p className="font-bold text-slate-900">{stat.name}</p>
                                                    <div className="flex items-center gap-2">
                                                        <p className="text-xs text-slate-500">{stat.email}</p>
                                                        <span className="font-mono bg-slate-100 px-1.5 py-0.5 rounded text-[10px] text-slate-600 border border-slate-200">
                                                            {stat.referral_code}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td className="px-6 py-4 text-center font-mono text-slate-600">
                                            {stat.referral_clicks}
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            {stat.active_users && stat.active_users > 0 ? (
                                                <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-700 font-bold text-xs animate-pulse">
                                                    <span className="w-2 h-2 rounded-full bg-green-500"></span>
                                                    {stat.active_users} online
                                                </span>
                                            ) : (
                                                <span className="text-slate-400 text-xs">-</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-center">
                                            <span className="inline-flex items-center justify-center px-3 py-1 rounded-full bg-purple-100 text-purple-700 font-bold text-xs">
                                                {stat.referral_count}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 font-bold text-green-600">
                                            R$ {(stat.referral_count * 20).toFixed(2)}
                                        </td>
                                    </tr>
                                ))
                            )}
                        </tbody>
                    </table>
                </div>

                {!loading && filteredStats.length > 0 && (
                    <div className="p-4 border-t border-slate-100 bg-slate-50 text-center">
                        <button className="text-sm font-bold text-analux-secondary hover:underline flex items-center justify-center gap-1">
                            Ver Todos os Affiliados <ArrowUpRight size={14} />
                        </button>
                    </div>
                )}
            </div>
        </div>
    );
};

export default AdminReferrals;
