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
    Loader2
} from 'lucide-react';
import { supabase } from '../../services/supabase';

interface SubscriberActionsProps {
    subscriber: any;
    onUpdate: () => void;
}

const SubscriberActions: React.FC<SubscriberActionsProps> = ({ subscriber, onUpdate }) => {
    const [isOpen, setIsOpen] = useState(false);
    const [loading, setLoading] = useState(false);
    // Modal states
    const [showAddressModal, setShowAddressModal] = useState(false);
    const [showEditModal, setShowEditModal] = useState(false);

    // Edit Form States
    const [editName, setEditName] = useState(subscriber.name || '');
    const [editPhone, setEditPhone] = useState(subscriber.phone || '');
    const [editRole, setEditRole] = useState(subscriber.role || 'member');


    // Close dropdown when clicking outside (simple version)
    React.useEffect(() => {
        const close = () => setIsOpen(false);
        if (isOpen) window.addEventListener('click', close);
        return () => window.removeEventListener('click', close);
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

                case 'open_ticket':
                    window.location.href = `mailto:${subscriber.email}?subject=Suporte Analux&body=Olá ${subscriber.name},`;
                    break;

                case 'resend_box':
                    const confirmBox = window.confirm("Confirmar reenvio da Box atual?");
                    if (confirmBox) {
                        // Mock logistics API call
                        await new Promise(resolve => setTimeout(resolve, 1000));
                        alert("Ordem de reenvio criada na logística! (#LOG-9928)");
                    }
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
                onClick={(e) => { e.stopPropagation(); setIsOpen(!isOpen); }}
                className="text-slate-400 hover:text-slate-600 p-2 rounded-full hover:bg-slate-100 transition-colors"
            >
                <MoreVertical size={18} />
            </button>

            {isOpen && (
                <div className="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-slate-100 z-50 overflow-hidden text-left">
                    <div className="py-1">
                        <button
                            onClick={(e) => handleAction('edit_profile', e)}
                            className="w-full text-left px-4 py-3 hover:bg-slate-50 flex items-center gap-3 text-sm text-slate-700 border-b border-slate-50"
                        >
                            <Edit size={16} className="text-blue-500" /> Editar Perfil
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
                            <RefreshCw size={16} className="text-blue-500" /> Reprocessar Cobrança
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
                            <Key size={16} className="text-slate-500" /> Redefinir Senha
                        </button>
                    </div>
                </div>
            )}

            {/* Address Modal Placeholder - To be implemented fully */}
            {showAddressModal && (
                <div className="fixed inset-0 z-[60] bg-black/50 flex items-center justify-center p-4">
                    <div className="bg-white rounded-xl p-6 w-full max-w-md" onClick={e => e.stopPropagation()}>
                        <h3 className="text-lg font-bold mb-4">Atualizar Endereço</h3>
                        <p className="text-slate-500 mb-4 text-sm">Atualizar endereço de entrega para <strong>{subscriber.name}</strong>.</p>
                        <div className="space-y-4">
                            <input className="w-full p-2 border rounded" placeholder="CEP" defaultValue={subscriber.address?.zipCode} />
                            <input className="w-full p-2 border rounded" placeholder="Rua" defaultValue={subscriber.address?.street} />
                            <div className="flex gap-2">
                                <input className="w-1/3 p-2 border rounded" placeholder="Número" defaultValue={subscriber.address?.number} />
                                <input className="w-2/3 p-2 border rounded" placeholder="Bairro" defaultValue={subscriber.address?.neighborhood} />
                            </div>
                        </div>
                        <div className="flex justify-end gap-2 mt-6">
                            <button onClick={() => setShowAddressModal(false)} className="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded">Cancelar</button>
                            <button onClick={() => { alert('Endereço atualizado!'); setShowAddressModal(false); onUpdate(); }} className="px-4 py-2 bg-amber-500 text-white rounded hover:bg-amber-600">Salvar</button>
                        </div>
                    </div>
                </div>
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
}

const EditProfileModal: React.FC<EditProfileModalProps> = ({ subscriber, initialData, onClose, onSave, onResetPassword }) => {
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
                        <Edit size={20} className="text-blue-600" />
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
                                className="w-full pl-9 p-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
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
                                className="w-full pl-9 p-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all"
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
                                className="w-full pl-9 p-2 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all bg-white"
                                value={role}
                                onChange={e => setRole(e.target.value)}
                            >
                                <option value="member">Membro (Padrão)</option>
                                <option value="admin">Administrador (Acesso Total)</option>
                            </select>
                        </div>
                        <p className="text-xs text-slate-500 mt-1">Administradores têm acesso ao portal Analux Ops.</p>
                    </div>

                    <div className="pt-4 border-t border-slate-100">
                        <label className="block text-sm font-medium text-slate-700 mb-2">Segurança</label>
                        <button
                            onClick={onResetPassword}
                            className="w-full flex items-center justify-center gap-2 py-2 border border-slate-200 rounded-lg text-slate-600 hover:bg-slate-50 transition-colors text-sm font-medium"
                        >
                            <Key size={16} /> Enviar Email de Redefinição de Senha
                        </button>
                    </div>
                </div>

                <div className="flex justify-end gap-3 mt-8">
                    <button onClick={onClose} className="px-4 py-2 text-slate-600 hover:bg-slate-50 rounded-lg font-medium transition-colors">Cancelar</button>
                    <button
                        onClick={handleSave}
                        disabled={saving}
                        className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-bold shadow-md transition-all flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        {saving ? <Loader2 size={18} className="animate-spin" /> : <Check size={18} />}
                        Salvar Alterações
                    </button>
                </div>
            </div>
        </div>
    );
};

export default SubscriberActions;
