import React from 'react';
import { X, CheckCircle2, ShieldCheck, ArrowRight, Loader2 } from 'lucide-react';
import { useUser } from '../context/UserContext';

interface SubscriptionReviewModalProps {
    onClose: () => void;
    onConfirm: () => Promise<void> | void;
    planName: string;
    cycle: 'monthly' | 'quarterly' | 'semiannual';
    price: string;
    perks: string[];
}

const SubscriptionReviewModal: React.FC<SubscriptionReviewModalProps> = ({
    onClose,
    onConfirm,
    planName,
    cycle,
    price,
    perks
}) => {
    const { user, updateUser } = useUser();
    const [step, setStep] = React.useState<1 | 2>(1);
    const [loading, setLoading] = React.useState(false);

    // Helper to sanitize mock data
    const getCleanValue = (key: string, userValue: string | undefined, mockValue: string = '') => {
        const saved = localStorage.getItem(key);
        // If saved value acts like mock, ignore it
        if (saved && (saved === 'Alameda das Joias' || saved === '01419-001' || saved === '123' || saved === 'Jardins')) return '';
        if (saved) return saved;

        // If user profile has mock data, ignore it
        if (userValue && (userValue === 'Alameda das Joias' || userValue === '01419-001' || userValue === '123' || userValue === 'Jardins' || userValue === 'SP')) return '';

        return userValue || '';
    };

    // Address State - Init from LocalStorage or User Profile (Sanitized)
    const [cep, setCep] = React.useState(() => getCleanValue('sub_draft_cep', user.address?.zipCode, '01419-001'));
    const [street, setStreet] = React.useState(() => getCleanValue('sub_draft_street', user.address?.street, 'Alameda das Joias'));
    const [number, setNumber] = React.useState(() => getCleanValue('sub_draft_number', user.address?.number, '123'));
    const [neighborhood, setNeighborhood] = React.useState(() => getCleanValue('sub_draft_neighborhood', user.address?.neighborhood, 'Jardins'));
    const [city, setCity] = React.useState(() => getCleanValue('sub_draft_city', user.address?.city, 'São Paulo'));
    const [state, setState] = React.useState(() => getCleanValue('sub_draft_state', user.address?.state, 'SP'));
    const [complement, setComplement] = React.useState(() => getCleanValue('sub_draft_complement', user.address?.complement));
    const [deliveryTime, setDeliveryTime] = React.useState<'morning' | 'afternoon' | 'commercial'>(
        (localStorage.getItem('sub_draft_time') as any) || 'commercial'
    );

    // Persist draft state
    React.useEffect(() => {
        localStorage.setItem('sub_draft_cep', cep);
        localStorage.setItem('sub_draft_street', street);
        localStorage.setItem('sub_draft_number', number);
        localStorage.setItem('sub_draft_neighborhood', neighborhood);
        localStorage.setItem('sub_draft_city', city);
        localStorage.setItem('sub_draft_state', state);
        localStorage.setItem('sub_draft_complement', complement);
        localStorage.setItem('sub_draft_time', deliveryTime);
    }, [cep, street, number, neighborhood, city, state, complement, deliveryTime]);

    const handleNext = () => setStep(2);

    const handleFinalize = async () => {
        setLoading(true);
        try {
            await updateUser({
                address: {
                    zipCode: cep,
                    street,
                    number,
                    neighborhood,
                    city,
                    state,
                    complement
                }
            });

            // Clear draft only on success
            localStorage.removeItem('sub_draft_cep');
            localStorage.removeItem('sub_draft_street');
            localStorage.removeItem('sub_draft_number');
            localStorage.removeItem('sub_draft_neighborhood');
            localStorage.removeItem('sub_draft_city');
            localStorage.removeItem('sub_draft_state');
            localStorage.removeItem('sub_draft_complement');
            localStorage.removeItem('sub_draft_time');

            // Await the redirect trigger.
            // If successful, we do NOT set loading to false; we let the page unload.
            await onConfirm();

        } catch (error) {
            console.error("Failed to save address", error);
            setLoading(false); // Only stop loading if error occurred
        }
    };

    const handleCepBlur = async () => {
        if (cep.length === 8) {
            try {
                const response = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
                const data = await response.json();
                if (!data.erro) {
                    setStreet(data.logradouro);
                    setNeighborhood(data.bairro);
                    setCity(data.localidade);
                    setState(data.uf);
                }
            } catch (error) {
                console.error("ViaCEP error", error);
            }
        }
    };

    return (
        <div className="fixed inset-0 z-[110] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm animate-fadeIn">
            <div className="bg-white w-full max-w-lg rounded-[40px] shadow-2xl p-8 relative animate-scaleIn overflow-hidden">
                <button
                    onClick={() => {
                        // Force clear draft so it doesn't reopen
                        localStorage.removeItem('sub_draft_active_plan');
                        onClose();
                    }}
                    className="absolute top-6 right-6 p-2 text-gray-400 hover:text-analux-primary hover:bg-gray-50 rounded-full transition-all z-20"
                >
                    <X size={20} />
                </button>

                {step === 1 ? (
                    <>
                        <div className="text-center mb-6">
                            <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-analux-secondary bg-analux-secondary/10 px-3 py-1 rounded-full">
                                Passo 1 de 2
                            </span>
                            <h3 className="text-3xl font-serif text-analux-primary mt-4">Resumo do Plano</h3>
                        </div>

                        <div className="bg-analux-contrast rounded-2xl p-6 mb-6 border border-analux-secondary/10 relative overflow-hidden">
                            {/* Plan Details from original code */}
                            <div className="absolute top-0 right-0 w-20 h-20 bg-analux-secondary/10 rounded-full -mr-10 -mt-10 blur-xl"></div>
                            <div className="flex justify-between items-start mb-4 relative z-10">
                                <div>
                                    <p className="text-xs text-gray-400 font-bold uppercase tracking-widest">Plano Escolhido</p>
                                    <h4 className="text-xl font-serif text-analux-primary mt-1">{planName}</h4>
                                </div>
                                <div className="text-right">
                                    <p className="text-xs text-gray-400 font-bold uppercase tracking-widest">Frequência</p>
                                    <p className="text-sm font-medium text-analux-primary mt-1 capitalize">{cycle === 'monthly' ? 'Mensal' : cycle === 'quarterly' ? 'Trimestral' : 'Semestral'}</p>
                                </div>
                            </div>
                            <div className="border-t border-gray-200 my-4 border-dashed"></div>
                            <div className="flex justify-between items-end relative z-10">
                                <span className="text-sm text-gray-500">Valor Total</span>
                                <div className="text-right">
                                    <span className="text-2xl font-serif text-analux-primary">R$ {price}</span>
                                    <span className="text-xs text-gray-400 block">/mês</span>
                                </div>
                            </div>
                        </div>

                        <div className="space-y-3 mb-8 pl-2">
                            {perks.slice(0, 3).map((perk, idx) => (
                                <div key={idx} className="flex items-center gap-3 text-sm text-gray-600">
                                    <CheckCircle2 size={16} className="text-analux-secondary shrink-0" />
                                    <span>{perk}</span>
                                </div>
                            ))}
                            {perks.length > 3 && (
                                <p className="text-xs text-gray-400 pl-7 italic">+ {perks.length - 3} outros benefícios exclusivos</p>
                            )}
                        </div>

                        <button
                            onClick={handleNext}
                            className="w-full bg-analux-primary text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-analux-primary/20 hover:bg-analux-dark hover:scale-[1.02] transition-all flex items-center justify-center gap-2"
                        >
                            Continuar para Entrega <ArrowRight size={16} />
                        </button>
                    </>
                ) : (
                    <>
                        <div className="text-center mb-6">
                            <span className="text-[10px] font-bold uppercase tracking-[0.2em] text-analux-secondary bg-analux-secondary/10 px-3 py-1 rounded-full">
                                Passo 2 de 2
                            </span>
                            <h3 className="text-2xl font-serif text-analux-primary mt-4">Onde vamos entregar?</h3>
                            <p className="text-xs text-gray-400 max-w-xs mx-auto mt-2">Informe o endereço para receber sua Box exclusiva com total segurança.</p>
                        </div>

                        <form onSubmit={(e) => { e.preventDefault(); handleFinalize(); }} className="space-y-3 animate-fadeIn">
                            <div className="flex gap-3">
                                <input
                                    placeholder="CEP (00000-000)"
                                    className="w-1/3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50"
                                    value={cep}
                                    onChange={(e) => setCep(e.target.value.replace(/\D/g, '').slice(0, 8))}
                                    onBlur={handleCepBlur}
                                    required
                                />
                                <input
                                    placeholder="Cidade"
                                    className="w-2/3 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50"
                                    value={city}
                                    onChange={(e) => setCity(e.target.value)}
                                    readOnly // Populated via CEP usually, but let's allow edit if needed or keep readonly for safety? Let's allow edit.
                                    required
                                />
                            </div>

                            <div className="flex gap-3">
                                <input
                                    placeholder="Endereço (Rua, Av...)"
                                    className="w-3/4 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50"
                                    value={street}
                                    onChange={(e) => setStreet(e.target.value)}
                                    required
                                />
                                <input
                                    placeholder="Nº"
                                    className="w-1/4 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50"
                                    value={number}
                                    onChange={(e) => setNumber(e.target.value)}
                                    required
                                />
                            </div>

                            <div className="flex gap-3">
                                <input
                                    placeholder="Bairro"
                                    className="w-1/2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50"
                                    value={neighborhood}
                                    onChange={(e) => setNeighborhood(e.target.value)}
                                    required
                                />
                                <input
                                    placeholder="Estado (UF)"
                                    className="w-1/2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50 uppercase"
                                    value={state}
                                    onChange={(e) => setState(e.target.value.slice(0, 2).toUpperCase())}
                                    required
                                />
                            </div>

                            <input
                                placeholder="Complemento (Apto, Bloco...)"
                                className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm outline-none focus:border-analux-secondary/50"
                                value={complement}
                                onChange={(e) => setComplement(e.target.value)}
                            />

                            <div className="pt-2">
                                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1 mb-2 block">Melhor Horário de Entrega</label>
                                <div className="grid grid-cols-3 gap-2">
                                    {(['morning', 'afternoon', 'commercial'] as const).map((t) => (
                                        <button
                                            key={t}
                                            type="button"
                                            onClick={() => setDeliveryTime(t)}
                                            className={`py-2 rounded-lg text-xs font-medium border transition-all ${deliveryTime === t
                                                ? 'bg-analux-primary text-white border-analux-primary'
                                                : 'bg-white text-gray-500 border-gray-200 hover:border-analux-primary/30'
                                                }`}
                                        >
                                            {t === 'morning' && 'Manhã'}
                                            {t === 'afternoon' && 'Tarde'}
                                            {t === 'commercial' && 'Comercial'}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            <button
                                type="submit"
                                disabled={loading}
                                className="w-full bg-analux-secondary text-analux-primary py-4 rounded-2xl font-bold uppercase text-xs tracking-widest shadow-lg hover:brightness-110 transition-all flex items-center justify-center gap-2 mt-4 disabled:opacity-80 disabled:cursor-wait"
                            >
                                {loading ? (
                                    <>
                                        Redirecionando...
                                        <Loader2 size={16} className="animate-spin" />
                                    </>
                                ) : (
                                    <>
                                        Ir para Pagamento
                                        <ShieldCheck size={16} />
                                    </>
                                )}
                            </button>
                            <button
                                type="button"
                                onClick={() => setStep(1)}
                                disabled={loading}
                                className="w-full py-2 text-xs text-gray-400 hover:text-analux-primary transition-colors"
                            >
                                Voltar
                            </button>
                        </form>
                    </>
                )}
            </div>
        </div>
    );
};

export default SubscriptionReviewModal;
