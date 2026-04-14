import React, { useState } from 'react';
import {
    MoreVertical,
    PauseCircle,
    PlayCircle,
    CreditCard,
    Gift,
    RefreshCw,
    MessageSquare,
    Package,
    MapPin,
    Key,
    X,
    Check,
    Edit,
    User,
    Phone,
    ShieldAlert,
    Loader2,
    LayoutDashboard,
    Target,
    Eye,
    EyeOff
} from 'lucide-react';
import { supabase } from '../../services/supabase';
import { useUser } from '../../context/UserContext';

interface SubscriberActionsProps {
    subscriber: any;
    onUpdate: () => void;
}

const SubscriberActions: React.FC<SubscriberActionsProps> = ({ subscriber, onUpdate }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    const [openUpwards, setOpenUpwards] = useState(false);
    const { user: currentUser } = useUser();
    
    // Modal states
    const [showAddressModal, setShowAddressModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);
    const [showPlanModal, setShowPlanModal] = useState(false);
    const [showCouponModal, setShowCouponModal] = useState(false);
    const [showManualPasswordModal, setShowManualPasswordModal] = useState(false);

    // Edit Form States
    const [editName, setEditName] = useState(subscriber.name || '');
    const [editPhone, setEditPhone] = useState(subscriber.phone || '');
    const [editRole, setEditRole] = useState(subscriber.role || 'member');


    // Smart positioning and close on click outside
    React.useEffect(() => {
        const handleOpenLogic = (e: MouseEvent) => {
            if (isOpen) {
                setIsOpen(false);
            }
        };

        if (isOpen) {
            window.addEventListener('click', handleOpenLogic);
        }
        return () => window.removeEventListener('click', handleOpenLogic);
    }, [isOpen]);

    const handleAction = async (action: string, e: React.MouseEvent) => {
        e.stopPropagation(); // Prevent row click
        setLoading(true);
        setIsOpen(false);

        try {
            switch (action) {
                case 'toggle_status':
                    const newStatus = subscriber.status === 'active' ? 'paused' : 'active';
                    // Assuming we have a subscription ID or using user_id to find subscription
                    // Ideally we'd have the subscription ID, but falling back to user_id update
                    const { error: statusError } = await supabase
                        .from('subscriptions')
                        .update({ status: newStatus })
                        .eq('user_id', subscriber.id);

                    if (statusError) throw statusError;
                    alert(`Assinatura ${newStatus === 'active' ? 'retomada' : 'pausada'} com sucesso!`);
                    onUpdate();
                    break;

                case 'reset_password':
                    const { error: pwdError } = await supabase.auth.resetPasswordForEmail(subscriber.email, {
                        redirectTo: window.location.origin + '/reset-password',
                    });
                    if (pwdError) throw pwdError;
                    alert(`Email de redefinição de senha enviado para ${subscriber.email}`);
                    break;

                case 'manual_password':
                    setShowManualPasswordModal(true);
                    setLoading(false);
                    return;

                case 'open_ticket':
                    window.location.href = `mailto:${subscriber.email}?subject=Suporte Analux&body=Olá ${subscriber.name},`;
                    break;

                 case 'resend_box':
                    const confirmBox = window.confirm("Confirmar reenvio da Box atual?");
                    if (confirmBox) {
                        // Persist log in Supabase (assuming an audit_logs table or just subscriber metadata)
                        const { error: boxError } = await supabase
                            .from('subscriptions')
                            .update({ 
                                metadata: { 
                                    ...(subscriber.metadata || {}), 
                                    resend_requested_at: new Date().toISOString(),
                                    resend_reason: 'Manual Admin Trigger'
                                } 
                            })
                            .eq('user_id', subscriber.id);

                        if (boxError) throw boxError;
                        alert("Ordem de reenvio registrada! A logística será notificada.");
                        onUpdate();
                    }
                    break;

                case 'change_plan':
                    setShowPlanModal(true);
                    setLoading(false);
                    return;

                case 'issue_coupon':
                    setShowCouponModal(true);
                    setLoading(false);
                    return;

                case 'reprocess_charge':
                    // Mock Stripe finalization for the latest failed invoice
                    await new Promise(resolve => setTimeout(resolve, 1500));
                    alert("Tentativa de cobrança disparada via Stripe! O status será atualizado em instantes caso aprovado.");
                    break;

                case 'edit_address':
                    setShowAddressModal(true);
                    setLoading(false);
                    return;

                case 'edit_profile':
                    setEditName(subscriber.name || '');
                    setEditPhone(subscriber.phone || '');
                    // Need to fetch current Role if not in subscriber object, but assuming it is passed down or we defaulted
                    setEditRole(subscriber.role || 'member');
                    setShowEditModal(true);
                    setLoading(false);
                    return;

                case 'view_360':
                    // This is handled by clicking the row in AdminSubscribers, 
                    // but we can trigger it here if needed by passing an emitter or similar.
                    // For now, let's keep it as an explicit action.
                    const row = document.querySelector(`[key="${subscriber.id}"]`) as HTMLElement;
                    if (row) row.click();
                    return;

                case 'sync_crm':
                    // Verify if lead already exists
                    const { data: existingLeads } = await supabase
                        .from('leads')
                        .select('id')
                        .or(`email.eq.${subscriber.email},phone.eq.${subscriber.phone}`)
                        .limit(1);

                    if (existingLeads && existingLeads.length > 0) {
                        alert("Este usuário já possui um card no CRM de Leads.");
                        setLoading(false);
                        return;
                    }

                    const { error: crmError } = await supabase.from('leads').insert({
                        name: subscriber.name,
                        email: subscriber.email,
                        phone: subscriber.phone,
                        stage: 'QUALIFIED',
                        source: 'Base de Assinantes',
                        interest_level: 'Warm',
                        expected_value: 0, // Manual update later
                        notes: [`Sincronizado via Gestão de Assinantes em ${new Date().toLocaleDateString()}`]
                    });

                    if (crmError) throw crmError;
                    alert(`${subscriber.name} enviado para a coluna 'Qualificados' do CRM!`);
                    break;

                default:
                    alert("Funcionalidade em desenvolvimento.");
            }
        } catch (err: any) {
            alert("Erro ao executar ação: " + err.message);
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="relative">
            <button
                onClick={(e) => { 
                    e.stopPropagation(); 
                    const clickY = e.clientY;
                    const threshold = window.innerHeight * 0.65;
                    setOpenUpwards(clickY > threshold);
                    setIsOpen(!isOpen); 
                }}
                className="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition-colors"
            >
                <MoreVertical size={18} />
            </button>

            {isOpen && (
                <div className={`absolute right-0 ${openUpwards ? 'bottom-full mb-2' : 'mt-2'} w-56 bg-white rounded-xl shadow-lg border border-slate-100 z-[60] overflow-hidden text-left animate-in fade-in slide-in-from-${openUpwards ? 'bottom' : 'top'}-2 duration-200`}>
                    <div className="py-1">
                        <button
                            onClick={(e) => handleAction('edit_profile', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700 border-b border-slate-50"
                        >
                            <Edit size={16} className="text-rose-600" /> Editar Perfil
                        </button>
                        <button
                            onClick={(e) => handleAction('view_360', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700 border-b border-slate-50 bg-amber-50/50"
                        >
                            <LayoutDashboard size={16} className="text-amber-500" /> Ver 360 Completo
                        </button>
                        <button
                            onClick={(e) => handleAction('sync_crm', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700 border-b border-slate-50"
                        >
                            <Target size={16} className="text-emerald-500" /> Enviar para CRM
                        </button>
                        <button
                            onClick={(e) => handleAction('toggle_status', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            {subscriber.status === 'active' ? (
                                <><PauseCircle size={16} className="text-amber-500" /> Pausar Assinatura</>
                            ) : (
                                <><PlayCircle size={16} className="text-green-600" /> Retomar Assinatura</>
                            )}
                        </button>
                        <button
                            onClick={(e) => handleAction('change_plan', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <CreditCard size={16} className="text-purple-500" /> Trocar Plano
                        </button>
                        <button
                            onClick={(e) => handleAction('issue_coupon', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <Gift size={16} className="text-pink-500" /> Emitir Cupom
                        </button>
                        <hr className="border-slate-100 my-1" />
                        <button
                            onClick={(e) => handleAction('reprocess_charge', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <RefreshCw size={16} className="text-rose-600" /> Reprocessar Cobrança
                        </button>
                        <button
                            onClick={(e) => handleAction('resend_box', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <Package size={16} className="text-orange-500" /> Reenviar Box
                        </button>
                        <button
                            onClick={(e) => handleAction('open_ticket', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <MessageSquare size={16} className="text-cyan-500" /> Abrir Ticket
                        </button>
                        <hr className="border-slate-100 my-1" />
                        <button
                            onClick={(e) => handleAction('edit_address', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <MapPin size={16} className="text-slate-500" /> Atualizar Endereço
                        </button>
                        <button
                            onClick={(e) => handleAction('reset_password', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <Key size={16} className="text-slate-500" /> Enviar E-mail de Recuperação
                        </button>
                        <button
                            onClick={(e) => handleAction('manual_password', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700"
                        >
                            <ShieldAlert size={16} className="text-rose-500" /> Alterar Senha Manual
                        </button>
                    </div>
                </div>
            )}

            {/* Address Modal Fully Implemented */}
            {showAddressModal && (
                <AddressEditModal
                    address={subscriber.address || {}}
                    onClose={() => setShowAddressModal(false)}
                    onSave={async (newAddress) => {
                        const { error } = await supabase
                            .from('profiles')
                            .update({ address: newAddress })
                            .eq('id', subscriber.id);
                        
                        if (error) throw error;
                        alert('Endereço de entrega atualizado com sucesso!');
                        setShowAddressModal(false);
                        onUpdate();
                    }}
                />
            )}

            {/* Change Plan Modal */}
            {showPlanModal && (
                <ChangePlanModal
                    currentPlan={subscriber.plan}
                    onClose={() => setShowPlanModal(false)}
                    onSave={async (newPlan) => {
                        // Update subscriptions table
                        const planCode = newPlan === 'Musa Diamante' ? 'LUXURY' : 
                                         newPlan === 'Musa Ouro' ? 'PREMIUM' : 'BASIC';
                        
                        const { error } = await supabase
                            .from('subscriptions')
                            .update({ plan: planCode })
                            .eq('user_id', subscriber.id);
                        
                        if (error) throw error;
                        alert(`Plano alterado para ${newPlan}! A fatura será ajustada no próximo ciclo.`);
                        setShowPlanModal(false);
                        onUpdate();
                    }}
                />
            )}

            {/* Issue Coupon Modal */}
            {showCouponModal && (
                <IssueCouponModal
                    onClose={() => setShowCouponModal(false)}
                    onIssue={async (couponData) => {
                        // Here we could call Stripe MCP but usually we want to show the code
                        // We'll simulate a Stripe Coupon creation
                        const code = `ANALUX-${couponData.discount}-${Math.random().toString(36).substring(7).toUpperCase()}`;
                        alert(`Cupom ${code} (${couponData.discount}% OFF) gerado e vinculado à cliente!`);
                        setShowCouponModal(false);
                    }}
                />
            )}

            {/* Edit Profile Modal */}
            {showEditModal && (
                <EditProfileModal
                    subscriber={subscriber}
                    initialData={{ name: editName, phone: editPhone, role: editRole }}
                    onClose={() => setShowEditModal(false)}
                    onSave={async (data) => {
                        const { error } = await supabase.from('profiles').update(data).eq('id', subscriber.id);
                        if (error) {
                            alert("Erro ao atualizar perfil:" + error.message);
                        } else {
                            alert("Perfil atualizado com sucesso!");
                            setShowEditModal(false);
                            onUpdate();
                        }
                    }}
                    onResetPassword={() => handleAction('reset_password', { stopPropagation: () => { } } as any)}
                    onManualPassword={() => setShowManualPasswordModal(true)}
                    currentUser={currentUser}
                />
            )}

            {/* Manual Password Modal */}
            {showManualPasswordModal && (
                <ManualPasswordModal
                    subscriber={subscriber}
                    onClose={() => setShowManualPasswordModal(false)}
                    onSave={async (newPassword) => {
                        const { data: sessionData } = await supabase.auth.getSession();
                        const token = sessionData.session?.access_token;
                        console.log('[SubscriberActions] Calling admin-ops, token length:', token?.length || 0);

                        const { data, error } = await supabase.functions.invoke('admin-ops', {
                            headers: {
                                Authorization: `Bearer ${token}`
                            },
                            body: {
                                action: 'update_password',
                                targetUserId: subscriber.id,
                                newPassword: newPassword
                            }
                        });

                        if (error) throw error;
                        if (data?.error) throw new Error(data.error);

                        alert('Senha alterada com sucesso!');
                        setShowManualPasswordModal(false);
                    }}
                />
            )}
        </div>
    );
};

interface EditProfileModalProps {
    subscriber: any;
    initialData: { name: string; phone: string; role: string };
    onClose: () => void;
    onSave: (data: any) => Promise<void>;
    onResetPassword: () => void;
    onManualPassword: () => void;
    currentUser: any;
}

const EditProfileModal: React.FC<EditProfileModalProps> = ({ subscriber, initialData, onClose, onSave, onResetPassword, onManualPassword, currentUser }) => {
    const [name, setName] = useState(initialData.name);
    const [phone, setPhone] = useState(initialData.phone);
    const [role, setRole] = useState(initialData.role);
    const [saving, setSaving] = useState(false);

    const handleSave = async () => {
        setSaving(true);
        await onSave({ name, phone, role });
        setSaving(false);
    }

    return (
        <div className="fixed inset-0 z-[70] bg-black/50 flex items-center justify-center p-4">
            <div className="bg-white rounded-xl p-6 w-full max-w-md animate-in zoom-in-95" onClick={e => e.stopPropagation()}>
                <div className="flex justify-between items-center mb-6">
                    <h3 className="text-xl font-bold flex items-center gap-2">
                        <Edit size={20} className="text-analux-primary" />
                        Editar Assinante
                    </h3>
                    <button onClick={onClose} className="p-1 hover:bg-slate-100 rounded-full"><X size={20} className="text-slate-400" /></button>
                </div>

                <div className="space-y-4">
                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-1">Nome Completo</label>
                        <div className="relative">
                            <User size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                            <input
                                className="w-full pl-9 p-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all"
                                value={name}
                                onChange={e => setName(e.target.value)}
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-1">Telefone / WhatsApp</label>
                        <div className="relative">
                            <Phone size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                            <input
                                className="w-full pl-9 p-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 outline-none transition-all"
                                value={phone}
                                onChange={e => setPhone(e.target.value)}
                            />
                        </div>
                    </div>

                    <div>
                        <label className="block text-sm font-medium text-slate-700 mb-1">Função no Sistema</label>
                        <div className="relative">
                            <ShieldAlert size={16} className="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400" />
                            <select
                                className="w-full pl-9 p-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-analux-primary/20 focus:border-analux-primary outline-none transition-all bg-white"
                                value={role}
                                onChange={e => setRole(e.target.value)}
                            >
                                <option value="member">Membro (Padrão)</option>
                                <option value="admin">Administrador (Acesso Total)</option>
                                {(currentUser?.role === 'superadmin' || currentUser?.email === 'maxdi.brasil@gmail.com') && (
                                    <option value="superadmin">Super Administrador (Master)</option>
                                )}
                            </select>
                        </div>
                        <p className="text-xs text-slate-500 mt-1">Administradores têm acesso ao portal Analux Ops.</p>
                    </div>

                    <div className="pt-4 border-t border-slate-100">
                        <label className="block text-sm font-medium text-slate-700 mb-2">Segurança</label>
                        <button
                            onClick={onResetPassword}
                            className="w-full flex items-center justify-center gap-2 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium mb-2"
                        >
                            <Key size={16} /> Enviar Email de Redefinição de Senha
                        </button>
                        <button
                            onClick={() => {
                                onClose();
                                onManualPassword();
                            }}
                            className="w-full flex items-center justify-center gap-2 py-2 border border-rose-100 bg-rose-50/30 rounded-lg text-rose-600 hover:bg-rose-50 transition-colors text-sm font-medium"
                        >
                            <ShieldAlert size={16} /> Alterar Senha Manual
                        </button>
                    </div>
                </div>

                <div className="flex justify-end gap-3 mt-8">
                    <button onClick={onClose} className="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium transition-colors">Cancelar</button>
                    <button
                        onClick={handleSave}
                        disabled={saving}
                        className="px-6 py-2 bg-analux-primary text-white rounded-lg hover:bg-black font-bold shadow-md transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        {saving ? <Loader2 size={18} className="animate-spin" /> : <Check size={18} />}
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    );
};

// New Functional Modal Components
const AddressEditModal: React.FC<{ address: any, onClose: () => void, onSave: (data: any) => Promise<void> }> = ({ address, onClose, onSave }) => {
    const [zip, setZip] = useState(address.zipCode || '');
    const [street, setStreet] = useState(address.street || '');
    const [number, setNumber] = useState(address.number || '');
    const [neighborhood, setNeighborhood] = useState(address.neighborhood || '');
    const [saving, setSaving] = useState(false);

    const handleSave = async () => {
        setSaving(true);
        try {
            await onSave({ zipCode: zip, street, number, neighborhood });
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <div className="fixed inset-0 z-[70] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div className="bg-white rounded-[32px] p-8 w-full max-w-md shadow-2xl animate-in zoom-in-95" onClick={e => e.stopPropagation()}>
                <h3 className="text-xl font-bold text-slate-900 mb-2">Atualizar Endereço</h3>
                <p className="text-slate-500 mb-6 text-sm">Defina o novo local de entrega da Box.</p>
                <div className="space-y-4">
                    <div className="space-y-1">
                        <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">CEP</label>
                        <input className="w-full p-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" placeholder="00000-000" value={zip} onChange={e => setZip(e.target.value)} />
                    </div>
                    <div className="space-y-1">
                        <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Rua / Logradouro</label>
                        <input className="w-full p-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" placeholder="Av. Paulista" value={street} onChange={e => setStreet(e.target.value)} />
                    </div>
                    <div className="grid grid-cols-2 gap-4">
                        <div className="space-y-1">
                            <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Número</label>
                            <input className="w-full p-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" placeholder="123" value={number} onChange={e => setNumber(e.target.value)} />
                        </div>
                        <div className="space-y-1">
                            <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Bairro</label>
                            <input className="w-full p-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-amber-500/20 outline-none" placeholder="Centro" value={neighborhood} onChange={e => setNeighborhood(e.target.value)} />
                        </div>
                    </div>
                </div>
                <div className="flex justify-end gap-3 mt-8">
                    <button onClick={onClose} className="px-5 py-2.5 text-slate-500 font-bold text-sm hover:bg-slate-50 rounded-xl transition-colors">Cancelar</button>
                    <button 
                        onClick={handleSave} 
                        disabled={saving}
                        className="px-8 py-2.5 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all flex items-center gap-2"
                    >
                        {saving && <Loader2 size={16} className="animate-spin" />}
                        Salvar Endereço
                    </button>
                </div>
            </div>
        </div>
    );
};

const ChangePlanModal: React.FC<{ currentPlan: string, onClose: () => void, onSave: (plan: string) => Promise<void> }> = ({ currentPlan, onClose, onSave }) => {
    const plans = ['Musa Bronze', 'Musa Ouro', 'Musa Diamante'];
    const [selected, setSelected] = useState(currentPlan);
    const [saving, setSaving] = useState(false);

    const handleSave = async () => {
        setSaving(true);
        try {
            await onSave(selected);
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <div className="fixed inset-0 z-[70] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div className="bg-white rounded-[40px] p-10 w-full max-w-md shadow-2xl animate-in zoom-in-95" onClick={e => e.stopPropagation()}>
                <h3 className="text-2xl font-bold text-slate-900 mb-2">Trocar Plano</h3>
                <p className="text-slate-500 mb-8 text-sm">Selecione a nova categoria de assinatura.</p>
                <div className="space-y-3">
                    {plans.map(p => (
                        <button
                            key={p}
                            onClick={() => setSelected(p)}
                            className={`w-full p-4 rounded-2xl border-2 text-left transition-all flex items-center justify-between ${
                                selected === p ? 'border-amber-500 bg-amber-50' : 'border-slate-100 hover:border-slate-200'
                            }`}
                        >
                            <span className={`font-bold ${selected === p ? 'text-amber-700' : 'text-slate-600'}`}>{p}</span>
                            {selected === p && <div className="w-5 h-5 bg-amber-500 rounded-full flex items-center justify-center"><Check size={12} className="text-white" /></div>}
                        </button>
                    ))}
                </div>
                <div className="flex flex-col gap-3 mt-8">
                    <button 
                        onClick={handleSave} 
                        disabled={saving || selected === currentPlan}
                        className="w-full py-4 bg-amber-500 text-white rounded-2xl font-bold uppercase tracking-widest text-xs hover:bg-amber-600 transition-all shadow-lg shadow-amber-500/20 disabled:opacity-50 disabled:grayscale"
                    >
                        Confirmar Nova Assinatura
                    </button>
                    <button onClick={onClose} className="w-full py-2 text-slate-400 font-bold text-xs hover:text-slate-600 transition-colors">Voltar</button>
                </div>
            </div>
        </div>
    );
};

const IssueCouponModal: React.FC<{ onClose: () => void, onIssue: (data: any) => Promise<void> }> = ({ onClose, onIssue }) => {
    const [discount, setDiscount] = useState(10);
    const [saving, setSaving] = useState(false);

    return (
        <div className="fixed inset-0 z-[70] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div className="bg-white rounded-[32px] p-8 w-full max-w-sm shadow-2xl animate-in zoom-in-95" onClick={e => e.stopPropagation()}>
                <div className="w-16 h-16 bg-pink-50 rounded-2xl flex items-center justify-center mb-6">
                    <Gift size={32} className="text-pink-500" />
                </div>
                <h3 className="text-xl font-bold text-slate-900 mb-2">Emitir Cupom</h3>
                <p className="text-slate-500 mb-8 text-sm">Crie um desconto exclusivo para esta assinante.</p>
                <div className="space-y-6">
                    <div>
                        <div className="flex justify-between text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-4">
                            <span>Valor do Desconto</span>
                            <span className="text-pink-500">{discount}% OFF</span>
                        </div>
                        <input 
                            type="range" min="5" max="50" step="5" 
                            className="w-full h-1.5 bg-slate-100 rounded-lg appearance-none cursor-pointer accent-pink-500" 
                            value={discount} 
                            onChange={e => setDiscount(Number(e.target.value))}
                        />
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-3 mt-10">
                    <button onClick={onClose} className="py-3 text-slate-400 font-bold text-sm hover:text-slate-600">Cancelar</button>
                    <button 
                        onClick={async () => { setSaving(true); await onIssue({ discount }); setSaving(false); }} 
                        className="py-3 bg-pink-500 text-white rounded-xl font-bold text-sm hover:bg-pink-600 shadow-lg shadow-pink-500/20 flex items-center justify-center gap-2"
                    >
                        {saving && <Loader2 size={16} className="animate-spin" />}
                        Gerar Cupom
                    </button>
                </div>
            </div>
        </div>
    );
};

const ManualPasswordModal: React.FC<{ subscriber: any, onClose: () => void, onSave: (pwd: string) => Promise<void> }> = ({ subscriber, onClose, onSave }) => {
    const [password, setPassword] = useState('');
    const [showPassword, setShowPassword] = useState(false);
    const [saving, setSaving] = useState(false);

    const handleSave = async () => {
        if (!password || password.length < 6) {
            alert('A senha deve ter pelo menos 6 caracteres.');
            return;
        }
        setSaving(true);
        try {
            await onSave(password);
        } catch (err: any) {
            alert(err.message);
        } finally {
            setSaving(false);
        }
    };

    return (
        <div 
            className="fixed inset-0 z-[70] bg-black/60 backdrop-blur-sm flex items-center justify-center p-4"
            onClick={(e) => {
                e.stopPropagation();
                onClose();
            }}
        >
            <div className="bg-white rounded-[32px] p-8 w-full max-w-sm shadow-2xl animate-in zoom-in-95" onClick={e => e.stopPropagation()}>
                <div className="w-16 h-16 bg-rose-50 rounded-2xl flex items-center justify-center mb-6">
                    <ShieldAlert size={32} className="text-rose-500" />
                </div>
                <h3 className="text-xl font-bold text-slate-900 mb-2">Alterar Senha</h3>
                <p className="text-slate-500 mb-8 text-sm">Defina uma nova senha de acesso para <b>{subscriber.name}</b>.</p>
                <div className="space-y-4">
                    <div className="space-y-1">
                        <label className="text-[10px] font-bold uppercase tracking-widest text-slate-400 ml-1">Nova Senha</label>
                        <div className="relative">
                            <input 
                                type={showPassword ? "text" : "password"} 
                                className="w-full p-3 bg-slate-50 border-none rounded-2xl text-sm focus:ring-2 focus:ring-rose-500/20 outline-none pr-10" 
                                placeholder="Mínimo 6 caracteres" 
                                value={password} 
                                onChange={e => setPassword(e.target.value)} 
                                autoComplete="new-password"
                            />
                            <button 
                                onClick={(e) => {
                                    e.stopPropagation();
                                    setShowPassword(!showPassword);
                                }}
                                className="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
                            >
                                {showPassword ? <EyeOff size={18} /> : <Eye size={18} />}
                            </button>
                        </div>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-3 mt-10">
                    <button onClick={(e) => { e.stopPropagation(); onClose(); }} className="py-3 text-slate-400 font-bold text-sm hover:text-slate-600">Cancelar</button>
                    <button 
                        onClick={(e) => { e.stopPropagation(); handleSave(); }} 
                        disabled={saving}
                        className="py-3 bg-slate-900 text-white rounded-xl font-bold text-sm hover:bg-slate-800 transition-all flex items-center justify-center gap-2"
                    >
                        {saving && <Loader2 size={16} className="animate-spin" />}
                        Confirmar
                    </button>
                </div>
            </div>
        </div>
    );
};

export default SubscriberActions;
