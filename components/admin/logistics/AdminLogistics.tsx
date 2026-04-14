import React, { useState, useEffect } from 'react';
import {
    Truck,
    Calendar,
    CheckCircle,
    AlertTriangle,
    Package,
    Search,
    RefreshCw,
    Box,
    ClipboardCheck,
    Barcode,
    ChevronRight,
    MapPin,
    Filter
} from 'lucide-react';
import { supabase } from '../../../services/supabase';

interface Shipment {
    id: string;
    user_id: string;
    status: 'PENDING' | 'ASSEMBLY' | 'QUALITY_CHECK' | 'READY' | 'SHIPPED';
    month: string;
    dispatch_group: number;
    verification_level: number;
    tracking_code?: string;
    profiles: {
        name: string;
        email: string;
        avatar?: string;
        address?: any;
    };
}

const AdminLogistics: React.FC = () => {
    const [loading, setLoading] = useState(true);
    const [generating, setGenerating] = useState(false);
    const [shipments, setShipments] = useState<Shipment[]>([]);
    const [selectedMonth, setSelectedMonth] = useState(new Date().toISOString().slice(0, 7)); // YYYY-MM
    const [selectedGroup, setSelectedGroup] = useState<number | 'all'>('all'); // 6, 10, 15 or all
    const [selectedShipment, setSelectedShipment] = useState<Shipment | null>(null); // For Modal

    useEffect(() => {
        fetchShipments();
    }, [selectedMonth]);

    const fetchShipments = async () => {
        setLoading(true);
        try {
            const { data, error } = await supabase
                .from('monthly_shipments')
                .select(`
                    *,
                    profiles (name, email, avatar, address)
                `)
                .eq('month', selectedMonth);

            if (error) throw error;
            setShipments(data || []);
        } catch (error) {
            console.error('Error fetching shipments:', error);
        } finally {
            setLoading(false);
        }
    };

    const generateShipments = async () => {
        if (!window.confirm(`Gerar remessas de envio para ${selectedMonth}?`)) return;

        setGenerating(true);
        try {
            // 1. Get all active subscriptions
            const { data: subs, error: subsError } = await supabase
                .from('subscriptions')
                .select('user_id, dispatch_day')
                .eq('status', 'ACTIVE'); // Careful with case sensitivity fixed previously? 
            // Let's assume database uses uppercase now based on imports.

            if (subsError) throw subsError;

            // 2. Filter out already existing shipments
            const existingUserIds = shipments.map(s => s.user_id);
            const newShipments = subs
                .filter(sub => !existingUserIds.includes(sub.user_id))
                .map(sub => ({
                    user_id: sub.user_id,
                    month: selectedMonth,
                    dispatch_group: sub.dispatch_day || 10, // Default to 10
                    status: 'PENDING',
                    verification_level: 0
                }));

            if (newShipments.length === 0) {
                alert('Todas as assinaturas ativas já possuem remessa para este mês.');
                return;
            }

            // 3. Insert new shipments
            const { error: insertError } = await supabase
                .from('monthly_shipments')
                .insert(newShipments);

            if (insertError) throw insertError;

            alert(`${newShipments.length} novas remessas geradas com sucesso!`);
            fetchShipments();

        } catch (error: any) {
            alert('Erro ao gerar remessas: ' + error.message);
        } finally {
            setGenerating(false);
        }
    };

    // Filter Logic
    const filteredShipments = shipments.filter(s =>
        selectedGroup === 'all' || s.dispatch_group === selectedGroup
    );

    const stats = {
        total: filteredShipments.length,
        pending: filteredShipments.filter(s => s.status === 'PENDING').length,
        assembly: filteredShipments.filter(s => s.status === 'ASSEMBLY').length,
        quality: filteredShipments.filter(s => s.status === 'QUALITY_CHECK').length,
        ready: filteredShipments.filter(s => s.status === 'READY' || s.status === 'SHIPPED').length,
    };

    return (
        <div className="space-y-8 animate-fade-in">
            {/* Header */}
            <div className="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 className="text-3xl font-bold text-slate-900 flex items-center gap-3">
                        <Truck className="text-amber-500" size={32} />
                        Centro de Expedição
                    </h1>
                    <p className="text-slate-500 mt-1">Gerenciamento de envios e workflow logístico.</p>
                </div>

                <div className="flex items-center gap-4 bg-white p-2 rounded-xl shadow-sm border border-slate-100">
                    <Calendar size={20} className="text-slate-400 ml-2" />
                    <input
                        type="month"
                        value={selectedMonth}
                        onChange={(e) => setSelectedMonth(e.target.value)}
                        className="border-none focus:ring-0 text-slate-700 font-bold bg-transparent"
                    />
                    <div className="h-6 w-px bg-slate-200"></div>
                    <button
                        onClick={generateShipments}
                        disabled={generating}
                        className="px-4 py-2 bg-slate-900 text-white rounded-lg hover:bg-slate-800 disabled:opacity-50 flex items-center gap-2 font-bold text-sm"
                    >
                        {generating ? <RefreshCw className="animate-spin" size={16} /> : <Box size={16} />}
                        Gerar Remessas
                    </button>
                </div>
            </div>

            {/* Stats Cards */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4">
                <StatCard icon={<Box />} label="A Separar" value={stats.pending} color="bg-slate-100 text-slate-600" />
                <StatCard icon={<ClipboardCheck />} label="Em Montagem" value={stats.assembly} color="bg-blue-100 text-blue-600" />
                <StatCard icon={<CheckCircle />} label="Controle Qualidade" value={stats.quality} color="bg-purple-100 text-purple-600" />
                <StatCard icon={<Truck />} label="Prontos/Enviados" value={stats.ready} color="bg-green-100 text-green-600" />
            </div>

            {/* Main Content Area */}
            <div className="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden min-h-[600px] flex flex-col">

                {/* Visual Tabs for Dispatch Groups */}
                <div className="flex border-b border-slate-100">
                    {[
                        { id: 'all', label: 'Todos os Envios' },
                        { id: 6, label: 'Corte Dia 06' },
                        { id: 10, label: 'Corte Dia 10' },
                        { id: 15, label: 'Corte Dia 15' },
                    ].map((tab) => (
                        <button
                            key={tab.id}
                            onClick={() => setSelectedGroup(tab.id as any)}
                            className={`px-6 py-4 font-bold text-sm transition-all border-b-2 ${selectedGroup === tab.id
                                ? 'border-amber-500 text-amber-500 bg-amber-50'
                                : 'border-transparent text-slate-400 hover:text-slate-600 hover:bg-slate-50'
                                }`}
                        >
                            {tab.label}
                        </button>
                    ))}
                </div>

                {/* List Header */}
                <div className="bg-slate-50 px-6 py-3 grid grid-cols-12 gap-4 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    <div className="col-span-4">Cliente</div>
                    <div className="col-span-2 text-center">Grupo</div>
                    <div className="col-span-3 text-center">Status Atual</div>
                    <div className="col-span-3 text-right">Progresso</div>
                </div>

                {/* Scrollable List */}
                <div className="flex-1 overflow-y-auto">
                    {loading ? (
                        <div className="flex flex-col items-center justify-center h-full text-slate-300 gap-4">
                            <Truck className="animate-bounce" size={48} />
                            <p>Carregando remessas...</p>
                        </div>
                    ) : filteredShipments.length === 0 ? (
                        <div className="flex flex-col items-center justify-center h-full text-slate-400 gap-4">
                            <Box size={48} className="opacity-20" />
                            <p>Nenhuma remessa encontrada para este grupo.</p>
                            <button onClick={generateShipments} className="text-amber-500 font-bold hover:underline">
                                Gerar agora?
                            </button>
                        </div>
                    ) : (
                        <div className="divide-y divide-slate-50">
                            {filteredShipments.map(shipment => (
                                <div
                                    key={shipment.id}
                                    onClick={() => setSelectedShipment(shipment)}
                                    className="px-6 py-4 grid grid-cols-12 gap-4 items-center hover:bg-slate-50 cursor-pointer group transition-colors"
                                >
                                    {/* Client Info */}
                                    <div className="col-span-4 flex items-center gap-3">
                                        <div className="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-slate-500 text-xs overflow-hidden">
                                            {shipment.profiles.avatar ? <img src={shipment.profiles.avatar} className="w-full h-full object-cover" /> : shipment.profiles.name.charAt(0)}
                                        </div>
                                        <div>
                                            <h4 className="font-bold text-slate-800 text-sm group-hover:text-amber-600 transition-colors">{shipment.profiles.name}</h4>
                                            <p className="text-xs text-slate-400 flex items-center gap-1">
                                                <MapPin size={10} />
                                                {shipment.profiles.address?.neighborhood || 'Endereço Pendente'}
                                            </p>
                                        </div>
                                    </div>

                                    {/* Dispatch Group */}
                                    <div className="col-span-2 flex justify-center">
                                        <span className="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-xs font-bold border border-slate-200">
                                            Dia {shipment.dispatch_group}
                                        </span>
                                    </div>

                                    {/* Status Badge */}
                                    <div className="col-span-3 flex justify-center">
                                        <StatusBadge status={shipment.status} />
                                    </div>

                                    {/* Progress Bar */}
                                    <div className="col-span-3 flex flex-col items-end gap-1">
                                        <div className="flex items-center gap-1 text-xs font-bold text-slate-500">
                                            Nível {shipment.verification_level} de 3
                                            <ChevronRight size={12} />
                                        </div>
                                        <div className="w-24 h-2 bg-slate-100 rounded-full overflow-hidden">
                                            <div
                                                className={`h-full transition-all duration-500 ${shipment.verification_level === 3 ? 'bg-green-500' :
                                                    shipment.verification_level === 2 ? 'bg-purple-500' :
                                                        shipment.verification_level === 1 ? 'bg-blue-500' : 'bg-slate-300'
                                                    }`}
                                                style={{ width: `${(shipment.verification_level / 3) * 100}%` }}
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            </div>

            {/* Modal for 3-Level Verification */}
            {selectedShipment && (
                <VerificationModal
                    shipment={selectedShipment}
                    onClose={() => setSelectedShipment(null)}
                    onUpdate={() => { fetchShipments(); setSelectedShipment(null); }}
                />
            )}
        </div>
    );
};

// Sub-components
const StatCard = ({ icon, label, value, color }: any) => (
    <div className="bg-white p-4 rounded-xl shadow-sm border border-slate-100 flex items-center gap-4">
        <div className={`p-3 rounded-lg ${color}`}>{icon}</div>
        <div>
            <p className="text-slate-400 text-xs font-bold uppercase">{label}</p>
            <p className="text-2xl font-bold text-slate-900">{value}</p>
        </div>
    </div>
);

const StatusBadge = ({ status }: { status: string }) => {
    const map: any = {
        'PENDING': { color: 'bg-slate-100 text-slate-500', label: 'Aguardando' },
        'ASSEMBLY': { color: 'bg-blue-50 text-blue-600 border-blue-100', label: 'Em Separação' },
        'QUALITY_CHECK': { color: 'bg-purple-50 text-purple-600 border-purple-100', label: 'Conferência' },
        'READY': { color: 'bg-green-50 text-green-600 border-green-100', label: 'Pronto p/ Envio' },
        'SHIPPED': { color: 'bg-emerald-100 text-emerald-800 border-emerald-200', label: 'Despachado' },
    };
    const s = map[status] || map['PENDING'];
    return (
        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${s.color}`}>
            {s.label}
        </span>
    );
};

// Verification Modal (The Nerve Center Workflow)
const VerificationModal = ({ shipment, onClose, onUpdate }: any) => {
    const [step, setStep] = useState(shipment.verification_level + 1);
    const [loading, setLoading] = useState(false);
    const [trackingCode, setTrackingCode] = useState(shipment.tracking_code || '');

    const advanceLevel = async (newStatus: string, level: number) => {
        setLoading(true);
        try {
            const updates: any = { status: newStatus, verification_level: level };
            if (level === 3) updates.tracking_code = trackingCode;

            const { error } = await supabase
                .from('monthly_shipments')
                .update(updates)
                .eq('id', shipment.id);

            if (error) throw error;
            onUpdate();
        } catch (err: any) {
            alert("Erro: " + err.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="fixed inset-0 z-[60] bg-black/60 flex items-center justify-center p-4 backdrop-blur-sm animate-fade-in">
            <div className="bg-white w-full max-w-2xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                {/* Header */}
                <div className="bg-slate-900 text-white p-6 flex justify-between items-start">
                    <div>
                        <div className="flex items-center gap-2 mb-2 opactiy-80">
                            <span className="bg-white/20 px-2 py-0.5 rounded text-xs">Grupo Dia {shipment.dispatch_group}</span>
                            <span className="text-slate-400 text-xs">#{shipment.id.slice(0, 8)}</span>
                        </div>
                        <h2 className="text-2xl font-bold">{shipment.profiles.name}</h2>
                        <p className="text-slate-400 text-sm">Validando Box de {shipment.month}</p>
                    </div>
                    <button onClick={onClose} className="p-2 hover:bg-white/10 rounded-full transition-colors">
                        <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                {/* Progress Steps */}
                <div className="flex border-b border-slate-100 bg-slate-50">
                    {[
                        { lvl: 1, label: '1. Separação', icon: <Package size={16} /> },
                        { lvl: 2, label: '2. Qualidade', icon: <ClipboardCheck size={16} /> },
                        { lvl: 3, label: '3. Expedição', icon: <Truck size={16} /> },
                    ].map(s => (
                        <div key={s.lvl} className={`flex-1 p-4 flex items-center justify-center gap-2 text-sm font-bold border-b-2 transition-colors ${s.lvl === step ? 'border-amber-500 text-slate-900 bg-white' :
                            s.lvl < step ? 'border-green-500 text-green-600' : 'border-transparent text-slate-300'
                            }`}>
                            {s.lvl < step ? <CheckCircle size={16} /> : s.icon}
                            {s.label}
                        </div>
                    ))}
                </div>

                {/* Body Content */}
                <div className="p-8 flex-1 overflow-y-auto">
                    {step === 1 && (
                        <div className="text-center space-y-6">
                            <div className="w-20 h-20 bg-blue-50 text-blue-600 rounded-full mx-auto flex items-center justify-center mb-4">
                                <Package size={40} />
                            </div>
                            <h3 className="text-xl font-bold text-slate-800">Iniciar Separação</h3>
                            <p className="text-slate-600 max-w-sm mx-auto">Confira o perfil de estilo do cliente e separe os itens da caixa.</p>

                            <div className="bg-slate-50 p-4 rounded-xl text-left text-sm space-y-2 border border-slate-100">
                                <p><strong>Perfil:</strong> Casual / Moderno</p>
                                <p><strong>Tamanho:</strong> M / 38</p>
                                <p><strong>Restrições:</strong> Nenhuma</p>
                            </div>

                            <button
                                onClick={() => advanceLevel('ASSEMBLY', 1)}
                                disabled={loading}
                                className="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-blue-500/20 transition-all flex items-center justify-center gap-2"
                            >
                                {loading ? <RefreshCw className="animate-spin" /> : <CheckCircle />}
                                Confirmar Separação
                            </button>
                        </div>
                    )}

                    {step === 2 && (
                        <div className="text-center space-y-6">
                            <div className="w-20 h-20 bg-purple-50 text-purple-600 rounded-full mx-auto flex items-center justify-center mb-4">
                                <ClipboardCheck size={40} />
                            </div>
                            <h3 className="text-xl font-bold text-slate-800">Conferência de Qualidade</h3>
                            <p className="text-slate-600 max-w-sm mx-auto">Verifique se os itens não possuem defeitos e se a apresentação está impecável.</p>

                            <div className="grid grid-cols-2 gap-3">
                                <label className="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-slate-50">
                                    <input type="checkbox" className="w-5 h-5 text-amber-500 rounded focus:ring-amber-500" />
                                    <span className="font-medium text-slate-700">Sem Defeitos</span>
                                </label>
                                <label className="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-slate-50">
                                    <input type="checkbox" className="w-5 h-5 text-amber-500 rounded focus:ring-amber-500" />
                                    <span className="font-medium text-slate-700">Cheirinho Aplicado</span>
                                </label>
                                <label className="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-slate-50">
                                    <input type="checkbox" className="w-5 h-5 text-amber-500 rounded focus:ring-amber-500" />
                                    <span className="font-medium text-slate-700">Carta Inclusa</span>
                                </label>
                                <label className="flex items-center gap-3 p-4 border rounded-xl cursor-pointer hover:bg-slate-50">
                                    <input type="checkbox" className="w-5 h-5 text-amber-500 rounded focus:ring-amber-500" />
                                    <span className="font-medium text-slate-700">Lacre Fechado</span>
                                </label>
                            </div>

                            <button
                                onClick={() => advanceLevel('QUALITY_CHECK', 2)}
                                disabled={loading}
                                className="w-full py-4 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-purple-500/20 transition-all flex items-center justify-center gap-2"
                            >
                                {loading ? <RefreshCw className="animate-spin" /> : <CheckCircle />}
                                Aprovar Qualidade
                            </button>
                        </div>
                    )}

                    {step === 3 && (
                        <div className="text-center space-y-6">
                            <div className="w-20 h-20 bg-green-50 text-green-600 rounded-full mx-auto flex items-center justify-center mb-4">
                                <Barcode size={40} />
                            </div>
                            <h3 className="text-xl font-bold text-slate-800">Expedição Final</h3>
                            <p className="text-slate-600 max-w-sm mx-auto">Insira o código de rastreio para finalizar o processo.</p>

                            <div className="relative">
                                <Barcode className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" />
                                <input
                                    type="text"
                                    placeholder="Código de Rastreio (ou Bipar)"
                                    className="w-full pl-12 pr-4 py-4 text-lg font-mono border-2 border-slate-200 rounded-xl focus:border-green-500 focus:ring-0 outline-none transition-colors"
                                    value={trackingCode}
                                    onChange={e => setTrackingCode(e.target.value)}
                                    autoFocus
                                />
                            </div>

                            <button
                                onClick={() => advanceLevel('SHIPPED', 3)}
                                disabled={loading || !trackingCode}
                                className="w-full py-4 bg-green-600 hover:bg-green-700 text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-green-500/20 transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                {loading ? <RefreshCw className="animate-spin" /> : <Truck />}
                                Finalizar Envio
                            </button>
                        </div>
                    )}

                    {step > 3 && (
                        <div className="text-center py-10">
                            <div className="w-24 h-24 bg-green-100 text-green-600 rounded-full mx-auto flex items-center justify-center mb-6 animate-scale-in">
                                <CheckCircle size={48} />
                            </div>
                            <h3 className="text-2xl font-bold text-slate-900 mb-2">Processo Concluído!</h3>
                            <p className="text-slate-500">Box despachada com sucesso.</p>
                            <button onClick={onClose} className="mt-8 px-8 py-3 bg-slate-100 font-bold rounded-lg hover:bg-slate-200">Fechar</button>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default AdminLogistics;
