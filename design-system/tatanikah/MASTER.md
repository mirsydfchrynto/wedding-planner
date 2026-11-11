# Design System Master File — Tatanikah

**Project:** Tatanikah  
**Category:** Wedding & Event Planning SaaS (B2C Couples & B2B Wedding Organizers)  
**Direction:** Warm Heritage Neo-Brutalism & Nusantara Sogan Ledger

---

## Global Rules

### 1. Color Palette (Warm Heritage Neo-Brutalism & Nusantara Sogan)

| Role | Hex | CSS Variable | Tailwind Utility |
|------|-----|--------------|------------------|
| Primary Desk Ground | `#E7DBC7` | `--color-desk` | `bg-desk` |
| Primary Paper Surface | `#FAF5EC` | `--color-paper` | `bg-paper` |
| Secondary Paper / Memo | `#F3E8D3` | `--color-paper-2` | `bg-paper-2` |
| Primary Ink / Outline | `#2B2118` | `--color-ink` | `text-ink`, `border-ink` |
| Secondary Ink / Taupe | `#5D4936` | `--color-taupe` / `--color-ink-2` | `text-taupe` |
| Sogan Nusantara Accent | `#7A4425` | `--color-soga` | `text-soga`, `bg-soga` |
| Sogan Dark | `#532C15` | `--color-soga-dark` | `text-soga-dark` |
| Kunyit Warm Accent | `#F6D365` | `--color-kunyit` | `bg-kunyit` |
| Prada Gold | `#B58A2B` | `--color-prada` | `text-prada` |
| Mint Ground | `#CDE7D8` | `--color-mint` | `bg-mint` |
| Forest Positive / Paid | `#3F5F46` | `--color-forest` | `text-forest`, `bg-forest` |
| Terracotta Accent | `#A64B2A` | `--color-terracotta` | `bg-terracotta` |
| Terracotta Light | `#F7C5B8` | `--color-terracotta-light` | `bg-terracotta-light` |
| Wine Warning / Overdue | `#9C2F24` | `--color-wine` | `text-wine`, `border-wine` |

### 2. Typography

- **Display Serif:** `Cormorant Garamond` (`500`, `600`, `700`) — used for hero headlines, screen titles, and section headers (`font-display`).
- **Body Sans:** `Plus Jakarta Sans` (`400`, `500`, `600`, `700`, `800`) — used for UI copy, forms, table headers, and navigation.
- **Handwriting Accent:** `Kalam` (`400`, `700`) — used for countdown callouts and informal notes (`hand`).
- **Monospace & Numeric Discipline:** `JetBrains Mono` / `.tabular-nums` on all currency amounts (`Rp`), countdowns, and percentages (`rupiah`, `tabular-nums`).

### 3. Neo-Brutalist Ledger Motifs

- **Borders & Shadows:** Crisp `2px solid #2B2118` on all cards, buttons, tabs, and form controls. Hard-offset drop shadows (`3px 3px 0px #2B2118` on buttons, `4px 4px 0px #2B2118` on bento cards).
- **Physical Ledger Cues:** Tape decals (`.tape`, `.tape-right`), postal stamp badges (`.stamp`), index tabs (`.index-tab`).
- **Mobile Bottom Navigation:** Fixed 5-column single-row bar (`.bottom-nav`, `.bottom-nav-item`) with 44px+ hit targets and zero hamburger menus.

---

## Anti-Patterns (Forbidden)

- Do not use emojis as icons in UI controls; use Lucide SVG vector paths (`<x-ui.icon>`). Emoji are permitted only inside WhatsApp message export strings.
- Do not mix accounting bases without clear labels (always distinguish total budget cap, committed vendor contracts, and paid disbursements).
- Do not allow horizontal overflow on mobile viewports (verified across all screens down to `390px`).
- Do not invent speculative features or add dependencies when native platform/standard library suffices.
