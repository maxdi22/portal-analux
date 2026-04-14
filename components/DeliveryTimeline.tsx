import React from 'react';
import { Box, Truck, CheckCircle, Package, RefreshCw } from 'lucide-react';

interface DeliveryTimelineProps {
    cycleStartDate: string; // The "Cycle Day" (e.g. renewal date)
    status?: 'active' | 'shipped' | 'delivered'; // Optional override
    onConfirmReceipt?: () => void;
}

const DeliveryTimeline: React.FC<DeliveryTimelineProps> = ({ cycleStartDate, status, onConfirmReceipt }) => {
    // 1. Calculate Milestones based on Cycle Start
    // Policy: Preparation immediately after cycle start. Shipping within operational days.
    const start = new Date(cycleStartDate);

    // SLA Configuration (Simulated)
    const slas = {
        prepDays: 2,
        shipDays: 5,
        deliveryDays: 12
    };

    const dates = {
        renewal: start,
        preparation: new Date(new Date(start).setDate(start.getDate() + slas.prepDays)), // Est. Prep complete
        shipping: new Date(new Date(start).setDate(start.getDate() + slas.shipDays)),   // Est. Shipping start
        delivery: new Date(new Date(start).setDate(start.getDate() + slas.deliveryDays)) // Est. Arrival
    };

    const today = new Date(); // Current system time

    // 2. Determine Active Step based on Time vs SLAs
    // (In real app, backend status is source of truth, but we simulate timeline based on policy)
    let activeStep = 1;

    if (status === 'delivered') {
        activeStep = 4;
    } else if (status === 'shipped' || today > dates.shipping) {
        activeStep = 3;
    } else if (today > dates.preparation) {
        activeStep = 2;
    } else {
        activeStep = 1;
    }

    const steps = [
        { id: 1, label: 'Renovação', icon: <RefreshCw size={18} />, date: dates.renewal, desc: 'Ciclo iniciado' },
        { id: 2, label: 'Preparação', icon: <Package size={18} />, date: dates.preparation, desc: 'Curadoria & Montagem' },
        { id: 3, label: 'Transporte', icon: <Truck size={18} />, date: dates.shipping, desc: 'Enviado para você' },
        { id: 4, label: 'Entrega', icon: <CheckCircle size={18} />, date: dates.delivery, desc: 'Previsão de chegada', isEstimate: true }
    ];

    const getStepStatus = (stepId: number) => {
        if (activeStep > stepId) return 'completed';
        if (activeStep === stepId) return 'current';
        return 'upcoming';
    };

    const formatDate = (date: Date) => {
        return new Intl.DateTimeFormat('pt-BR', { day: '2-digit', month: '2-digit' }).format(date);
    };

    return (
        <div className="w-full">
            {/* Progress Bar */}
            <div className="relative mb-8 mx-4 md:mx-10 mt-6">
                <div className="absolute top-1/2 left-0 w-full h-1 bg-gray-100 -translate-y-1/2 rounded-full z-0"></div>
                <div
                    className="absolute top-1/2 left-0 h-1 bg-analux-secondary -translate-y-1/2 rounded-full transition-all duration-1000 z-0"
                    style={{ width: `${((activeStep - 1) / 3) * 100}%` }}
                ></div>

                <div className="relative z-10 flex justify-between">
                    {steps.map((step) => {
                        const sStatus = getStepStatus(step.id);
                        return (
                            <div key={step.id} className="flex flex-col items-center group relative">
                                <div className={`w-10 h-10 rounded-full flex items-center justify-center border-4 transition-all duration-500 bg-white relative z-10 ${sStatus === 'completed' || sStatus === 'current'
                                        ? 'border-analux-secondary text-analux-secondary shadow-lg scale-110'
                                        : 'border-gray-100 text-gray-300'
                                    }`}>
                                    {step.icon}
                                </div>
                                <div className="mt-3 text-center w-24 absolute top-10 left-1/2 -translate-x-1/2">
                                    <p className={`text-[9px] font-bold uppercase tracking-widest mb-0.5 ${sStatus === 'current' ? 'text-analux-primary' : 'text-gray-400'
                                        }`}>
                                        {step.label}
                                    </p>
                                    <p className="text-[10px] text-gray-400 leading-tight">
                                        {formatDate(step.date)}
                                    </p>
                                </div>
                            </div>
                        );
                    })}
                </div>
            </div>

            <div className="h-12"></div> {/* Spacer for labels */}

            {/* Current Status Message & Action */}
            <div className="bg-analux-secondary/5 rounded-2xl p-6 border border-analux-secondary/10 flex flex-col md:flex-row items-center justify-between gap-6 transition-all">
                <div className="flex items-center gap-4">
                    <div className="p-3 bg-white rounded-xl shadow-sm text-analux-secondary animate-fade-in">
                        {activeStep === 1 && <RefreshCw size={24} className="animate-spin-slow" />}
                        {activeStep === 2 && <Package size={24} />}
                        {activeStep === 3 && <Truck size={24} />}
                        {activeStep === 4 && <CheckCircle size={24} />}
                    </div>
                    <div>
                        <h4 className="font-serif text-lg text-analux-contrast">
                            {activeStep === 1 && 'Ciclo Renovado!'}
                            {activeStep === 2 && 'Preparando seu Ritual'}
                            {activeStep === 3 && 'A caminho de você'}
                            {activeStep === 4 && 'Ritual Entregue'}
                        </h4>
                        <p className="text-sm text-gray-500">
                            {activeStep === 1 && 'Sua box entrou na fila de produção.'}
                            {activeStep === 2 && 'Nossos artesãos estão montando sua box.'}
                            {activeStep === 3 && 'Acompanhe a entrega em sua casa.'}
                            {activeStep === 4 && 'Esperamos que ame cada detalhe ✨'}
                        </p>
                    </div>
                </div>

                {activeStep === 3 && status !== 'delivered' && (
                    <button
                        onClick={onConfirmReceipt}
                        className="px-6 py-3 bg-analux-primary text-white rounded-xl font-bold uppercase text-xs tracking-widest hover:bg-analux-dark transition-all shadow-lg shadow-analux-primary/20 flex items-center gap-2 animate-pulse"
                    >
                        <CheckCircle size={16} /> Confirmar
                    </button>
                )}
            </div>
        </div>
    );
};

export default DeliveryTimeline;
