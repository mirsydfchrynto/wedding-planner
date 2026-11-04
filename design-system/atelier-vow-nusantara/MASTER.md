# Design System Master File — Atelier Vow Nusantara

**Project:** Atelier Vow Nusantara
**Category:** Wedding & Event Planning SaaS (B2C Couples & B2B Wedding Organizers)
**Direction:** Colour Chapters (`seed 941202981`) + Breeze Slate Admin Console

---

## Global Rules

### 1. Color Palette (Low-Chroma Architectural Stone & Sogan Nusantara)

| Role | Hex | CSS Variable |
|------|-----|--------------|
| Primary Ground | `#F4F4F2` | `--color-alabaster` |
| Secondary Field | `#E8E8E5` | `--color-ivory` |
| Surface Card | `#FFFFFF` | `--color-pearl` |
| Hairline Rule | `#DCDCD7` | `--color-stone-line` |
| Primary Ink | `#171614` | `--color-espresso` |
| Secondary Ink | `#2B2926` | `--color-charcoal` |
| Muted Caption | `#524D47` | `--color-taupe` |
| Brand Accent | `#6E5434` | `--color-champagne` |
| Positive / Paid | `#264E3A` | `--color-sage` |
| Warning / DP | `#9A4406` | `--color-ochre` |
| Negative / Overdue | `#8E1919` | `--color-wine` |

### 2. Typography

- **Primary Font:** `Plus Jakarta Sans` (`400`, `500`, `600`, `700`)
- **Numeric Discipline:** `.tabular-nums` (`font-variant-numeric: tabular-nums`) on all currency (`Rp`), countdowns, and percentages.
- **Scale:**
  - Landing Hero `h1`: `clamp(2.25rem, 4.2vw, 3.75rem)` (`<= 2 lines` at all viewports)
  - Landing Chapter `h2`: `2.25rem` (`36px`)
  - App Screen `h1`: `1.5rem` (`24px`)
  - App Section `h2`: `1.25rem` (`20px`)
  - Body Copy: `1.125rem` (`18px`) on Landing Page; `0.875rem` (`14px`) on App Screens.

### 3. Motion Choreography (`prefers-reduced-motion` guarded)

- **Curves:**
  - `--ease-out: cubic-bezier(0.22, 1, 0.36, 1)`
  - `--ease-expo: cubic-bezier(0.16, 1, 0.3, 1)`
  - `--ease-in-out: cubic-bezier(0.76, 0, 0.24, 1)`
- **Recipes:**
  - Hero headline masked line rise (`[data-line]`, `750ms var(--ease-expo)`)
  - Hero stage shutter reveal (`[data-open]`, `950ms var(--ease-in-out)`)
  - Scroll-triggered section entrance (`[data-reveal]`, `IntersectionObserver`, fires once)

---

## Anti-Patterns (Forbidden)

- Do not use emojis as icons; use Lucide SVG vector paths (`<x-ui.icon>`).
- Do not place eyebrow/kicker pills above headings.
- Do not use `1px` outlined card grids on the public landing page; separate chapters by full-bleed flat tone steps.
- Do not animate layout properties (`width`, `height`, `margin`); animate only `transform` and `opacity`.
- Do not allow mobile inputs below `16px` (`1rem`) or touch targets below `44px` (`2.75rem`).
