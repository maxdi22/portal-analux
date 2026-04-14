import React from 'react';
import { BarChart2, Users, UserMinus, Heart, HelpCircle, ArrowRight } from 'lucide-react';
import { BarChart, Bar, XAxis, YAxis, CartesianGrid, Tooltip, ResponsiveContainer, Cell } from 'recharts';

const churnData = [
  { reason: 'Preço', count: 45, color: '#f87171' },
  { reason: 'Muitas Joias', count: 32, color: '#fb923c' },
  { reason: 'Estilo', count: 28, color: '#fbbf24' },
  { reason: 'Financeiro', count: 54, color: '#ef4444' },
  { reason: 'Outros', count: 18, color: '#94a3b8' },
];

const AdminRetention: React.FC = () => {
  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-serif text-analux-primary">Análise de Retenção</h1>
          <p className="text-slate-500">Entenda por que as usuárias ficam e por que algumas saem.</p>
        </div>
      </div>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {[
          { label: 'Saúde da Base', value: 'Excelente', sub: '92% renovação', icon: <Heart className="text-pink-500" /> },
          { label: 'Cancelamentos (Mês)', value: '14', sub: '-2 vs mês passado', icon: <UserMinus className="text-slate-400" /> },
          { label: 'Reativações', value: '5', sub: '+12% de sucesso', icon: <Users className="text-emerald-500" /> },
        ].map((stat, i) => (
          <div key={i} className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
            <div className="flex items-center gap-4 mb-2">
              <div className="p-2 bg-slate-50 rounded-lg">{stat.icon}</div>
              <p className="text-sm font-medium text-slate-500">{stat.label}</p>
            </div>
            <p className="text-3xl font-bold text-slate-800">{stat.value}</p>
            <p className="text-xs text-slate-400 mt-1">{stat.sub}</p>
          </div>
        ))}
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
          <h3 className="font-bold text-slate-800 mb-6 flex items-center justify-between">
            Motivos de Cancelamento (Churn)
            <HelpCircle size={16} className="text-slate-300" title="Dados coletados no fluxo de saída" />
          </h3>
          <div className="h-64">
            <ResponsiveContainer width="100%" height="100%">
              <BarChart data={churnData} layout="vertical" margin={{ left: 20 }}>
                <CartesianGrid strokeDasharray="3 3" horizontal={true} vertical={false} stroke="#f1f5f9" />
                <XAxis type="number" hide />
                <YAxis dataKey="reason" type="category" axisLine={false} tickLine={false} tick={{fill: '#475569', fontSize: 12}} />
                <Tooltip 
                  cursor={{fill: 'transparent'}}
                  contentStyle={{ borderRadius: '12px', border: 'none', boxShadow: '0 10px 15px -3px rgb(0 0 0 / 0.1)' }}
                />
                <Bar dataKey="count" radius={[0, 4, 4, 0]} barSize={30}>
                  {churnData.map((entry, index) => (
                    <Cell key={`cell-${index}`} fill={entry.color} />
                  ))}
                </Bar>
              </BarChart>
            </ResponsiveContainer>
          </div>
        </div>

        <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
          <h3 className="font-bold text-slate-800 mb-6">Sugestões de Retenção (IA Analux)</h3>
          <div className="flex-1 space-y-4">
            {[
              { title: 'Promoção de Upgrade', desc: 'Oferecer 3 meses de Ritual pelo preço de Essencial para usuárias com 6+ meses.', impact: 'Alto' },
              { title: 'Reativação Preditiva', desc: 'Identificar usuárias que não abrem o portal há 30 dias e enviar "mimo digital".', impact: 'Médio' },
              { title: 'Pesquisa de Estilo', desc: 'Usuárias alegando "Estilo" como motivo podem precisar de nova curadoria.', impact: 'Alto' },
            ].map((tip, i) => (
              <div key={i} className="p-4 bg-slate-50 rounded-xl border border-slate-100 group hover:border-analux-secondary transition-all">
                <div className="flex justify-between items-start mb-2">
                  <span className="text-xs font-bold uppercase tracking-wider text-analux-secondary">{tip.impact} Impacto</span>
                  <ArrowRight size={16} className="text-slate-300 group-hover:translate-x-1 transition-transform" />
                </div>
                <h4 className="font-bold text-slate-800 mb-1">{tip.title}</h4>
                <p className="text-sm text-slate-500 leading-relaxed">{tip.desc}</p>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminRetention;
