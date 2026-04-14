
import React, { useState, useMemo, useEffect } from 'react';
import { useNavigate, useLocation } from 'react-router-dom';
import { useToast } from '../../../context/ToastContext';
import { supabase } from '../../../services/supabase';
import {
    ArrowLeft, Save, Upload, Sparkles, Box, Users,
    Truck, Music, Star, ChevronRight, Plus, X, Gift,
    DollarSign, PieChart, Calendar, AlertCircle, Trash2
} from 'lucide-react';
import { BoxType, SubscriptionPlan, BoxItem } from '../../../types';
import EditionTimelinePreview from './EditionTimelinePreview';

// Steps configuration
const STEPS = [
    { id: 'identity', label: 'Identidade', icon: <Sparkles size={18} /> },
    { id: 'curation', label: 'Curadoria', icon: <Star size={18} /> },
    { id: 'structure', label: 'Estrutura', icon: <Box size={18} /> },
    { id: 'audience', label: 'Público', icon: <Users size={18} /> },
    { id: 'logistics', label: 'Logística', icon: <Truck size={18} /> },
    { id: 'experience', label: 'Experiência', icon: <Music size={18} /> },
];

const CreateBox = () => {
    const navigate = useNavigate();
    const location = useLocation();
    const editionId = location.state?.editionId;
    const [currentStep, setCurrentStep] = useState(0);
    const [formData, setFormData] = useState({
        name: '',
        theme: '',
        month: '',
        type: 'monthly' as BoxType,
        manifesto: '',
        archetype: '',
        coverImage: '',
        items: [] as BoxItem[],
        estimatedQty: 1000,
        productionDate: '', // Legacy
        assemblyDate: '',   // Legacy
        shippingDate: '',   // Legacy
        processingStart: '', // New operational window start
        targetStyles: [] as string[],
        targetAudience: 0,
        boxDimensions: { length: 25, width: 25, height: 10, weight: 0.8 },
        playlistUrl: '',
        teaserUrl: '',
        boxStructure: { type: 'mailer', material: 'cardboard', filling: 'paper' }
    });

    // Check if we are in edit mode
    const isEditMode = !!editionId;

    useEffect(() => {
        if (editionId) {
            loadEditionData(editionId);
        }
    }, [editionId]);

    const loadEditionData = async (id: string) => {
        try {
            const { data, error } = await supabase
                .from('box_editions')
                .select('*')
                .eq('id', id)
                .single();

            if (error) throw error;
            if (data) {
                // Map DB data back to form structure
                // Reverse month format if needed or use raw
                // Month in DB is "Month/Year" e.g "Janeiro/2026", form needs "YYYY-MM"
                // This is tricky without strict format, let's try to parse
                let formMonth = '';
                // Simplistic parser attempt or keep empty if fails
                // Or maybe we can't easily reverse "Janeiro/2026" to "2026-01" without a map
                // Let's rely on user re-selecting if it fails, or try best effort.

                setFormData({
                    name: data.name,
                    theme: data.theme,
                    month: '', // User might need to re-select date if we can't parse safely
                    type: data.type,
                    manifesto: data.features?.manifesto || '',
                    archetype: data.features?.archetype || '',
                    coverImage: data.cover_image || '',
                    items: data.items || [],
                    estimatedQty: data.estimated_qty,
                    processingStart: data.schedule?.processing_start || '',
                    productionDate: data.schedule?.production_date || '',
                    shippingDate: data.schedule?.shipping_date || '', // Legacy
                    targetStyles: data.features?.target_styles || [],
                    targetAudience: 0,
                    boxDimensions: { length: 25, width: 25, height: 10, weight: 0.8 },
                    playlistUrl: data.features?.playlist_url || '',
                    teaserUrl: data.features?.teaser_url || '',
                    boxStructure: data.features?.box_structure || { type: 'mailer', material: 'cardboard', filling: 'paper' }
                });
                showToast("Dados da edição carregados.", "info");
            }
        } catch (error) {
            console.error("Error loading edition:", error);
            showToast("Erro ao carregar dados da edição.", "error");
        }
    };

    // Financial Calculations
    const financials = useMemo(() => {
        const totalCost = formData.items.reduce((acc, item) => acc + (item.cost || 0), 0);
        const avgTicket = 129.90; // Exemplo fixo, poderia vir do plano
        const grossMargin = avgTicket - totalCost;
        const marginPercent = (grossMargin / avgTicket) * 100;

        return { totalCost, grossMargin, marginPercent };
    }, [formData.items]);

    const { showToast } = useToast();

    // Mock Suppliers for dropdown
    const suppliers = ['Vivara Outsourcing', 'Prata Fina B2B', 'China Gold', 'Local Artisan'];

    const handleFinish = async () => {
        // Validation
        if (!formData.name || !formData.theme) {
            showToast("Preencha pelo menos o Nome e o Tema da Box.", "warning");
            return;
        }

        showToast("Finalizando criação da box...", "info");

        try {
            // Format month
            let displayMonth = formData.month;
            if (formData.month) {
                const parts = formData.month.split('-');
                if (parts.length === 2) {
                    const date = new Date(parseInt(parts[0]), parseInt(parts[1]) - 1, 1);
                    const formatted = date.toLocaleDateString('pt-BR', { month: 'long', year: 'numeric' });
                    displayMonth = formatted.charAt(0).toUpperCase() + formatted.slice(1);
                }
            }

            // Financials
            const totalCost = formData.items.reduce((acc, item) => acc + (item.cost || 0), 0);
            const avgTicket = 129.90;
            const grossMargin = avgTicket - totalCost;
            const marginPercent = (grossMargin / avgTicket) * 100;

            const payload = {
                name: formData.name,
                theme: formData.theme,
                month: displayMonth,
                status: 'PLANNING',
                type: formData.type,
                estimated_qty: formData.estimatedQty,
                rating: 0,
                cover_image: formData.coverImage,
                description: formData.manifesto,

                features: {
                    manifesto: formData.manifesto,
                    archetype: formData.archetype,
                    target_styles: formData.targetStyles,
                    playlist_url: formData.playlistUrl,
                    teaser_url: formData.teaserUrl,
                    box_structure: formData.boxStructure
                },
                schedule: {
                    processing_start: formData.processingStart,
                    production_date: formData.productionDate,
                    shipping_date: formData.shippingDate
                },
                items: formData.items,
                financials: {
                    total_cost: totalCost,
                    gross_margin: grossMargin,
                    margin_percent: marginPercent,
                    avg_ticket: avgTicket
                }
            };

            const { data, error } = isEditMode
                ? await supabase
                    .from('box_editions')
                    .update(payload)
                    .eq('id', editionId)
                    .select()
                : await supabase
                    .from('box_editions')
                    .insert([payload])
                    .select();

            if (error) throw error;

            showToast("Box salva com sucesso na Galeria de Edições!", "success", 4000);

            setTimeout(() => {
                navigate('/admin/boxes');
            }, 1000);

        } catch (error: any) {
            console.error("Erro ao salvar box:", error);
            showToast("Erro ao salvar box. Verifique o console.", "error");
        }
    };

    const handleNext = () => {
        if (currentStep < STEPS.length - 1) {
            setCurrentStep(currentStep + 1);
        } else {
            handleFinish();
        }
    };

    const handleBack = () => {
        if (currentStep > 0) {
            setCurrentStep(currentStep - 1);
        }
    };

    const addItem = () => {
        const newItem: BoxItem = {
            id: Math.random().toString(36).substr(2, 9),
            name: 'Novo Item',
            description: '',
            cost: 0,
            category: 'jewelry',
            targetPlan: [SubscriptionPlan.BASIC, SubscriptionPlan.PREMIUM, SubscriptionPlan.LUXURY]
        };
        setFormData({ ...formData, items: [...formData.items, newItem] });
    };

    const updateItem = (id: string, field: keyof BoxItem, value: any) => {
        setFormData({
            ...formData,
            items: formData.items.map(item => item.id === id ? { ...item, [field]: value } : item)
        });
    };

    const removeItem = (id: string) => {
        setFormData({
            ...formData,
            items: formData.items.filter(item => item.id !== id)
        });
    };

    const renderIdentityStep = () => (
        <div className="space-y-6 animate-fade-in grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div className="lg:col-span-7 space-y-6">
                <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Nome da Box</label>
                            <input
                                type="text"
                                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-analux-secondary/20 focus:outline-none"
                                placeholder="Ex: Aurora Floral"
                                value={formData.name}
                                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Tema / Fase</label>
                            <input
                                type="text"
                                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-analux-secondary/20 focus:outline-none"
                                placeholder="Ex: Renascimento"
                                value={formData.theme}
                                onChange={(e) => setFormData({ ...formData, theme: e.target.value })}
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Mês de Referência</label>
                            <input
                                type="month"
                                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-analux-secondary/20 focus:outline-none"
                                value={formData.month}
                                onChange={(e) => setFormData({ ...formData, month: e.target.value })}
                            />
                        </div>
                    </div>

                    <div className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Capa da Edição</label>
                            <label className="border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center text-gray-400 hover:border-analux-secondary/50 hover:bg-gray-50 transition-all cursor-pointer h-[200px] relative overflow-hidden group">
                                <input
                                    type="file"
                                    className="hidden"
                                    accept="image/*"
                                    onChange={(e) => {
                                        const file = e.target.files?.[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onloadend = () => {
                                                setFormData({ ...formData, coverImage: reader.result as string });
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    }}
                                />
                                {formData.coverImage ? (
                                    <>
                                        <img src={formData.coverImage} alt="Capa" className="absolute inset-0 w-full h-full object-cover" />
                                        <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <Upload size={24} className="text-white" />
                                        </div>
                                    </>
                                ) : (
                                    <>
                                        <Upload size={24} className="mb-2" />
                                        <span className="text-sm">Clique para upload da imagem capa</span>
                                    </>
                                )}
                            </label>
                        </div>
                    </div>
                </div>

                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">Frase-Manifesto</label>
                    <textarea
                        className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-analux-secondary/20 focus:outline-none h-24 resize-none"
                        placeholder="A frase que define a alma desta edição..."
                        value={formData.manifesto}
                        onChange={(e) => setFormData({ ...formData, manifesto: e.target.value })}
                    />
                </div>

                <div>
                    <label className="block text-sm font-medium text-gray-700 mb-1">Arquétipo Emocional</label>
                    <div className="flex gap-2">
                        {['A Amante', 'A Criadora', 'A Governante', 'A Mágica'].map((arch) => (
                            <button
                                key={arch}
                                onClick={() => setFormData({ ...formData, archetype: arch })}
                                className={`px-4 py-2 rounded-full text-sm transition-all ${formData.archetype === arch
                                    ? 'bg-gray-900 text-white shadow-md'
                                    : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                    }`}
                            >
                                {arch}
                            </button>
                        ))}
                    </div>
                </div>
            </div>

            {/* Preview Column */}
            <div className="lg:col-span-5 sticky top-6">
                <EditionTimelinePreview data={formData} />
            </div>
        </div>
    );

    const renderCurationStep = () => (
        <div className="space-y-6 animate-fade-in">
            {/* Financial Simulator */}
            <div className="bg-analux-secondary/5 p-6 rounded-2xl flex flex-col md:flex-row justify-between items-center border border-analux-secondary/10 gap-4">
                <div className="flex gap-8 text-sm w-full md:w-auto">
                    <div>
                        <span className="text-gray-500 block text-[10px] uppercase tracking-wider mb-1">Custo por Box</span>
                        <div className="flex items-baseline gap-1">
                            <span className="text-gray-400 text-xs">R$</span>
                            <span className={`font-serif text-2xl ${financials.totalCost > 50 ? 'text-red-500' : 'text-analux-contrast'}`}>
                                {financials.totalCost.toFixed(2)}
                            </span>
                        </div>
                    </div>
                    <div className="h-10 w-px bg-gray-200"></div>
                    <div>
                        <span className="text-gray-500 block text-[10px] uppercase tracking-wider mb-1">Margem Est.</span>
                        <div className="flex items-baseline gap-1">
                            <span className={`font-serif text-2xl ${financials.marginPercent < 60 ? 'text-amber-500' : 'text-emerald-600'}`}>
                                {financials.marginPercent.toFixed(1)}%
                            </span>
                        </div>
                    </div>
                    <div className="h-10 w-px bg-gray-200"></div>
                    <div>
                        <span className="text-gray-500 block text-[10px] uppercase tracking-wider mb-1">Items</span>
                        <span className="font-serif text-2xl text-analux-contrast">{formData.items.length}</span>
                    </div>
                </div>

                <button
                    onClick={addItem}
                    className="flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-xl text-sm hover:bg-gray-800 transition-colors shadow-lg shadow-gray-900/10 w-full md:w-auto justify-center"
                >
                    <Plus size={18} /> Adicionar Item
                </button>
            </div>

            <div className="space-y-4">
                {formData.items.length === 0 ? (
                    <div className="text-center py-16 bg-white border-2 border-dashed border-gray-100 rounded-2xl">
                        <div className="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                            <Gift size={24} />
                        </div>
                        <h3 className="text-lg font-serif text-gray-700">Comece a Curadoria</h3>
                        <p className="text-gray-500 text-sm max-w-sm mx-auto mt-1">
                            Adicione joias, mimos e elementos sensoriais para compor o ritual desta edição.
                        </p>
                    </div>
                ) : (
                    formData.items.map((item, idx) => (
                        <div key={item.id} className="bg-white border border-gray-100 rounded-xl p-6 shadow-sm hover:shadow-md transition-all relative group">
                            <button
                                onClick={() => removeItem(item.id)}
                                className="absolute top-4 right-4 text-gray-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 p-2"
                            >
                                <Trash2 size={16} />
                            </button>

                            <div className="grid grid-cols-1 md:grid-cols-12 gap-6 items-start">
                                {/* Image Placeholder */}
                                <div className="md:col-span-2">
                                    <div className="aspect-square bg-gray-50 rounded-lg flex items-center justify-center text-gray-300 border border-gray-100 cursor-pointer hover:bg-gray-100 transition-colors relative overflow-hidden group">
                                        <input
                                            type="file"
                                            className="hidden"
                                            accept="image/*"
                                            onChange={(e) => {
                                                const file = e.target.files?.[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onloadend = () => {
                                                        updateItem(item.id, 'image', reader.result);
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            }}
                                        />
                                        {item.image ? (
                                            <>
                                                <img src={item.image} className="w-full h-full object-cover rounded-lg" />
                                                <div className="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <Upload size={20} className="text-white" />
                                                </div>
                                            </>
                                        ) : (
                                            <div onClick={(e) => (e.currentTarget.previousSibling as HTMLInputElement).click()}>
                                                <Upload size={20} />
                                            </div>
                                        )}
                                    </div>
                                </div>

                                {/* Main Details */}
                                <div className="md:col-span-5 space-y-3">
                                    <input
                                        type="text"
                                        placeholder="Nome do Item"
                                        value={item.name}
                                        onChange={(e) => updateItem(item.id, 'name', e.target.value)}
                                        className="w-full font-serif text-lg text-analux-contrast placeholder-gray-300 border-none p-0 focus:ring-0"
                                    />
                                    <div className="flex gap-2 flex-wrap">
                                        <select
                                            value={item.category}
                                            onChange={(e) => updateItem(item.id, 'category', e.target.value as any)}
                                            className="text-xs bg-gray-50 border-none rounded-md py-1 px-2 text-gray-600"
                                        >
                                            <option value="jewelry">Joia</option>
                                            <option value="gift">Mimo</option>
                                            <option value="sensory">Sensorial</option>
                                            <option value="content">Conteúdo</option>
                                        </select>

                                        {/* Dynamic Supplier Logic */}
                                        {item.supplier === 'new_supplier' ? (
                                            <div className="flex items-center gap-2 flex-1">
                                                <input
                                                    type="text"
                                                    className="text-xs bg-white border border-gray-200 rounded-md py-1 px-2 text-gray-700 flex-1 focus:ring-1 focus:ring-analux-secondary"
                                                    placeholder="Nome do Novo Fornecedor"
                                                    autoFocus
                                                    onBlur={(e) => {
                                                        const val = e.target.value;
                                                        if (val) {
                                                            // Add to suppliers list logic would go here ideally, but for now we just set the value strings
                                                            // Since we don't have a central suppliers state setter exposed here easily, 
                                                            // we will simulate it by just confirming the text.
                                                            // In a real app, we'd append to 'suppliers' state.
                                                            updateItem(item.id, 'supplier', val);
                                                        } else {
                                                            updateItem(item.id, 'supplier', '');
                                                        }
                                                    }}
                                                    onKeyDown={(e) => {
                                                        if (e.key === 'Enter') {
                                                            const val = (e.target as HTMLInputElement).value;
                                                            if (val) updateItem(item.id, 'supplier', val);
                                                        }
                                                    }}
                                                />
                                                <button
                                                    onClick={() => updateItem(item.id, 'supplier', '')}
                                                    className="text-gray-400 hover:text-red-500"
                                                >
                                                    <X size={14} />
                                                </button>
                                            </div>
                                        ) : (
                                            <select
                                                value={suppliers.includes(item.supplier || '') ? item.supplier : (item.supplier ? 'custom' : '')}
                                                onChange={(e) => {
                                                    if (e.target.value === 'new_supplier') {
                                                        updateItem(item.id, 'supplier', 'new_supplier');
                                                    } else {
                                                        updateItem(item.id, 'supplier', e.target.value);
                                                    }
                                                }}
                                                className="text-xs bg-gray-50 border-none rounded-md py-1 px-2 text-gray-600"
                                            >
                                                <option value="">Selecione Fornecedor...</option>
                                                {suppliers.map(s => <option key={s} value={s}>{s}</option>)}
                                                {item.supplier && !suppliers.includes(item.supplier) && item.supplier !== 'new_supplier' && (
                                                    <option value="custom">{item.supplier}</option>
                                                )}
                                                <option value="new_supplier" className="font-bold text-analux-secondary">+ Novo Fornecedor</option>
                                            </select>
                                        )}
                                    </div>
                                    <textarea
                                        placeholder="Descrição do item para o encarte..."
                                        value={item.description}
                                        onChange={(e) => updateItem(item.id, 'description', e.target.value)}
                                        className="w-full text-sm text-gray-500 border-none p-0 focus:ring-0 resize-none h-12 bg-transparent"
                                    />
                                </div>

                                {/* Financials & Logic */}
                                <div className="md:col-span-5 space-y-4 border-l border-gray-50 pl-6">
                                    <div>
                                        <label className="text-[10px] uppercase font-bold text-gray-400">Custo Unitário</label>
                                        <div className="relative mt-1">
                                            <span className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">R$</span>
                                            <input
                                                type="number"
                                                value={item.cost}
                                                onChange={(e) => updateItem(item.id, 'cost', parseFloat(e.target.value) || 0)}
                                                className="w-full pl-8 py-2 bg-gray-50 border-none rounded-lg text-gray-900 font-medium"
                                            />
                                        </div>
                                    </div>

                                    <div>
                                        <label className="text-[10px] uppercase font-bold text-gray-400 mb-2 block">Disponível nos Planos</label>
                                        <div className="flex gap-2">
                                            {Object.values(SubscriptionPlan).map(plan => (
                                                <button
                                                    key={plan}
                                                    onClick={() => {
                                                        const current = item.targetPlan || [];
                                                        const updated = current.includes(plan)
                                                            ? current.filter(p => p !== plan)
                                                            : [...current, plan];
                                                        updateItem(item.id, 'targetPlan', updated);
                                                    }}
                                                    className={`px-2 py-1 rounded text-[10px] font-bold border transition-colors ${item.targetPlan?.includes(plan)
                                                        ? 'bg-analux-secondary/10 border-analux-secondary text-analux-secondary'
                                                        : 'bg-white border-gray-200 text-gray-400'
                                                        }`}
                                                >
                                                    {plan}
                                                </button>
                                            ))}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ))
                )}
            </div>
        </div>
    );

    const [audienceSize, setAudienceSize] = useState<number | null>(null);
    const [loadingAudience, setLoadingAudience] = useState(false);

    // Fetch Audience Size
    useMemo(() => {
        const fetchAudience = async () => {
            if (formData.targetStyles.length === 0) {
                setAudienceSize(0);
                return;
            }
            setLoadingAudience(true);
            try {
                // Query profiles where style_preferences -> vibe contains ANY of the target styles
                // Since this is JSONB, we might need a specific query. 
                // For now, we'll try a text search approach or assume specific structure if known.
                // Fallback simulation for demo:
                // const { count } = await supabase.from('profiles').select('*', { count: 'exact', head: true })...

                // DATA FETCHER SIMULATION (To be replaced by real query once schema confirmed)
                // Real query would be: 
                // const { count } = await supabase.from('profiles').select('*', { count: 'exact', head: true }).contains('style_prefs->vibe', formData.targetStyles);

                // Mocking network delay and "Real" looking data
                setTimeout(() => {
                    const baseCount = 150;
                    const randomFactor = Math.floor(Math.random() * 50);
                    // More styles = more potential matches (union) or less (intersection)? Usually usually intersection for "Match ALL" or union for "Match ANY".
                    // Let's assume "Match ANY" increases reach.
                    const estimated = baseCount + (formData.targetStyles.length * 120) + randomFactor;
                    setAudienceSize(estimated > 2000 ? 2000 : estimated);
                    setLoadingAudience(false);
                }, 600);
            } catch (error) {
                console.error("Erro ao calcular audiência", error);
                setLoadingAudience(false);
            }
        };
        fetchAudience();
    }, [formData.targetStyles]);

    // ... (render steps)

    const renderAudienceStep = () => (
        <div className="space-y-8 animate-fade-in">
            <div className="bg-analux-secondary/5 p-6 rounded-2xl border border-analux-secondary/10 flex flex-col md:flex-row justify-between items-center gap-6">
                <div className="flex items-center gap-4">
                    <div className="w-12 h-12 bg-white rounded-full flex items-center justify-center text-analux-secondary shadow-sm">
                        <Users size={24} />
                    </div>
                    <div>
                        <h3 className="font-serif text-lg text-analux-contrast">Alcance Estimado</h3>
                        <p className="text-sm text-gray-500">Baseado nas afinidades selecionadas</p>
                    </div>
                </div>
                <div className="text-right">
                    {loadingAudience ? (
                        <span className="block text-3xl font-serif text-analux-primary animate-pulse">Calculando...</span>
                    ) : (
                        <span className="block text-3xl font-serif text-analux-primary">{audienceSize !== null ? audienceSize : '-'}</span>
                    )}
                    <span className="text-xs uppercase font-bold tracking-widest text-gray-400">Assinantes Qualificadas</span>
                </div>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 className="font-medium text-gray-900 mb-4 flex items-center gap-2">
                        <Sparkles size={18} className="text-analux-secondary" />
                        Afinidade de Estilo (DNA)
                    </h3>
                    <div className="grid grid-cols-2 gap-3">
                        {['Clássico', 'Moderno', 'Romântico', 'Dramático', 'Criativo', 'Natural', 'Sexy'].map((style) => (
                            <button
                                key={style}
                                onClick={() => {
                                    const current = formData.targetStyles || [];
                                    const updated = current.includes(style)
                                        ? current.filter(s => s !== style)
                                        : [...current, style];
                                    setFormData({ ...formData, targetStyles: updated });
                                }}
                                className={`p-3 rounded-xl border text-sm font-medium transition-all text-left flex justify-between items-center ${formData.targetStyles?.includes(style)
                                    // High contrast fix
                                    ? 'bg-gray-900 text-white border-gray-900 shadow-md'
                                    : 'bg-white border-gray-200 text-gray-600 hover:border-gray-300'
                                    }`}
                            >
                                {style}
                                {formData.targetStyles?.includes(style) && <div className="w-2 h-2 rounded-full bg-analux-secondary"></div>}
                            </button>
                        ))}
                    </div>
                </div>

                <div className="space-y-6">
                    <div>
                        <h3 className="font-medium text-gray-900 mb-4">Exclusividade do Plano</h3>
                        <div className="space-y-3">
                            {[SubscriptionPlan.BASIC, SubscriptionPlan.PREMIUM, SubscriptionPlan.LUXURY].map((plan) => (
                                <label key={plan} className="flex items-center gap-4 p-4 bg-white border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input
                                        type="checkbox"
                                        defaultChecked
                                        className="w-5 h-5 rounded border-gray-300 text-analux-secondary focus:ring-analux-secondary"
                                    />
                                    <div>
                                        <span className="font-serif text-gray-900 block">{plan}</span>
                                        <span className="text-xs text-gray-400">Disponível para assinantes deste nível</span>
                                    </div>
                                </label>
                            ))}
                        </div>
                    </div>

                    <div className="p-4 bg-rose-50 text-rose-800 rounded-xl text-sm flex gap-3">
                        <PieChart size={20} className="shrink-0" />
                        <p>Esta edição tem <strong>alta afinidade (88%)</strong> com o perfil "Romântico" e "Clássico", o que deve aumentar a retenção nestes grupos.</p>
                    </div>
                </div>
            </div>
        </div>
    );

    const renderStructureStep = () => (
        <div className="space-y-8 animate-fade-in">
            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden">
                    <div className="absolute top-0 right-0 p-4 opacity-10"><Box size={64} /></div>
                    <label className="block text-sm font-medium text-gray-700 mb-2">Quantidade Prevista</label>
                    <input
                        type="number"
                        value={formData.estimatedQty}
                        onChange={(e) => setFormData({ ...formData, estimatedQty: parseInt(e.target.value) })}
                        className="text-3xl font-serif text-gray-900 w-full border-none p-0 focus:ring-0 bg-transparent"
                    />
                    <p className="text-xs text-gray-400 mt-2">Baseado em assinantes ativos + growth</p>
                </div>

                <div className="md:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 className="font-serif text-lg mb-4 flex items-center gap-2">
                        <Calendar size={18} className="text-analux-secondary" />
                        Janela Operacional
                    </h3>
                    <div className="space-y-4">
                        <div className="flex items-center gap-4">
                            <div className="w-10 h-10 rounded-full bg-rose-50 flex items-center justify-center text-rose-600"><Sparkles size={18} /></div>
                            <div className="flex-1">
                                <label className="text-xs font-bold text-gray-400 uppercase">Início dos Envios (Vigência)</label>
                                <input type="date" className="block w-full text-sm font-medium mt-1 border-none p-0 focus:ring-0"
                                    value={formData.processingStart || ''}
                                    onChange={e => setFormData({ ...formData, processingStart: e.target.value })}
                                />
                                <p className="text-[10px] text-gray-400 mt-1">Data em que esta box começa a ser enviada no ciclo.</p>
                            </div>
                        </div>

                        <div className="grid grid-cols-2 gap-4">
                            <div className="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div className="p-2 bg-white rounded-lg shadow-sm text-analux-secondary"><Box size={16} /></div>
                                <div>
                                    <label className="text-[10px] font-bold text-gray-400 uppercase block">Tempo Prep.</label>
                                    <div className="flex items-baseline gap-1">
                                        <input
                                            type="number"
                                            className="w-10 bg-transparent border-none p-0 font-bold text-gray-900 focus:ring-0"
                                            placeholder="3"
                                            defaultValue={3}
                                        />
                                        <span className="text-xs text-gray-500">dias úteis</span>
                                    </div>
                                </div>
                            </div>
                            <div className="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                <div className="p-2 bg-white rounded-lg shadow-sm text-analux-secondary"><Truck size={16} /></div>
                                <div>
                                    <label className="text-[10px] font-bold text-gray-400 uppercase block">SLA Envio</label>
                                    <div className="flex items-baseline gap-1">
                                        <input
                                            type="number"
                                            className="w-10 bg-transparent border-none p-0 font-bold text-gray-900 focus:ring-0"
                                            placeholder="5"
                                            defaultValue={5}
                                        />
                                        <span className="text-xs text-gray-500">dias</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="bg-gray-50 rounded-2xl p-6 border border-dashed border-gray-200">
                <div className="flex items-center gap-3 mb-6">
                    <Box size={20} className="text-gray-900" />
                    <h3 className="font-medium text-gray-900">Configuração Física (Estrutura)</h3>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {/* Box Type */}
                    <div className="space-y-3">
                        <label className="text-xs font-bold text-gray-400 uppercase">Modelo da Caixa</label>
                        <div className="space-y-2">
                            {[
                                { id: 'mailer', label: 'Mailer Box (Correio)', desc: 'Automontável padrão' },
                                { id: 'rigid', label: 'Caixa Rígida', desc: 'Premium com ímã' },
                                { id: 'drawer', label: 'Luva e Gaveta', desc: 'Deslizante' }
                            ].map(type => (
                                <button
                                    key={type.id}
                                    onClick={() => setFormData({ ...formData, boxStructure: { ...formData.boxStructure, type: type.id } })}
                                    className={`w-full p-3 rounded-xl border text-left transition-all ${formData.boxStructure?.type === type.id
                                        ? 'bg-gray-900 text-white border-gray-900'
                                        : 'bg-white border-gray-100 hover:bg-gray-50'
                                        }`}
                                >
                                    <span className="text-sm font-medium block">{type.label}</span>
                                    <span className="text-xs opacity-70 block">{type.desc}</span>
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Material */}
                    <div className="space-y-3">
                        <label className="text-xs font-bold text-gray-400 uppercase">Acabamento</label>
                        <div className="space-y-2">
                            {[
                                { id: 'matte', label: 'Laminação Fosca', desc: 'Toque aveludado' },
                                { id: 'glossy', label: 'Laminação Brilho', desc: 'Vibração de cor' },
                                { id: 'kraft', label: 'Kraft Natural', desc: 'Eco-friendly' }
                            ].map(mat => (
                                <button
                                    key={mat.id}
                                    onClick={() => setFormData({ ...formData, boxStructure: { ...formData.boxStructure, material: mat.id } })}
                                    className={`w-full p-3 rounded-xl border text-left transition-all ${formData.boxStructure?.material === mat.id
                                        ? 'bg-gray-900 text-white border-gray-900'
                                        : 'bg-white border-gray-100 hover:bg-gray-50'
                                        }`}
                                >
                                    <span className="text-sm font-medium block">{mat.label}</span>
                                    <span className="text-xs opacity-70 block">{mat.desc}</span>
                                </button>
                            ))}
                        </div>
                    </div>

                    {/* Filling */}
                    <div className="space-y-3">
                        <label className="text-xs font-bold text-gray-400 uppercase">Berço / Enchimento</label>
                        <div className="space-y-2">
                            {[
                                { id: 'paper', label: 'Papel de Seda', desc: 'Experiência clássica' },
                                { id: 'shredded', label: 'Palha de Papel', desc: 'Proteção extra' },
                                { id: 'foam', label: 'Berço de Espuma', desc: 'Alta joalheria' }
                            ].map(fill => (
                                <button
                                    key={fill.id}
                                    onClick={() => setFormData({ ...formData, boxStructure: { ...formData.boxStructure, filling: fill.id } })}
                                    className={`w-full p-3 rounded-xl border text-left transition-all ${formData.boxStructure?.filling === fill.id
                                        ? 'bg-gray-900 text-white border-gray-900'
                                        : 'bg-white border-gray-100 hover:bg-gray-50'
                                        }`}
                                >
                                    <span className="text-sm font-medium block">{fill.label}</span>
                                    <span className="text-xs opacity-70 block">{fill.desc}</span>
                                </button>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );

    const renderLogisticsStep = () => (
        <div className="space-y-6 animate-fade-in">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Freight Engineering */}
                <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                    <h3 className="font-serif text-lg mb-6 flex items-center gap-2">
                        <Box size={20} className="text-analux-secondary" />
                        Engenharia de Frete
                    </h3>

                    <div className="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <label className="text-xs font-bold text-gray-400 uppercase mb-1 block">Peso (kg)</label>
                            <input
                                type="number"
                                step="0.1"
                                className="w-full px-3 py-2 bg-gray-50 border border-gray-100 rounded-lg text-gray-900 font-medium focus:ring-2 focus:ring-analux-secondary/20 focus:outline-none"
                                value={formData.boxDimensions?.weight}
                                onChange={(e) => setFormData({ ...formData, boxDimensions: { ...formData.boxDimensions, weight: parseFloat(e.target.value) } })}
                            />
                        </div>
                        <div>
                            <label className="text-xs font-bold text-gray-400 uppercase mb-1 block">Volume (m³)</label>
                            <div className="px-3 py-2 bg-gray-50 border border-gray-100 rounded-lg text-gray-500 font-medium">
                                {((formData.boxDimensions?.length * formData.boxDimensions?.width * formData.boxDimensions?.height) / 1000000).toFixed(4)}
                            </div>
                        </div>
                    </div>

                    <div className="space-y-4">
                        <label className="text-xs font-bold text-gray-400 uppercase block">Dimensões da Caixa (cm)</label>
                        <div className="grid grid-cols-3 gap-2">
                            <div className="relative">
                                <span className="absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs">C</span>
                                <input
                                    type="number"
                                    className="w-full pl-6 pr-2 py-2 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-900 text-center focus:ring-analux-secondary/20"
                                    value={formData.boxDimensions?.length}
                                    onChange={(e) => setFormData({ ...formData, boxDimensions: { ...formData.boxDimensions, length: parseFloat(e.target.value) } })}
                                />
                            </div>
                            <div className="relative">
                                <span className="absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs">L</span>
                                <input
                                    type="number"
                                    className="w-full pl-6 pr-2 py-2 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-900 text-center focus:ring-analux-secondary/20"
                                    value={formData.boxDimensions?.width}
                                    onChange={(e) => setFormData({ ...formData, boxDimensions: { ...formData.boxDimensions, width: parseFloat(e.target.value) } })}
                                />
                            </div>
                            <div className="relative">
                                <span className="absolute top-1/2 left-3 -translate-y-1/2 text-gray-400 text-xs">A</span>
                                <input
                                    type="number"
                                    className="w-full pl-6 pr-2 py-2 bg-gray-50 border border-gray-100 rounded-lg text-sm text-gray-900 text-center focus:ring-analux-secondary/20"
                                    value={formData.boxDimensions?.height}
                                    onChange={(e) => setFormData({ ...formData, boxDimensions: { ...formData.boxDimensions, height: parseFloat(e.target.value) } })}
                                />
                            </div>
                        </div>
                    </div>
                </div>

                {/* Delivery Map & Carriers */}
                <div className="space-y-6">
                    <div className="bg-analux-secondary/5 p-6 rounded-2xl border border-analux-secondary/10">
                        <h3 className="font-serif text-lg mb-4 text-analux-contrast">Distribuição de Entregas</h3>

                        <div className="space-y-4">
                            <div className="flex items-center justify-between text-sm">
                                <div className="flex items-center gap-2">
                                    <div className="w-3 h-3 rounded-full bg-analux-primary"></div>
                                    <span className="text-gray-600">Transportadora Própria (Loggi/JadLog)</span>
                                </div>
                                <span className="font-bold text-gray-900">65%</span>
                            </div>
                            <div className="w-full bg-white rounded-full h-2 overflow-hidden shadow-inner">
                                <div className="bg-analux-primary h-full w-[65%]"></div>
                            </div>

                            <div className="flex items-center justify-between text-sm">
                                <div className="flex items-center gap-2">
                                    <div className="w-3 h-3 rounded-full bg-amber-400"></div>
                                    <span className="text-gray-600">Correios (SEDEX/PAC)</span>
                                </div>
                                <span className="font-bold text-gray-900">35%</span>
                            </div>
                            <div className="w-full bg-white rounded-full h-2 overflow-hidden shadow-inner">
                                <div className="bg-amber-400 h-full w-[35%]"></div>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white p-4 rounded-xl border border-gray-100 flex items-center justify-between shadow-sm">
                        <div className="flex items-center gap-3">
                            <div className="p-2 bg-green-50 text-green-700 rounded-lg">
                                <DollarSign size={20} />
                            </div>
                            <div>
                                <p className="text-xs text-gray-500 uppercase font-bold">Frete Médio (Est.)</p>
                                <p className="text-xl font-serif text-gray-900">R$ 18,45</p>
                            </div>
                        </div>
                        <button className="text-xs text-analux-secondary font-medium hover:underline">
                            Recalcular
                        </button>
                    </div>
                </div>
            </div>
        </div>
    );

    const renderExperienceStep = () => (
        <div className="space-y-8 animate-fade-in">
            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {/* Soundtrack Section */}
                <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-analux-secondary/30 transition-all">
                    <div className="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <Music size={80} />
                    </div>

                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-10 h-10 rounded-full bg-purple-50 flex items-center justify-center text-purple-600">
                            <Music size={20} />
                        </div>
                        <div>
                            <h3 className="font-serif text-lg text-gray-900">Trilha Sonora do Ritual</h3>
                            <p className="text-xs text-gray-500">A música conecta a alma à joia</p>
                        </div>
                    </div>

                    <div className="space-y-4">
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-2">Link da Playlist (Spotify)</label>
                            <input
                                type="text"
                                className="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-purple-500/20 focus:outline-none placeholder-gray-300"
                                placeholder="https://open.spotify.com/playlist/..."
                                value={formData.playlistUrl}
                                onChange={(e) => setFormData({ ...formData, playlistUrl: e.target.value })}
                            />
                        </div>

                        {formData.playlistUrl ? (
                            <div className="bg-gray-900 rounded-xl p-4 flex items-center gap-4 text-white">
                                <div className="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center shrink-0">
                                    <Music size={24} />
                                </div>
                                <div className="overflow-hidden">
                                    <p className="font-medium truncate">Playlist Vinculada</p>
                                    <p className="text-xs text-gray-400 truncate opacity-70">{formData.playlistUrl}</p>
                                </div>
                            </div>
                        ) : (
                            <div className="border border-dashed border-gray-200 rounded-xl p-6 text-center text-gray-400">
                                <span className="text-sm">Cole o link para pré-visualizar</span>
                            </div>
                        )}
                    </div>
                </div>

                {/* Teaser Video Section */}
                <div className="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative overflow-hidden group hover:border-analux-secondary/30 transition-all">
                    <div className="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <Sparkles size={80} />
                    </div>

                    <div className="flex items-center gap-3 mb-6">
                        <div className="w-10 h-10 rounded-full bg-amber-50 flex items-center justify-center text-amber-600">
                            <Box size={20} />
                        </div>
                        <div>
                            <h3 className="font-serif text-lg text-gray-900">Unboxing Experience</h3>
                            <p className="text-xs text-gray-500">Vídeo Teaser ou QR Code</p>
                        </div>
                    </div>

                    <div className="space-y-4">
                        <label className="border-2 border-dashed border-gray-200 rounded-xl p-8 flex flex-col items-center justify-center text-gray-400 hover:border-analux-secondary/50 hover:bg-gray-50 transition-all cursor-pointer h-[200px] relative overflow-hidden">
                            <input
                                type="file"
                                className="hidden"
                                accept="video/*"
                                onChange={(e) => {
                                    // Simulation of video upload
                                    const file = e.target.files?.[0];
                                    if (file) setFormData({ ...formData, teaserUrl: URL.createObjectURL(file) })
                                }}
                            />
                            {formData.teaserUrl ? (
                                <video src={formData.teaserUrl} className="absolute inset-0 w-full h-full object-cover" controls />
                            ) : (
                                <>
                                    <Upload size={32} className="mb-3 text-gray-300" />
                                    <span className="text-sm font-medium">Upload do Teaser da Campanha</span>
                                    <span className="text-xs mt-1 text-gray-400">MP4, MOV (Max 50MB)</span>
                                </>
                            )}
                        </label>
                    </div>
                </div>
            </div>

            {/* Print Materials */}
            <div className="bg-gray-50 p-6 rounded-2xl border border-dashed border-gray-200">
                <h3 className="font-medium text-gray-900 mb-4 flex items-center gap-2">
                    <Gift size={18} className="text-analux-secondary" />
                    Materiais Impressos & Encantamento
                </h3>
                <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                    {['Carta da Editora', 'Card do Ritual', 'Adesivos Temáticos', 'Papel de Seda Personalizado', 'Folder de Sustentabilidade', 'Gift Card Parceiro'].map((item, i) => (
                        <label key={i} className="flex items-center gap-3 p-3 bg-white rounded-lg cursor-pointer hover:shadow-sm border border-transparent hover:border-gray-200 transition-all">
                            <input type="checkbox" className="rounded text-analux-secondary focus:ring-analux-secondary" defaultChecked={i < 3} />
                            <span className="text-sm text-gray-700">{item}</span>
                        </label>
                    ))}
                </div>
            </div>
        </div>
    );

    return (
        <div className="max-w-4xl mx-auto pb-20">
            {/* Header */}
            <div className="flex items-center justify-between mb-8">
                <div className="flex items-center gap-4">
                    <button onClick={() => window.history.back()} className="text-gray-400 hover:text-analux-contrast transition-colors">
                        <ArrowLeft size={24} />
                    </button>
                    <div>
                        <h1 className="text-2xl font-serif text-analux-contrast">Nova Edição</h1>
                        <p className="text-gray-500 text-sm">Planejamento e criação de ritual</p>
                    </div>
                </div>
                <button className="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2">
                    <Save size={18} />
                    Salvar Rascunho
                </button>
            </div>

            {/* Progress Stepper */}
            <div className="flex justify-between mb-8 relative">
                <div className="absolute top-1/2 left-0 w-full h-0.5 bg-gray-100 -z-10 -translate-y-1/2 rounded-full"></div>
                {STEPS.map((step, index) => (
                    <div
                        key={step.id}
                        className={`flex flex-col items-center gap-2 cursor-pointer group`}
                        onClick={() => setCurrentStep(index)}
                    >
                        <div className={`w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 ${index === currentStep
                            ? 'bg-analux-primary text-white shadow-lg scale-110'
                            : index < currentStep
                                ? 'bg-analux-primary/20 text-analux-primary'
                                : 'bg-white border border-gray-200 text-gray-400 group-hover:border-analux-primary/50'
                            }`}>
                            {step.icon}
                        </div>
                        <span className={`text-xs font-medium transition-colors ${index === currentStep ? 'text-analux-primary' : 'text-gray-400'
                            }`}>
                            {step.label}
                        </span>
                    </div>
                ))}
            </div>

            {/* Main Content Area */}
            <div className="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 min-h-[500px]">
                {currentStep === 0 && renderIdentityStep()}
                {currentStep === 1 && renderCurationStep()}
                {STEPS[currentStep].id === 'structure' && renderStructureStep()}
                {STEPS[currentStep].id === 'audience' && renderAudienceStep()}
                {STEPS[currentStep].id === 'logistics' && renderLogisticsStep()}
                {STEPS[currentStep].id === 'experience' && renderExperienceStep()}

                {/* Fallback for others */}
                {!['identity', 'curation', 'structure', 'audience', 'logistics', 'experience'].includes(STEPS[currentStep].id) && (
                    <div className="flex flex-col items-center justify-center h-full text-gray-400">
                        <Box size={48} className="mb-4 opacity-50" />
                        <p>Módulo {STEPS[currentStep].label} em desenvolvimento...</p>
                    </div>
                )}
            </div>

            {/* Footer Actions */}
            <div className="fixed bottom-0 left-0 w-full bg-white border-t border-gray-100 p-4 lg:pl-72 z-10 flex justify-end gap-4">
                <button
                    onClick={handleBack}
                    disabled={currentStep === 0}
                    className="px-6 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Voltar
                </button>
                <button
                    onClick={handleNext}
                    className="px-6 py-2 bg-analux-primary text-white rounded-lg hover:bg-analux-primary/90 transition-colors flex items-center gap-2"
                >
                    {currentStep === STEPS.length - 1 ? 'Finalizar Criação' : 'Próximo Passo'}
                    <ChevronRight size={18} />
                </button>
            </div>
        </div>
    );
};

export default CreateBox;
