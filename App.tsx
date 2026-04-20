import React from 'react';
import { BrowserRouter, Routes, Route, Navigate, Outlet } from 'react-router-dom';

import DashboardHome from './components/DashboardHome';
import StyleDNA from './components/StyleDNA';
import Community from './components/Community';
// import StyleAI from './components/StyleAI';
import SubscriptionManagement from './components/SubscriptionManagement';
import UserProfile from './components/UserProfile';
import ConnectedStore from './components/ConnectedStore';
// import Editorial from './components/Editorial';
import LandingPage from './components/LandingPage';
import LandingPageB from './components/LandingPageB';
import LandingPageC from './components/LandingPageC';
import DigitalVault from './components/DigitalVault';
import AdminLayout from './components/admin/AdminLayout';
import AdminDashboard from './components/admin/AdminDashboard';
import AdminSubscribers from './components/admin/AdminSubscribers';
import AdminReferrals from './components/admin/AdminReferrals';
import AdminLogistics from './components/admin/logistics/AdminLogistics';
import BoxesDashboard from './components/admin/boxes/BoxesDashboard';
import CreateBox from './components/admin/boxes/CreateBox';
import MarketingCampaigns from './components/admin/MarketingCampaigns';

import AdminStock from './components/admin/AdminStock';
import AdminFinance from './components/admin/AdminFinance';
import AdminPlans from './components/admin/AdminPlans';
import AdminRetention from './components/admin/AdminRetention';
import AdminReports from './components/admin/AdminReports';
import AdminSettings from './components/admin/AdminSettings';
import AdminCommunity from './components/admin/AdminCommunity';
import AdminLeads from './components/admin/AdminLeads';
import AdminMarketingAds from './components/admin/AdminMarketingAds';
import AdsLiveDashboard from './components/admin/AdsLiveDashboard';

import { UserProvider, useUser } from './context/UserContext';
import { MarketingProvider } from './context/MarketingContext';
import { ToastProvider } from './context/ToastContext';
import { SubscriptionStatus } from './types';

import LayoutWrapper from './components/LayoutWrapper';
import PausedLockScreen from './components/PausedLockScreen';

const ProtectedLayout: React.FC = () => {
  const { isMember } = useUser();

  if (!isMember) {
    return <Navigate to="/" replace />;
  }

  return <LayoutWrapper />;
};

const ActiveMemberRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { user } = useUser();

  if (user?.subscription?.status === SubscriptionStatus.PAUSED) {
    return <PausedLockScreen />;
  }

  return <>{children}</>;
};

const PublicRoute: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { isMember } = useUser();
  if (isMember) {
    return <Navigate to="/dashboard" replace />;
  }
  return <>{children}</>;
};

const AppContent: React.FC = () => {
  const { user, updateUser, isMember, setIsMember, loading } = useUser();

  if (loading) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-analux-contrast">
        <div className="flex flex-col items-center gap-4">
          <div className="w-12 h-12 border-4 border-analux-secondary border-t-transparent rounded-full animate-spin"></div>
          <p className="text-analux-primary font-serif animate-pulse">Carregando seu brilho...</p>
        </div>
      </div>
    );
  }

  return (
    <Routes>
      <Route path="/" element={<LandingPageC />} />
      <Route path="/lp-b" element={<LandingPageB />} />
      <Route path="/lp-old" element={<LandingPage />} />
      <Route path="/convite/:code" element={<LandingPageC />} />

      <Route element={<ProtectedLayout />}>
        {/* Allowed routes for everyone, including paused users */}
        <Route path="/dashboard" element={<DashboardHome />} />
        <Route path="/shop" element={<ConnectedStore />} />
        <Route path="/box" element={<SubscriptionManagement />} />
        <Route path="/settings" element={<UserProfile />} />

        {/* Restricted routes, locked for paused users */}
        <Route path="/style-dna" element={<ActiveMemberRoute><StyleDNA /></ActiveMemberRoute>} />
        <Route path="/community" element={<ActiveMemberRoute><Community /></ActiveMemberRoute>} />
        <Route path="/vault" element={<ActiveMemberRoute><DigitalVault /></ActiveMemberRoute>} />

        {/* Fallback route */}
        <Route path="*" element={<Navigate to="/dashboard" replace />} />
      </Route>

      {/* Admin Portal Routes */}
      <Route path="/admin" element={<AdminLayout />}>
        <Route index element={<Navigate to="/admin/dashboard" replace />} />
        <Route path="dashboard" element={<AdminDashboard />} />
        <Route path="subscribers" element={<AdminSubscribers />} />
        <Route path="leads" element={<AdminLeads />} />
        <Route path="referrals" element={<AdminReferrals />} />

        {/* Logistics Module */}
        <Route path="logistics" element={<AdminLogistics />} />

        {/* Boxes Module */}
        <Route path="boxes" element={<BoxesDashboard />} />
        <Route path="boxes/new" element={<CreateBox />} />

        {/* Marketing Module */}
        <Route path="marketing" element={<Navigate to="/admin/marketing/live" replace />} />
        <Route path="marketing/live" element={<AdsLiveDashboard />} />
        <Route path="marketing/config" element={<AdminMarketingAds />} />
        <Route path="marketing/campaigns" element={<MarketingCampaigns />} />

        {/* Novos Módulos Admin */}
        <Route path="stock" element={<AdminStock />} />
        <Route path="finance" element={<AdminFinance />} />
        <Route path="plans" element={<AdminPlans />} />
        <Route path="retention" element={<AdminRetention />} />
        <Route path="reports" element={<AdminReports />} />
        <Route path="settings" element={<AdminSettings />} />
        <Route path="community" element={<AdminCommunity />} />

        {/* Fallback for other admin routes */}
        <Route path="*" element={<div className="p-10 text-slate-500">Módulo em construção...</div>} />
      </Route>
    </Routes>
  );
};

const App: React.FC = () => {
  return (
    <UserProvider>
      <ToastProvider>
        <BrowserRouter>
          <MarketingProvider>
            <AppContent />
          </MarketingProvider>
        </BrowserRouter>
      </ToastProvider>
    </UserProvider>
  );
};

export default App;
