
import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { User, MemberLevel } from '../types';
import { initialMockData } from '../services/mockData';
import { supabase } from '../services/supabase';
import { Session } from '@supabase/supabase-js';

// Re-export mock data as initialMockData to avoid confusion if needed elsewhere
export { initialMockData };

interface UserContextType {
    user: User;
    session: Session | null;
    isMember: boolean;
    loading: boolean;
    updateUser: (updates: Partial<User>) => Promise<void>;
    signIn: (email: string, password: string) => Promise<{ error: any }>;
    signUp: (email: string, password: string, name: string) => Promise<{ error: any }>;
    resetPassword: (email: string) => Promise<{ error: any }>;
    signOut: () => Promise<void>;
    refreshData: () => Promise<void>;
    syncBoxHistory: () => Promise<void>;
    setIsMember: React.Dispatch<React.SetStateAction<boolean>>;
}

const UserContext = createContext<UserContextType | undefined>(undefined);

export const UserProvider: React.FC<{ children: ReactNode }> = ({ children }) => {
    const [user, setUser] = useState<User>({
        id: '',
        name: '',
        email: '',
        phone: '',
        avatar: '',
        points: 0,
        lifetimePoints: 0,
        cashback: 0,
        pendingCashback: 0,
        level: MemberLevel.BRONZE, // Default level
        invitesAvailable: 0,
        isStoreConnected: false,
        address: { street: '', number: '', neighborhood: '', city: '', state: '', zipCode: '' },
        billing: { brand: '', lastFour: '', expiry: '' },
        stylePrefs: { metals: [], stones: [], vibe: [] },
        achievements: [],
        digitalVault: [],
        boxHistory: [],
        lifePhases: [],
        // subscription: undefined // Explicitly undefined by default
    });
    const [session, setSession] = useState<Session | null>(null);
    const [loading, setLoading] = useState(true);
    const [isMember, setIsMember] = useState(false);

    useEffect(() => {
        // Check active session
        supabase.auth.getSession().then(({ data: { session } }) => {
            setSession(session);
            setIsMember(!!session);
            if (session) refreshData(session.user.id);
            else setLoading(false);
        });

        // Listen for auth changes
        const { data: { subscription } } = supabase.auth.onAuthStateChange((_event, session) => {
            setSession(session);
            setIsMember(!!session);
            if (session) refreshData(session.user.id);
            else {
                setUser({
                    id: '',
                    name: '',
                    email: '',
                    phone: '',
                    avatar: '',
                    points: 0,
                    lifetimePoints: 0,
                    cashback: 0,
                    pendingCashback: 0,
                    level: MemberLevel.BRONZE,
                    invitesAvailable: 0,
                    isStoreConnected: false,
                    address: { street: '', number: '', neighborhood: '', city: '', state: '', zipCode: '' },
                    billing: { brand: '', lastFour: '', expiry: '' },
                    stylePrefs: { metals: [], stones: [], vibe: [] },
                    achievements: [],
                    digitalVault: [],
                    boxHistory: [],
                    lifePhases: []
                });
                setLoading(false);
            }
        });

        return () => subscription.unsubscribe();
    }, []);

    const checkAndSeedData = async (userId: string, email: string, name?: string) => {
        try {
            const { data: profile } = await supabase
                .from('profiles')
                .select('*')
                .eq('id', userId)
                .maybeSingle();

            if (!profile) {
                console.log('Seeding initial data for user...');
                const { error: profileError } = await supabase.from('profiles').upsert({
                    id: userId,
                    email: email,
                    name: name || email.split('@')[0],
                    avatar: `https://ui-avatars.com/api/?name=${name || email}&background=edd4c5&color=fff`,
                    points: 0, // FORCE ZERO
                    cashback: 0, // FORCE ZERO
                    level: MemberLevel.BRONZE,
                    invites_available: 0,
                    is_store_connected: false,
                    address: { street: '', number: '', neighborhood: '', city: '', state: '', zipCode: '' },
                    billing: { brand: '', lastFour: '', expiry: '' },
                    style_prefs: { metals: [], stones: [], vibe: [] }
                }, { onConflict: 'id' });
                if (profileError) throw profileError;
                console.log('User profile initialized (clean).');
                return true;
            }
            return false;
        } catch (err) {
            console.error('Error seeding data:', err);
            return false;
        }
    };

    const refreshData = async (userId?: string) => {
        const targetId = userId || session?.user.id;
        if (!targetId) return;

        try {
            const { data: { user: authUser } } = await supabase.auth.getUser();
            if (authUser?.email) {
                await checkAndSeedData(targetId, authUser.email, authUser.user_metadata?.name);
            }

            const [
                { data: profile },
                { data: subscription },
                { data: vault },
                { data: history },
                { data: storeData },
                { data: phases },
                { data: activeEditions }
            ] = await Promise.all([
                supabase.from('profiles').select('*').eq('id', targetId).maybeSingle(),
                supabase.from('subscriptions').select('*').eq('user_id', targetId).maybeSingle(),
                supabase.from('digital_vault').select('*').eq('user_id', targetId),
                supabase.from('box_history').select('*').eq('user_id', targetId),
                supabase.from('store_data').select('*').eq('user_id', targetId).maybeSingle(),
                supabase.from('life_phases').select('*').eq('user_id', targetId),
                supabase.from('box_editions').select('*').order('created_at', { ascending: false }).limit(1)
            ]);

            const currentBoxData = activeEditions?.[0];

            if (profile) {
                // Initialize referral code logic
                let finalRefCode = profile.referral_code;

                if (!finalRefCode && profile.name) {
                    try {
                        const code = (profile.name.split(' ')[0] + Math.floor(Math.random() * 1000)).toUpperCase().replace(/[^A-Z0-9]/g, '');
                        // Safe update using profile.id
                        const { error: updateError } = await supabase
                            .from('profiles')
                            .update({ referral_code: code })
                            .eq('id', profile.id);

                        if (!updateError) finalRefCode = code;
                    } catch (e) {
                        console.error("Auto-gen referral failed", e);
                    }
                }

                const fullUser: User = {
                    id: profile.id,
                    name: profile.name || authUser?.user_metadata?.name || authUser?.email?.split('@')[0] || '',
                    email: profile.email || authUser?.email || '',
                    phone: profile.phone || '',
                    avatar: profile.avatar || `https://ui-avatars.com/api/?name=${profile.name || 'User'}&background=edd4c5&color=fff`,
                    points: profile.points || 0,
                    lifetimePoints: profile.lifetime_points || 0,
                    cashback: profile.cashback || 0,
                    pendingCashback: profile.pending_cashback || 0,
                    level: (profile.level as MemberLevel) || MemberLevel.BRONZE,
                    invitesAvailable: profile.invites_available || 0,
                    isStoreConnected: profile.is_store_connected || false,
                    address: profile.address || { street: '', number: '', neighborhood: '', city: '', state: '', zipCode: '' },
                    billing: profile.billing || { brand: '', lastFour: '', expiry: '' },
                    stylePrefs: (profile.style_profile && (profile.style_profile.plating || (profile.style_profile.styles && profile.style_profile.styles.length > 0))) ? {
                        metals: profile.style_profile.plating ? [profile.style_profile.plating] : [],
                        vibe: profile.style_profile.styles || [],
                        stones: []
                    } : (profile.style_prefs || { metals: [], stones: [], vibe: [] }),
                    styleProfile: profile.style_profile || undefined,
                    achievements: [], // Start without achievements in production
                    subscription: subscription ? {
                        plan: subscription.plan,
                        status: subscription.status,
                        frequency: subscription.frequency,
                        currentBoxStatus: subscription.current_box_status,
                        nextBoxDate: subscription.next_box_date,
                        memberSince: subscription.member_since,
                        autoRenew: subscription.auto_renew,
                        currentBox: currentBoxData ? {
                            id: currentBoxData.id,
                            name: currentBoxData.name,
                            theme: currentBoxData.theme,
                            month: currentBoxData.month,
                            status: currentBoxData.status,
                            type: currentBoxData.type,
                            coverImage: currentBoxData.cover_image,
                            items: currentBoxData.items
                        } : undefined
                    } : undefined,
                    digitalVault: vault?.map(v => ({
                        id: v.id,
                        name: v.name,
                        image: v.image,
                        acquiredAt: v.acquired_at,
                        origin: v.origin,
                        memory: v.memory,
                        mood: v.mood,
                        location: v.location,
                        status: v.status,
                        externalId: v.external_id,
                        originalPrice: v.original_price
                    })) || [],
                    boxHistory: history?.map(h => ({
                        id: h.id,
                        name: h.name,
                        date: h.date,
                        image: h.image,
                        theme: h.theme,
                        status: h.status
                    })) || [],
                    storeData: storeData ? {
                        recentOrders: storeData.recent_orders,
                        favorites: storeData.favorites,
                        exclusiveOffers: storeData.exclusive_offers
                    } : undefined,
                    lifePhases: phases?.map(p => ({
                        id: p.id,
                        title: p.title,
                        date: p.date,
                        description: p.description,
                        category: p.category,
                        linkedJewelryIds: p.linked_jewelry_ids || []
                    })) || [],
                    onboardingCompleted: profile.onboarding_completed,
                    role: profile.role || 'member',
                    isAdmin: profile.role === 'admin' || ['maxdi.brasil@gmail.com', 'anaiara.adv@hotmail.com', 'maxdi.agency@gmail.com'].includes(profile.email || authUser?.email || ''),
                    referralCode: finalRefCode,
                    referralStats: {
                        totalInvited: profile.referral_count || 0,
                        totalEarned: (profile.referral_count || 0) * 20,
                        totalClicks: profile.referral_clicks || 0,
                        activeNow: 0 // Will be updated by realtime presence
                    }
                };
                setUser(fullUser);
            } else if (authUser) {
                // FALLBACK: User is authenticated but profile record is missing or slow
                console.warn('%c [UserContext] Perfil não encontrado no banco de dados. Usando dados básicos do cadastro. ', 'color: #ff9900; font-weight: bold;');
                
                setUser({
                    id: targetId,
                    name: authUser.user_metadata?.name || authUser.email?.split('@')[0] || 'Usuária',
                    email: authUser.email || '',
                    phone: '',
                    avatar: `https://ui-avatars.com/api/?name=${authUser.email}&background=edd4c5&color=fff`,
                    points: 0,
                    lifetimePoints: 0,
                    cashback: 0,
                    pendingCashback: 0,
                    level: MemberLevel.BRONZE,
                    invitesAvailable: 0,
                    isStoreConnected: false,
                    address: { street: '', number: '', neighborhood: '', city: '', state: '', zipCode: '' },
                    billing: { brand: '', lastFour: '', expiry: '' },
                    stylePrefs: { metals: [], stones: [], vibe: [] },
                    achievements: [],
                    digitalVault: [],
                    boxHistory: [],
                    lifePhases: [],
                    role: 'member'
                });
            } else {
                 console.error('%c [UserContext] Falha crítica: Sessão ativa mas dados de autenticação não resolvidos. ', 'background: #ff0000; color: #fff;');
            }
        } catch (error) {
            console.error('[UserContext] Erro ao carregar dados do usuário:', error);
        } finally {
            setLoading(false);
        }
    };

    const syncBoxHistory = async () => {
        if (!session?.user.id) return;

        try {
            const { data: { invoices }, error: proxyError } = await supabase.functions.invoke('stripe-proxy', {
                body: { action: 'get_invoices' }
            });

            if (proxyError || !invoices) return;

            const { data: currentHistory } = await supabase
                .from('box_history')
                .select('external_id')
                .eq('user_id', session.user.id);

            const existingIds = new Set(currentHistory?.map(h => h.external_id));

            const newBoxes = invoices
                .filter((inv: any) => !existingIds.has(inv.id))
                .map((inv: any) => ({
                    user_id: session.user.id,
                    external_id: inv.id,
                    name: `Box ${inv.date.split('/')[1]}/${inv.date.split('/')[2]}`,
                    date: inv.date,
                    theme: 'Curadoria Surpresa',
                    image: 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=300&h=300',
                    status: 'active'
                }));

            if (newBoxes.length > 0) {
                await supabase.from('box_history').insert(newBoxes);
                await refreshData();
            }

        } catch (err) {
            console.error("Sync History Failed", err);
        }
    };

    const updateUser = async (updates: Partial<User>) => {
        if (!session?.user.id) return;

        setUser(prev => ({ ...prev, ...updates }));

        try {
            const profileUpdates: any = {};
            if (updates.name) profileUpdates.name = updates.name;
            if (updates.avatar) profileUpdates.avatar = updates.avatar;
            if (updates.phone) profileUpdates.phone = updates.phone;
            if (updates.email) profileUpdates.email = updates.email;
            if (updates.address) profileUpdates.address = updates.address;
            if (updates.stylePrefs) profileUpdates.style_prefs = updates.stylePrefs;
            if (updates.styleProfile) profileUpdates.style_profile = updates.styleProfile;
            if (updates.onboardingCompleted !== undefined) profileUpdates.onboarding_completed = updates.onboardingCompleted;
            if (updates.isStoreConnected !== undefined) profileUpdates.is_store_connected = updates.isStoreConnected;

            if (Object.keys(profileUpdates).length > 0) {
                await supabase.from('profiles').update(profileUpdates).eq('id', session.user.id);
            }

            if (updates.subscription) {
                await supabase.from('subscriptions').update({
                    auto_renew: updates.subscription.autoRenew,
                    frequency: updates.subscription.frequency
                }).eq('user_id', session.user.id);
            }
        } catch (error) {
            console.error('Error updating user:', error);
        }
    };

    const signIn = async (email: string, password: string) => {
        const { error } = await supabase.auth.signInWithPassword({
            email,
            password
        });
        return { error };
    };

    const signUp = async (email: string, password: string, name: string) => {
        const { error, data } = await supabase.auth.signUp({
            email,
            password,
            options: {
                data: {
                    name: name
                }
            }
        });

        if (!error && data.user) {
            // Pre-seed data immediately on signup to ensure data availability
            await checkAndSeedData(data.user.id, email, name);
        }

        return { error, data };
    };

    const resetPassword = async (email: string) => {
        const { error } = await supabase.auth.resetPasswordForEmail(email, {
            redirectTo: window.location.origin + '/reset-password',
        });
        return { error };
    };

    const signOut = async () => {
        await supabase.auth.signOut();
        setIsMember(false);
        setUser({
            id: '',
            name: '',
            email: '',
            phone: '',
            avatar: '',
            points: 0,
            lifetimePoints: 0,
            cashback: 0,
            pendingCashback: 0,
            level: MemberLevel.BRONZE,
            invitesAvailable: 0,
            isStoreConnected: false,
            address: { street: '', number: '', neighborhood: '', city: '', state: '', zipCode: '' },
            billing: { brand: '', lastFour: '', expiry: '' },
            stylePrefs: { metals: [], stones: [], vibe: [] },
            achievements: [],
            digitalVault: [],
            boxHistory: [],
            lifePhases: []
        });

        // Clear any subscription drafts
        localStorage.removeItem('sub_draft_active_plan');
        localStorage.removeItem('sub_draft_cep');
        localStorage.removeItem('sub_draft_street');
        localStorage.removeItem('sub_draft_number');
        localStorage.removeItem('sub_draft_neighborhood');
        localStorage.removeItem('sub_draft_city');
        localStorage.removeItem('sub_draft_state');
        localStorage.removeItem('sub_draft_complement');
        localStorage.removeItem('sub_draft_time');
    };

    return (
        <UserContext.Provider value={{
            user,
            session,
            isMember,
            loading,
            updateUser,
            signIn,
            signUp,
            resetPassword,
            signOut,
            refreshData,
            syncBoxHistory,
            setIsMember
        }}>
            {children}
        </UserContext.Provider>
    );
};

export const useUser = () => {
    const context = useContext(UserContext);
    if (context === undefined) {
        throw new Error('useUser must be used within a UserProvider');
    }
    return context;
};
