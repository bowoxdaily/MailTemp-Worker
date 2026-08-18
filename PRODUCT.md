# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Anyone needing a throwaway email address without registration. Primary personas:

- Privacy-conscious users avoiding spam on their real inbox
- Developers testing email flows (signup, verification, notifications)
- QA testers validating email-dependent features

## Product Purpose

Free temporary email service. Users get an instant disposable email address, receive messages in real-time, read OTPs and verification emails, then the address auto-expires and all data is deleted. Revenue via non-intrusive advertising.

## Positioning

[inferred] Cloudflare-native infrastructure — Cloudflare Email Routing + Workers handle receiving, so delivery is fast and reliable without maintaining a mail server. Clean, mobile-first UI that stays out of the way when reading OTPs.

## Operating Context

- User opens website → gets random email instantly → copies it → uses it on any service → checks inbox for incoming mail → address expires automatically
- No account, no login, no personal data stored beyond the temp session
- Admin manages domains, monitors traffic, blocks abuse via admin panel
- Cloudflare Email Routing catches all mail for configured domains → Worker forwards to Laravel backend API

## Capabilities and Constraints

**Confirmed capabilities (from PRD):**

- Generate random email addresses with configurable expiration (10min default, 30min, 1hr)
- Real-time or polling inbox updates
- Email detail view with HTML (sanitized), plain text, attachments
- Copy address, refresh inbox, delete individual/all emails, delete address
- Admin panel: dashboard stats, domain CRUD, email monitoring, abuse management
- Rate limiting, anti-abuse, HTML sanitization, attachment validation
- SEO landing pages for target keywords
- Ad placements (header, generator area, inbox, footer — never on copy button or OTP area)

**Technical constraints:**

- Laravel 12 + PHP 8.2+ backend, Blade + Tailwind CSS 4 frontend
- MySQL persistence, Redis for cache/sessions/rate-limit/realtime
- Cloudflare Email Routing + Workers as email receiving layer
- Domain(s) configurable via admin panel once Cloudflare is connected

**Undecided:**

- Specific domain(s) — to be configured when Cloudflare service is connected
- WebSocket vs polling for real-time updates (PRD lists WebSocket as optional/P3)
- Object storage provider for attachments

## Brand Commitments

No established brand identity yet. Product name: **Cloudflare Temp Mail** (working title from PRD).

## Evidence on Hand

- Comprehensive PRD.md with user flows, API specs, database schema, security requirements, and phased development plan
- No real content, testimonials, or analytics data yet (greenfield project)

## Product Principles

1. **Zero friction** — instant email, no signup, no personal data
2. **Fast and light** — page load <2s, API <500ms, mobile-first
3. **Privacy by default** — minimal data retention, auto-expiry, auto-deletion
4. **Non-intrusive monetization** — ads never block core functionality (copy, OTP reading)
5. **Abuse-resistant** — rate limiting and blocking at every layer

## Accessibility & Inclusion

Mobile-first responsive design. Target browsers: Android Chrome, iPhone Safari, Desktop Chrome, Firefox, Edge. No specific WCAG level established yet — follow sensible defaults (contrast, keyboard nav, screen reader labels).
