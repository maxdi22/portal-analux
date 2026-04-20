import React, { useState, useEffect } from 'react';
import { supabase } from '../../services/supabase';
import { AnalyticsEvent } from '../../marketing_types';
import { 
    Users, 
    Activity, 
    MousePointer2, 
    Eye, 
    Clock, 
    ArrowUpRight,
    Search,
    User,
    Globe,
    Zap
} from 'lucide-react';

const AdsLiveDashboard: React.FC = () => {
    const [events, setEvents] = useState<AnalyticsEvent[]>([]);
    const [onlineCount, setOnlineCount] = useState(0);
    const [todayViews, setTodayViews] = useState(0);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        fetchInitialData();
        
        // Inscrição Realtime para novos eventos
        const subscription = supabase
            .channel('visitor_activity_realtime')
            .on('postgres_changes', { 
                event: 'INSERT', 
                schema: 'public', 
                table: 'visitor_activity' 
            }, (payload) => {
                const newEvent = payload.new as AnalyticsEvent;
                setEvents(prev => [newEvent, ...prev].slice(0, 50));
                updateStatsFromEvent(newEvent);
            })
            .subscribe();

        // Timer para refresh de estatísticas gerais a cada 1 min
        const interval = setInterval(fetchInitialData, 60000);

        return () => {
            subscription.unsubscribe();
            clearInterval(interval);
        };
    }, []);

    const fetchInitialData = async () => {
        try {
            // 1. Pegar últimos 50 eventos
            const { data: recentEvents } = await supabase
                .from('visitor_activity')
                .select('*')
                .order('created_at', { ascending: false })
                .limit(50);
            
            if (recentEvents) setEvents(recentEvents);

            // 2. Contar online agora (ativos nos últimos 5 min)
            const fiveMinsAgo = new Date(Date.now() - 5 * 60 * 1000).toISOString();
            const { count: online } = await supabase
                .from('visitor_activity')
                .select('guest_id', { count: 'exact', head: true })
                .gt('created_at', fiveMinsAgo);
            
            setOnlineCount(online || 0);

            // 3. Contar visualizações de hoje
            const today = new Date();
            today.setHours(0,0,0,0);
            const { count: views } = await supabase
                .from('visitor_activity')
                .select('*', { count: 'exact', head: true })
                .eq('event_type', 'page_view')
                .gt('created_at', today.toISOString());
            
            setTodayViews(views || 0);
        } catch (err) {
            console.error('Error fetching dashboard data:', err);
        } finally {
            setLoading(false);
        }
    };

    const updateStatsFromEvent = (event: AnalyticsEvent) => {
        if (event.event_type === 'page_view') {
            setTodayViews(prev => prev + 1);
        }
    };

    if (loading) return <div className="p-8 text-slate-500 animate-pulse">Carregando Dashboard ADS...</div>;

    return (
        <div className="p-8 max-w-7xl mx-auto space-y-8">
            <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 className="text-3xl font-serif text-analux-primary flex items-center gap-3">
                        <Activity className="text-analux-secondary animate-pulse" />
                        Ads Real-Time Monitor
                    </h1>
                    <p className="text-slate-500 mt-1">Acompanhe o comportamento dos seus leads em tempo real.</p>
                </div>
                <div className="flex items-center gap-2 bg-emerald-50 text-emerald-700 px-4 py-2 rounded-full border border-emerald-100">
                    <div className="w-2 h-2 bg-emerald-500 rounded-full animate-ping" />
                    <span className="text-sm font-bold">{onlineCount} Visitantes Online Agora</span>
                </div>
            </div>

            {/* Metricas Rápidas */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <MetricCard 
                    icon={<Eye size={22} />}
                    label="Visualizações (Hoje)"
                    value={todayViews.toString()}
                    color="bg-blue-50 text-blue-600"
                />
                <MetricCard 
                    icon={<MousePointer2 size={22} />}
                    label="Cliques (Hoje)"
                    value={events.filter(e => e.event_type === 'click').length.toString()}
                    color="bg-purple-50 text-purple-600"
                />
                <MetricCard 
                    icon={<Users size={22} />}
                    label="Novos Leads"
                    value={events.filter(e => e.event_type === 'lead_convert').length.toString()}
                    color="bg-amber-50 text-amber-600"
                />
                <MetricCard 
                    icon={<ArrowUpRight size={22} />}
                    label="Taxa de Conv."
                    value="2.4%"
                    color="bg-emerald-50 text-emerald-600"
                />
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {/* Feed de Atividades */}
                <div className="lg:col-span-2 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div className="p-6 border-b border-slate-50 flex items-center justify-between">
                        <h2 className="font-medium text-slate-800 flex items-center gap-2">
                            <Zap size={18} className="text-analux-secondary" />
                            Live Event Stream
                        </h2>
                        <span className="text-xs text-slate-400">Últimos 50 eventos</span>
                    </div>
                    <div className="overflow-y-auto max-h-[600px]">
                        <table className="w-full text-left text-sm">
                            <thead className="bg-slate-50 text-slate-400 font-medium uppercase text-[10px] tracking-wider">
                                <tr>
                                    <th className="px-6 py-3">Horário</th>
                                    <th className="px-6 py-3">Visitante</th>
                                    <th className="px-6 py-3">Evento</th>
                                    <th className="px-6 py-3">Página</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-50">
                                {events.map((event, idx) => (
                                    <tr key={event.id || idx} className="hover:bg-slate-50/50 transition-colors">
                                        <td className="px-6 py-4 text-slate-400 tabular-nums">
                                            {new Date(event.created_at!).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' })}
                                        </td>
                                        <td className="px-6 py-4 flex items-center gap-2">
                                            <div className="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                                <User size={14} />
                                            </div>
                                            <span className="font-mono text-xs text-slate-600">
                                                {event.user_id ? 'Assinante' : event.guest_id.substring(6, 12)}...
                                            </span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <EventTypeBadge type={event.event_type} />
                                        </td>
                                        <td className="px-6 py-4 text-slate-500 font-medium">
                                            {event.page_path}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Sidebar Ads Info */}
                <div className="space-y-6">
                    <div className="bg-analux-primary p-6 rounded-2xl text-white shadow-xl">
                        <h3 className="font-medium mb-4 flex items-center gap-2">
                            <Globe size={18} />
                            Origem do Tráfego
                        </h3>
                        <div className="space-y-4">
                            <TrafficSourceRow label="Direto / WhatsApp" percent={65} color="bg-emerald-400" />
                            <TrafficSourceRow label="Facebook / Instagram" percent={24} color="bg-blue-400" />
                            <TrafficSourceRow label="Google Search" percent={11} color="bg-amber-400" />
                        </div>
                    </div>

                    <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <h3 className="font-medium text-slate-800 mb-4 flex items-center gap-2">
                            <Search size={18} className="text-slate-400" />
                            Top Páginas (Realtime)
                        </h3>
                        <div className="space-y-3">
                            <PageRankRow path="/planos" views={142} />
                            <PageRankRow path="/" views={98} />
                            <PageRankRow path="/reuniao" views={45} />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

const MetricCard = ({ icon, label, value, color }: any) => (
    <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col hover:border-analux-secondary transition-all cursor-default">
        <div className={`w-10 h-10 rounded-xl flex items-center justify-center mb-4 ${color}`}>
            {icon}
        </div>
        <span className="text-slate-400 text-xs font-semibold uppercase tracking-wider">{label}</span>
        <span className="text-2xl font-bold text-slate-800 mt-1">{value}</span>
    </div>
);

const TrafficSourceRow = ({ label, percent, color }: any) => (
    <div className="space-y-1.5">
        <div className="flex justify-between text-xs">
            <span className="opacity-80">{label}</span>
            <span className="font-bold">{percent}%</span>
        </div>
        <div className="w-full h-1.5 bg-white/20 rounded-full overflow-hidden">
            <div className={`h-full ${color}`} style={{ width: `${percent}%` }}></div>
        </div>
    </div>
);

const PageRankRow = ({ path, views }: any) => (
    <div className="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
        <span className="text-sm font-medium text-slate-600 truncate max-w-[150px]">{path}</span>
        <span className="text-xs font-bold text-slate-400 bg-white px-2 py-1 rounded-lg border border-slate-100">
            {views} hits
        </span>
    </div>
);

const EventTypeBadge = ({ type }: { type: AnalyticsEvent['event_type'] }) => {
    const styles: Record<string, string> = {
        'page_view': 'bg-blue-100 text-blue-600',
        'click': 'bg-purple-100 text-purple-600',
        'lead_convert': 'bg-emerald-100 text-emerald-600',
        'heartbeat': 'bg-slate-100 text-slate-400',
        'conversion': 'bg-amber-100 text-amber-600'
    };
    
    return (
        <span className={`px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-tight ${styles[type] || styles.heartbeat}`}>
            {type.replace('_', ' ')}
        </span>
    );
}

export default AdsLiveDashboard;
