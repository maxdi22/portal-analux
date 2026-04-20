
import React from 'react';

export enum SubscriptionStatus {
  ACTIVE = 'ACTIVE',
  PAUSED = 'PAUSED',
  CANCELLED = 'CANCELLED'
}

export enum SubscriptionPlan {
  BASIC = 'BASIC',
  PREMIUM = 'PREMIUM',
  LUXURY = 'LUXURY'
}

export enum MemberLevel {
  BRONZE = 'Musa Bronze',
  SILVER = 'Musa Prata',
  GOLD = 'Musa Ouro',
  DIAMOND = 'Musa Diamante'
}

export interface LifePhase {
  id: string;
  title: string;
  date: string;
  description: string;
  category: 'Carreira' | 'Amor' | 'Viagem' | 'Autoconhecimento' | 'Maternidade';
  linkedJewelryIds: string[];
}

export interface DigitalJewelry {
  id: string;
  name: string;
  image: string;
  acquiredAt: string;
  origin: 'Box Mensal' | 'Loja Analux' | 'Presente';
  memory?: string;
  mood?: string;
  location?: string;
  status?: 'active' | 'pending';
  externalId?: string;
  originalPrice?: number;
}

export interface StoreOrderItem {
  id: number;
  name: string;
  image: string;
  price: number;
  quantity: number;
}

export interface StoreOrder {
  id: string;
  number: string;
  date: string;
  status: 'Processando' | 'Concluído' | 'Enviado';
  total: number;
  itemsCount: number;
  items?: StoreOrderItem[];
}

export interface StoreProduct {
  id: string;
  name: string;
  image: string;
  regularPrice: number;
  memberPrice: number;
  category: string;
  permalink: string;
}

export interface User {
  id: string;
  name: string;
  email: string;
  phone: string;
  avatar: string;
  points: number;
  lifetimePoints?: number;
  cashback: number;
  pendingCashback?: number;
  level: MemberLevel;
  achievements: Achievement[];
  boxHistory: BoxHistory[];
  digitalVault: DigitalJewelry[];
  lifePhases: LifePhase[];
  invitesAvailable: number;
  isStoreConnected: boolean;
  storeData?: {
    recentOrders: StoreOrder[];
    favorites: StoreProduct[];
    exclusiveOffers: StoreProduct[];
  };
  onboardingCompleted?: boolean;
  styleProfile?: StyleProfile;
  stylePrefs?: StylePreferences;
  address: Address;
  billing: BillingInfo;
  subscription?: {
    plan: SubscriptionPlan; // Mantido para retrocompatibilidade
    tier_code?: 'essencial' | 'signature';
    plan_code?: 'essential_flex' | 'essential_6m' | 'signature_flex' | 'signature_6m';
    monthly_price?: number;
    shipping_rule?: string;
    minimum_cycles?: number;
    cycles_paid?: number;
    can_cancel_now?: boolean;
    signup_benefit?: string;
    renewal_date?: string;
    status: SubscriptionStatus;
    frequency: SubscriptionFrequency; // Mantido para retrocompatibilidade
    currentBoxStatus: BoxStatus;
    firstBoxDate?: string;
    nextBoxDate: string;
    memberSince: string;
    autoRenew: boolean;
    currentBox?: BoxEdition;
    isDelivered?: boolean;
  };
  isAdmin?: boolean;
  role?: 'superadmin' | 'admin' | 'member';
  referralCode?: string;
  referralStats?: {
    totalInvited: number;
    totalEarned: number;
    totalClicks?: number; // New
    activeNow?: number;   // New
  };
  needsPasswordReset?: boolean;
}

export enum SubscriptionFrequency {
  MONTHLY = 'Mensal',
  QUARTERLY = 'Trimestral'
}

export interface StyleProfile {
  // Personal Info
  fullName: string;
  birthDate: string;
  acquisitionChannel: string;

  // Style Definition
  styles: string[]; // Clássico, Moderno, etc (Checkbox)

  // Plating
  plating: 'Dourado' | 'Prateado' | 'Todos';

  // Jewelry Types (Checkboxes)
  necklaces: string[];
  earrings: string[];
  earringHoles: string;
  earringOther: string;

  bracelets: string[];
  braceletOther: string;

  rings: string[];
  ringSize: string;
  ringOther: string;

  anklets: string[];
  ankletSize: string;

  // Details
  elements: string[]; // Olho grego, etc
  religious: string[];
  sign: string;
  coloredZirconia: boolean;
  coloredZirconiaColor: string;

  // Personal Details
  pets: string;
  children: string;
  favoriteColors: string;

  // Open Text
  observations: string;
}

// Legacy simple prefs for dashboard summary
export interface StylePreferences {
  metals: string[];
  stones: string[];
  vibe: string[];
}

export enum BoxStatus {
  ORDERED = 'Pedido Confirmado',
  PREPARING = 'Preparando sua Box',
  CURATING = 'Curadoria de Estilo',
  SHIPPED = 'Em Trânsito',
  DELIVERED = 'Entregue'
}

export enum BoxEditionStatus {
  PLANNING = 'Planejamento',
  CURATING = 'Curadoria',
  PRODUCTION = 'Em Produção',
  ASSEMBLY = 'Montagem',
  SHIPPING = 'Em Envio',
  COMPLETED = 'Concluído'
}

export type BoxType = 'monthly' | 'thematic' | 'special';

export interface BoxItem {
  id: string;
  name: string;
  description: string;
  image?: string;
  cost: number;
  supplier?: string;
  stock?: number;
  category: 'jewelry' | 'gift' | 'sensory' | 'print' | 'content';
  targetPlan: SubscriptionPlan[];
}

export interface BoxEdition {
  id: string;
  name: string;      // "Aurora Floral"
  theme: string;     // Tema/Fase
  month: string;     // "Novembro/2026"
  status: BoxEditionStatus;
  type: BoxType;

  // Identidade
  manifesto?: string;
  archetype?: string;
  colors?: string[];
  coverImage?: string;
  teaserVideo?: string;

  // KPIs
  subscriberCount?: number;
  rating?: number;
  deliveryRate?: number;
  churnImpact?: number;
  estimatedCost?: number;
  margin?: number;

  // Dates
  productionDeadline?: string;
  assemblyDate?: string;
  shippingDate?: string;

  // Items
  items?: BoxItem[];
}

export interface Achievement {
  id: string;
  title: string;
  icon: string;
  description: string;
  unlockedAt?: string;
}

export interface BoxHistory {
  id: string;
  name: string;
  date: string;
  image: string;
  theme: string;
}

export interface Address {
  street: string;
  number: string;
  complement?: string;
  neighborhood: string;
  city: string;
  state: string;
  zipCode: string;
}

export interface BillingInfo {
  lastFour: string;
  brand: string;
  expiry: string;
}

export interface StylePreferences {
  metals: string[];
  stones: string[];
  vibe: string[];
}

export interface PollOption {
  id: string;
  text: string;
  votes: number;
}

export interface ForumTopic {
  id: string;
  authorId: string;
  authorName: string;
  authorAvatar: string;
  authorBadge?: string;
  title: string;
  content: string;
  images?: string[];
  poll?: {
    question: string;
    options: PollOption[];
    totalVotes: number;
  };
  category: 'Estilo' | 'Unboxing' | 'Dúvidas' | 'Geral' | 'Enquete' | 'Mural';
  likes: number;
  replies: number;
  timestamp: string;
  isPinned?: boolean;
  isOfficial?: boolean;
  isHidden?: boolean;
  reportsCount?: number;
}

export interface SubscriberNote {
  id: string;
  subscriberId: string;
  adminId: string;
  content: string;
  category: 'Geral' | 'Suporte' | 'Logística' | 'Financeiro' | 'Retenção';
  createdAt: string;
}

export interface SubscriberInteraction {
  id: string;
  type: 'post' | 'comment' | 'rate' | 'vote' | 'payment_success' | 'payment_failed' | 'box_delivered' | 'admin_note';
  content: string;
  date: string;
  metadata?: any;
}

export type LeadStage = 'NEW' | 'CONTACTED' | 'QUALIFIED' | 'NEGOTIATION' | 'CONVERTED' | 'LOST';

export interface LeadData {
  id: string;
  name: string;
  email?: string;
  phone?: string;
  stage: LeadStage;
  source: string;
  interestLevel: 'Cold' | 'Warm' | 'Hot';
  expectedValue: number;
  createdAt: string;
  lastContact?: string;
  notes?: any[];
}

export interface LeadInteraction {
  id: string;
  leadId: string;
  adminId: string;
  type: 'whatsapp' | 'email' | 'call' | 'note' | 'stage_change';
  content: string;
  metadata?: any;
  createdAt: string;
}

export interface NavItem {
  id: string;
  label: string;
  icon: React.ReactNode;
}

export interface LandingPageConfig {
  assets: {
    logoDark: string;
    heroBg: string;
    heroModel: string;
    productModel: string;
    lifestyle: string;
    boxStill: string;
    goldPanel: string;
    editorialVideo: string;
    [key: string]: string | undefined;
  };
  content: {
    hero: {
      eyebrow: string;
      titlePart1: string;
      titleItalic: string;
      titlePart2: string;
      description1: string;
      description2: string;
      primaryCTA: string;
      secondaryCTA: string;
    };
    stats: Array<{ label: string; desc: string }>;
    differentiation: {
      eyebrow: string;
      title: string;
      text1: string;
      text2: string;
      card?: {
        background: {
          type: 'image' | 'video';
          url: string;
          fit: 'cover' | 'contain' | 'auto';
          repeat: 'no-repeat' | 'repeat' | 'repeat-x' | 'repeat-y';
          opacity: number;
        };
        items: Array<{
          id: string;
          icon: string;
          title: string;
          desc: string;
        }>;
      };
    };
    faqItems: Array<{ question: string; answer: string }>;
    howItWorks?: {
      title: string;
      description: string;
      steps: Array<{
        id: string;
        number: string;
        title: string;
        desc: string;
        icon: string;
      }>;
    };
    marquee?: {
      items: Array<{
        title: string;
        desc: string;
      }>;
    };
    box?: {
      eyebrow: string;
      title: string;
      description: string;
      image: string;
      items: Array<{
        title: string;
        desc: string;
      }>;
    };
    whoItsFor?: {
      eyebrow: string;
      title: string;
      description: string;
      image1: string;
      image2: string;
      quote: string;
      quoteTag: string;
    };
    ritual?: {
      eyebrow: string;
      title: string;
      steps: Array<{
        id: string;
        n: string;
        t: string;
        d: string;
      }>;
      videoUrl: string;
      videoPoster: string;
    };
    portal?: {
      eyebrow: string;
      dna: {
        title: string;
        titleItalic: string;
        desc: string;
        quote: string;
      };
      vault: {
        title: string;
        titleItalic: string;
        desc: string;
        quote: string;
      };
      cta: string;
    };
    footer?: {
      manifestoTitle: string;
      manifestoQuote: string;
      paragraphs: string[];
      cta: string;
    };
    [key: string]: any;
  };
  styling: {
    heroBgOpacity: number;
    heroSectionPaddingTop: number;
    heroSectionPaddingBottom: number;
    [key: string]: any;
  };
}
