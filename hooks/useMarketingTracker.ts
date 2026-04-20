import { useEffect, useCallback } from 'react';
import { useLocation } from 'react-router-dom';
import { supabase } from '../services/supabase';
import { AnalyticsEvent } from '../marketing_types';
import { useUser } from '../context/UserContext';

const GUEST_ID_KEY = 'analux_guest_id';

export const useMarketingTracker = () => {
    const location = useLocation();
    const { user } = useUser();

    // Recupera ou cria um Guest ID persistente
    const getGuestId = useCallback(() => {
        let guestId = localStorage.getItem(GUEST_ID_KEY);
        if (!guestId) {
            guestId = 'guest_' + Math.random().toString(36).substring(2, 11) + Date.now().toString(36);
            localStorage.setItem(GUEST_ID_KEY, guestId);
        }
        return guestId;
    }, []);

    // Função universal para rastrear qualquer evento
    const trackEvent = useCallback(async (
        type: AnalyticsEvent['event_type'], 
        metadata: any = {}
    ) => {
        const guestId = getGuestId();
        const event: AnalyticsEvent = {
            guest_id: guestId,
            user_id: user?.id,
            event_type: type,
            page_path: window.location.pathname + window.location.search,
            metadata: {
                ...metadata,
                userAgent: navigator.userAgent,
                referrer: document.referrer,
                timestamp: new Date().toISOString()
            }
        };

        // 1. Salvar no Supabase (Tracker Interno)
        try {
            await supabase.from('visitor_activity').insert([event]);
        } catch (err) {
            console.error('Error tracking internal event:', err);
        }

        // 2. Disparar Meta Pixel (se existir globalmente)
        if (window.fbq) {
            if (type === 'page_view') window.fbq('track', 'PageView');
            else if (type === 'lead_convert') window.fbq('track', 'Lead', metadata);
            else if (type === 'conversion') window.fbq('track', 'Purchase', metadata);
            else window.fbq('trackCustom', type, metadata);
        }

        // 3. Disparar Google Tag (se existir globalmente)
        if (window.gtag) {
            window.gtag('event', type, metadata);
        }

        // console.log(`[Marketing] Event tracked: ${type}`, event);
    }, [user, getGuestId]);

    // Rastrear mudança de página automaticamente
    useEffect(() => {
        trackEvent('page_view');
    }, [location.pathname, trackEvent]);

    // Heartbeat para sistema de "Online Agora"
    useEffect(() => {
        const interval = setInterval(() => {
            trackEvent('heartbeat');
        }, 30000); // A cada 30 segundos

        return () => clearInterval(interval);
    }, [trackEvent]);

    return { trackEvent, guestId: getGuestId() };
};

// Extender o objeto Window para o TypeScript não reclamar dos Pixels
declare global {
    interface Window {
        fbq: any;
        gtag: any;
        dataLayer: any[];
    }
}
