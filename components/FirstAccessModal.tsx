import React, { useState } from 'react';
import { Lock, Eye, EyeOff, Save, CheckCircle } from 'lucide-react';
import { useUser } from '../context/UserContext';
import { supabase } from '../services/supabase';

const FirstAccessModal: React.FC = () => {
    const { user, refreshData } = useUser();
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [error, setError] = useState('');
    const [loading, setLoading] = useState(false);
    const [step, setStep] = useState<1 | 2>(1); // 1: Senha, 2: Checkout

    const handleSubmitPassword = async (e: React.FormEvent) => {
        e.preventDefault();
        setError('');

        if (password.length < 6) {
            setError('A senha deve ter pelo menos 6 caracteres.');
            return;
        }

        if (password !== confirmPassword) {
            setError('As senhas não coincidem.');
            return;
        }

        setLoading(true);

        try {
            // Atualiza a senha no Supabase Auth
            const { error: authError } = await supabase.auth.updateUser({
                password: password,
            });

            if (authError) throw authError;

            // Avança para o próximo passo se deu tudo certo com a senha.
            // Mas ainda não removemos o bloqueio de "needs_password_reset",
            // Ele só é removido no Step 2: Revisar Pagamento.
            setStep(2);

        } catch (err: any) {
            console.error('Erro ao atualizar senha', err);
            setError(err.message || 'Ocorreu um erro ao redefinir a senha.');
        } finally {
            setLoading(false);
        }
    };

    const handleConfirmPaymentReview = async () => {
        setLoading(true);
        try {
            // Remove a badge do metadata
            const { error: metadataError } = await supabase.auth.updateUser({
                data: { needs_password_reset: false }
            });

            if (metadataError) throw metadataError;

            // Recarrega o app, o que deve sumir com essa tela
            await refreshData();
        } catch (err: any) {
            console.error('Erro ao concluir primeiro acesso', err);
            setError(err.message || 'Erro ao finalizar.');
        } finally {
            setLoading(false);
        }
    };

    const handleGoToStripe = async () => {
        setLoading(true);
        setError('');
        try {
            const { data, error: proxyError } = await supabase.functions.invoke('stripe-proxy', {
                body: { action: 'create_portal_session', returnUrl: window.location.href }
            });

            if (proxyError) throw proxyError;

            if (data?.url) {
                window.location.href = data.url;
            } else {
                throw new Error("URL do portal não encontrada.");
            }
        } catch (err: any) {
            console.error('Error creating portal session:', err);
            setError('Falha ao conectar com o gateway de pagamento. Tente novamente.');
        } finally {
            setLoading(false);
        }
    };


    return (
        <div className="fixed inset-0 z-[9999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
            <div className="bg-white max-w-md w-full rounded-2xl shadow-xl overflow-hidden relative">
                <div className="bg-amber-100 p-6 text-center">
                    <div className="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm">
                        <Lock className="w-8 h-8 text-amber-600" />
                    </div>
                    <h2 className="text-2xl font-serif text-slate-800">Bem-vinda ao Novo Portal!</h2>
                    <p className="text-slate-600 mt-2 text-sm">
                        Para sua segurança e para aproveitar todas as novidades, precisamos que você atualize algumas informações.
                    </p>
                </div>

                <div className="p-6">
                    {error && (
                        <div className="mb-6 p-3 bg-red-50 text-red-600 text-sm rounded-lg border border-red-100">
                            {error}
                        </div>
                    )}

                    {step === 1 ? (
                        <form onSubmit={handleSubmitPassword} className="space-y-4">
                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">
                                    Crie sua nova senha de acesso
                                </label>
                                <div className="relative">
                                    <input
                                        type={showPassword ? "text" : "password"}
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        className="w-full pl-4 pr-10 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all font-mono text-lg"
                                        placeholder="••••••••"
                                        required
                                        minLength={6}
                                        disabled={loading}
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition-colors"
                                    >
                                        {showPassword ? <EyeOff className="w-5 h-5" /> : <Eye className="w-5 h-5" />}
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label className="block text-sm font-medium text-slate-700 mb-1">
                                    Confirme a nova senha
                                </label>
                                <input
                                    type="password"
                                    value={confirmPassword}
                                    onChange={(e) => setConfirmPassword(e.target.value)}
                                    className="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-amber-500 transition-all font-mono text-lg"
                                    placeholder="••••••••"
                                    required
                                    disabled={loading}
                                />
                            </div>

                            <button
                                type="submit"
                                disabled={loading || !password || !confirmPassword}
                                className="w-full flex items-center justify-center gap-2 bg-amber-600 hover:bg-amber-700 text-white py-3 rounded-xl font-medium transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-6"
                            >
                                {loading ? 'Salvando...' : 'Atualizar Senha'}
                            </button>
                        </form>
                    ) : (
                        <div className="space-y-6 text-center">
                            <div className="flex flex-col items-center">
                                <div className="w-12 h-12 bg-green-100 text-green-600 rounded-full flex items-center justify-center mb-4">
                                    <CheckCircle className="w-6 h-6" />
                                </div>
                                <h3 className="text-xl font-medium text-slate-800">Senha atualizada!</h3>
                                <p className="text-slate-500 text-sm mt-2">
                                    Último passo: Revise seu método de pagamento para garantir a continuidade da sua assinatura.
                                </p>
                            </div>

                            <div className="space-y-3">
                                <button
                                    onClick={handleGoToStripe}
                                    disabled={loading}
                                    className="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white py-3 rounded-xl font-medium transition-colors"
                                >
                                    Revisar Pagamento com Stripe
                                </button>

                                <button
                                    onClick={handleConfirmPaymentReview}
                                    disabled={loading}
                                    className="w-full flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white py-3 rounded-xl font-medium transition-colors"
                                >
                                    Já revisei / Tudo Certo
                                </button>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default FirstAccessModal;
