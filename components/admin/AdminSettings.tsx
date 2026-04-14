import React, { useState } from 'react';
import { Settings, Save, Lock, Github, Globe, CreditCard, Bell, Database } from 'lucide-react';

const AdminSettings: React.FC = () => {
  const [activeTab, setActiveTab] = useState('general');

  const tabs = [
    { id: 'general', label: 'Geral', icon: <Settings size={18} /> },
    { id: 'integrations', label: 'Integrações', icon: <Globe size={18} /> },
    { id: 'security', label: 'Segurança', icon: <Lock size={18} /> },
    { id: 'notifications', label: 'Notificações', icon: <Bell size={18} /> },
    { id: 'database', label: 'Banco de Dados', icon: <Database size={18} /> },
  ];

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-serif text-analux-primary">Configurações do Portal</h1>
          <p className="text-slate-500">Gerencie chaves, integrações e comportamento do sistema.</p>
        </div>
        <button className="flex items-center gap-2 bg-analux-primary text-white px-6 py-2 rounded-lg hover:bg-opacity-90 transition-all font-medium shadow-sm">
          <Save size={20} />
          Salvar Alterações
        </button>
      </div>

      <div className="flex flex-col lg:flex-row gap-8">
        {/* Tab Sidebar */}
        <div className="w-full lg:w-64 space-y-1">
          {tabs.map((tab) => (
            <button
              key={tab.id}
              onClick={() => setActiveTab(tab.id)}
              className={`w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium ${
                activeTab === tab.id 
                ? 'bg-analux-secondary text-white shadow-md' 
                : 'text-slate-500 hover:bg-slate-50'
              }`}
            >
              {tab.icon}
              {tab.label}
            </button>
          ))}
        </div>

        {/* Settings Content */}
        <div className="flex-1 space-y-6">
          <div className="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
            {activeTab === 'general' && (
              <div className="space-y-6">
                <h3 className="text-lg font-bold text-slate-800 mb-6">Informações Gerais</h3>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div>
                    <label className="block text-sm font-bold text-slate-700 mb-2">Nome da Plataforma</label>
                    <input type="text" defaultValue="Analux Box Portal" className="w-full p-3 bg-slate-50 rounded-lg border-none focus:ring-2 focus:ring-analux-secondary" />
                  </div>
                  <div>
                    <label className="block text-sm font-bold text-slate-700 mb-2">URL de Produção</label>
                    <input type="text" defaultValue="https://portal.analux.com.br" className="w-full p-3 bg-slate-50 rounded-lg border-none focus:ring-2 focus:ring-analux-secondary" />
                  </div>
                  <div className="md:col-span-2">
                    <label className="block text-sm font-bold text-slate-700 mb-2">E-mail de Suporte Administrativo</label>
                    <input type="email" defaultValue="contato@analux.com.br" className="w-full p-3 bg-slate-50 rounded-lg border-none focus:ring-2 focus:ring-analux-secondary" />
                  </div>
                </div>
              </div>
            )}

            {activeTab === 'integrations' && (
              <div className="space-y-8">
                <div className="flex items-center gap-4 mb-6">
                  <div className="p-3 bg-indigo-50 rounded-xl">
                    <CreditCard className="text-indigo-600" size={24} />
                  </div>
                  <div>
                    <h3 className="text-lg font-bold text-slate-800">Stripe Payment Gateway</h3>
                    <p className="text-sm text-slate-500">Configuração de pagamentos e assinaturas.</p>
                  </div>
                </div>
                
                <div className="space-y-4">
                  <div>
                    <label className="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Live Publishable Key</label>
                    <input type="text" placeholder="pk_live_..." className="w-full p-3 bg-slate-50 rounded-lg border border-slate-200 font-mono text-sm" />
                  </div>
                  <div>
                    <label className="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Stripe Webhook Secret</label>
                    <input type="password" value="•••••••••••••••••••••" readOnly className="w-full p-3 bg-slate-50 rounded-lg border border-slate-200 font-mono text-sm" />
                  </div>
                  <div className="p-4 bg-amber-50 rounded-lg border border-amber-200 flex gap-3 text-amber-700">
                    <Lock size={20} className="shrink-0" />
                    <p className="text-sm">As chaves secretas (Secret Keys) são gerenciadas diretamente no cofre de segredos do Supabase para maior segurança.</p>
                  </div>
                </div>
              </div>
            )}
            
            {(activeTab !== 'general' && activeTab !== 'integrations') && (
              <div className="py-20 text-center opacity-30">
                <Settings size={48} className="mx-auto mb-4" />
                <p>Configurações de {activeTab} em desenvolvimento...</p>
              </div>
            )}
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminSettings;
