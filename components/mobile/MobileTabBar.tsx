import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { LayoutDashboard, ShoppingBag, Package, User, Sparkles } from 'lucide-react';

const MobileTabBar: React.FC = () => {
    const location = useLocation();

    const menuItems = [
        { id: 'dashboard', path: '/dashboard',  label: 'Início',  icon: LayoutDashboard },
        { id: 'shop',      path: '/shop',        label: 'E-Shop',  icon: ShoppingBag },
        { id: 'box',       path: '/box',         label: 'Minha Box', icon: Package },
        { id: 'style',     path: '/style-dna',   label: 'DNA',     icon: Sparkles },
        { id: 'settings',  path: '/settings',    label: 'Perfil',  icon: User },
    ];

    const isActive = (path: string) => {
        if (path === '/' && location.pathname === '/') return true;
        return path !== '/' && location.pathname.startsWith(path);
    };

    return (
        <nav className="fixed bottom-0 left-0 right-0 z-50 pb-[env(safe-area-inset-bottom)]">
            {/* Frosted glass bar */}
            <div className="bg-white/85 backdrop-blur-2xl border-t border-gray-200/60 shadow-[0_-1px_0_rgba(0,0,0,0.06)]">
                <div className="flex justify-around items-end h-[58px] px-2">
                    {menuItems.map((item) => {
                        const active = isActive(item.path);
                        const Icon = item.icon;
                        return (
                            <Link
                                key={item.id}
                                to={item.path}
                                className="flex flex-col items-center justify-end pb-2 gap-[3px] w-full h-full transition-all duration-200 active:scale-90"
                            >
                                <div className="relative flex items-center justify-center">
                                    {/* Active pill background */}
                                    {active && (
                                        <span className="absolute inset-0 -m-1.5 rounded-xl bg-analux-primary/10 scale-100 animate-in zoom-in-75 duration-200" />
                                    )}
                                    <Icon
                                        size={22}
                                        strokeWidth={active ? 2.2 : 1.7}
                                        className={`relative z-10 transition-colors duration-200 ${
                                            active ? 'text-analux-primary' : 'text-gray-400'
                                        }`}
                                    />
                                </div>
                                <span className={`text-[10px] leading-none font-medium tracking-tight transition-colors duration-200 ${
                                    active ? 'text-analux-primary font-semibold' : 'text-gray-400'
                                }`}>
                                    {item.label}
                                </span>
                            </Link>
                        );
                    })}
                </div>
            </div>
        </nav>
    );
};

export default MobileTabBar;
