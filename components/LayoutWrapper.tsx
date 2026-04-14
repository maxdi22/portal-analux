import React from 'react';
import { Outlet } from 'react-router-dom';
import { useIsMobile } from '../hooks/useIsMobile';
import Sidebar from './Sidebar';
import MobileLayout from './mobile/MobileLayout';
import { Toaster } from 'react-hot-toast';
import { useUser } from '../context/UserContext';
import { SubscriptionStatus } from '../types';
import PausedWarningBanner from './PausedWarningBanner';
import FirstAccessModal from './FirstAccessModal';

const LayoutWrapper: React.FC = () => {
    const isMobile = useIsMobile();
    const { user } = useUser();
    const isPaused = user?.subscription?.status === SubscriptionStatus.PAUSED;

    if (isMobile) {
        return (
            <>
                {user?.needsPasswordReset && <FirstAccessModal />}
                {isPaused && <PausedWarningBanner />}
                <MobileLayout />
                <Toaster position="top-center" />
            </>
        );
    }

    // Desktop Layout (Preserving original structure)
    return (
        <div className="flex min-h-screen bg-neutral-50 font-sans flex-col">
            {user?.needsPasswordReset && <FirstAccessModal />}
            {isPaused && <PausedWarningBanner />}
            <div className="flex flex-1">
                <Sidebar />
                <main className="flex-1 ml-64 p-8 overflow-y-auto">
                    <div className="max-w-7xl mx-auto space-y-8 pb-12">
                        <Outlet />
                    </div>
                </main>
            </div>
            <Toaster position="top-right" />
        </div>
    );
};

export default LayoutWrapper;
