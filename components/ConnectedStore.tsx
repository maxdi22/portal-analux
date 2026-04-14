import React, { useState, useEffect } from 'react';
import {
  ShoppingBag,
  Link as LinkIcon,
  ExternalLink,
  History,
  Star,
  Tag,
  ChevronRight,
  ArrowRight,
  Zap,
  CheckCircle2,
  Lock,
  RefreshCw,
  Mail,
  Key,
  Info,
  Plus,
  Crown
} from 'lucide-react';
import { StoreProduct } from '../types';
import { useUser } from '../context/UserContext';

import { supabase } from '../services/supabase';
import LoyaltyClub from './LoyaltyClub';

const ConnectedStore: React.FC = () => {
  const { user, updateUser, refreshData } = useUser();
  const [step, setStep] = useState<'intro' | 'login' | 'syncing'>('intro');
  const [email, setEmail] = useState(user.email);
  const [password, setPassword] = useState('');
  const [syncProgress, setSyncProgress] = useState(0);
  const [logs, setLogs] = useState<string[]>([]);
  const [errorMsg, setErrorMsg] = useState('');
  const [expandedOrderId, setExpandedOrderId] = useState<string | null>(null);
  const [isWishlistModalOpen, setIsWishlistModalOpen] = useState(false);
  const [activeTab, setActiveTab] = useState<'orders' | 'rewards'>('orders');
  const [loadingOfferId, setLoadingOfferId] = useState<string | null>(null);

  const handleOfferClick = async (offer: StoreProduct) => {
    setLoadingOfferId(offer.id);
    try {
      const { data, error } = await supabase.functions.invoke('woocommerce-proxy', {
        body: { action: 'create_coupon', email: user.email }
      });

      if (error) throw error;
      if (data?.error) throw new Error(data.error);

      const couponCode = data.coupon || 'ANALUX_VIP';

      // Redirect directly to cart with item and coupon
      // Standard WooCommerce slug is /cart/
      const url = `https://analux.shop/cart/?add-to-cart=${offer.id}&coupon_code=${couponCode}`;

      window.open(url, '_blank');
    } catch (err) {
      console.error('Error generating coupon:', err);
      // Fallback
      const url = `https://analux.shop/cart/?add-to-cart=${offer.id}&coupon_code=ANALUX_VIP`;
      window.open(url, '_blank');
    } finally {
      setLoadingOfferId(null);
    }
  };

  const addLog = (msg: string) => setLogs(prev => [...prev, `[${new Date().toLocaleTimeString()}] ${msg}`]);

  const handleConnect = async () => {
    setStep('syncing');
    setSyncProgress(10);
    setErrorMsg('');
    setLogs([]);
    addLog('Iniciando conexão segura...');

    try {
      // 1. Connect
      setSyncProgress(30);
      addLog(`Autenticando usuário: ${email}...`);

      const { data: connectData, error: connectError } = await supabase.functions.invoke('woocommerce-proxy', {
        body: { action: 'connect', email, password }
      });

      if (connectError) throw connectError;
      if (connectData?.error) throw new Error(connectData.error);

      addLog('Autenticação bem-sucedida!');
      addLog(`Cliente identificado: ${connectData.customer?.first_name || 'Cliente'}`);

      setSyncProgress(60);

      // 2. Sync Data
      addLog('Buscando histórico de pedidos...');
      const { data: syncData, error: syncError } = await supabase.functions.invoke('woocommerce-proxy', {
        body: { action: 'sync', email }
      });

      if (syncError) throw syncError;

      addLog(`Encontrados ${syncData.orders?.length || 0} pedidos recentes.`);

      if (syncData.vipProducts?.length > 0) {
        addLog(`Encontrados ${syncData.vipProducts.length} produtos VIP exclusivos.`);
      } else {
        addLog('Nenhum produto VIP encontrado (verifique a categoria "vip").');
      }

      setSyncProgress(90);

      // 3. Refresh Local
      addLog('Sincronizando banco de dados local...');

      if (syncData.cashback !== undefined) {
        addLog(`💰 Saldo Cashback Atualizado: R$ ${syncData.cashback.toFixed(2)}`);
      }

      await refreshData(user.id);
      setSyncProgress(100);
      addLog('Concluído com sucesso!');

      setTimeout(() => {
        updateUser({ isStoreConnected: true });
      }, 1000);

    } catch (err: any) {
      console.error('Connection failed:', err);

      let finalMsg = err.message || 'Falha ao conectar à loja.';

      // Try to parse Supabase Edge Function error body
      if (err.context && err.context.response) {
        try {
          const body = await err.context.response.json();
          if (body?.error) finalMsg = body.error;
        } catch (e) { /* ignore json parse error */ }
      }

      addLog(`ERRO: ${finalMsg}`);
      // Keep logs visible for a moment before failing
      setTimeout(() => {
        setStep('login');
        setErrorMsg(finalMsg);
      }, 3000);
    }
  };

  const [isSyncing, setIsSyncing] = useState(false);

  const syncData = async () => {
    setIsSyncing(true);
    const originalText = 'Atualizar Dados';
    try {
      addLog('Iniciando sincronização manual...');
      await supabase.functions.invoke('woocommerce-proxy', {
        body: { action: 'sync', email: user.email }
      });
      await refreshData(user.id);
      // alert('Dados atualizados com sucesso!'); // Removed intrusive alert
    } catch (e) {
      console.error(e);
      // alert('Erro ao sincronizar.');
    } finally {
      setIsSyncing(false);
    }
  };


  useEffect(() => {
    // Only run initial sync animation if explicitly in syncing step (managed by handleConnect)
  }, [step]);

  // Auto-sync on mount
  useEffect(() => {
    if (user.isStoreConnected) {
      syncData();
    }
  }, []); // Run once on mount

  if (!user.isStoreConnected) {
    return (
      <div className="max-w-4xl mx-auto py-12 animate-fadeIn">
        <div className="bg-white rounded-[48px] p-8 md:p-16 shadow-2xl border border-analux-secondary/10 relative overflow-hidden">
          <div className="absolute top-0 right-0 w-64 h-64 bg-analux-secondary/5 rounded-full -mr-32 -mt-32 blur-3xl"></div>

          {step === 'intro' && (
            <div className="relative z-10 space-y-8 text-center">
              <div className="w-24 h-24 bg-analux-contrast rounded-[32px] mx-auto flex items-center justify-center text-analux-secondary shadow-inner">
                <ShoppingBag size={40} />
              </div>
              <div className="space-y-4">
                <h2 className="text-4xl font-serif text-analux-primary">Analux Hub Unificado</h2>
                <p className="text-gray-500 max-w-lg mx-auto leading-relaxed">
                  Conecte sua conta do e-commerce Analux para gerenciar seus pedidos avulsos, cashback e desbloquear preços exclusivos de membra.
                </p>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-left max-w-2xl mx-auto">
                {['Pedidos Integrados', 'Cashback Unificado', 'Preços VIP'].map((feat) => (
                  <div key={feat} className="flex items-center gap-3 p-4 bg-analux-contrast rounded-2xl">
                    <CheckCircle2 size={16} className="text-analux-secondary shrink-0" />
                    <span className="text-[11px] font-bold text-analux-primary uppercase tracking-tight">{feat}</span>
                  </div>
                ))}
              </div>

              <button
                onClick={() => setStep('login')}
                className="bg-analux-primary text-white px-12 py-5 rounded-3xl font-bold flex items-center justify-center gap-3 mx-auto shadow-2xl shadow-analux-primary/20 hover:scale-105 transition-all"
              >
                Começar Sincronização <ArrowRight size={20} />
              </button>
            </div>
          )}

          {step === 'login' && (
            <div className="relative z-10 max-w-md mx-auto space-y-8">
              <div className="text-center">
                <h3 className="text-2xl font-serif text-analux-primary">Acesse sua Conta da Loja</h3>
                <p className="text-sm text-gray-400 mt-2">Use o mesmo e-mail que você usa no site oficial.</p>
              </div>

              <div className="space-y-4">
                <div className="space-y-1">
                  <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">E-mail</label>
                  <div className="relative">
                    <Mail size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                    <input
                      type="email"
                      value={email}
                      onChange={e => setEmail(e.target.value)}
                      className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 transition-all outline-none"
                      placeholder="seu@email.com"
                    />
                  </div>
                </div>
                <div className="space-y-1">
                  <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Senha da Loja</label>
                  <div className="relative">
                    <Key size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                    <input
                      type="password"
                      value={password}
                      onChange={e => setPassword(e.target.value)}
                      className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-analux-secondary/20 transition-all outline-none"
                      placeholder="••••••••"
                    />
                  </div>
                </div>
              </div>

              <div className="space-y-4">
                <button
                  onClick={handleConnect}
                  disabled={!password}
                  className="w-full bg-analux-primary text-white py-5 rounded-3xl font-bold flex items-center justify-center gap-3 shadow-xl shadow-analux-primary/20 hover:scale-[1.02] transition-all disabled:opacity-50"
                >
                  Vincular Agora
                </button>
                <button onClick={() => setStep('intro')} className="w-full text-xs font-bold text-gray-400 hover:text-analux-primary uppercase">Voltar</button>
              </div>
            </div>
          )}

          {step === 'syncing' && (
            <div className="relative z-10 text-center space-y-8 py-10">
              <div className="relative w-32 h-32 mx-auto">
                <div className="absolute inset-0 border-4 border-analux-contrast rounded-full"></div>
                <div
                  className="absolute inset-0 border-4 border-analux-secondary rounded-full transition-all duration-300"
                  style={{ clipPath: `inset(0 0 0 0)`, strokeDasharray: 400, strokeDashoffset: 400 - (4 * syncProgress) }}
                ></div>
                <div className="absolute inset-0 flex items-center justify-center">
                  <RefreshCw size={32} className="text-analux-secondary animate-spin" />
                </div>
              </div>
              <div className="space-y-2">
                <h3 className="text-2xl font-serif text-analux-primary">Sincronizando Ecossistema...</h3>
                <p className="text-sm text-gray-400">Buscando pedidos, favoritos e cashback no WooCommerce Analux.</p>
              </div>
              <div className="max-w-xs mx-auto h-1.5 bg-analux-contrast rounded-full overflow-hidden">
                <div className="h-full bg-analux-secondary transition-all" style={{ width: `${syncProgress}%` }}></div>
              </div>
              <div className="flex justify-center gap-4 text-[9px] font-bold text-analux-secondary uppercase tracking-widest">
                <span className={syncProgress > 30 ? 'opacity-100' : 'opacity-20'}>Pedidos ✓</span>
                <span className={syncProgress > 60 ? 'opacity-100' : 'opacity-20'}>Fidelidade ✓</span>
                <span className={syncProgress > 90 ? 'opacity-100' : 'opacity-20'}>Ofertas ✓</span>
              </div>

              <div className="max-w-md mx-auto mt-6 bg-black/5 rounded-xl p-4 h-32 overflow-y-auto text-left">
                {logs.map((log, i) => (
                  <p key={i} className="text-[10px] font-mono text-gray-500 border-b border-gray-100 last:border-0 py-1">
                    {log}
                  </p>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>
    );
  }

  return (
    <div className="space-y-10 animate-fadeIn pb-20">
      <div className="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
        <div>
          <div className="flex items-center gap-3 mb-1">
            <h1 className="text-4xl font-serif text-analux-primary">Minhas Compras</h1>
            <span className="bg-green-100 text-green-600 text-[9px] font-bold px-2 py-0.5 rounded-full uppercase flex items-center gap-1">
              {isSyncing ? <RefreshCw size={10} className="animate-spin" /> : <CheckCircle2 size={10} />}
              {isSyncing ? 'Sincronizando...' : 'Sincronizado'}
            </span>
          </div>
          <p className="text-gray-500">Seu histórico completo de compras avulsas e recompensas.</p>
        </div>
        <div className="flex gap-2">
          {/* Tab Switcher */}
          <div className="bg-gray-100 p-1 rounded-2xl flex gap-1 h-fit self-center">
            <button
              onClick={() => setActiveTab('orders')}
              className={`px-4 py-2 rounded-xl text-xs font-bold transition-all ${activeTab === 'orders' ? 'bg-white shadow-sm text-analux-primary' : 'text-gray-400 hover:text-analux-primary'}`}
            >
              Meus Pedidos
            </button>
            <button
              onClick={() => setActiveTab('rewards')}
              className={`px-4 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1 ${activeTab === 'rewards' ? 'bg-white shadow-sm text-analux-secondary' : 'text-gray-400 hover:text-analux-secondary'}`}
            >
              <Crown size={12} />
              Clube AnaLux
            </button>
          </div>

          <button
            onClick={syncData}
            disabled={isSyncing}
            className="flex items-center gap-2 text-xs font-bold text-gray-400 bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 hover:text-analux-primary transition-all disabled:opacity-70 h-fit self-center"
          >
            <RefreshCw size={14} className={isSyncing ? "animate-spin text-analux-secondary" : ""} />
            {isSyncing ? 'Atualizando...' : 'Atualizar Dados'}
          </button>
        </div>
        <a href="https://analux.com.br" target="_blank" rel="noopener noreferrer" className="flex items-center gap-2 text-xs font-bold text-white bg-analux-secondary px-6 py-3 rounded-2xl shadow-lg shadow-analux-secondary/20 hover:scale-105 transition-all">
          Ir para Loja Oficial <ExternalLink size={14} />
        </a>
      </div>

      {/* Stats Grid */}
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div className="bg-analux-primary text-white p-8 rounded-[40px] shadow-xl relative overflow-hidden group">
          <div className="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform">
            <ShoppingBag size={120} />
          </div>
          <div className="relative z-10">
            <p className="text-[10px] uppercase font-bold tracking-widest text-analux-secondary mb-2">Histórico de Compras</p>
            <p className="text-4xl font-serif mb-1">R$ 1.240,00</p>
            <div className="flex items-center gap-2 text-white/50 text-xs mt-4">
              <div className="w-1 h-1 bg-analux-secondary rounded-full"></div>
              {user.storeData?.recentOrders?.length || 0} pedidos finalizados
            </div>
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm flex flex-col justify-between">
          <div>
            <p className="text-[10px] uppercase font-bold tracking-widest text-gray-400 mb-2 flex items-center justify-between">
              Cashback Unificado <Info size={12} className="cursor-help" title="Soma da assinatura e compras avulsas" />
            </p>
            <p className="text-4xl font-serif text-analux-primary mb-1">R$ {user.cashback.toFixed(2)}</p>
          </div>
          <div className="pt-4 flex items-center gap-1 text-[10px] text-green-500 font-bold bg-green-50 px-3 py-1.5 rounded-xl w-fit">
            {/* Show real pending cashback from backend */}
            <Zap size={12} /> + R$ {(user.pendingCashback || 0).toFixed(2)} em processamento
          </div>
        </div>
        <div className="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm flex flex-col justify-between">
          <div>
            <p className="text-[10px] uppercase font-bold tracking-widest text-gray-400 mb-2">Pontos do Club</p>
            <p className="text-4xl font-serif text-analux-primary mb-1">{user.points}</p>
          </div>
          <p className="text-[11px] text-analux-secondary font-medium italic mt-2">Troque por recompensas exclusivas no Clube Analux</p>
        </div>
      </div>
      {activeTab === 'rewards' ? (
        <LoyaltyClub />
      ) : (
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Main Feed */}
          <div className="lg:col-span-2 space-y-12">

            <section>
              <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2 mb-8 px-2">
                <ShoppingBag size={20} className="text-analux-secondary" /> Pedidos Recentes
              </h3>

              <div className="space-y-6 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                {user.storeData?.recentOrders.map(order => (
                  <div key={order.id}>
                    <div
                      className={`bg-white rounded-[32px] p-6 cursor-pointer border-2 transition-all duration-300 flex items-center justify-between ${expandedOrderId === order.id ? 'border-analux-secondary shadow-lg scale-[1.01]' : 'border-transparent shadow-sm hover:scale-[1.01]'}`}
                      onClick={() => setExpandedOrderId(expandedOrderId === order.id ? null : order.id)}
                    >
                      <div className="flex items-center gap-5">
                        <div className={`w-14 h-14 rounded-2xl flex items-center justify-center transition-colors ${expandedOrderId === order.id ? 'bg-analux-primary text-white' : 'bg-analux-contrast text-analux-primary'}`}>
                          <ShoppingBag size={24} />
                        </div>
                        <div>
                          <p className="text-sm font-bold text-analux-primary">Pedido #{order.number}</p>
                          <p className="text-[10px] text-gray-400 uppercase tracking-tighter mt-0.5">{order.date} • {order.itemsCount} itens</p>
                        </div>
                      </div>
                      <div className="flex items-center gap-8">
                        <div className="text-right">
                          <p className="text-sm font-bold text-analux-primary">R$ {order.total.toFixed(2)}</p>
                          <span className={`text-[9px] font-bold px-2 py-0.5 rounded-full uppercase ${order.status === 'Enviado' ? 'text-blue-500 bg-blue-50' : 'text-green-500 bg-green-50'
                            }`}>
                            {order.status}
                          </span>
                        </div>
                        <ChevronRight size={20} className={`text-gray-200 transition-transform duration-300 ${expandedOrderId === order.id ? 'rotate-90 text-analux-secondary' : ''}`} />
                      </div>
                    </div>

                    {/* Expanded Items */}
                    {expandedOrderId === order.id && (
                      <div className="px-6 pb-6 pt-2 bg-gray-50/50 border-t border-gray-100 animate-in slide-in-from-top-2">
                        <p className="text-[10px] uppercase font-bold text-gray-400 mb-3 tracking-widest">Itens do Pedido</p>
                        <div className="space-y-3">
                          {order.items && order.items.length > 0 ? (
                            order.items.map((item, idx) => (
                              <div key={idx} className="flex items-center gap-4 bg-white p-3 rounded-2xl shadow-sm border border-gray-100">
                                <div className="w-12 h-12 rounded-xl bg-gray-100 overflow-hidden">
                                  <img src={item.image} alt={item.name} className="w-full h-full object-cover" />
                                </div>
                                <div className="flex-1">
                                  <p className="text-xs font-bold text-analux-primary line-clamp-1">{item.name}</p>
                                  <p className="text-[10px] text-gray-400">Qtd: {item.quantity}</p>
                                </div>
                                <p className="text-xs font-bold text-analux-secondary">R$ {item.price.toFixed(2)}</p>
                              </div>
                            ))
                          ) : (
                            <p className="text-xs text-gray-400 italic">Detalhes dos itens não disponíveis para este pedido antigo.</p>
                          )}
                        </div>
                      </div>
                    )}
                  </div>
                ))}
              </div>
            </section>

            <section>
              <h3 className="text-xl font-serif text-analux-primary flex items-center gap-2 mb-8 px-2">
                <Star size={20} className="text-analux-secondary" /> Lista de Desejos
              </h3>
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-6">
                {/* Show only first 3 items in preview */}
                {user.storeData?.favorites.slice(0, 3).map(prod => (
                  <div key={prod.id} className="group cursor-pointer">
                    <div className="aspect-[3/4] rounded-3xl overflow-hidden bg-white mb-3 relative shadow-sm group-hover:shadow-lg transition-all">
                      <img src={prod.image} alt={prod.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                      <div className="absolute top-3 right-3 p-2 bg-white/90 backdrop-blur-sm rounded-full text-red-500 shadow-sm">
                        <Star size={14} className="fill-red-500 text-red-500" />
                      </div>
                    </div>
                    <div className="px-1">
                      <p className="text-[11px] font-bold text-analux-primary truncate">{prod.name}</p>
                      <div className="flex items-center justify-between mt-1">
                        <p className="text-[10px] text-analux-secondary font-bold">R$ {prod.memberPrice.toFixed(2)}</p>
                        <span className="text-[8px] text-gray-300 line-through">R$ {prod.regularPrice.toFixed(2)}</span>
                      </div>
                    </div>
                  </div>
                ))}

                {/* Show 'Mais Favoritos' button only if there are more than 3 items */}
                {(user.storeData?.favorites?.length || 0) > 3 && (
                  <div
                    onClick={() => setIsWishlistModalOpen(true)}
                    className="aspect-[3/4] rounded-3xl border-2 border-dashed border-gray-100 flex flex-col items-center justify-center text-gray-300 group hover:border-analux-secondary hover:text-analux-secondary transition-all cursor-pointer"
                  >
                    <Star size={24} className="mb-2" />
                    <span className="text-[10px] font-bold uppercase text-center px-4">Ver Todos ({user.storeData?.favorites.length})</span>
                  </div>
                )}
              </div>

              {/* Full Wishlist Modal */}
              {isWishlistModalOpen && (
                <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-analux-primary/20 backdrop-blur-xl animate-fadeIn">
                  <div className="bg-white w-full max-w-5xl h-[80vh] rounded-[40px] shadow-2xl p-10 relative animate-scaleIn flex flex-col">
                    <button
                      onClick={() => setIsWishlistModalOpen(false)}
                      className="absolute top-6 right-6 p-2 text-gray-300 hover:text-analux-primary rounded-full hover:bg-gray-50 transition-colors"
                    >
                      <Plus size={24} className="rotate-45" />
                    </button>

                    <h2 className="text-3xl font-serif text-analux-primary mb-2 flex items-center gap-3">
                      <Star className="fill-analux-secondary text-analux-secondary" />
                      Sua Lista de Desejos Completa
                    </h2>
                    <p className="text-gray-400 text-sm mb-8 ml-1">Você tem {user.storeData?.favorites.length} itens salvos.</p>

                    <div className="overflow-y-auto pr-2 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 content-start">
                      {user.storeData?.favorites.map(prod => (
                        <div key={prod.id} className="group cursor-pointer">
                          <div className="aspect-[3/4] rounded-3xl overflow-hidden bg-gray-50 mb-3 relative shadow-sm group-hover:shadow-lg transition-all">
                            <img src={prod.image} alt={prod.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div className="absolute bottom-3 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity w-[90%]">
                              <a href={`https://analux.shop/?p=${prod.id}`} target="_blank" className="block w-full text-center py-2 bg-white/90 backdrop-blur text-[9px] font-bold uppercase tracking-widest text-analux-primary rounded-full shadow-lg hover:bg-analux-secondary hover:text-white transition-colors">
                                Ver na Loja
                              </a>
                            </div>
                          </div>
                          <div className="px-1">
                            <p className="text-xs font-bold text-analux-primary line-clamp-1" title={prod.name}>{prod.name}</p>
                            <div className="flex items-center gap-2 mt-1">
                              <p className="text-xs text-analux-secondary font-bold">R$ {prod.memberPrice.toFixed(2)}</p>
                              {prod.regularPrice > prod.memberPrice && (
                                <span className="text-[10px] text-gray-300 line-through">R$ {prod.regularPrice.toFixed(2)}</span>
                              )}
                            </div>
                          </div>
                        </div>
                      ))}
                    </div>
                  </div>
                </div>
              )}
            </section>
          </div>

          {/* Sidebar Offers */}
          <div className="space-y-8">
            <div className="bg-analux-secondary/10 p-8 rounded-[48px] border border-analux-secondary/20 relative overflow-hidden shadow-sm">
              <Tag size={100} className="absolute -right-6 -bottom-6 text-analux-secondary/10 -rotate-12" />
              <h3 className="text-2xl font-serif text-analux-primary mb-2">Ofertas VIP</h3>
              <p className="text-xs text-gray-600 mb-8 leading-relaxed">Exclusivo: adicione estes itens à sua próxima box e garanta <b>condições exclusivas</b> no pedido avulso.</p>

              <div className="space-y-6 relative z-10">
                {user.storeData?.exclusiveOffers.map(offer => (
                  <div key={offer.id} className="flex gap-4 group bg-white/40 p-3 rounded-3xl hover:bg-white transition-all shadow-sm">
                    <div className="w-20 h-24 rounded-2xl overflow-hidden bg-white shrink-0 shadow-sm">
                      <img src={offer.image} alt={offer.name} className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                    </div>
                    <div className="flex flex-col justify-center flex-1">
                      <h4 className="text-[11px] font-bold text-analux-primary leading-tight mb-1">{offer.name}</h4>
                      <div className="flex items-center gap-2 mb-3">
                        <span className="text-[9px] text-gray-400 line-through">R$ {offer.regularPrice.toFixed(2)}</span>
                        <span className="text-xs font-bold text-analux-secondary">R$ {offer.memberPrice.toFixed(2)}</span>
                      </div>
                      <button
                        onClick={() => handleOfferClick(offer)}
                        disabled={loadingOfferId === offer.id}
                        className="text-[10px] font-bold text-white bg-analux-primary px-4 py-2 rounded-xl flex items-center gap-2 hover:bg-analux-dark transition-all w-fit shadow-md group-active:scale-95 cursor-pointer disabled:opacity-70"
                      >
                        {loadingOfferId === offer.id ? <RefreshCw size={12} className="animate-spin" /> : 'Aproveitar Oferta'}
                        {!loadingOfferId && <ArrowRight size={12} />}
                      </button>
                    </div>
                  </div>
                ))}
              </div>

              <div className="mt-8 pt-6 border-t border-analux-secondary/20 relative z-10">
                <a
                  href="https://analux.shop/product-category/vip/"
                  target="_blank"
                  rel="noopener noreferrer"
                  className="w-full py-4 bg-white text-analux-primary rounded-2xl text-xs font-bold shadow-sm hover:shadow-md hover:translate-y-[-2px] transition-all flex items-center justify-center gap-2"
                >
                  Explorar Coleção Completa <ExternalLink size={14} />
                </a>
              </div>
            </div>

            {/* Loyalty Reminder */}
            <div className="bg-white p-8 rounded-[40px] border border-dashed border-gray-200 text-center space-y-4 relative overflow-hidden">
              <div className="relative z-10">
                <div className="w-12 h-12 bg-analux-contrast rounded-2xl flex items-center justify-center text-analux-secondary mx-auto mb-2">
                  <Lock size={20} />
                </div>
                <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Próximo Early Access</p>
                <p className="text-sm font-serif text-analux-primary italic">Coleção de Verão "Bossa Nova"</p>
                <p className="text-[10px] text-analux-secondary font-bold mt-2">Liberado em 48h para você</p>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* Stats Grid */}



    </div >
  );
};

export default ConnectedStore;
