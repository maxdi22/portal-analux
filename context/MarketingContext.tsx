import React, { createContext, useContext, useEffect, useState } from 'react';
import { supabase } from '../services/supabase';
import { MarketingConfig } from '../marketing_types';
import { useMarketingTracker } from '../hooks/useMarketingTracker';

interface MarketingContextType {
    config: MarketingConfig | null;
    loading: boolean;
    trackEvent: (type: any, metadata?: any) => void;
}

const MarketingContext = createContext<MarketingContextType | undefined>(undefined);

export const MarketingProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
    const [config, setConfig] = useState<MarketingConfig | null>(null);
    const [loading, setLoading] = useState(true);
    const { trackEvent } = useMarketingTracker();

    useEffect(() => {
        const fetchMarketingConfig = async () => {
            try {
                const { data, error } = await supabase
                    .from('app_settings')
                    .select('value')
                    .eq('key', 'marketing_config_v1')
                    .maybeSingle();

                if (!error && data?.value) {
                    const mktConfig = data.value as MarketingConfig;
                    setConfig(mktConfig);
                    initializeThirdPartyScripts(mktConfig);
                }
            } catch (err) {
                console.error('Error loading marketing config:', err);
            } finally {
                setLoading(false);
            }
        };

        fetchMarketingConfig();
    }, []);

    const initializeThirdPartyScripts = (mktConfig: MarketingConfig) => {
        // 1. Meta Pixel
        if (mktConfig.meta_pixel_id && !window.fbq) {
            const pixelId = mktConfig.meta_pixel_id;
            (function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js'));
            window.fbq('init', pixelId);
        }

        // 2. Google Tag (gtag.js)
        if (mktConfig.google_tag_id && !window.gtag) {
            const tagId = mktConfig.google_tag_id;
            const script = document.createElement('script');
            script.async = true;
            script.src = `https://www.googletagmanager.com/gtag/js?id=${tagId}`;
            document.head.appendChild(script);

            window.dataLayer = window.dataLayer || [];
            window.gtag = function(){window.dataLayer.push(arguments);}
            window.gtag('js', new Date());
            window.gtag('config', tagId);
        }
    };

    return (
        <MarketingContext.Provider value={{ config, loading, trackEvent }}>
            {children}
        </MarketingContext.Provider>
    );
};

export const useMarketing = () => {
    const context = useContext(MarketingContext);
    if (!context) {
        throw new Error('useMarketing must be used within a MarketingProvider');
    }
    return context;
};
