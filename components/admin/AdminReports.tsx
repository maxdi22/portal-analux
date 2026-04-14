import React from 'react';
import { FileText, Download, Calendar, Filter, PieChart, BarChart } from 'lucide-react';

const AdminReports: React.FC = () => {
  const reports = [
    { title: 'Relatório de Faturamento Mensal', type: 'Financeiro', lastGenerated: 'Há 2 dias', icon: <FileText className="text-rose-600" /> },
    { title: 'Listagem de Assinantes Ativos', type: 'Clientes', lastGenerated: 'Hoje', icon: <FileText className="text-emerald-500" /> },
    { title: 'Inventário de Peças (Box Atual)', type: 'Logística', lastGenerated: 'Há 1 semana', icon: <FileText className="text-amber-500" /> },
    { title: 'Taxa de Conversão de Indicações', type: 'Marketing', lastGenerated: 'Hoje', icon: <FileText className="text-purple-500" /> },
    { title: 'Métricas de Churn e Retenção', type: 'Estratégico', lastGenerated: 'Há 3 dias', icon: <FileText className="text-red-500" /> },
  ];

  return (
    <div className="p-6">
      <div className="flex justify-between items-center mb-8">
        <div>
          <h1 className="text-2xl font-serif text-analux-primary">Centro de Relatórios</h1>
          <p className="text-slate-500">Gere e exporte dados detalhados da operação.</p>
        </div>
      </div>

      <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {/* Left Column: Report List */}
        <div className="lg:col-span-2 space-y-4">
          <div className="flex items-center gap-4 mb-6">
            <div className="relative flex-1">
              <input 
                type="text" 
                placeholder="Buscar relatório..."
                className="w-full p-3 pl-4 bg-white rounded-xl border border-slate-200 outline-none focus:ring-2 focus:ring-analux-secondary transition-all"
              />
            </div>
            <button className="p-3 bg-white border border-slate-200 rounded-xl text-slate-400 hover:text-slate-600">
              <Filter size={20} />
            </button>
          </div>

          <div className="space-y-3">
            {reports.map((report, i) => (
              <div key={i} className="bg-white p-4 rounded-xl border border-slate-100 flex items-center justify-between hover:shadow-md transition-all group">
                <div className="flex items-center gap-4">
                  <div className="p-3 bg-slate-50 rounded-lg group-hover:scale-110 transition-transform">
                    {report.icon}
                  </div>
                  <div>
                    <h4 className="font-bold text-slate-800">{report.title}</h4>
                    <p className="text-xs text-slate-500">{report.type} • Última geração: {report.lastGenerated}</p>
                  </div>
                </div>
                <div className="flex items-center gap-2">
                  <button className="p-2 text-slate-400 hover:text-analux-secondary hover:bg-analux-secondary/5 rounded-lg transition-all" title="Visualizar">
                    <PieChart size={18} />
                  </button>
                  <button className="flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-700 rounded-lg border border-slate-200 hover:bg-slate-100 transition-all font-medium text-sm">
                    <Download size={16} />
                    PDF
                  </button>
                  <button className="flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-black transition-all font-medium text-sm">
                    <Download size={16} />
                    CSV
                  </button>
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Right Column: Custom Generation */}
        <div className="space-y-6">
          <div className="bg-analux-primary text-white p-6 rounded-2xl shadow-xl">
            <h3 className="text-lg font-bold mb-4 flex items-center gap-2">
              <Calendar size={20} />
              Gerar Relatório Customizado
            </h3>
            <div className="space-y-4">
              <div>
                <label className="block text-xs font-bold uppercase tracking-wider text-analux-secondary mb-2">Tipo de Relatório</label>
                <select className="w-full p-3 bg-white/10 border border-white/20 rounded-xl outline-none focus:bg-white/20">
                  <option className="text-slate-800">Financeiro</option>
                  <option className="text-slate-800">Logística</option>
                  <option className="text-slate-800">Estoque</option>
                  <option className="text-slate-800">Marketing</option>
                </select>
              </div>
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-analux-secondary mb-2">Início</label>
                  <input type="date" className="w-full p-3 bg-white/10 border border-white/20 rounded-xl outline-none" />
                </div>
                <div>
                  <label className="block text-xs font-bold uppercase tracking-wider text-analux-secondary mb-2">Fim</label>
                  <input type="date" className="w-full p-3 bg-white/10 border border-white/20 rounded-xl outline-none" />
                </div>
              </div>
              <button className="w-full py-4 mt-4 bg-analux-secondary text-white rounded-xl font-bold hover:brightness-110 transition-all shadow-lg shadow-black/20">
                Gerar Relatório Agora
              </button>
            </div>
          </div>

          <div className="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h3 className="font-bold text-slate-800 mb-4 flex items-center gap-2">
              <BarChart size={18} className="text-analux-secondary" />
              Métricas Rápidas
            </h3>
            <div className="space-y-4">
              <div className="flex justify-between items-center text-sm">
                <span className="text-slate-500">Taxa de Abertura</span>
                <span className="font-bold text-slate-800">72%</span>
              </div>
              <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div className="bg-emerald-500 h-full w-[72%]"></div>
              </div>
              
              <div className="flex justify-between items-center text-sm">
                <span className="text-slate-500">Retenção (M3)</span>
                <span className="font-bold text-slate-800">89%</span>
              </div>
              <div className="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                <div className="bg-analux-secondary h-full w-[89%]"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminReports;
