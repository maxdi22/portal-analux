
import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { LayoutDashboard, ShoppingBag, Package, User, Sparkles } from 'lucide-react';
import { useUser } from '../../context/UserContext';

const MobileTabBar: React.FC = () => {
    const location = useLocation();
    const { user } = useUser();

    // Standard items: Home, Shop, Box, Profile
    const menuItems = [
        { id: 'dashboard', path: '/dashboard', label: 'Início', icon: <LayoutDashboard size={24} /> },
        { id: 'shop', path: '/shop', label: 'E-shop', icon: <ShoppingBag size={24} /> },
        { id: 'box', path: '/box', label: 'Box', icon: <Package size={24} /> },
        { id: 'style', path: '/style-dna', label: 'DNA', icon: <Sparkles size={24} /> },
        { id: 'settings', path: '/settings', label: 'Perfil', icon: <User size={24} /> },
    ];

    const isActive = (path: string) => {
        if (path === '/' && location.pathname === '/') return true;
        if (path !== '/' && location.pathname.startsWith(path)) return true;
        return false;
    };

    return (
        <nav className="fixed bottom-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-t border-gray-200/50 pb-[env(safe-area-inset-bottom)] z-50">
            <div className="flex justify-around items-center h-[52px] pt-1 px-1">
                {menuItems.map((item) => {
                    const active = isActive(item.path);
                    return (
                        <Link
                            key={item.id}
                            to={item.path}
                            className={`flex flex-col items-center justify-center w-full h-full space-y-[2px] transition-colors duration-200 active:opacity-50 ${active ? 'text-analux-primary' : 'text-gray-400'
                                }`}
                        >
                            <div className="relative">
                                {item.icon}
                            </div>
                            <span className={`text-[10px] font-medium leading-none ${active ? 'font-semibold' : ''}`}>
                                {item.label}
                            </span>
                        </Link>
                    )
                })}
            </div>
        </nav>
    );
};

export default MobileTabBar;
