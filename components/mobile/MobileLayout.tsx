
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
        <header className="fixed top-0 left-0 right-0 bg-white/80 backdrop-blur-xl border-b border-gray-200/50 pt-[env(safe-area-inset-top)] z-50 h-[52px] flex items-center justify-between px-4 transition-all duration-300">
            {/* Left Placeholder (width of button) */}
            <div className="w-9" />

            {/* Center Logo */}
            <div className="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 pt-[env(safe-area-inset-top)] grid place-items-center h-full max-h-[52px]">
                <img src="/sidebar-logo.png" alt="Analux" className="h-6 w-auto object-contain" />
            </div>

            {/* Right Action */}
            <button
                onClick={() => navigate('/notifications')}
                className="relative p-2 text-gray-800 active:opacity-50 transition-opacity"
            >
                <Bell size={22} strokeWidth={2} />
                <span className="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border border-white shadow-sm"></span>
            </button>
        </header>
    );
};

const MobileLayout: React.FC = () => {
    const location = useLocation();
    const { user } = useUser();
    const isPaused = user?.subscription?.status === SubscriptionStatus.PAUSED;

    // Hide nav on login/auth pages if any unique logic is needed, but assuming authenticated layout usage
    return (
        <div className="min-h-screen bg-[#F8F9FA] pb-24 font-sans antialiased text-gray-900 select-none flex flex-col pt-[52px]">
            <MobileHeader />
            {isPaused && <PausedWarningBanner />}

            {/* Main Content Area with Safe Area Padding */}
            <main className="flex-1 px-4 py-8 pb-[env(safe-area-inset-bottom)] animate-in fade-in duration-300">
                <Outlet />
            </main>

            <MobileTabBar />
        </div>
    );
};

export default MobileLayout;
