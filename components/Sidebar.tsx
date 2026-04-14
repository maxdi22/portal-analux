
import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import {
  LayoutDashboard,
  Users,
  Settings,
  Package,
  Sparkles,
  ShoppingBag,
  BookOpen,
  Gem,
  LogOut,
  Shield
} from 'lucide-react';
import { supabase } from '../services/supabase';
import { useUser } from '../context/UserContext';

const Sidebar: React.FC = () => {
  const location = useLocation();
  const { signOut, user } = useUser();

  const [testResult, setTestResult] = React.useState<string | null>(null);
  const [loading, setLoading] = React.useState(false);

  const performTest = async () => {
    setLoading(true);
    try {
      const { data: { user } } = await supabase.auth.getUser();
      if (!user) {
        setTestResult("Erro: Usuário não logado no Portal.");
        setLoading(false);
        return;
      }

      const { data, error } = await supabase.functions.invoke('woocommerce-proxy', {
        body: { action: 'sync', email: user.email } // Use 'sync' to see full debug
      });

      if (error) {
        let errorMsg = error.message;
        if (error instanceof Error && 'context' in error) {
          // @ts-ignore
          const body = await error.context?.response?.json().catch(() => null);
          if (body?.error) errorMsg = JSON.stringify(body, null, 2);
        }
        setTestResult(`FALHA NA REQUISIÇÃO:\n${errorMsg}`);
      } else {
        setTestResult(JSON.stringify(data, null, 2));
      }
    } catch (err: any) {
      setTestResult(`ERRO CLIENTE: ${err.message}`);
    }
    setLoading(false);
  };

  const menuItems = [
    { id: 'dashboard', path: '/dashboard', label: 'Meu Painel', icon: <LayoutDashboard size={20} /> },
    { id: 'style', path: '/style-dna', label: 'Meu DNA', icon: <Sparkles size={20} /> },
    { id: 'community', path: '/community', label: 'Comunidade', icon: <Users size={20} /> },
    { id: 'vault', path: '/vault', label: 'Porta Joias', icon: <Gem size={20} /> },
    { id: 'box', path: '/box', label: 'Minha Box', icon: <Package size={20} /> },
    { id: 'shop', path: '/shop', label: 'Minhas Compras', icon: <ShoppingBag size={20} /> },
    { id: 'settings', path: '/settings', label: 'Configurações', icon: <Settings size={20} /> },
  ];

  const isActive = (path: string) => {
    if (path === '/' && location.pathname === '/') return true;
    if (path !== '/' && location.pathname.startsWith(path)) return true;
    return false;
  };

  return (
    <>
      <div className="w-64 bg-analux-primary h-screen fixed left-0 top-0 text-white flex flex-col p-6 shadow-2xl z-50">
        <div className="mb-12 flex flex-col items-center">
          <div className="w-48 h-20 flex items-center justify-center mb-2">
            <img src="/sidebar-logo.png" alt="Analux Luxury Club" className="w-full h-full object-contain" />
          </div>
          <span className="text-[10px] uppercase tracking-[0.3em] text-analux-secondary font-medium">Club Exclusive</span>
        </div>

        <nav className="flex-1 space-y-2">
          {menuItems.map((item) => (
            <Link
              key={item.id}
              to={item.path}
              className={`w-full flex items-center gap-4 px-4 py-3 rounded-xl transition-all duration-300 ${isActive(item.path)
                ? 'bg-analux-secondary text-analux-primary font-semibold shadow-lg scale-105'
                : 'hover:bg-analux-dark text-analux-contrast/70 hover:text-white'
                }`}
            >
              {item.icon}
              <span className="text-sm">{item.label}</span>
            </Link>
          ))}
        </nav>

        <div className="pt-6 border-t border-analux-dark space-y-2">
          {user?.isAdmin && (
            <button
              onClick={performTest}
              disabled={loading}
              className="w-full flex items-center gap-4 px-4 py-3 text-analux-secondary hover:text-white transition-colors border border-analux-secondary/20 rounded-xl disabled:opacity-50"
            >
              <span className="text-xs font-bold uppercase">{loading ? 'Testando...' : 'Testar Conexão API'}</span>
            </button>
          )}

          <button
            onClick={signOut}
            className="w-full flex items-center gap-4 px-4 py-3 text-analux-contrast/50 hover:text-red-300 transition-colors"
          >
            <LogOut size={20} />
            <span className="text-sm">Sair</span>
          </button>

          {user?.isAdmin && (
            <Link
              to="/admin"
              className="w-full flex items-center gap-4 px-4 py-3 text-amber-500 hover:text-amber-400 transition-colors border border-amber-500/20 rounded-xl mt-2 bg-amber-500/10"
            >
              <Shield size={20} />
              <span className="text-sm font-bold uppercase tracking-wider">Portal Admin</span>
            </Link>
          )}
        </div>
      </div>

      {testResult && (
        <div className="fixed inset-0 z-[100] bg-black/80 flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl w-full max-w-2xl max-h-[80vh] flex flex-col overflow-hidden shadow-2xl animate-in zoom-in-95 duration-200">
            <div className="p-4 border-b flex justify-between items-center bg-gray-50">
              <h3 className="font-bold text-lg text-gray-800">Resultado do Teste API</h3>
              <button onClick={() => setTestResult(null)} className="text-gray-400 hover:text-red-500 font-bold p-2">✕</button>
            </div>
            <div className="p-0 overflow-auto bg-[#1e1e1e] text-green-400 font-mono text-xs">
              <pre className="p-4">{testResult}</pre>
            </div>
            <div className="p-4 border-t bg-gray-50 flex justify-end">
              <button
                onClick={() => { navigator.clipboard.writeText(testResult); alert('Copiado!'); }}
                className="px-4 py-2 bg-analux-primary text-white rounded-lg text-sm font-bold hover:bg-analux-dark"
              >
                Copiar Log
              </button>
            </div>
          </div>
        </div>
      )}
    </>
  );
};

export default Sidebar;
