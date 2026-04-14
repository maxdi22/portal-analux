import React, { useState } from 'react';
import {
    X, User, Mail, Phone, Lock, Eye, EyeOff,
    CreditCard, MapPin, ChevronRight, ChevronLeft,
    CheckCircle, Loader2, Shield, Star, Crown, Gem
} from 'lucide-react';
import { supabase } from '../../services/supabase';

interface CreateSubscriberModalProps {
    onClose: () => void;
    onSuccess: () => void;
}

interface FormData {
    // Step 1 – Dados Pessoais
    name: string;
    email: string;
    phone: string;
    password: string;
    confirmPassword: string;
    // Step 2 – Plano & Role
    plan: 'BASIC' | 'PREMIUM' | 'LUXURY' | 'none';
    role: 'member' | 'admin' | 'superadmin';
    musaCategory: 'Musa Bronze' | 'Musa Prata' | 'Musa Ouro' | 'Musa Diamante';
    subscriptionStatus: 'active' | 'paused' | 'cancelled';
    // Step 3 – Endereço
    zipCode: string;
    street: string;
    number: string;
    complement: string;
    neighborhood: string;
    city: string;
    state: string;
}

const STEPS = ['Dados Pessoais', 'Plano & Perfil', 'Endereço'];

const PLAN_OPTIONS = [
    {
        value: 'BASIC',
        label: 'Musa Bronze',
        description: 'Plano essencial de entrada',
        icon: <Star size={20} className="text-amber-600" />,
        color: 'border-amber-300 bg-amber-50',
        selected: 'border-amber-500 bg-amber-100 ring-2 ring-amber-400'
    },
    {
        value: 'PREMIUM',
        label: 'Musa Ouro',
        description: 'Plano intermediário premium',
        icon: <Crown size={20} className="text-yellow-500" />,
        color: 'border-yellow-300 bg-yellow-50',
        selected: 'border-yellow-500 bg-yellow-100 ring-2 ring-yellow-400'
    },
    {
        value: 'LUXURY',
        label: 'Musa Diamante',
        description: 'Plano topo de linha exclusivo',
        icon: <Gem size={20} className="text-blue-500" />,
        color: 'border-blue-300 bg-blue-50',
        selected: 'border-blue-500 bg-blue-100 ring-2 ring-blue-400'
    },
    {
        value: 'none',
        label: 'Sem Plano',
        description: 'Criar perfil sem assinatura ativa',
        icon: <User size={20} className="text-slate-400" />,
        color: 'border-slate-200 bg-slate-50',
        selected: 'border-slate-400 bg-slate-100 ring-2 ring-slate-300'
    },
];

const ROLE_OPTIONS = [
    { value: 'member', label: 'Assinante', description: 'Acesso padrão de cliente', icon: <User size={16} /> },
    { value: 'admin', label: 'Admin', description: 'Acesso ao painel administrativo', icon: <Shield size={16} /> },
    { value: 'superadmin', label: 'Master', description: 'Acesso total ao sistema', icon: <Shield size={16} className="text-rose-600" /> },
];

const MUSA_CATEGORIES = ['Musa Bronze', 'Musa Prata', 'Musa Ouro', 'Musa Diamante'];

const ESTADOS_BR = [
    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
    'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
    'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];

const CreateSubscriberModal: React.FC<CreateSubscriberModalProps> = ({ onClose, onSuccess }) => {
    const [step, setStep] = useState(0);
    const [loading, setLoading] = useState(false);
    const [cepLoading, setCepLoading] = useState(false);
    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [errors, setErrors] = useState<Record<string, string>>({});
    const [submitError, setSubmitError] = useState('');

    const [form, setForm] = useState<FormData>({
        name: '',
        email: '',
        phone: '',
        password: '',
        confirmPassword: '',
        plan: 'BASIC',
        role: 'member',
        musaCategory: 'Musa Bronze',
        subscriptionStatus: 'active',
        zipCode: '',
        street: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state: 'SP',
    });

    const set = (field: keyof FormData, value: string) => {
        setForm(prev => ({ ...prev, [field]: value }));
        if (errors[field]) setErrors(prev => { const n = { ...prev }; delete n[field]; return n; });
    };

    // ── CEP lookup ──────────────────────────────────────────────────────────
    const lookupCep = async (cep: string) => {
        const clean = cep.replace(/\D/g, '');
        if (clean.length !== 8) return;
        setCepLoading(true);
        try {
            const res = await fetch(`https://viacep.com.br/ws/${clean}/json/`);
            const data = await res.json();
            if (!data.erro) {
                setForm(prev => ({
                    ...prev,
                    street: data.logradouro || prev.street,
                    neighborhood: data.bairro || prev.neighborhood,
                    city: data.localidade || prev.city,
                    state: data.uf || prev.state,
                }));
            }
        } catch { /* ignore */ }
        finally { setCepLoading(false); }
    };

    // ── Validation ──────────────────────────────────────────────────────────
    const validateStep = (): boolean => {
        const e: Record<string, string> = {};

        if (step === 0) {
            if (!form.name.trim()) e.name = 'Nome é obrigatório';
            if (!form.email.trim() || !/\S+@\S+\.\S+/.test(form.email)) e.email = 'Email inválido';
            if (!form.password || form.password.length < 6) e.password = 'Senha mínima de 6 caracteres';
            if (form.password !== form.confirmPassword) e.confirmPassword = 'Senhas não coincidem';
        }

        if (step === 2) {
            if (!form.zipCode.replace(/\D/g, '')) e.zipCode = 'CEP é obrigatório';
            if (!form.street.trim()) e.street = 'Rua é obrigatória';
            if (!form.number.trim()) e.number = 'Número é obrigatório';
            if (!form.city.trim()) e.city = 'Cidade é obrigatória';
        }

        setErrors(e);
        return Object.keys(e).length === 0;
    };

    const next = () => { if (validateStep()) setStep(s => Math.min(s + 1, 2)); };
    const back = () => setStep(s => Math.max(s - 1, 0));

    // ── Submit ───────────────────────────────────────────────────────────────
    const handleSubmit = async () => {
        if (!validateStep()) return;
        setLoading(true);
        setSubmitError('');

        try {
            // Save the current admin session before creating the new user
            const { data: sessionData } = await supabase.auth.getSession();
            const adminSession = sessionData?.session;

            // 1. Use a secondary client to sign up the new user without disturbing admin session
            const { createClient } = await import('@supabase/supabase-js');
            const tempClient = createClient(
                import.meta.env.VITE_SUPABASE_URL,
                import.meta.env.VITE_SUPABASE_ANON_KEY
            );

            const { data: authData, error: authError } = await tempClient.auth.signUp({
                email: form.email.trim().toLowerCase(),
                password: form.password,
            });

            if (authError) throw new Error(authError.message);
            if (!authData.user) throw new Error('Não foi possível criar o usuário. Verifique os dados e tente novamente.');

            const userId = authData.user.id;

            // Sign out the temp session (doesn't affect the admin's main session)
            await tempClient.auth.signOut();

            // Restore admin session if it was somehow affected
            if (adminSession) {
                await supabase.auth.setSession({
                    access_token: adminSession.access_token,
                    refresh_token: adminSession.refresh_token,
                });
            }

            // 2. Upsert profile using the admin client
            const addressPayload = {
                street: form.street,
                number: form.number,
                complement: form.complement,
                neighborhood: form.neighborhood,
                city: form.city,
                state: form.state,
                zipCode: form.zipCode.replace(/\D/g, ''),
            };

            const { error: profileError } = await supabase
                .from('profiles')
                .upsert({
                    id: userId,
                    name: form.name.trim(),
                    email: form.email.trim().toLowerCase(),
                    phone: form.phone,
                    role: form.role,
                    musa_category: form.musaCategory,
                    address: addressPayload,
                    created_at: new Date().toISOString(),
                }, { onConflict: 'id' });

            if (profileError) throw new Error(profileError.message);

            // 3. Create subscription if plan selected
            if (form.plan !== 'none') {
                const { error: subError } = await supabase
                    .from('subscriptions')
                    .insert({
                        user_id: userId,
                        plan: form.plan,
                        status: form.subscriptionStatus,
                        created_at: new Date().toISOString(),
                    });

                if (subError) throw new Error(subError.message);
            }

            onSuccess();
            onClose();

        } catch (err: any) {
            console.error('CreateSubscriber error:', err);
            setSubmitError(err.message || 'Erro ao criar assinante. Verifique os dados e tente novamente.');
        } finally {
            setLoading(false);
        }
    };

    // ── Render helpers ───────────────────────────────────────────────────────
    const inputClass = (field: string) =>
        `w-full px-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:ring-2 transition-all ${errors[field]
            ? 'border-red-400 focus:ring-red-300 bg-red-50'
            : 'border-slate-200 focus:ring-amber-400/50 bg-white'
        }`;

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm px-4">
            <div className="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden animate-in fade-in zoom-in-95 duration-200">

                {/* Header */}
                <div className="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5 flex items-center justify-between">
                    <div>
                        <h2 className="text-white font-bold text-lg">Novo Assinante</h2>
                        <p className="text-amber-100 text-xs mt-0.5">Passo {step + 1} de {STEPS.length} — {STEPS[step]}</p>
                    </div>
                    <button
                        onClick={onClose}
                        className="text-white/70 hover:text-white hover:bg-white/20 p-2 rounded-lg transition-all"
                    >
                        <X size={20} />
                    </button>
                </div>

                {/* Progress bar */}
                <div className="h-1 bg-amber-100">
                    <div
                        className="h-full bg-amber-500 transition-all duration-500"
                        style={{ width: `${((step + 1) / STEPS.length) * 100}%` }}
                    />
                </div>

                {/* Body */}
                <div className="p-6 space-y-4 max-h-[60vh] overflow-y-auto">

                    {/* ── STEP 0: Dados Pessoais ── */}
                    {step === 0 && (
                        <>
                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    <User size={12} className="inline mr-1" />Nome Completo *
                                </label>
                                <input
                                    type="text"
                                    placeholder="Maria Silva"
                                    className={inputClass('name')}
                                    value={form.name}
                                    onChange={e => set('name', e.target.value)}
                                />
                                {errors.name && <p className="text-red-500 text-xs mt-1">{errors.name}</p>}
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    <Mail size={12} className="inline mr-1" />Email *
                                </label>
                                <input
                                    type="email"
                                    placeholder="maria@email.com"
                                    className={inputClass('email')}
                                    value={form.email}
                                    onChange={e => set('email', e.target.value)}
                                    autoComplete="off"
                                />
                                {errors.email && <p className="text-red-500 text-xs mt-1">{errors.email}</p>}
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    <Phone size={12} className="inline mr-1" />Telefone / WhatsApp
                                </label>
                                <input
                                    type="tel"
                                    placeholder="(11) 99999-9999"
                                    className={inputClass('phone')}
                                    value={form.phone}
                                    onChange={e => set('phone', e.target.value)}
                                />
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        <Lock size={12} className="inline mr-1" />Senha *
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={showPassword ? 'text' : 'password'}
                                            placeholder="••••••••"
                                            className={`${inputClass('password')} pr-10`}
                                            value={form.password}
                                            onChange={e => set('password', e.target.value)}
                                            autoComplete="new-password"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowPassword(v => !v)}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                        >
                                            {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                                        </button>
                                    </div>
                                    {errors.password && <p className="text-red-500 text-xs mt-1">{errors.password}</p>}
                                </div>

                                <div>
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Confirmar Senha *
                                    </label>
                                    <div className="relative">
                                        <input
                                            type={showConfirmPassword ? 'text' : 'password'}
                                            placeholder="••••••••"
                                            className={`${inputClass('confirmPassword')} pr-10`}
                                            value={form.confirmPassword}
                                            onChange={e => set('confirmPassword', e.target.value)}
                                            autoComplete="new-password"
                                        />
                                        <button
                                            type="button"
                                            onClick={() => setShowConfirmPassword(v => !v)}
                                            className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                                        >
                                            {showConfirmPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                                        </button>
                                    </div>
                                    {errors.confirmPassword && <p className="text-red-500 text-xs mt-1">{errors.confirmPassword}</p>}
                                </div>
                            </div>
                        </>
                    )}

                    {/* ── STEP 1: Plano & Perfil ── */}
                    {step === 1 && (
                        <>
                            {/* Plan selector */}
                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    <CreditCard size={12} className="inline mr-1" />Plano de Assinatura
                                </label>
                                <div className="grid grid-cols-2 gap-2">
                                    {PLAN_OPTIONS.map(opt => (
                                        <button
                                            key={opt.value}
                                            type="button"
                                            onClick={() => set('plan', opt.value)}
                                            className={`flex items-start gap-3 p-3 rounded-xl border-2 text-left transition-all ${form.plan === opt.value ? opt.selected : opt.color + ' hover:border-slate-300'}`}
                                        >
                                            <div className="mt-0.5 shrink-0">{opt.icon}</div>
                                            <div>
                                                <div className="font-bold text-sm text-slate-800">{opt.label}</div>
                                                <div className="text-[11px] text-slate-500 mt-0.5">{opt.description}</div>
                                            </div>
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Subscription status (only if plan selected) */}
                            {form.plan !== 'none' && (
                                <div>
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Status da Assinatura
                                    </label>
                                    <select
                                        className="w-full px-4 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50"
                                        value={form.subscriptionStatus}
                                        onChange={e => set('subscriptionStatus', e.target.value)}
                                    >
                                        <option value="active">Ativa</option>
                                        <option value="paused">Pausada</option>
                                        <option value="cancelled">Cancelada</option>
                                    </select>
                                </div>
                            )}

                            {/* Role */}
                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-2 uppercase tracking-wide">
                                    <Shield size={12} className="inline mr-1" />Perfil de Acesso
                                </label>
                                <div className="space-y-2">
                                    {ROLE_OPTIONS.map(opt => (
                                        <button
                                            key={opt.value}
                                            type="button"
                                            onClick={() => set('role', opt.value)}
                                            className={`w-full flex items-center gap-3 px-4 py-2.5 rounded-xl border-2 text-left transition-all ${form.role === opt.value
                                                ? 'border-amber-500 bg-amber-50 ring-2 ring-amber-300'
                                                : 'border-slate-200 bg-white hover:border-slate-300'}`}
                                        >
                                            <div className={`p-1.5 rounded-lg ${form.role === opt.value ? 'bg-amber-100 text-amber-600' : 'bg-slate-100 text-slate-500'}`}>
                                                {opt.icon}
                                            </div>
                                            <div>
                                                <div className="font-bold text-sm text-slate-800">{opt.label}</div>
                                                <div className="text-[11px] text-slate-400">{opt.description}</div>
                                            </div>
                                            {form.role === opt.value && (
                                                <CheckCircle size={16} className="ml-auto text-amber-500" />
                                            )}
                                        </button>
                                    ))}
                                </div>
                            </div>

                            {/* Musa Category */}
                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    Categoria Musa
                                </label>
                                <div className="grid grid-cols-2 gap-2">
                                    {MUSA_CATEGORIES.map(cat => (
                                        <button
                                            key={cat}
                                            type="button"
                                            onClick={() => set('musaCategory', cat)}
                                            className={`py-2 px-3 rounded-lg border-2 text-sm font-semibold transition-all ${form.musaCategory === cat
                                                ? 'border-amber-500 bg-amber-50 text-amber-700'
                                                : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300'}`}
                                        >
                                            {cat}
                                        </button>
                                    ))}
                                </div>
                            </div>
                        </>
                    )}

                    {/* ── STEP 2: Endereço ── */}
                    {step === 2 && (
                        <>
                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    <MapPin size={12} className="inline mr-1" />CEP *
                                </label>
                                <div className="relative">
                                    <input
                                        type="text"
                                        placeholder="00000-000"
                                        maxLength={9}
                                        className={inputClass('zipCode')}
                                        value={form.zipCode}
                                        onChange={e => {
                                            const v = e.target.value.replace(/\D/g, '').replace(/^(\d{5})(\d)/, '$1-$2');
                                            set('zipCode', v);
                                            if (v.replace(/\D/g, '').length === 8) lookupCep(v);
                                        }}
                                    />
                                    {cepLoading && (
                                        <Loader2 size={16} className="absolute right-3 top-1/2 -translate-y-1/2 animate-spin text-amber-500" />
                                    )}
                                </div>
                                {errors.zipCode && <p className="text-red-500 text-xs mt-1">{errors.zipCode}</p>}
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    Rua / Logradouro *
                                </label>
                                <input
                                    type="text"
                                    placeholder="Rua das Flores"
                                    className={inputClass('street')}
                                    value={form.street}
                                    onChange={e => set('street', e.target.value)}
                                />
                                {errors.street && <p className="text-red-500 text-xs mt-1">{errors.street}</p>}
                            </div>

                            <div className="grid grid-cols-2 gap-3">
                                <div>
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Número *
                                    </label>
                                    <input
                                        type="text"
                                        placeholder="123"
                                        className={inputClass('number')}
                                        value={form.number}
                                        onChange={e => set('number', e.target.value)}
                                    />
                                    {errors.number && <p className="text-red-500 text-xs mt-1">{errors.number}</p>}
                                </div>
                                <div>
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Complemento
                                    </label>
                                    <input
                                        type="text"
                                        placeholder="Apto 42"
                                        className={inputClass('complement')}
                                        value={form.complement}
                                        onChange={e => set('complement', e.target.value)}
                                    />
                                </div>
                            </div>

                            <div>
                                <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                    Bairro
                                </label>
                                <input
                                    type="text"
                                    placeholder="Centro"
                                    className={inputClass('neighborhood')}
                                    value={form.neighborhood}
                                    onChange={e => set('neighborhood', e.target.value)}
                                />
                            </div>

                            <div className="grid grid-cols-3 gap-3">
                                <div className="col-span-2">
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        Cidade *
                                    </label>
                                    <input
                                        type="text"
                                        placeholder="São Paulo"
                                        className={inputClass('city')}
                                        value={form.city}
                                        onChange={e => set('city', e.target.value)}
                                    />
                                    {errors.city && <p className="text-red-500 text-xs mt-1">{errors.city}</p>}
                                </div>
                                <div>
                                    <label className="block text-xs font-semibold text-slate-600 mb-1.5 uppercase tracking-wide">
                                        UF
                                    </label>
                                    <select
                                        className="w-full px-3 py-2.5 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-amber-400/50"
                                        value={form.state}
                                        onChange={e => set('state', e.target.value)}
                                    >
                                        {ESTADOS_BR.map(uf => (
                                            <option key={uf} value={uf}>{uf}</option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                        </>
                    )}

                    {/* Error message */}
                    {submitError && (
                        <div className="bg-red-50 border border-red-200 rounded-lg p-3 text-red-700 text-sm">
                            {submitError}
                        </div>
                    )}
                </div>

                {/* Footer */}
                <div className="border-t border-slate-100 px-6 py-4 flex justify-between items-center bg-slate-50">
                    <button
                        onClick={back}
                        disabled={step === 0}
                        className="flex items-center gap-2 px-4 py-2 rounded-lg text-slate-600 border border-slate-200 hover:bg-slate-100 disabled:opacity-30 disabled:cursor-not-allowed transition-all font-medium text-sm"
                    >
                        <ChevronLeft size={16} /> Voltar
                    </button>

                    <div className="flex items-center gap-2">
                        {STEPS.map((_, i) => (
                            <div
                                key={i}
                                className={`w-2 h-2 rounded-full transition-all ${i === step ? 'bg-amber-500 w-5' : i < step ? 'bg-amber-300' : 'bg-slate-300'}`}
                            />
                        ))}
                    </div>

                    {step < 2 ? (
                        <button
                            onClick={next}
                            className="flex items-center gap-2 px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-lg transition-all shadow-sm text-sm"
                        >
                            Próximo <ChevronRight size={16} />
                        </button>
                    ) : (
                        <button
                            onClick={handleSubmit}
                            disabled={loading}
                            className="flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition-all shadow-sm text-sm disabled:opacity-60"
                        >
                            {loading ? (
                                <><Loader2 size={16} className="animate-spin" /> Criando...</>
                            ) : (
                                <><CheckCircle size={16} /> Criar Assinante</>
                            )}
                        </button>
                    )}
                </div>
            </div>
        </div>
    );
};

export default CreateSubscriberModal;
