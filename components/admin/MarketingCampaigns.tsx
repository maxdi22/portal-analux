import React, { useState, useEffect } from 'react';
import { Tag, Save, AlertCircle, CheckCircle2, Music } from 'lucide-react';
import { supabase } from '../../services/supabase';
import MoodManager from './marketing/MoodManager';

const MarketingCampaigns: React.FC = () => {
    const [activeTab, setActiveTab] = useState<'general' | 'moods'>('general');
    const [vipDiscount, setVipDiscount] = useState('5');
    const [isLoading, setIsLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

    useEffect(() => {
        fetchSettings();
    }, []);

    const fetchSettings = async () => {
        try {
            const { data, error } = await supabase
                .from('app_settings')
                .select('value')
                .eq('key', 'vip_offer_discount')
                .single();

            if (error && error.code !== 'PGRST116') throw error;

            if (data) {
                setVipDiscount(String(data.value));
            }
        } catch (error) {
            console.error('Error fetching settings:', error);
            setMessage({ type: 'error', text: 'Erro ao carregar configurações.' });
        } finally {
            setIsLoading(false);
        }
    };

    const handleSave = async () => {
        setIsSaving(true);
        setMessage(null);

        try {
            const { error } = await supabase
                .from('app_settings')
                .upsert({
                    key: 'vip_offer_discount',
                    value: vipDiscount,
                    updated_at: new Date().toISOString()
                });

            if (error) throw error;

            setMessage({ type: 'success', text: 'Configurações salvas com sucesso!' });
        } catch (error) {
            console.error('Error saving settings:', error);
            setMessage({ type: 'error', text: 'Erro ao salvar configurações.' });
        } finally {
            setIsSaving(false);
        }
    };

    if (isLoading) return <div className="p-8 text-center text-gray-500">Carregando...</div>;

    return (
        <div className="space-y-8 animate-fadeIn">
            <div className="flex items-center justify-between">
                <div>
                    <h1 className="text-3xl font-serif text-analux-primary">Marketing e Campanhas</h1>
                    <p className="text-gray-500 mt-1">Gerencie promoções e playlists de experiência</p>
                </div>
            </div>

            {/* TAB NAVIGATION */}
            <div className="flex gap-4 border-b border-gray-100">
                <button
                    onClick={() => setActiveTab('general')}
                    className={`pb-4 px-2 text-sm font-bold uppercase tracking-widest transition-all ${activeTab === 'general'
                            ? 'text-analux-secondary border-b-2 border-analux-secondary'
                            : 'text-gray-400 hover:text-gray-600'
                        }`}
                >
                    <div className="flex items-center gap-2"><Tag size={16} /> Promoções</div>
                </button>
                <button
                    onClick={() => setActiveTab('moods')}
                    className={`pb-4 px-2 text-sm font-bold uppercase tracking-widest transition-all ${activeTab === 'moods'
                            ? 'text-analux-secondary border-b-2 border-analux-secondary'
                            : 'text-gray-400 hover:text-gray-600'
                        }`}
                >
                    <div className="flex items-center gap-2"><Music size={16} /> Moods & Playlists</div>
                </button>
            </div>

            {/* CONTENT */}
            <div className="pt-4">
                {activeTab === 'general' ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {/* VIP Offers Card */}
                        <div className="bg-white p-8 rounded-[40px] shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden">
                            <div className="absolute top-0 right-0 w-32 h-32 bg-analux-secondary/5 rounded-full -mr-16 -mt-16 blur-2xl"></div>

                            <div className="relative z-10">
                                <div className="flex items-start justify-between mb-6">
                                    <div className="w-12 h-12 bg-analux-contrast rounded-2xl flex items-center justify-center text-analux-secondary">
                                        <Tag size={24} />
                                    </div>
                                    <div className="bg-analux-secondary/10 px-3 py-1 rounded-full text-[10px] font-bold text-analux-secondary uppercase tracking-widest">
                                        Ativo
                                    </div>
                                </div>

                                <h3 className="text-xl font-bold text-analux-primary mb-2">Ofertas VIP Integradas</h3>
                                <p className="text-sm text-gray-400 mb-6">
                                    Define a porcentagem de desconto aplicada automaticamente quando um assinante clica em "Aproveitar Oferta" na área de membros.
                                </p>

                                <div className="space-y-4">
                                    <div>
                                        <label className="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Desconto (%)</label>
                                        <div className="relative mt-1">
                                            <input
                                                type="number"
                                                value={vipDiscount}
                                                onChange={(e) => setVipDiscount(e.target.value)}
                                                className="w-full bg-analux-contrast border-0 rounded-2xl py-4 pl-4 pr-12 text-lg font-bold text-analux-primary focus:ring-2 focus:ring-analux-secondary/20 transition-all outline-none"
                                            />
                                            <span className="absolute right-6 top-1/2 -translate-y-1/2 text-gray-400 font-bold">%</span>
                                        </div>
                                    </div>

                                    {message && (
                                        <div className={`p-3 rounded-xl text-xs flex items-center gap-2 ${message.type === 'success' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-500'}`}>
                                            {message.type === 'success' ? <CheckCircle2 size={14} /> : <AlertCircle size={14} />}
                                            {message.text}
                                        </div>
                                    )}

                                    <button
                                        onClick={handleSave}
                                        disabled={isSaving}
                                        className="w-full py-4 bg-analux-primary text-white rounded-2xl font-bold flex items-center justify-center gap-2 shadow-xl shadow-analux-primary/20 hover:scale-[1.02] transition-all disabled:opacity-70"
                                    >
                                        {isSaving ? 'Salvando...' : 'Salvar Alterações'} <Save size={16} />
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Placeholder for future campaigns */}
                        <div className="bg-gray-50 p-8 rounded-[40px] border border-dashed border-gray-200 flex flex-col items-center justify-center text-center opacity-60">
                            <div className="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4 text-gray-300">
                                <Tag size={32} />
                            </div>
                            <h3 className="text-lg font-bold text-gray-400">Nova Campanha</h3>
                            <p className="text-xs text-gray-400 mt-2 max-w-xs">Mais ferramentas de marketing serão adicionadas aqui em breve.</p>
                        </div>
                    </div>
                ) : (
                    <MoodManager />
                )}
            </div>
        </div>
    );
};

export default MarketingCampaigns;
