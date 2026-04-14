import React from 'react';
import { Package, Plus, Search, Filter, AlertTriangle } from 'lucide-react';

const AdminStock: React.FC = () => {
  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-serif text-analux-primary">Gestão de Estoque</h1>
          <p className="text-slate-500">Controle o inventário de peças e insumos.</p>
        </div>
        <button className="flex items-center gap-2 bg-analux-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all">
          <Plus size={20} />
          Novo Item
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
          <p className="text-sm text-slate-500 mb-1">Total de Itens</p>
          <p className="text-3xl font-bold text-analux-primary">1.240</p>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
          <p className="text-sm text-slate-500 mb-1">Itens em Alerta</p>
          <div className="flex items-center gap-2">
            <p className="text-3xl font-bold text-red-500">12</p>
            <AlertTriangle className="text-red-500" size={24} />
          </div>
        </div>
        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
          <p className="text-sm text-slate-500 mb-1">Valor em Estoque</p>
          <p className="text-3xl font-bold text-analux-secondary">R$ 45.300</p>
        </div>
      </div>

      <div className="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden text-slate-600">
        <div className="p-4 border-b border-slate-100 flex gap-4">
          <div className="relative flex-1">
            <Search className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" size={18} />
            <input 
              type="text" 
              placeholder="Buscar por SKU ou Nome..."
              className="w-full pl-10 pr-4 py-2 bg-slate-50 rounded-lg border-none focus:ring-2 focus:ring-analux-secondary"
            />
          </div>
          <button className="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-lg border border-slate-200">
            <Filter size={18} />
            Filtros
          </button>
        </div>
        
        <div className="p-20 text-center opacity-50">
          <Package size={48} className="mx-auto mb-4 text-slate-300" />
          <p>Módulo de estoque em fase final de conexão com o banco de dados...</p>
        </div>
      </div>
    </div>
  );
};

export default AdminStock;
