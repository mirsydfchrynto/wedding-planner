# Accessibility Audit Report

**Audit ID:** `full_application_20261104`
**Date:** 2026-11-04 13:28
**Target:** `resources/views/` (Entire Application — 20 Blade templates & `resources/css/app.css`)
**WCAG Level:** AA
**Standard:** WCAG 2.1 / 2.2

## Executive Summary

**Compliance Status:** Passing (100% of checked WCAG 2.1/2.2 Level AA criteria met after immediate remediation of 2 Moderate findings)

| Severity | Count | % of Issues |
| -------- | ----- | ----------- |
| Critical | 0     | 0%          |
| Serious  | 0     | 0%          |
| Moderate | 2 (Remediated) | 100% |
| Minor    | 0     | 0%          |

**Criteria Checked:** 24
**Criteria Passed:** 24 (100%)
**Files Audited:** 20

---

## Critical Issues (Must Fix)

None (0).

---

## Serious Issues

None (0).

---

## Moderate Issues (Remediated in Commit)

### Issue 1: Missing Skip-to-Main-Content Link (`#main-content`)

**WCAG Criterion:** 2.4.1 - Bypass Blocks (Level A)
**Severity:** Moderate (Remediated)
**Location:** `resources/views/layouts/app.blade.php:29`, `resources/views/layouts/admin.blade.php:18`
**Element:** `<body>`

**Problem:**
Keyboard and screen-reader users had to tab through all top header or sidebar navigation links before reaching the `<main>` content region.

**Impact:**
Increases keystrokes required for keyboard-only users navigating between modules.

**Remediation:**
Added a visually hidden (`sr-only focus:not-sr-only`) skip link at the top of `<body>` targeting `<main id="main-content">` in both `layouts/app.blade.php` and `layouts/admin.blade.php`.

**Code Fix:**

```html
<!-- Added at top of <body> -->
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-50 focus:rounded focus:bg-espresso focus:px-4 focus:py-2 focus:text-sm focus:font-bold focus:text-alabaster">
    Lewati ke konten utama
</a>
```

---

### Issue 2: Unlabeled Filter `<select>` Elements in Module Toolbars

**WCAG Criterion:** 4.1.2 - Name, Role, Value (Level A) & 1.3.1 - Info and Relationships (Level A)
**Severity:** Moderate (Remediated)
**Location:** `resources/views/budget/index.blade.php:111,120`, `resources/views/checklist/index.blade.php:82,91`, `resources/views/vendors/index.blade.php:56,65`, `resources/views/livewire/admin-vendor-manager.blade.php:14`
**Element:** `<select name="category" ...>`

**Problem:**
Toolbar filter dropdowns relied on their first `<option>` text instead of an explicit programmatic label (`aria-label`).

**Impact:**
Screen readers may not announce the purpose of the filter once a non-default option is selected.

**Remediation:**
Added explicit Indonesian `aria-label` attributes (`Filter Kategori Anggaran`, `Filter Status Pembayaran Anggaran`, `Filter Status Tugas`, `Filter Penanggung Jawab PIC`, `Filter Kategori Vendor`, `Filter Status Booking Vendor`, `Filter Status SLA Vendor`) to all 7 toolbar `<select>` controls.

---

## Color Contrast Verification (WCAG 1.4.3 Level AA & 1.4.6 Level AAA)

All active token combinations exceed the 4.5:1 Level AA requirement (and most exceed 7.0:1 Level AAA):

| Token Pair | Contrast Ratio | WCAG AA (4.5:1) | WCAG AAA (7.0:1) |
| :--- | :--- | :--- | :--- |
| `Espresso #171614` on `Alabaster #F4F4F2` | **16.42:1** | PASS | PASS |
| `Charcoal #2B2926` on `Alabaster #F4F4F2` | **13.17:1** | PASS | PASS |
| `Taupe #524D47` on `Alabaster #F4F4F2` | **7.59:1** | PASS | PASS |
| `Taupe #524D47` on `Ivory #E8E8E5` | **6.81:1** | PASS | — |
| `Champagne #6E5434` on `Pearl #FFFFFF` | **7.05:1** | PASS | PASS |
| `Champagne #6E5434` on `Champagne-Light #EFECE6` | **5.98:1** | PASS | — |
| `Sage #264E3A` on `Sage-BG #E6F0EB` | **8.07:1** | PASS | PASS |
| `Ochre #9A4406` on `Ochre-BG #FDEFD8` | **5.78:1** | PASS | — |
| `Wine #8E1919` on `Wine-BG #FCE4E4` | **7.54:1** | PASS | PASS |
| `Alabaster #F4F4F2` on `Sage #264E3A` | **8.54:1** | PASS | PASS |

---

## Passed Criteria

| Criterion | Name | Level | Status |
| --- | --- | --- | --- |
| 1.1.1 | Non-text Content (`alt` on photos, `aria-hidden="true"` on decorative SVGs, `role="img"` + `aria-label` on `<canvas>`) | A | Passed |
| 1.3.1 | Info and Relationships (`<header>`, `<nav>`, `<main>`, `<footer>`, `<th scope="col">`, `<label>`) | A | Passed |
| 1.4.3 | Contrast (Minimum >= 4.5:1 across all text & badges; lowest pair is 5.78:1) | AA | Passed |
| 1.4.10 | Reflow (`0px` horizontal overflow at `390px`, `768px`, and `1440px`) | AA | Passed |
| 2.1.1 | Keyboard (`ESC` closes Alpine modals, all actions use native `<button>` or `<a>`) | A | Passed |
| 2.3.1 / 2.3.3 | Animation from Interactions (`@media (prefers-reduced-motion)` disables transforms) | AAA | Passed |
| 2.4.1 | Bypass Blocks (`#main-content` skip link in both layouts) | A | Passed |
| 2.4.7 | Focus Visible (`2px solid var(--color-champagne)` with `2px` offset) | AA | Passed |
| 2.5.8 | Target Size (`min-height: 2.75rem` / `44px` on mobile controls) | AA/AAA | Passed |
| 3.1.1 | Language of Page (`<html lang="id">`) | A | Passed |
| 4.1.2 | Name, Role, Value (`role="dialog"`, `aria-modal="true"`, `role="progressbar"`, `aria-valuenow`) | A | Passed |

---

_Generated by UI Design Accessibility Audit_
_WCAG Reference: https://www.w3.org/WAI/WCAG21/quickref/_
