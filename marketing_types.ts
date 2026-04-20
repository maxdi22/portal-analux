
export interface MarketingConfig {
    meta_pixel_id?: string;
    google_tag_id?: string;
    tiktok_pixel_id?: string;
    google_ads_id?: string;
    track_page_views: boolean;
    track_conversions: boolean;
    track_realtime: boolean;
}

export interface VisitorSession {
    guestId: string;
    userId?: string;
    lastActive: string;
    isOnline: boolean;
    currentPath: string;
    deviceType: string;
    browser: string;
}

export interface AnalyticsEvent {
    id?: string;
    guest_id: string;
    user_id?: string;
    event_type: 'page_view' | 'click' | 'lead_convert' | 'heartbeat' | 'conversion';
    page_path: string;
    metadata: {
        label?: string;
        value?: any;
        userAgent?: string;
        referrer?: string;
        [key: string]: any;
    };
    created_at?: string;
}
