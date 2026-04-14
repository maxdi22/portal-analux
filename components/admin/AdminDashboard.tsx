import React from 'react';
import {
    Users,
    DollarSign,
    Package,
    TrendingUp,
    AlertCircle,
    Clock
} from 'lucide-react';

const AdminDashboard: React.FC = () => {
    return (
        <div className="space-y-8">
            <div>
                <h1 className="text-2xl font-bold text-slate-900">Dashboard Executivo</h1>
                <p className="text-slate-500">Visão geral do negócio e operações</p>
            </div>

            {/* KPI Cards */}
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
                    <div className="flex justify-between items-start mb-4">
                        <div className="p-3 bg-green-100 text-green-600 rounded-lg">
                            <DollarSign size={24} />
                        </div>
                        <span className="text-xs font-semibold text-green-600 bg-green-50 px-2 py-1 rounded-full">+12%</span>
                    </div>
                    <span className="text-slate-500 text-sm font-medium">MRR (Mensal)</span>
                    <span className="text-3xl font-bold text-slate-800">R$ 48.500</span>
                </div>

                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
                    <div className="flex justify-between items-start mb-4">
                        <div className="p-3 bg-analux-primary/10 text-analux-primary rounded-lg">
                            <Users size={24} />
                        </div>
                        <span className="text-xs font-semibold text-rose-500 bg-rose-50 px-2 py-1 rounded-full">+24 novos</span>
                    </div>
                    <span className="text-slate-500 text-sm font-medium">Assinantes Ativos</span>
                    <span className="text-3xl font-bold text-slate-800">1,248</span>
                </div>

                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
                    <div className="flex justify-between items-start mb-4">
                        <div className="p-3 bg-purple-100 text-purple-600 rounded-lg">
                            <Package size={24} />
                        </div>
                        <span className="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-1 rounded-full">85 Pendentes</span>
                    </div>
                    <span className="text-slate-500 text-sm font-medium">Envios da Mês</span>
                    <span className="text-3xl font-bold text-slate-800">856/1100</span>
                </div>

                <div className="bg-white p-6 rounded-xl shadow-sm border border-slate-100 flex flex-col">
                    <div className="flex justify-between items-start mb-4">
                        <div className="p-3 bg-red-100 text-red-600 rounded-lg">
                            <TrendingUp size={24} className="rotate-180" />
                        </div>
                        <span className="text-xs font-semibold text-red-600 bg-red-50 px-2 py-1 rounded-full">2.1%</span>
                    </div>
                    <span className="text-slate-500 text-sm font-medium">Churn Rate</span>
                    <span className="text-3xl font-bold text-slate-800">1.8%</span>
                </div>
            </div>

            {/* Main Content Grid */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {/* Recent Activity / Envios em Risco */}
                <div className="lg:col-span-2 bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col">
                    <div className="p-6 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="font-bold text-slate-800 flex items-center gap-2">
                            <AlertCircle size={20} className="text-amber-500" />
                            Atenção Operacional
                        </h3>
                        <button className="text-sm text-analux-primary hover:text-black font-medium">Ver tudo</button>
                    </div>
                    <div className="p-0">
                        <table className="w-full text-sm text-left">
                            <thead className="bg-slate-50 text-slate-500 font-medium">
                                <tr>
                                    <th className="px-6 py-3">Evento</th>
                                    <th className="px-6 py-3">Cliente</th>
                                    <th className="px-6 py-3">Status</th>
                                    <th className="px-6 py-3">Ação</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-slate-100">
                                <tr>
                                    <td className="px-6 py-4">Falha Pagamento</td>
                                    <td className="px-6 py-4 font-medium">Mariana Silva</td>
                                    <td className="px-6 py-4"><span className="bg-red-100 text-red-600 px-2 py-1 rounded text-xs font-bold">RETRY 2/3</span></td>
                                    <td className="px-6 py-4"><button className="text-analux-primary font-medium hover:underline">Resolver</button></td>
                                </tr>
                                <tr>
                                    <td className="px-6 py-4">Endereço Inválido</td>
                                    <td className="px-6 py-4 font-medium">Carla Dias</td>
                                    <td className="px-6 py-4"><span className="bg-amber-100 text-amber-600 px-2 py-1 rounded text-xs font-bold">Retido</span></td>
                                    <td className="px-6 py-4"><button className="text-analux-primary font-medium hover:underline">Verificar</button></td>
                                </tr>
                                <tr>
                                    <td className="px-6 py-4">Churn Risco Alto</td>
                                    <td className="px-6 py-4 font-medium">Beatriz Costa</td>
                                    <td className="px-6 py-4"><span className="bg-orange-100 text-orange-600 px-2 py-1 rounded text-xs font-bold">NPS 4</span></td>
                                    <td className="px-6 py-4"><button className="text-analux-primary font-medium hover:underline">Contactar</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {/* Status Envio Box */}
                <div className="bg-white rounded-xl shadow-sm border border-slate-100 flex flex-col">
                    <div className="p-6 border-b border-slate-100">
                        <h3 className="font-bold text-slate-800 flex items-center gap-2">
                            <Package size={20} className="text-purple-600" />
                            Edição Atual: "Noite de Gala"
                        </h3>
                    </div>
                    <div className="p-6 space-y-6">
                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-slate-500">Montagem</span>
                                <span className="font-bold text-slate-700">92%</span>
                            </div>
                            <div className="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div className="h-full bg-analux-primary w-[92%]"></div>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-slate-500">Etiquetagem</span>
                                <span className="font-bold text-slate-700">65%</span>
                            </div>
                            <div className="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div className="h-full bg-purple-500 w-[65%]"></div>
                            </div>
                        </div>

                        <div className="space-y-2">
                            <div className="flex justify-between text-sm">
                                <span className="text-slate-500">Expedição</span>
                                <span className="font-bold text-slate-700">12%</span>
                            </div>
                            <div className="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div className="h-full bg-green-500 w-[12%]"></div>
                            </div>
                        </div>

                        <div className="pt-4 border-t border-slate-100">
                            <div className="flex items-center gap-3 text-slate-500 text-sm">
                                <Clock size={16} />
                                <span>Próximo envio em <strong>5 dias</strong></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    );
};

export default AdminDashboard;
