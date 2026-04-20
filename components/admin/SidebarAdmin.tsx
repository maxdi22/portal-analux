import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import {
    LayoutDashboard,
    Users,
    Layers,
    Package,
    Truck,
    Database,
    CreditCard,
    BarChart2,
    MessageSquare,
    Settings,
    FileText,
    LogOut,
    ArrowLeft,
    Megaphone,
    Target
} from 'lucide-react';
import { useUser } from '../../context/UserContext';

const SidebarAdmin: React.FC = () => {
    const location = useLocation();
    const { signOut } = useUser();

    const menuItems = [
        { id: 'dashboard', path: '/admin/dashboard', label: 'Dashboard', icon: <LayoutDashboard size={20} /> },
        { id: 'subscribers', path: '/admin/subscribers', label: 'Assinantes', icon: <Users size={20} /> },
        { id: 'leads', path: '/admin/leads', label: 'CRM de Leads', icon: <Target size={20} className="text-amber-500" /> },
        { id: 'referrals', path: '/admin/referrals', label: 'Indicações', icon: <Users size={20} className="text-amber-500" /> }, // Reused icon or new one
        { id: 'plans', path: '/admin/plans', label: 'Planos & Produtos', icon: <Layers size={20} /> },
        { id: 'boxes', path: '/admin/boxes', label: 'Boxes & Edições', icon: <Package size={20} /> },
        { id: 'logistics', path: '/admin/logistics', label: 'Expedição / Logística', icon: <Truck size={20} /> },
        { id: 'stock', path: '/admin/stock', label: 'Estoque', icon: <Database size={20} /> },
        { id: 'finance', path: '/admin/finance', label: 'Financeiro', icon: <CreditCard size={20} /> },
        { id: 'retention', path: '/admin/retention', label: 'Retenção', icon: <BarChart2 size={20} /> },
        { id: 'marketing_live', path: '/admin/marketing/live', label: 'Monitor de ADS', icon: <Megaphone size={20} className="text-emerald-500" /> },
        { id: 'marketing_config', path: '/admin/marketing/config', label: 'Central de Pixels', icon: <Target size={20} className="text-blue-500" /> },
        { id: 'marketing_campaigns', path: '/admin/marketing/campaigns', label: 'Campanhas', icon: <Megaphone size={20} /> },
        { id: 'content', path: '/admin/community', label: 'Comunidade', icon: <MessageSquare size={20} /> },
        { id: 'reports', path: '/admin/reports', label: 'Relatórios', icon: <FileText size={20} /> },
        { id: 'settings', path: '/admin/settings', label: 'Configurações', icon: <Settings size={20} /> },
    ];

    const isActive = (path: string) => {
        return location.pathname === path || location.pathname.startsWith(path + '/');
    };

    return (
        <div className="w-64 bg-slate-900 h-screen fixed left-0 top-0 text-white flex flex-col p-6 shadow-2xl z-50">
            <div className="mb-8 flex flex-col items-center">
                <div className="flex items-center gap-2 mb-2">
                    <span className="text-xl font-bold tracking-widest text-amber-500 uppercase">Analux Ops</span>
                </div>
                <span className="text-[10px] uppercase tracking-[0.2em] text-slate-400">Admin Portal</span>
            </div>

            <nav className="flex-1 space-y-1 overflow-y-auto pr-2 custom-scrollbar">
                {menuItems.map((item) => (
                    <Link
                        key={item.id}
                        to={item.path}
                        className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 ${isActive(item.path)
                            ? 'bg-amber-600/20 text-amber-500 border border-amber-500/20 shadow-sm'
                            : 'hover:bg-slate-800 text-slate-400 hover:text-slate-100'
                            }`}
                    >
                        {item.icon}
                        <span className="text-sm font-medium">{item.label}</span>
                    </Link>
                ))}
            </nav>

            <div className="pt-4 border-t border-slate-800 space-y-2 mt-2">
                <Link
                    to="/dashboard"
                    className="w-full flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:text-white transition-colors hover:bg-slate-800 rounded-lg"
                >
                    <ArrowLeft size={20} />
                    <span className="text-sm font-medium">Voltar ao App</span>
                </Link>

                <button
                    onClick={signOut}
                    className="w-full flex items-center gap-3 px-3 py-2.5 text-red-400 hover:text-red-300 transition-colors hover:bg-slate-800 rounded-lg"
                >
                    <LogOut size={20} />
                    <span className="text-sm font-medium">Sair</span>
                </button>
            </div>
        </div>
    );
};

export default SidebarAdmin;
