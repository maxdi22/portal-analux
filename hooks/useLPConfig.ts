import { useState, useEffect } from 'react';
import { supabase } from '../services/supabase';
import { LandingPageConfig } from '../types';

export const useLPConfig = () => {
    const [config, setConfig] = useState<LandingPageConfig | null>(null);
    const [draft, setDraft] = useState<LandingPageConfig | null>(null);
    const [loading, setLoading] = useState(true);
    const [isSaving, setIsSaving] = useState(false);
    const [error, setError] = useState<string | null>(null);

    const hasChanges = JSON.stringify(draft) !== JSON.stringify(config);

    useEffect(() => {
        fetchConfig();
    }, []);

    const fetchConfig = async () => {
        try {
            setLoading(true);
            const { data, error } = await supabase
                .from('app_settings')
                .select('value')
                .eq('key', 'lp_config_v1')
                .maybeSingle();

            if (error) throw error;
            if (data?.value) {
                setConfig(data.value as LandingPageConfig);
                setDraft(data.value as LandingPageConfig);
            }
        } catch (err: any) {
            console.error('Error fetching LP config:', err);
            setError(err.message);
        } finally {
            setLoading(false);
        }
    };

    const updateDraft = (updates: any) => {
        setDraft(prev => {
            if (!prev) return null;
            // Deep merge logic or specific update logic
            return { ...prev, ...updates };
        });
    };

    const updateField = (section: string, field: string, value: any) => {
        setDraft(prev => {
            if (!prev) return null;
            return {
                ...prev,
                [section]: {
                    ...(prev as any)[section],
                    [field]: value
                }
            };
        });
    };

    const updateNestedField = (path: string[], value: any) => {
        setDraft(prev => {
            if (!prev) return null;
            const newDraft = JSON.parse(JSON.stringify(prev));
            let current = newDraft;
            for (let i = 0; i < path.length - 1; i++) {
                current = current[path[i]];
            }
            current[path[path.length - 1]] = value;
            return newDraft;
        });
    };

    const publish = async () => {
        if (!draft) return;
        try {
            setIsSaving(true);
            const { error } = await supabase
                .from('app_settings')
                .upsert({
                    key: 'lp_config_v1',
                    value: draft,
                    updated_at: new Date().toISOString()
                });

            if (error) throw error;
            setConfig(draft);
            return { success: true };
        } catch (err: any) {
            console.error('Error publishing LP config:', err);
            return { success: false, error: err.message };
        } finally {
            setIsSaving(false);
        }
    };

    const resetDraft = () => {
        setDraft(config);
    };

    return {
        config: draft || config, // Use draft for live preview
        originalConfig: config,
        loading,
        isSaving,
        hasChanges,
        error,
        updateDraft,
        updateField,
        updateNestedField,
        publish,
        resetDraft,
        refresh: fetchConfig
    };
};
