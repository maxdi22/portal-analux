import React, { useState, useEffect } from 'react';
import { supabase } from '../../services/supabase';
import { MarketingConfig } from '../../marketing_types';
import { Settings, Facebook, BarChart3, Save, CheckCircle2, ShieldCheck, Zap } from 'lucide-react';

const AdminMarketingAds: React.FC = () => {
    const [config, setConfig] = useState<MarketingConfig>({
        meta_pixel_id: '',
        google_tag_id: '',
        track_page_views: true,
        track_conversions: true,
        track_realtime: true
    });
    const [loading, setLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [message, setMessage] = useState<{ type: 'success' | 'error', text: string } | null>(null);

    useEffect(() => {
        fetchConfig();
    }, []);

    const fetchConfig = async () => {
        try {
            setLoading(true);
            const { data, error } = await supabase
                .from('app_settings')
                .select('value')
                .eq('key', 'marketing_config_v1')
                .maybeSingle();

            if (error) throw error;
            if (data?.value) {
                setConfig(data.value as MarketingConfig);
            }
        } catch (err: any) {
            console.error('Error fetching config:', err);
        } finally {
            setLoading(false);
        }
    };

    const handleSave = async () => {
        try {
            setIsSaving(true);
            const { error } = await supabase
                .from('app_settings')
                .upsert({
                    key: 'marketing_config_v1',
                    value: config,
                    updated_at: new Date().toISOString()
                });

            if (error) throw error;
            setMessage({ type: 'success', text: 'Configurações de Marketing salvas com sucesso!' });
            setTimeout(() => setMessage(null), 3000);
        } catch (err: any) {
            setMessage({ type: 'error', text: 'Erro ao salvar: ' + err.message });
        } finally {
            setIsSaving(false);
        }
    };

    if (loading) return <div className="p-8 text-slate-500 animate-pulse">Carregando central de ADS...</div>;

    return (
        <div className="p-8 max-w-5xl mx-auto">
            <div className="flex items-center justify-between mb-8">
                <div>
                    <h1 className="text-3xl font-serif text-analux-primary flex items-center gap-3">
                        <Zap className="text-analux-secondary" />
                        Central de Marketing & ADS
                    </h1>
                    <p className="text-slate-500 mt-2">Gerencie seus pixels, tags e sensores de rastreamento em um só lugar.</p>
                </div>
                <button
                    onClick={handleSave}
                    disabled={isSaving}
                    className="bg-analux-primary text-white px-6 py-2.5 rounded-full flex items-center gap-2 hover:bg-analux-plum transition-all shadow-lg text-sm font-medium disabled:opacity-50"
                >
                    {isSaving ? 'Salvando...' : <><Save size={18} /> Salvar Configurações</>}
                </button>
            </div>

            {message && (
                <div className={`mb-6 p-4 rounded-xl flex items-center gap-3 animate-slide-in ${
                    message.type === 'success' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-rose-50 text-rose-700 border border-rose-100'
                }`}>
                    {message.type === 'success' ? <CheckCircle2 size={20} /> : <ShieldCheck size={20} />}
                    {message.text}
                </div>
            )}

            <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
                {/* Meta Ads (Facebook) */}
                <div className="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div className="flex items-center gap-4 mb-6">
                        <div className="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                            <Facebook size={24} />
                        </div>
                        <div>
                            <h2 className="text-xl font-medium text-slate-800">Meta Ads</h2>
                            <p className="text-sm text-slate-500">Pixel do Facebook / Instagram</p>
                        </div>
                    </div>
                    
                    <div className="space-y-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Meta Pixel ID</label>
                            <input 
                                type="text"
                                value={config.meta_pixel_id}
                                onChange={(e) => setConfig({...config, meta_pixel_id: e.target.value})}
                                placeholder="Ex: 123456789012345"
                                className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-analux-secondary outline-none transition-all"
                            />
                        </div>
                    </div>
                </div>

                {/* Google Ads / Tracking */}
                <div className="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all">
                    <div className="flex items-center gap-4 mb-6">
                        <div className="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                            <BarChart3 size={24} />
                        </div>
                        <div>
                            <h2 className="text-xl font-medium text-slate-800">Google Ads / GA4</h2>
                            <p className="text-sm text-slate-500">Google Tag Manager / Measurement ID</p>
                        </div>
                    </div>

                    <div className="space-y-4">
                        <div>
                            <label className="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Google Tag ID</label>
                            <input 
                                type="text"
                                value={config.google_tag_id}
                                onChange={(e) => setConfig({...config, google_tag_id: e.target.value})}
                                placeholder="Ex: G-XXXXXXXXXX ou AW-XXXXXXXXXX"
                                className="w-full px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-analux-secondary outline-none transition-all"
                            />
                        </div>
                    </div>
                </div>

                {/* Sensores & Comportamento */}
                <div className="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm col-span-1 md:col-span-2">
                    <div className="flex items-center gap-4 mb-8">
                        <div className="w-12 h-12 bg-analux-plum rounded-xl flex items-center justify-center text-white">
                            <Settings size={24} />
                        </div>
                        <div>
                            <h2 className="text-xl font-medium text-slate-800">Sensores de Comportamento</h2>
                            <p className="text-sm text-slate-500">Controle o que a aplicação deve rastrear internamente.</p>
                        </div>
                    </div>

                    <div className="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <label className="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-all border border-slate-200">
                            <span className="text-sm font-medium text-slate-700">Rastrear Visualizações</span>
                            <input 
                                type="checkbox"
                                checked={config.track_page_views}
                                onChange={(e) => setConfig({...config, track_page_views: e.target.checked})}
                                className="w-5 h-5 accent-analux-primary"
                            />
                        </label>
                        <label className="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-all border border-slate-200">
                            <span className="text-sm font-medium text-slate-700">Rastrear Conversões</span>
                            <input 
                                type="checkbox"
                                checked={config.track_conversions}
                                onChange={(e) => setConfig({...config, track_conversions: e.target.checked})}
                                className="w-5 h-5 accent-analux-primary"
                            />
                        </label>
                        <label className="flex items-center justify-between p-4 bg-slate-50 rounded-xl cursor-pointer hover:bg-slate-100 transition-all border border-slate-200">
                            <span className="text-sm font-medium text-slate-700">Monitor Real-time</span>
                            <input 
                                type="checkbox"
                                checked={config.track_realtime}
                                onChange={(e) => setConfig({...config, track_realtime: e.target.checked})}
                                className="w-5 h-5 accent-analux-primary"
                            />
                        </label>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default AdminMarketingAds;
