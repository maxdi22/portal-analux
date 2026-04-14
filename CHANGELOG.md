# Changelog - AnaLux Portal

## 🚀 1.1.1 Release Notes (Critical Hotfix)
**Date:** 2026-02-02 | **Tag:** Stripe_OK

### 🔴 Critical Fixes
- **Subscription Engine (Stripe Proxy V20):**
  - **Fixed 500 Error:** Implemented "Force 200 OK" error handling pattern.
  - **Database Constraint:** Added UNIQUE constraint to `subscriptions.user_id`.
  - **Data Sync:** Restored missing logic for invoices and portal sessions.
- **Frontend:**
  - **Auth:** Fixed "User already registered" handling.
  - **Dashboard:** Restored payment methods and invoice lists.

---

**Version:** 1.1.0 Beta
**Date:** 2026-02-02
**Author:** CLBe - CLB Enterprises
**Website:** [cble.tech](https://cble.tech)

---

## 🚀 1.1.0 Beta Release Notes

This release introduces community engagement features and backend administration tools for subscription boxes.

### ✨ New Features
- **Community Mural:**
  - Users can create posts and share updates.
  - Image upload support for posts.
  - Emoji picker integration for expressive content.
- **Admin - Boxes & Editions:**
  - New dashboard interface for managing Subscription Boxes.
  - Ability to create and edit Boxes and their monthly Editions.

### 🛠️ Improvements & Fixes
- **Subscription Flow:**
  - Verified and fixed Stripe payment integration.
  - Improved redirection logic to "DNA" profile after successful subscription.

---

## 🚀 1.0.0 Beta Release Notes

**Version:** 1.0.0 Beta
**Date:** 2026-01-26
**Author:** CLBe - CLB Enterprises
**Website:** [cble.tech](https://cble.tech)

---

## 🚀 1.0.0 Beta Release Notes

This release stabilizes the core user experience, completing the "Indique & Brilhe" referral system and the "Style DNA" profile management.

### ✨ New Features
- **Referral System (Indique & Brilhe):**
  - Implemented persistent Referral Code generation (saved to Database).
  - Added "Click" tracking with `increment_referral_click` RPC function.
  - Added Realtime "Online" presence tracking for Referral Links.
  - UI: "Reveal" mechanic for referral stats on Dashboard.

- **Style DNA (Meu DNA):**
  - Full persistence of Style Profile (JSONB) in Supabase.
  - Automatic mapping from rich `style_profile` to Dashboard summary (`stylePrefs`).
  - Fixed "Empty State" flicker by correctly syncing Context state.

### 🛠️ Improvements & Fixes
- **User Architecture:**
  - Revised `UserContext` to robustly handle missing profile fields.
  - Fixed critical crash related to `user.id` vs `profile.id` during referral code generation.
  - Improved `refreshData` error handling to prevent auth state loss.
  - Optimized Onboarding Modal trigger logic (only shows when explicitly needed).

- **Database:**
  - Added independent migration columns: `referral_code`, `referral_clicks`, `referral_count`.

### 📦 Backup Info
This archive contains the full source code for the Frontend Portal.
*Node Modules and .git excluded.*
