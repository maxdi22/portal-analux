
import React, { useState } from 'react';
import { X, Mail, Lock, User as UserIcon, ArrowRight, Loader2, Eye, EyeOff } from 'lucide-react';
import { useUser } from '../context/UserContext';

interface AuthModalProps {
    onClose: () => void;
    initialMode?: 'LOGIN' | 'REGISTER';
    customWelcomeMessage?: string;
    onAuthSuccess?: () => void;
}

type AuthMode = 'LOGIN' | 'REGISTER' | 'RECOVER';

const AuthModal: React.FC<AuthModalProps> = ({ onClose, initialMode = 'LOGIN', customWelcomeMessage, onAuthSuccess }) => {
    const { signIn, signUp, resetPassword } = useUser();
    const [mode, setMode] = useState<AuthMode>(initialMode);

    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState(''); // NEW state
    const [showPassword, setShowPassword] = useState(false); // NEW toggle state
    const [showConfirmPassword, setShowConfirmPassword] = useState(false); // NEW toggle state

    const [loading, setLoading] = useState(false);
    const [message, setMessage] = useState<{ text: string, type: 'error' | 'success' } | null>(null);

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setMessage(null);

        try {
            if (mode === 'LOGIN') {
                const { error } = await signIn(email, password);
                if (error) throw error;
                // Success handled by router/context state change
                if (onAuthSuccess) onAuthSuccess();
                onClose();
            } else if (mode === 'REGISTER') {
                if (password !== confirmPassword) {
                    setMessage({ text: 'As senhas não coincidem.', type: 'error' });
                    setLoading(false);
                    return;
                }
                const { error, data } = await signUp(email, password, name);

                // Removed Debug Alerts

                if (error) throw error;

                if (data?.user && data.user.identities && data.user.identities.length === 0) {
                    setMessage({ text: 'Esta conta já existe. Tente fazer login.', type: 'error' });
                } else if (!data.session) {
                    setMessage({ text: 'Conta criada! Confirme seu e-mail para acessar.', type: 'success' });
                } else {
                    setMessage({ text: 'Conta criada com sucesso! Redirecionando...', type: 'success' });
                    setTimeout(() => {
                        if (onAuthSuccess) onAuthSuccess();
                        onClose();
                    }, 2000);
                }
            } else if (mode === 'RECOVER') {
                const { error } = await resetPassword(email);
                if (error) throw error;
                setMessage({ text: 'E-mail de recuperação enviado! Verifique sua caixa de entrada.', type: 'success' });
            }
        } catch (err: any) {
            // IMPROVED ERROR HANDLING
            if (err.message === 'User already registered') {
                setMessage({ text: 'Esta conta já existe. Redirecionando para login...', type: 'success' }); // Use success style for better UX
                setTimeout(() => {
                    setMode('LOGIN');
                    setMessage(null);
                }, 1500);
            } else {
                setMessage({ text: err.message || 'Ocorreu um erro. Tente novamente.', type: 'error' });
            }
        } finally {
            if (mode !== 'REGISTER' || (message?.type === 'error' && message.text !== 'Esta conta já existe. Redirecionando para login...')) {
                setLoading(false);
            }
        }
    };

    return (
        <div className="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-black/60 backdrop-blur-sm animate-fadeIn">
            <div className="bg-white w-full max-w-md rounded-[40px] shadow-2xl p-10 relative animate-scaleIn overflow-hidden">

                {/* Decorative Background Elements */}
                <div className="absolute top-0 right-0 w-32 h-32 bg-analux-secondary/10 rounded-full -mr-16 -mt-16 blur-xl pointer-events-none"></div>
                <div className="absolute bottom-0 left-0 w-24 h-24 bg-analux-primary/5 rounded-full -ml-12 -mb-12 blur-lg pointer-events-none"></div>

                <button
                    onClick={onClose}
                    className="absolute top-6 right-6 p-2 text-gray-400 hover:text-analux-primary hover:bg-gray-50 rounded-full transition-all z-10"
                >
                    <X size={20} />
                </button>

                <div className="text-center mb-8 relative z-10">
                    <span className="text-2xl font-serif font-bold tracking-widest text-analux-primary">ANALUX</span>
                    <p className="text-[9px] uppercase tracking-[0.3em] text-analux-secondary mt-1">Portal do Assinante</p>
                </div>

                <div className="relative z-10">
                    <h3 className="text-3xl font-serif text-analux-primary mb-2 text-center text-balance leading-tight">
                        {mode === 'LOGIN' && 'Bem-vinda de volta'}
                        {mode === 'REGISTER' && 'Comece seu Ritual'}
                        {mode === 'RECOVER' && 'Recupere seu Acesso'}
                    </h3>
                    <p className="text-gray-400 text-xs text-center mb-8">
                        {customWelcomeMessage && mode === initialMode ? customWelcomeMessage : (
                            <>
                                {mode === 'LOGIN' && 'Digite suas credenciais para acessar seu universo exclusivo.'}
                                {mode === 'REGISTER' && 'Crie sua conta para desbloquear a experiência completa.'}
                                {mode === 'RECOVER' && 'Enviaremos um link para você redefinir sua senha.'}
                            </>
                        )}
                    </p>

                    <form onSubmit={handleSubmit} className="space-y-5">

                        {mode === 'REGISTER' && (
                            <div className="group space-y-2">
                                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-2 group-focus-within:text-analux-secondary transition-colors">Nome Completo</label>
                                <div className="relative">
                                    <UserIcon size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                                    <input
                                        type="text"
                                        value={name}
                                        onChange={(e) => setName(e.target.value)}
                                        placeholder="Como gostaria de ser chamada?"
                                        className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-12 pr-6 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none transition-all placeholder:text-gray-300"
                                        required
                                    />
                                </div>
                            </div>
                        )}

                        <div className="group space-y-2">
                            <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-2 group-focus-within:text-analux-secondary transition-colors">E-mail</label>
                            <div className="relative">
                                <Mail size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                                <input
                                    type="email"
                                    value={email}
                                    onChange={(e) => setEmail(e.target.value)}
                                    placeholder="seu@email.com"
                                    className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-12 pr-6 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none transition-all placeholder:text-gray-300"
                                    required
                                />
                            </div>
                        </div>

                        {mode !== 'RECOVER' && (
                            <div className="group space-y-2">
                                <div className="flex justify-between items-center px-1">
                                    <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-1 group-focus-within:text-analux-secondary transition-colors">Senha</label>
                                    {mode === 'LOGIN' && (
                                        <button type="button" onClick={() => setMode('RECOVER')} className="text-[10px] text-gray-400 hover:text-analux-secondary underline decoration-analux-secondary/30">
                                            Esqueceu a senha?
                                        </button>
                                    )}
                                </div>
                                <div className="relative">
                                    <Lock size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                                    <input
                                        type={showPassword ? "text" : "password"}
                                        value={password}
                                        onChange={(e) => setPassword(e.target.value)}
                                        placeholder="••••••••"
                                        className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-12 pr-12 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none transition-all placeholder:text-gray-300"
                                        required
                                        minLength={6}
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowPassword(!showPassword)}
                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-analux-secondary transition-colors"
                                    >
                                        {showPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                                    </button>
                                </div>
                            </div>
                        )}

                        {mode === 'REGISTER' && (
                            <div className="group space-y-2">
                                <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest pl-2 group-focus-within:text-analux-secondary transition-colors">Confirmar Senha</label>
                                <div className="relative">
                                    <Lock size={16} className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-300" />
                                    <input
                                        type={showConfirmPassword ? "text" : "password"}
                                        value={confirmPassword}
                                        onChange={(e) => setConfirmPassword(e.target.value)}
                                        placeholder="••••••••"
                                        className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-12 pr-12 text-sm focus:ring-2 focus:ring-analux-secondary/20 outline-none transition-all placeholder:text-gray-300"
                                        required
                                        minLength={6}
                                    />
                                    <button
                                        type="button"
                                        onClick={() => setShowConfirmPassword(!showConfirmPassword)}
                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-analux-secondary transition-colors"
                                    >
                                        {showConfirmPassword ? <EyeOff size={16} /> : <Eye size={16} />}
                                    </button>
                                </div>
                            </div>
                        )}

                        {message && (
                            <div className={`p-4 rounded-2xl text-xs font-medium text-center flex items-center justify-center gap-2 ${message.type === 'error' ? 'bg-red-50 text-red-500' : 'bg-green-50 text-green-600'
                                }`}>
                                {message.text}
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={loading}
                            className="w-full bg-analux-primary text-white py-4 rounded-2xl font-bold uppercase text-xs tracking-widest shadow-lg shadow-analux-primary/20 hover:bg-analux-dark hover:scale-[1.02] active:scale-[0.98] transition-all disabled:opacity-50 disabled:scale-100 flex items-center justify-center gap-2"
                        >
                            {loading ? (
                                <>
                                    <Loader2 size={16} className="animate-spin" />
                                    Processando...
                                </>
                            ) : (
                                <>
                                    {mode === 'LOGIN' && 'Entrar Agora'}
                                    {mode === 'REGISTER' && 'Criar Conta'}
                                    {mode === 'RECOVER' && 'Enviar Link'}
                                    <ArrowRight size={16} />
                                </>
                            )}
                        </button>
                    </form>

                    <div className="mt-8 text-center">
                        {mode === 'LOGIN' ? (
                            <p className="text-xs text-gray-500">
                                Ainda não é assinante?{' '}
                                <button onClick={() => setMode('REGISTER')} className="font-bold text-analux-primary hover:text-analux-secondary transition-colors underline decoration-analux-secondary/30">
                                    Crie sua conta
                                </button>
                            </p>
                        ) : (
                            <p className="text-xs text-gray-500">
                                Já possui conta?{' '}
                                <button onClick={() => setMode('LOGIN')} className="font-bold text-analux-primary hover:text-analux-secondary transition-colors underline decoration-analux-secondary/30">
                                    Fazer Login
                                </button>
                            </p>
                        )}
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AuthModal;
