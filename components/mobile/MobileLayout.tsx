import React from 'react';
import { Outlet, useLocation } from 'react-router-dom';
import MobileTabBar from './MobileTabBar';
import { Bell } from 'lucide-react';
import { useUser } from '../../context/UserContext';
import { useNavigate } from 'react-router-dom';
import { SubscriptionStatus } from '../../types';
import PausedWarningBanner from '../PausedWarningBanner';

const MobileHeader: React.FC = () => {
    const { user } = useUser();
    const navigate = useNavigate();

    return (
        <header className="fixed top-0 left-0 right-0 bg-white/90 backdrop-blur-xl border-b border-gray-100/80 pt-[env(safe-area-inset-top)] z-50 shadow-sm">
            <div className="h-[52px] flex items-center justify-between px-5">
                {/* Left Placeholder */}
                <div className="w-9" />

                {/* Center Logo */}
                <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pt-[env(safe-area-inset-top)] flex items-center">
                    <img src="/sidebar-logo.png" alt="Analux" className="h-6 w-auto object-contain" />
                </div>

                {/* Notification Bell */}
                <button
                    onClick={() => navigate('/notifications')}
                    className="relative p-2 text-gray-700 active:opacity-50 transition-opacity rounded-full hover:bg-gray-100"
                >
                    <Bell size={20} strokeWidth={1.8} />
                    <span className="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full border-2 border-white shadow-sm" />
                </button>
            </div>
        </header>
    );
};

const MobileLayout: React.FC = () => {
    const { user } = useUser();
    const isPaused = user?.subscription?.status === SubscriptionStatus.PAUSED;
    const bannerHeight = isPaused ? 44 : 0; // px height of the banner

    return (
        <div
            className="min-h-screen bg-[#F5F5F7] pb-24 font-sans antialiased text-gray-900 select-none flex flex-col"
            style={{ paddingTop: `calc(52px + ${bannerHeight}px)` }}
        >
            <MobileHeader />

            {/* Paused Banner — rendered ONCE here, not in LayoutWrapper */}
            {isPaused && (
                <div className="fixed top-[52px] left-0 right-0 z-40">
                    <PausedWarningBanner />
                </div>
            )}

            {/* Main Content */}
            <main className="flex-1 px-4 pt-5 pb-[env(safe-area-inset-bottom)] animate-in fade-in duration-300">
                <Outlet />
            </main>

            <MobileTabBar />
        </div>
    );
};

export default MobileLayout;
