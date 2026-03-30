# CSS Refactor Plan

## Overview

The current CSS architecture has grown organically and now contains conflicting layout systems, magic numbers, and brittle breakpoints. This document outlines a phased approach to refactor the CSS into a maintainable, performant, and future-proof system.

**Current Issues:**
- Two competing layout systems (unified grid vs. legacy page-specific layouts)
- Fixed pixel values that don't scale gracefully
- Magic number breakpoints (1119px, 1279px, etc.)
- ~400 lines of unused legacy CSS
- Inconsistent spacing units (mix of rem and relational)
- No fallbacks for older browsers
- Accessibility issues with mobile menu

**Goals:**
- Single, unified layout system
- Truly responsive grid (fluid, not fixed)
- Semantic breakpoints
- Clean, maintainable code
- Better browser support
- Improved accessibility

---

## Phase 1: Clean Up Legacy Code
**Priority:** High
**Estimated Time:** 2-3 hours
**Risk:** Low (safe deletions)

### Tasks

#### 1.1 Remove Legacy Layout CSS
**Lines to delete:** ~400 lines

Delete these unused layout systems:
- `.home-layout` and related styles (lines 659-712)
- `.about-layout` and related styles (lines 822-895)
- `.projects-layout` and related styles (lines 897-961)
- `.links-layout` and related styles (lines 964-1043)
- `.posts-layout` / `.article-layout` (lines 1048-1205)

**Before deleting:** Verify no templates still reference these classes
```bash
grep -r "home-layout\|about-layout\|projects-layout\|links-layout\|posts-layout\|article-layout" site/templates/
```

#### 1.2 Remove Unused CSS Rules
- Card color variants (`.card-posts`, `.card-projects`, `.card-links`) - lines 806-825
- Duplicate typography rules (redundant heading styles)
- Old breadcrumb padding rules tied to legacy layouts

#### 1.3 Consolidate Main Element Styles
Currently have multiple conflicting `main` styles:
- Base `main` at line 127 (max-width: 600px)
- Override for `.site-main` at line 536
- Multiple `main:has()` overrides for legacy layouts

**Action:** Keep only the `.site-main` approach, remove others

### Verification
- [ ] All templates still render correctly
- [ ] No console errors
- [ ] Visual regression test on all pages
- [ ] CSS file size reduced by ~400 lines

---

## Phase 2: Fix Core Architecture
**Priority:** High
**Estimated Time:** 4-6 hours
**Risk:** Medium (requires testing)

### Tasks

#### 2.1 Make Grid Truly Responsive

**Current problem:** Fixed 480px columns don't scale

**Solution:** Use `min()` for fluid columns
```css
:root {
    /* Fluid column width: 480px OR 40% of viewport, whichever is smaller */
    --col-width: min(480px, 40vw);
    /* Gap remains proportional */
    --col-gap: calc(var(--col-width) * 0.5);
}
```

**Alternative:** Use CSS `clamp()` for bounded scaling
```css
--col-width: clamp(320px, 40vw, 480px);
```

#### 2.2 Implement Semantic Breakpoints

**Replace magic numbers with meaningful names:**
```css
:root {
    /* Based on content needs, not arbitrary device sizes */
    --bp-mobile-max: 767px;
    --bp-tablet-min: 768px;
    --bp-tablet-max: 1199px;
    --bp-desktop-min: 1200px;
    --bp-wide-min: 1560px;
}

@media (min-width: var(--bp-desktop-min)) {
    /* Grid layout */
}
```

#### 2.3 Add CSS Grid Fallbacks

**Support browsers without grid:**
```css
.site-main {
    /* Flexbox fallback */
    display: flex;
    flex-direction: column;
}

@supports (display: grid) {
    @media (min-width: 1200px) {
        .site-main {
            display: grid;
            grid-template-columns: var(--col-width) var(--col-width);
        }
    }
}
```

#### 2.4 Fix Mobile Menu Accessibility

**Current issue:** Hidden menu still accessible to screen readers

**Solution:**
```css
.menu {
    /* When hidden */
    visibility: hidden;
    max-height: 0;
    overflow: hidden;
}

.menu[aria-hidden="false"] {
    visibility: visible;
    max-height: 400px;
}
```

### Verification
- [ ] Grid scales smoothly at all viewport sizes
- [ ] Layout works in IE11/older Safari (with fallbacks)
- [ ] Screen reader only announces visible menu items
- [ ] No layout shift when resizing

---

## Phase 3: Standardize & Optimize
**Priority:** Medium
**Estimated Time:** 3-4 hours
**Risk:** Low

### Tasks

#### 3.1 Consistent Spacing System

**Replace all rem values with relational units:**

Current mix:
```css
margin: 1.5rem;  /* Arbitrary rem */
padding: 3rem;   /* Arbitrary rem */
```

Standardized:
```css
margin: calc(var(--base-unit) * 0.1875);  /* 1.5rem at 80px base */
padding: calc(var(--base-unit) * 0.375);  /* 3rem at 80px base */
```

**Or:** Define semantic spacing scale
```css
:root {
    --space-xs: calc(var(--base-unit) * 0.125);   /* 10px */
    --space-sm: calc(var(--base-unit) * 0.1875);  /* 15px */
    --space-md: calc(var(--base-unit) * 0.25);    /* 20px */
    --space-lg: calc(var(--base-unit) * 0.5);     /* 40px */
    --space-xl: calc(var(--base-unit) * 1);       /* 80px */
}
```

#### 3.2 Component Isolation

**Separate concerns into logical blocks:**
- `_header.css` - Header and navigation
- `_cards.css` - Card components
- `_art.css` - Procedural art container
- `_layout.css` - Grid system
- `_typography.css` - Type styles
- `_utilities.css` - Helper classes

**Or:** Keep single file but improve organization with clear sections

#### 3.3 Improve Responsive Strategy

**Document breakpoint rationale:**
```css
/*
 * BREAKPOINT STRATEGY
 *
 * Mobile-first approach with content-based breakpoints:
 *
 * - Mobile (<768px): Single column, stacked layout
 * - Tablet (768-1199px): Transitional, may stack or show grid
 * - Desktop (≥1200px): Two-column grid (requires 1200px for content)
 * - Wide (≥1560px): Enhanced spacing, larger headers
 */
```

### Verification
- [ ] All spacing uses relational units
- [ ] Code is logically organized
- [ ] Comments explain "why" not "what"

---

## Phase 4: Nice-to-Have Enhancements
**Priority:** Low
**Estimated Time:** 4-6 hours
**Risk:** Low (optional improvements)

### Tasks

#### 4.1 CSS Container Queries

**Make components truly responsive to their container:**
```css
.card {
    container-type: inline-size;
}

@container (min-width: 400px) {
    .card-title {
        font-size: var(--font-lg);
    }
}
```

**Browser support:** Chrome 105+, Safari 16+ (fallback to media queries)

#### 4.2 Enhanced Theming

**Dynamic color schemes:**
```css
:root {
    --theme-primary: var(--color-home);
    --theme-on-primary: white;
}

[data-section="about"] {
    --theme-primary: var(--color-about);
}

.accent {
    background: var(--theme-primary);
    color: var(--theme-on-primary);
}
```

#### 4.3 Performance Optimization

**Split critical CSS:**
- Inline critical path CSS (above-the-fold)
- Lazy load rest with `preload`

**Minification:**
- Use PostCSS or cssnano for production builds
- Remove comments and whitespace

**Tree-shaking:**
- Use PurgeCSS to remove unused styles (if using Tailwind or similar)

### Verification
- [ ] Container queries work with fallbacks
- [ ] Theme switching is smooth
- [ ] Performance metrics improved (Lighthouse score)

---

## Migration Strategy

### Safe Migration Path

**Don't refactor everything at once.** Use feature flags:

```css
/* Old layout (deprecated) */
.home-layout {
    /* ... existing code ... */
}

/* New layout (active) */
.site-main {
    /* ... new code ... */
}
```

### Rollout Plan

1. **Phase 1** → Commit, test on staging, deploy
2. **Phase 2** → Commit in smaller chunks, test each change
3. **Phase 3** → Can be done incrementally
4. **Phase 4** → Optional, implement as time allows

### Testing Checklist

For each phase:
- [ ] Visual regression test (screenshots before/after)
- [ ] Test on real devices (iPhone, Android, iPad)
- [ ] Test in multiple browsers (Chrome, Firefox, Safari, Edge)
- [ ] Test with assistive technologies (screen reader)
- [ ] Check Lighthouse scores (performance, accessibility)
- [ ] Validate HTML/CSS (W3C validators)

---

## Success Metrics

**Code Quality:**
- Reduce CSS file size by 30-40% (~400-500 lines)
- Zero unused CSS rules
- 100% consistent spacing system
- Clear, documented architecture

**Performance:**
- Lighthouse performance score: 95+
- First Contentful Paint: <1s
- Cumulative Layout Shift: <0.1

**Maintainability:**
- Single source of truth for layouts
- Easy to add new pages without new CSS
- Clear breakpoint strategy
- Well-documented code

**Compatibility:**
- Works in all modern browsers
- Graceful degradation for older browsers
- Passes WCAG 2.1 AA accessibility

---

## Resources

**Tools:**
- [PurgeCSS](https://purgecss.com/) - Remove unused CSS
- [PostCSS](https://postcss.org/) - Transform CSS with JS
- [CSS Validation](https://jigsaw.w3.org/css-validator/)
- [Can I Use](https://caniuse.com/) - Browser support tables

**References:**
- [MDN: CSS Grid Layout](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Grid_Layout)
- [MDN: Container Queries](https://developer.mozilla.org/en-US/docs/Web/CSS/CSS_Container_Queries)
- [Every Layout](https://every-layout.dev/) - Reusable layout patterns

**Testing:**
- [BrowserStack](https://www.browserstack.com/) - Cross-browser testing
- [Percy](https://percy.io/) - Visual regression testing
- [Lighthouse CI](https://github.com/GoogleChrome/lighthouse-ci) - Automated audits

---

## Notes

**Created:** 2026-03-29
**Status:** Draft
**Last Updated:** 2026-03-29

**Context:** After implementing the centered layout, we discovered the CSS had grown organically with multiple competing systems. This plan addresses technical debt while improving maintainability and performance.

**Estimated Total Time:** 13-19 hours across all phases
**Recommended Approach:** Complete Phase 1 and 2, defer 3 and 4 to future iterations
