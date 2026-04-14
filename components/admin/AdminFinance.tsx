import React from 'react';
import { TrendingUp, CreditCard, DollarSign, ArrowUpRight, ArrowDownRight, Download } from 'lucide-react';
import { LineChart, Line, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, AreaChart, Area } from 'recharts';

const mockData = [
  { name: 'Jan', value: 45000 },
  { name: 'Fev', value: 52000 },
  { name: 'Mar', value: 48000 },
  { name: 'Abr', value: 61000 },
  { name: 'Mai', value: 55000 },
  { name: 'Jun', value: 67000 },
];

const AdminFinance: React.FC = () => {
  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-serif text-analux-primary">Dashboard Financeiro</h1>
          <p className="text-slate-500">Acompanhe a saúde financeira, MRR e transações.</p>
        </div>
        <button className="flex items-center gap-2 bg-slate-900 text-white px-4 py-2 rounded-lg hover:bg-black transition-all">
          <Download size={18} />
          Exportar Relatório
        </button>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        {[
          { label: 'MRR (Receita Mensal)', value: 'R$ 42.500', trend: '+12%', up: true, icon: <TrendingUp className="text-emerald-500" /> },
          { label: 'Faturamento Total', value: 'R$ 284.900', trend: '+8.4%', up: true, icon: <DollarSign className="text-analux-primary" /> },
          { label: 'Taxa de Churn', value: '2.4%', trend: '-0.5%', up: true, icon: <ArrowDownRight className="text-emerald-500" /> },
          { label: 'Ticket Médio', value: 'R$ 247,00', trend: '+2%', up: true, icon: <CreditCard className="text-amber-500" /> },
        ].map((stat, i) => (
          <div key={i} className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div className="flex justify-between items-start mb-4">
              <div className="p-2 bg-slate-50 rounded-lg">{stat.icon}</div>
              <span className={`text-xs font-bold px-2 py-1 rounded-full ${stat.up ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600'}`}>
                {stat.trend}
              </span>
            </div>
            <p className="text-sm text-slate-500 mb-1">{stat.label}</p>
            <p className="text-2xl font-bold text-slate-800">{stat.value}</p>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
          <h3 className="font-bold text-slate-800 mb-6">Crescimento de Receita (6 Meses)</h3>
          <div className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <AreaChart data={mockData}>
                <defs>
                  <linearGradient id="colorValue" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="5%" stopColor="#edd4c5" stopOpacity={0.8}/>
                    <stop offset="95%" stopColor="#edd4c5" stopOpacity={0}/>
                  </linearGradient>
                </defs>
                <CartesianGrid strokeDasharray="3 3" vertical={false} stroke="#f1f5f9" />
                <XAxis dataKey="name" axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12}} />
                <YAxis axisLine={false} tickLine={false} tick={{fill: '#94a3b8', fontSize: 12}} />
                <Tooltip 
                  contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)' }}
                />
                <Area type="monotone" dataKey="value" stroke="#d4a373" fillOpacity={1} fill="url(#colorValue)" strokeWidth={3} />
              </AreaChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
          <h3 className="font-bold text-slate-800 mb-6 font-serif">Status de Pagamento (Stripe Live)</h3>
          <div className="space-y-4">
            {[
              { id: '1', user: 'Maria Silva', plan: 'Ritual', date: 'Hoje', value: 'R$ 297,00', status: 'Pago' },
              { id: '2', user: 'Ana Paula', plan: 'Premium', date: 'Hoje', value: 'R$ 497,00', status: 'Processando' },
              { id: '3', user: 'Fernanda Oliveira', plan: 'Essencial', date: 'Ontem', value: 'R$ 147,00', status: 'Pago' },
              { id: '4', user: 'Juliana Costa', plan: 'Ritual', date: 'Ontem', value: 'R$ 297,00', status: 'Falha' },
            ].map((tx) => (
              <div key={tx.id} className="flex justify-between items-center p-3 hover:bg-slate-50 rounded-lg transition-all group">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-400 group-hover:bg-analux-secondary group-hover:text-white transition-colors">
                    {tx.user.charAt(0)}
                  </div>
                  <div>
                    <p className="font-medium text-slate-800">{tx.user}</p>
                    <p className="text-xs text-slate-500">{tx.plan} • {tx.date}</p>
                  </div>
                </div>
                <div className="text-right">
                  <p className="font-bold text-slate-800">{tx.value}</p>
                  <span className={`text-[10px] uppercase font-bold ${
                    tx.status === 'Pago' ? 'text-emerald-500' : 
                    tx.status === 'Falha' ? 'text-red-500' : 'text-amber-500'
                  }`}>
                    {tx.status}
                  </span>
                </div>
              </div>
            ))}
            <button className="w-full py-2 text-sm text-analux-primary font-medium hover:underline">Ver todas as transações</button>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminFinance;
