import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import { X, ArrowRight, ArrowLeft, CheckCircle2, Sparkles, User, Heart, Star, ShieldCheck } from 'lucide-react';
import { StyleProfile } from '../types';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';

interface OnboardingModalProps {
    onClose: () => void;
    onComplete: () => void;
    isEditing?: boolean;
}

const steps = [
    { id: 1, title: 'Sobre Você', icon: <User size={20} /> },
    { id: 2, 'title': 'Seu Estilo', icon: <Sparkles size={20} /> },
    { id: 3, title: 'Preferências', icon: <Heart size={20} /> },
    { id: 4, title: 'Detalhes', icon: <Star size={20} /> },
    { id: 5, title: 'Revisão', icon: <ShieldCheck size={20} /> }
];

const OnboardingModal: React.FC<OnboardingModalProps> = ({ onClose, onComplete, isEditing = false }) => {
    const { user, updateUser } = useUser();
    const [currentStep, setCurrentStep] = useState(1);
    const [loading, setLoading] = useState(false);

    const [formData, setFormData] = useState<StyleProfile>({
        fullName: user.styleProfile?.fullName || user.name || '',
        birthDate: user.styleProfile?.birthDate || '',
        acquisitionChannel: user.styleProfile?.acquisitionChannel || '',
        styles: user.styleProfile?.styles || [],
        plating: user.styleProfile?.plating || 'Dourado',
        necklaces: user.styleProfile?.necklaces || [],
        earrings: user.styleProfile?.earrings || [],
        earringHoles: user.styleProfile?.earringHoles || '',
        earringOther: user.styleProfile?.earringOther || '',
        bracelets: user.styleProfile?.bracelets || [],
        braceletOther: user.styleProfile?.braceletOther || '',
        rings: user.styleProfile?.rings || [],
        ringSize: user.styleProfile?.ringSize || '',
        ringOther: user.styleProfile?.ringOther || '',
        anklets: user.styleProfile?.anklets || [],
        ankletSize: user.styleProfile?.ankletSize || '',
        elements: user.styleProfile?.elements || [],
        religious: user.styleProfile?.religious || [],
        sign: user.styleProfile?.sign || '',
        coloredZirconia: user.styleProfile?.coloredZirconia || false,
        coloredZirconiaColor: user.styleProfile?.coloredZirconiaColor || '',
        pets: user.styleProfile?.pets || '',
        children: user.styleProfile?.children || '',
        favoriteColors: user.styleProfile?.favoriteColors || '',
        observations: user.styleProfile?.observations || ''
    });

    const updateField = (field: keyof StyleProfile, value: any) => {
        setFormData(prev => ({ ...prev, [field]: value }));
    };

    const updateArrayField = (field: keyof StyleProfile, value: string) => {
        setFormData(prev => {
            const currentArray = prev[field] as string[];
            if (currentArray.includes(value)) {
                return { ...prev, [field]: currentArray.filter(item => item !== value) };
            } else {
                return { ...prev, [field]: [...currentArray, value] };
            }
        });
    };

    const handleNext = () => {
        if (currentStep < 5) {
            setCurrentStep(prev => prev + 1);
            // Scroll to top of modal content
            document.getElementById('modal-content')?.scrollTo(0, 0);
        } else {
            handleSubmit();
        }
    };

    const handleBack = () => {
        if (currentStep > 1) {
            setCurrentStep(prev => prev - 1);
        }
    };

    const handleSubmit = async () => {
        setLoading(true);
        try {
            // Simulate backend save
            await new Promise(resolve => setTimeout(resolve, 1500));

            // Update User Context
            // Logic for 5th business day rule would be backend-side, here we just show success
            if (user) {
                await updateUser({
                    styleProfile: formData,
                    onboardingCompleted: true
                });
            }

            onComplete();
        } catch (error) {
            console.error('Error saving profile:', error);
            alert('Erro ao salvar perfil. Tente novamente.');
        } finally {
            setLoading(false);
        }
    };

    // Helper Inputs
    const RenderCheckboxGroup = ({ options, field }: { options: string[], field: keyof StyleProfile }) => (
        <div className="grid grid-cols-1 md:grid-cols-2 gap-3">
            {options.map(opt => (
                <button
                    key={opt}
                    onClick={() => updateArrayField(field, opt)}
                    className={`text-left p-4 rounded-xl border text-sm transition-all flex items-center justify-between group ${(formData[field] as string[]).includes(opt)
                        ? 'bg-analux-secondary/10 border-analux-secondary text-analux-primary font-bold'
                        : 'bg-gray-50 border-gray-100 text-gray-500 hover:border-analux-secondary/30'
                        }`}
                >
                    {opt}
                    {(formData[field] as string[]).includes(opt) && <CheckCircle2 size={16} className="text-analux-secondary" />}
                </button>
            ))}
        </div>
    );

    return ReactDOM.createPortal(
        <div className="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-analux-primary/90 backdrop-blur-md animate-fadeIn">
            <div className="bg-white w-full max-w-4xl h-[90vh] md:h-auto md:max-h-[90vh] rounded-[40px] shadow-2xl flex flex-col md:flex-row overflow-hidden relative animate-scaleIn">

                {/* Sidebar Steps (Desktop) */}
                <div className="hidden md:flex flex-col w-72 bg-analux-contrast p-8 border-r border-gray-100 relative">
                    <button onClick={onClose} className="absolute top-6 left-6 p-2 rounded-full hover:bg-black/5 transition-colors">
                        <X size={20} className="text-gray-400" />
                    </button>

                    <div className="mb-10 mt-8">
                        <span className="text-xs font-bold text-analux-secondary uppercase tracking-widest">{isEditing ? 'Editar Perfil' : 'Setup de Estilo'}</span>
                        <h2 className="text-2xl font-serif text-analux-primary mt-2">D N A <br />Analux</h2>
                    </div>

                    <div className="space-y-6 relative">
                        {/* Progress Line */}
                        <div className="absolute left-[19px] top-4 bottom-4 w-0.5 bg-gray-200 -z-10"></div>

                        {steps.map((step) => (
                            <div key={step.id} className="flex items-center gap-4 relative">
                                <div className={`w-10 h-10 rounded-full flex items-center justify-center border-2 transition-all ${currentStep >= step.id
                                    ? 'bg-analux-secondary border-analux-secondary text-white shadow-lg'
                                    : 'bg-white border-gray-200 text-gray-300'
                                    }`}>
                                    {step.icon}
                                </div>
                                <div className={currentStep === step.id ? 'opacity-100' : 'opacity-40'}>
                                    <p className="text-[10px] uppercase font-bold tracking-widest text-analux-primary">{step.title}</p>
                                    <p className="text-[9px] text-gray-400">Passo {step.id}</p>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="mt-auto pt-8">
                        <p className="text-[10px] text-gray-400 leading-relaxed italic">
                            "Preencha com carinho. Sua box será o reflexo dessas escolhas."
                        </p>
                    </div>
                </div>

                {/* Content Area */}
                <div className="flex-1 flex flex-col bg-white overflow-hidden">
                    {/* Mobile Header */}
                    <div className="md:hidden p-6 border-b border-gray-100 flex justify-between items-center bg-white sticky top-0 z-10">
                        <div className="flex items-center gap-4">
                            <button onClick={onClose}>
                                <X size={24} className="text-gray-400" />
                            </button>
                            <div>
                                <span className="text-[10px] font-bold text-analux-secondary uppercase tracking-widest">Passo {currentStep} de 5</span>
                                <h3 className="text-xl font-serif text-analux-primary">{steps[currentStep - 1].title}</h3>
                            </div>
                        </div>
                        <div className="w-10 h-10 bg-analux-contrast rounded-full flex items-center justify-center text-analux-secondary">
                            {steps[currentStep - 1].icon}
                        </div>
                    </div>
                    {/* Scrollable Form */}
                    <div id="modal-content" className="flex-1 overflow-y-auto p-6 md:p-10 space-y-8">

                        {/* STEP 1: PERSONAL INFO */}
                        {currentStep === 1 && (
                            <div className="space-y-6 animate-fadeIn">
                                <div className="bg-analux-contrast/50 p-6 rounded-3xl border border-analux-secondary/10 mb-8">
                                    <h3 className="text-lg font-serif text-analux-primary mb-2">Olá, {user.name.split(' ')[0]}! ✨</h3>
                                    <p className="text-sm text-gray-500">Estamos muito felizes em ter você aqui. Para começarmos, confirme alguns dados para que nossa curadoria te conheça melhor.</p>
                                </div>

                                <div className="space-y-4">
                                    <div>
                                        <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nome Completo</label>
                                        <input
                                            value={formData.fullName}
                                            onChange={e => updateField('fullName', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-analux-secondary transition-colors"
                                        />
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Data de Nascimento</label>
                                        <input
                                            type="date"
                                            value={formData.birthDate}
                                            onChange={e => updateField('birthDate', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-analux-secondary transition-colors"
                                        />
                                    </div>

                                    <div>
                                        <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Como conheceu a AnaLux Box?</label>
                                        <select
                                            value={formData.acquisitionChannel}
                                            onChange={e => updateField('acquisitionChannel', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 outline-none focus:border-analux-secondary transition-colors appearance-none"
                                        >
                                            <option value="">Selecione...</option>
                                            <option value="instagram">Instagram</option>
                                            <option value="tiktok">TikTok</option>
                                            <option value="indicacao">Indicação de Amiga</option>
                                            <option value="outros">Outros</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* STEP 2: ESTILO */}
                        {currentStep === 2 && (
                            <div className="space-y-8 animate-fadeIn">
                                <div>
                                    <h3 className="text-xl font-serif text-analux-primary mb-1">Qual estilo melhor te define?</h3>
                                    <p className="text-xs text-gray-400 mb-6">Você pode selecionar mais de um.</p>
                                    <RenderCheckboxGroup
                                        field="styles"
                                        options={['Clássico', 'Moderno', 'Romântico', 'Minimalista', 'Fashionista', 'Boho', 'Casual', 'Glam', 'Gosto de tudo']}
                                    />
                                </div>

                                <div className="border-t border-gray-100 pt-8">
                                    <h3 className="text-xl font-serif text-analux-primary mb-4">Banho de Semijoia Preferido</h3>
                                    <div className="grid grid-cols-3 gap-4">
                                        {['Dourado', 'Prateado', 'Todos'].map(opt => (
                                            <button
                                                key={opt}
                                                onClick={() => updateField('plating', opt)}
                                                className={`p-4 rounded-xl border text-sm transition-all flex flex-col items-center gap-2 ${formData.plating === opt
                                                    ? 'bg-analux-primary text-white border-analux-primary shadow-lg'
                                                    : 'bg-white border-gray-200 text-gray-500 hover:border-analux-primary/30'
                                                    }`}
                                            >
                                                <span className="font-bold">{opt}</span>
                                            </button>
                                        ))}
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* STEP 3: PREFERÊNCIAS (LONGO) */}
                        {currentStep === 3 && (
                            <div className="space-y-10 animate-fadeIn">

                                {/* Colares */}
                                <div>
                                    <h3 className="text-lg font-serif text-analux-primary mb-4 flex items-center gap-2"><Sparkles size={16} className="text-analux-secondary" /> Colares</h3>
                                    <RenderCheckboxGroup
                                        field="necklaces"
                                        options={[
                                            'Curtos/Chokers', 'Médios', 'Longos', 'Delicados e finos', 'Robustos',
                                            'Pingente grande', 'Pingente médio', 'Pingente pequeno',
                                            'Zircônia colorida', 'Zircônia branca', 'Gravatinha',
                                            'Colar duplo', 'Pérola', 'Aro', 'Elos', 'Preciso de extensor', 'Não uso colar'
                                        ]}
                                    />
                                </div>

                                {/* Brincos */}
                                <div className="border-t border-gray-100 pt-8">
                                    <h3 className="text-lg font-serif text-analux-primary mb-4 flex items-center gap-2"><Sparkles size={16} className="text-analux-secondary" /> Brincos</h3>
                                    <RenderCheckboxGroup
                                        field="earrings"
                                        options={[
                                            'Pequenos e discretos', 'Médios', 'Grandes e marcantes',
                                            'Argolinhas lisas', 'Cravejadas', 'Com pingente pequeno', 'Com pingente médio',
                                            'Compridos', 'Franja', 'Ear cuff', 'Ear hook', 'Fixo', 'Grandes',
                                            'Amassados', 'Ponto de luz', 'Pérola', 'Piercing fake junto',
                                            'Torcida', 'Quadrada', 'Trio', 'Não uso'
                                        ]}
                                    />
                                    <div className="mt-4 grid grid-cols-2 gap-4">
                                        <input
                                            placeholder="Qtd. de furos"
                                            value={formData.earringHoles}
                                            onChange={e => updateField('earringHoles', e.target.value)}
                                            className="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm"
                                        />
                                        <input
                                            placeholder="Outro estilo de brinco..."
                                            value={formData.earringOther}
                                            onChange={e => updateField('earringOther', e.target.value)}
                                            className="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 text-sm"
                                        />
                                    </div>
                                </div>

                                {/* Pulseiras/Aneis Compacto */}
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 pt-8">
                                    <div>
                                        <h3 className="text-lg font-serif text-analux-primary mb-4">Pulseiras</h3>
                                        <div className="flex flex-col gap-2">
                                            {['Bracelete fino', 'Bracelete largo', 'Com pingente', 'Pulseira fina', 'Larga', 'Elos', 'Não uso'].map(opt => (
                                                <label key={opt} className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-analux-primary">
                                                    <input
                                                        type="checkbox"
                                                        checked={(formData.bracelets as string[]).includes(opt)}
                                                        onChange={() => updateArrayField('bracelets', opt)}
                                                        className="rounded text-analux-secondary focus:ring-analux-secondary"
                                                    />
                                                    {opt}
                                                </label>
                                            ))}
                                            <input placeholder="Outro tipo..." className="mt-2 text-sm bg-gray-50 p-2 rounded-lg" value={formData.braceletOther} onChange={e => updateField('braceletOther', e.target.value)} />
                                        </div>
                                    </div>

                                    <div>
                                        <h3 className="text-lg font-serif text-analux-primary mb-4">Anéis</h3>
                                        <div className="flex flex-col gap-2">
                                            {['Duplo', 'Com zircônias', 'Lisos', 'Detalhados', 'Falange', 'Largo', 'Dedinho', 'Não uso'].map(opt => (
                                                <label key={opt} className="flex items-center gap-2 text-sm text-gray-600 cursor-pointer hover:text-analux-primary">
                                                    <input
                                                        type="checkbox"
                                                        checked={(formData.rings as string[]).includes(opt)}
                                                        onChange={() => updateArrayField('rings', opt)}
                                                        className="rounded text-analux-secondary focus:ring-analux-secondary"
                                                    />
                                                    {opt}
                                                </label>
                                            ))}
                                            <div className="grid grid-cols-2 gap-2 mt-2">
                                                <input placeholder="Tamanho" className="text-sm bg-gray-50 p-2 rounded-lg" value={formData.ringSize} onChange={e => updateField('ringSize', e.target.value)} />
                                                <input placeholder="Outro..." className="text-sm bg-gray-50 p-2 rounded-lg" value={formData.ringOther} onChange={e => updateField('ringOther', e.target.value)} />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* STEP 4: DETALHES & ELEMENTOS */}
                        {currentStep === 4 && (
                            <div className="space-y-8 animate-fadeIn">
                                <div>
                                    <h3 className="text-xl font-serif text-analux-primary mb-4">Elementos Preferidos</h3>
                                    <div className="flex flex-wrap gap-2">
                                        {[
                                            'Olho grego', 'Iniciais', 'Signos', 'Lua', 'Estrela', 'Místicos',
                                            'Medalhas', 'Sol', 'Coração', 'Sereia', 'Concha', 'Borboleta', 'Flor',
                                            'Raio', 'Geométricos', 'Cadeado', 'Chave', 'Cobra', 'Urso', 'Smile',
                                            'Pena', 'Yin Yang', 'Cetim', 'Cogumelo', 'Anjo', 'Natureza',
                                            'Escritas', 'Animais', 'Laço', 'Vinho/Taça', 'Trevo'
                                        ].map(elem => (
                                            <button
                                                key={elem}
                                                onClick={() => updateArrayField('elements', elem)}
                                                className={`px-4 py-2 rounded-full text-xs font-bold uppercase tracking-wide border transition-all ${formData.elements.includes(elem)
                                                    ? 'bg-analux-secondary text-white border-analux-secondary'
                                                    : 'bg-white border-gray-200 text-gray-400 hover:border-analux-primary/20'
                                                    }`}
                                            >
                                                {elem}
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                <div className="border-t border-gray-100 pt-8">
                                    <h3 className="text-lg font-serif text-analux-primary mb-4">Peças Religiosas</h3>
                                    <div className="flex flex-wrap gap-3">
                                        {['Católica', 'Evangélica', 'Umbanda', 'Espírita', 'Não quero peças religiosas', 'Outras'].map(opt => (
                                            <button
                                                key={opt}
                                                onClick={() => updateArrayField('religious', opt)}
                                                className={`px-4 py-2 rounded-lg text-sm border transition-all ${formData.religious.includes(opt)
                                                    ? 'bg-analux-primary text-white border-analux-primary'
                                                    : 'bg-white border-gray-200 text-gray-500'
                                                    }`}
                                            >
                                                {opt}
                                            </button>
                                        ))}
                                    </div>
                                </div>

                                <div className="border-t border-gray-100 pt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div className="space-y-4">
                                        <input
                                            placeholder="Seu Signo"
                                            value={formData.sign}
                                            onChange={e => updateField('sign', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
                                        />
                                        <div className="bg-gray-50 p-4 rounded-xl border border-gray-100">
                                            <label className="flex items-center gap-2 mb-2">
                                                <input
                                                    type="checkbox"
                                                    checked={formData.coloredZirconia}
                                                    onChange={e => updateField('coloredZirconia', e.target.checked)}
                                                />
                                                <span className="text-sm font-medium">Gosta de Zircônias Coloridas?</span>
                                            </label>
                                            {formData.coloredZirconia && (
                                                <input
                                                    placeholder="Quais cores prefere?"
                                                    value={formData.coloredZirconiaColor}
                                                    onChange={e => updateField('coloredZirconiaColor', e.target.value)}
                                                    className="w-full bg-white border border-gray-200 rounded-lg px-3 py-2 text-sm mt-2"
                                                />
                                            )}
                                        </div>
                                    </div>
                                    <div className="space-y-4">
                                        <input
                                            placeholder="Tem Pets? (Nome e Raça)"
                                            value={formData.pets}
                                            onChange={e => updateField('pets', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
                                        />
                                        <input
                                            placeholder="Tem Filhos? (Nome/Gênero)"
                                            value={formData.children}
                                            onChange={e => updateField('children', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
                                        />
                                        <input
                                            placeholder="Cores Favoritas"
                                            value={formData.favoriteColors}
                                            onChange={e => updateField('favoriteColors', e.target.value)}
                                            className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3"
                                        />
                                    </div>
                                </div>

                                <div className="border-t border-gray-100 pt-8">
                                    <label className="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Observações Finais</label>
                                    <textarea
                                        placeholder="Conte algo mais sobre seu estilo, preferências ou o que achar importante..."
                                        className="w-full bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 h-24 resize-none focus:outline-none focus:border-analux-secondary"
                                        value={formData.observations}
                                        onChange={e => updateField('observations', e.target.value)}
                                    />
                                </div>
                            </div>
                        )}

                        {/* STEP 5: REVIEW */}
                        {currentStep === 5 && (
                            <div className="space-y-6 animate-fadeIn">
                                <div className="text-center mb-8">
                                    <div className="w-16 h-16 bg-analux-secondary/10 text-analux-secondary rounded-full flex items-center justify-center mx-auto mb-4">
                                        <CheckCircle2 size={32} />
                                    </div>
                                    <h3 className="text-2xl font-serif text-analux-primary">Tudo Pronto para Enviar?</h3>
                                    <p className="text-sm text-gray-500 max-w-md mx-auto mt-2">
                                        Revise suas respostas. Ao confirmar, criaremos seu DNA de Estilo. Você poderá editar posteriormente na área de configurações.
                                    </p>
                                    <div className="mt-4 bg-yellow-50 border border-yellow-200 p-3 rounded-lg inline-block">
                                        <p className="text-[10px] text-yellow-700 font-bold uppercase tracking-wide">
                                            ⚠️ Alterações aplicadas apenas no próximo envio se feitas até o 5º dia útil.
                                        </p>
                                    </div>
                                </div>

                                <div className="bg-gray-50 rounded-2xl p-6 border border-gray-100 space-y-4 text-sm text-gray-600">
                                    <div className="grid grid-cols-2 gap-4">
                                        <div><span className="font-bold block">Estilos:</span> {formData.styles.join(', ') || '-'}</div>
                                        <div><span className="font-bold block">Banho:</span> {formData.plating}</div>
                                        <div><span className="font-bold block">Colares:</span> {formData.necklaces.join(', ') || '-'}</div>
                                        <div><span className="font-bold block">Brincos:</span> {formData.earrings.join(', ') || '-'}</div>
                                    </div>
                                    <div className="border-t border-gray-200 pt-4">
                                        <span className="font-bold block mb-1">Observações:</span>
                                        <p className="italic">{formData.observations || 'Nenhuma observação.'}</p>
                                    </div>
                                </div>
                            </div>
                        )}

                    </div>

                    {/* Footer Actions */}
                    <div className="p-6 md:p-8 border-t border-gray-100 flex justify-between bg-white">
                        <button
                            onClick={handleBack}
                            disabled={currentStep === 1}
                            className="flex items-center gap-2 text-sm font-bold text-gray-400 hover:text-analux-primary disabled:opacity-30 disabled:cursor-not-allowed px-4 py-2"
                        >
                            <ArrowLeft size={16} /> Voltar
                        </button>

                        <button
                            onClick={handleNext}
                            disabled={loading}
                            className="bg-analux-primary text-white px-8 py-3 rounded-xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-analux-primary/20 hover:bg-analux-dark hover:scale-105 transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-wait"
                        >
                            {loading ? 'Salvando...' : currentStep === 5 ? 'Confirmar DNA' : 'Próximo'}
                            {!loading && currentStep < 5 && <ArrowRight size={16} />}
                        </button>
                    </div>
                </div>
            </div>
        </div>,
        document.body
    );
};

export default OnboardingModal;
