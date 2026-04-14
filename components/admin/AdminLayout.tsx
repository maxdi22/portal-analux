import React from 'react';
import { Outlet, Navigate } from 'react-router-dom';
import SidebarAdmin from './SidebarAdmin';
import { useUser } from '../../context/UserContext';
import { initialMockData } from '../../services/mockData';

const AdminLayout: React.FC = () => {
    const { isMember, user, loading } = useUser();

    if (loading) {
        return (
            <div className="min-h-screen flex items-center justify-center bg-slate-900">
                <div className="flex flex-col items-center gap-4">
                    <div className="w-12 h-12 border-4 border-amber-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        );
    }

    // Double check admin status here, just in case
    const adminEmails = ['maxdi.brasil@gmail.com', 'maxdi.agency@gmail.com'];
    // Fallback to checking email directly if isAdmin isn't ready for some reason (though it should be)
    const isUserAdmin = user?.isAdmin || adminEmails.includes(user?.email || '');

    if (!isMember) {
        return <Navigate to="/" replace />;
    }

    if (!isUserAdmin) {
        return <Navigate to="/dashboard" replace />;
    }

    return (
        <div className="min-h-screen flex bg-slate-50 font-sans text-slate-900">
            <SidebarAdmin />

            <main className="flex-1 ml-64 p-8 overflow-y-auto h-screen custom-scrollbar">
                <div className="max-w-7xl mx-auto">
                    <Outlet />
                </div>
            </main>
        </div>
    );
};

export default AdminLayout;
