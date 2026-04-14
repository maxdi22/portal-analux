import React from 'react';
import { Layers, Edit3, Trash2, Eye, Plus, CheckCircle2 } from 'lucide-react';

const AdminPlans: React.FC = () => {
  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-serif text-analux-primary">Planos & Produtos</h1>
          <p className="text-slate-500">Gerencie os níveis de assinatura e preços vinculados ao Stripe.</p>
        </div>
        <button className="flex items-center gap-2 bg-analux-primary text-white px-4 py-2 rounded-lg hover:bg-opacity-90 transition-all">
          <Plus size={20} />
          Novo Plano
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
        {[
          { id: 'essencial', name: 'Analux Essencial', price: 'R$ 147', color: 'bg-emerald-50', text: 'text-emerald-700', border: 'border-emerald-100' },
          { id: 'ritual', name: 'Analux Ritual', price: 'R$ 297', color: 'bg-analux-secondary/10', text: 'text-analux-primary', border: 'border-analux-secondary/20' },
          { id: 'premium', name: 'Analux Premium', price: 'R$ 497', color: 'bg-amber-50', text: 'text-amber-700', border: 'border-amber-100' },
        ].map((plan) => (
          <div key={plan.id} className={`bg-white rounded-2xl border ${plan.border} overflow-hidden shadow-sm hover:shadow-md transition-all`}>
            <div className={`p-6 ${plan.color} border-b ${plan.border}`}>
              <div className="flex justify-between items-start mb-4">
                <div className="p-2 bg-white rounded-lg shadow-sm">
                  <Layers className={plan.text} size={24} />
                </div>
                <span className="text-xs font-bold uppercase tracking-widest text-slate-400">Ativo</span>
              </div>
              <h3 className={`text-xl font-bold ${plan.text} mb-1 font-serif`}>{plan.name}</h3>
              <p className="text-3xl font-bold text-slate-800">{plan.price}<span className="text-sm font-normal text-slate-400">/mês</span></p>
            </div>
            
            <div className="p-6">
              <div className="space-y-3 mb-8">
                <div className="flex items-center gap-2 text-sm text-slate-600">
                  <CheckCircle2 size={16} className="text-emerald-500" />
                  <span>Acesso ao Portal</span>
                </div>
                <div className="flex items-center gap-2 text-sm text-slate-600">
                  <CheckCircle2 size={16} className="text-emerald-500" />
                  <span>Acervo Digital</span>
                </div>
                <div className="flex items-center gap-2 text-sm text-slate-500 opacity-50 italic">
                  + 12 benefícios configurados...
                </div>
              </div>

              <div className="flex gap-2 pt-4 border-t border-slate-50">
                <button className="flex-1 flex items-center justify-center gap-2 py-2 text-sm font-medium border border-slate-200 rounded-lg hover:bg-slate-50 transition-colors">
                  <Edit3 size={16} />
                  Editar
                </button>
                <button className="px-3 py-2 text-slate-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all">
                  <Trash2 size={16} />
                </button>
              </div>
            </div>
          </div>
        ))}
      </div>

      <div className="mt-12 bg-slate-50 p-8 rounded-2xl border border-dashed border-slate-200 text-center">
        <p className="text-slate-500 max-w-md mx-auto">
          Os planos criados aqui são sincronizados com o **Stripe Customer Portal**. 
          Certifique-se de configurar os IDs de preço corretamente nas configurações.
        </p>
      </div>
    </div>
  );
};

export default AdminPlans;
