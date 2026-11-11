# Design System Master File — Tatanikah

**Project:** Tatanikah  
**Category:** Wedding & Event Planning Web Application (Indonesian Couples & Families)  
**Direction:** Warm Minimalism × Modern Editorial × Clean SaaS  

---

## Global Rules

### 1. Color Palette (Warm Minimalism & Sogan Editorial)

| Role | Hex | CSS Variable | Description |
|------|-----|--------------|-------------|
| Canvas Ground | `#FAF8F5` | `--color-canvas` | Warm Alabaster page ground |
| Primary Surface | `#FFFFFF` | `--color-surface` | Pure White card and modal backgrounds |
| Surface Subtle | `#F5F2EB` | `--color-surface-subtle` | Muted Oat sub-panel ground |
| Hairline Border | `#E7E4DF` | `--color-border` | 1px clean border |
| Hairline Divider | `#F0ECE4` | `--color-border-subtle` | Light table and card divider |
| Primary Ink | `#2B2520` | `--color-ink` | Deep charcoal primary body and header text |
| Secondary Ink | `#6E665E` | `--color-ink-muted` | Muted taupe for captions and labels |
| Tertiary Ink | `#9E968D` | `--color-ink-subtle` | Form placeholders and disabled items |
| Brand Accent | `#8C5835` | `--color-brand` | Warm Sogan Terracotta brand accent |
| Brand Dark | `#6E3F20` | `--color-brand-dark` | Hover state for brand buttons |
| Brand Soft | `#F4ECE4` | `--color-brand-soft` | Subtle tint for active pills and tags |
| Sage / Forest | `#2E5E3E` | `--color-sage` | Success, paid status, WhatsApp green |
| Sage Soft | `#EAF4EE` | `--color-sage-soft` | Background for success pills |
| Amber | `#A06414` | `--color-amber` | Pending, warning, in-progress items |
| Amber Soft | `#FEF7EC` | `--color-amber-soft` | Background for pending pills |
| Wine | `#9E352B` | `--color-wine` | Overdue, deficit, alert badges |
| Wine Soft | `#FDF2F0` | `--color-wine-soft` | Background for overdue alert cards |

### 2. Typography

- **Display Serif:** `Cormorant Garamond` (`500`, `600`, `700`) — used for hero headlines, screen titles, and section headers (`font-display`).
- **Body Sans:** `Plus Jakarta Sans` (`400`, `500`, `600`, `700`) — used for UI copy, forms, table headers, and navigation.
- **Monospace & Numeric Discipline:** `JetBrains Mono` / `.tabular-nums` on all currency amounts (`Rp`), countdowns, and percentages (`rupiah`, `tabular-nums`).

### 3. Components & Elevation

- **Cards:** `rounded-2xl border border-[#E7E4DF] bg-white shadow-sm`.
- **Buttons (Primary):** `rounded-full bg-[#2B2520] text-white px-5 py-2.5 text-xs font-semibold hover:bg-[#1C1815] active:scale-[0.98] transition`.
- **Buttons (Secondary):** `rounded-full border border-[#E7E4DF] bg-white text-[#2B2520] px-4 py-2 text-xs font-semibold hover:bg-[#F5F2EB] active:scale-[0.98] transition`.
- **Badges & Pills:** `rounded-full border border-[#E7E4DF] bg-[#FAF8F5] px-2.5 py-0.5 text-xs font-semibold`.
- **Inputs:** `rounded-xl border border-[#E7E4DF] bg-white px-3.5 py-2.5 text-xs sm:text-sm text-[#2B2520] placeholder-[#9E968D] focus:border-[#8C5835] focus:outline-none focus:ring-1 focus:ring-[#8C5835] transition`.
- **Desktop Navigation:** Frosted sticky header (`backdrop-blur-md bg-[#FAF8F5]/90 border-b border-[#E7E4DF]`) with pill tabs.
- **Mobile Bottom Navigation:** Frosted bottom bar (`backdrop-blur-lg bg-[#FAF8F5]/92 border-t border-[#E7E4DF]`) with 5 single-tap items.

---

## Anti-Patterns (Forbidden)

- No brutalist 2px/3px black outlines, cartoon drop shadows, or harsh angle transforms.
- No tape decals (`.tape`) or jagged stamp edges.
- Do not use emojis as icons in UI controls; use Lucide SVG vector paths (`<x-ui.icon>`). Emoji are permitted only inside WhatsApp message export strings.
- Do not allow horizontal overflow on mobile viewports (minimum width `390px`).
- Do not invent speculative features or add unnecessary third-party CSS frameworks.
