# Product Requirements Document (PRD): Analux Box

## 1. Executive Summary
**Product Name:** Analux Box Portal
**Vision:** To create a premium, immersive digital extension of the Analux jewelry subscription service. The portal serves as a "ritual space" where subscribers manage their collection, personalize their style DNA, and engage with the brand's ecosystem.
**Target Audience:** Women who value self-care, style, and exclusivity. They seek convenience but demand a premium experience.

## 2. Core Features & Scope

### 2.1 Authentication & Onboarding
- **Sign Up**: Email/Password registration with automatic data seeding for new users (immediate "wow" factor with demo content).
- **Login**: Email/Password authentication via Supabase.
- **Recovery**: Password reset flow.
- **Security**: Row Level Security (RLS) ensures users only access their own data.

### 2.2 Dashboard (Home)
- **Status Overview**: Visualization of the next box delivery status.
- **Gamification**: Display of points (Cashback) and Member Level (e.g., "Muse").
- **Quick Actions**: Access to latest editorial content or exclusive offers.

### 2.3 Subscription Management
- **Plan Selection**: Toggle between Monthly and Annual billing.
- **Tiers**:
  - **Essencial**: Entry-level.
  - **Ritual**: Mid-tier (Best Value).
  - **Premium**: VIP experience.
- **Control**: Pause subscription or change frequency.

### 2.4 Digital Vault (Acervo Digital)
- **Virtual Collection**: A visual gallery of all jewelry pieces received by the user.
- **Metadata**: Each item tracks Origin (Box Edition), Date Acquired, and "Mood".

### 2.5 Style DNA (Personalization)
- **Preferences**: Users configure:
  - **Metal Tone**: Ouro 18k vs. Ródio Branco.
  - **Style Archetype**: Minimalist, Romantic, Bold, etc.
  - **Product Types**: Necklaces, Earrings, Rings, etc.
- **AI Integration**: (Conceptual) "Style AI" suggests pieces based on this DNA.

### 2.6 Connected Store
- **Integration**: Link to the main e-commerce store.
- **Member Perks**: Exclusive discounts and early access to drops.

## 3. Technical Architecture

### 3.1 Frontend
- **Framework**: React (Vite).
- **Styling**: TailwindCSS with a custom design system (Variables for primary/secondary colors).
- **Icons**: Lucide React.
- **Routing**: React Router DOM (Protected & Public routes).

### 3.2 Backend (Supabase)
- **Authentication**: Native Supabase Auth (Email/Password).
- **Database**: PostgreSQL.
  - `profiles`: User details and preferences.
  - `subscriptions`: Plan status and billing info.
  - `digital_vault`: Items owned by the user.
  - `box_history`: Log of past deliveries.
  - `store_data`: E-commerce sync data.
- **Security**: RLS Policies enabled on all tables.

## 4. User Flows

### Sign Up to Dashboard
1.  User clicks "Acessar Portal" on Landing Page.
2.  Selects "Criar Conta" in the modal.
3.  Enters Name, Email, Password.
4.  **System Action**: Creates Auth User -> Seeds Database with default "Welcome" data.
5.  User is redirected to `/dashboard`.

### Changing Style Preferences
1.  User navigates to "Style AI" tab.
2.  Selects "Ouro" instead of "Ródio".
3.  **System Action**: Updates `profiles.style_prefs` in DB.
4.  Changes are reflected in future box curations (business logic).

## 5. Future Roadmap
- [ ] **Payment Integration**: Real Stripe/Pagar.me checkout for subscription billing.
- [ ] **Community**: Forum or comment section for members.
- [ ] **Mobile App**: PWA or Native wrapper for notifications.
