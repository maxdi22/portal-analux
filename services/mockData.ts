import { User, SubscriptionPlan, SubscriptionStatus, SubscriptionFrequency, BoxStatus, MemberLevel, BoxEditionStatus } from '../types';

export const initialMockData: User = {
  id: 'u1',
  name: 'Ana Luiza Ferreira',
  email: 'ana.ferreira@analux.com.br',
  phone: '(11) 98765-4321',
  avatar: 'https://ui-avatars.com/api/?background=e3a483&color=ffffff&name=Musa+Analux',
  points: 850,
  cashback: 45.90,
  level: MemberLevel.GOLD,
  isStoreConnected: false,
  invitesAvailable: 3,
  digitalVault: [], // Users start with empty vault
  lifePhases: [],   // No phases yet
  storeData: {
    recentOrders: [],
    favorites: [],
    exclusiveOffers: []
  },
  achievements: [
    { id: '1', title: 'Musa Fundadora', icon: '👑', description: 'Assinou nos primeiros meses do clube.', unlockedAt: 'JAN 2024' },
    { id: '2', title: 'Brilho Constante', icon: '✨', description: 'Manteve 6 meses de assinatura ativa.', unlockedAt: 'JUL 2024' }
  ],
  boxHistory: [
    { id: 'b3', name: 'Box Aurora Floral', date: 'Outubro 2024', image: 'https://images.unsplash.com/photo-1596566111082-d5df24433bdc?auto=format&fit=crop&q=80&w=300&h=300', theme: 'Primavera Elegante' },
    { id: 'b2', name: 'Box Noite de Gala', date: 'Setembro 2024', image: 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?auto=format&fit=crop&q=80&w=300&h=300', theme: 'Eventos Sophistiqués' }
  ],
  address: {
    street: 'Alameda das Semijoias',
    number: '123',
    neighborhood: 'Jardins',
    city: 'São Paulo',
    state: 'SP',
    zipCode: '01419-001'
  },
  billing: {
    brand: 'Mastercard',
    lastFour: '8821',
    expiry: '12/28'
  },
  stylePrefs: {
    metals: ['Ouro Amarelo', 'Ouro Branco / Prata'],
    stones: ['Pérolas', 'Cristais Claros'],
    vibe: ['Minimalista', 'Romântico']
  },
  subscription: {
    plan: SubscriptionPlan.PREMIUM,
    status: SubscriptionStatus.ACTIVE,
    frequency: SubscriptionFrequency.MONTHLY,
    currentBoxStatus: BoxStatus.CURATING,
    nextBoxDate: '15 de Nov, 2024',
    memberSince: 'Janeiro, 2024',
    autoRenew: true,
    isDelivered: false,
    currentBox: {
      id: 'b_curr',
      name: 'Box Aurora',
      theme: 'Renascimento',
      month: 'Novembro 2026',
      status: BoxEditionStatus.PRODUCTION,
      type: 'monthly',
      productionDeadline: '2026-01-20', // Past
      assemblyDate: '2026-01-25', // Past/Current
      shippingDate: '2026-01-30' // Future
    }
  }
};
