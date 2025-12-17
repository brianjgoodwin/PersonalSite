# Swiss-Inspired Layout Guide

## Design Overview

This is a personal website layout inspired by **International Typographic Style** (Swiss Design), featuring:

- Clean, modular grid-based structure
- Limited color palette (black, white, gray, red accent, page theme colors)
- Asymmetric but balanced composition
- Strong typography hierarchy
- Geometric precision with functional simplicity

---

## Main Layout Structure

### CSS Grid System

The layout uses CSS Grid (not visible grid lines) for positioning:

```css
body {
    display: grid;
    grid-template-rows: auto 1fr auto;
}
```

This creates three main sections:
1. **Header** (auto height)
2. **Main content area** (1fr - takes remaining space)
3. **Footer** (auto height)

### Page Wrapper Grid

```css
.page-wrapper {
    grid-template-columns: 1fr auto;
}
```

Two columns:
1. **Main content** (`1fr`) - expands to fill available space
2. **Vertical sidebar** (`auto`) - width based on content

---

## Key Components

### 1. Header (`<header class="site-header">`)

**Structure:**
- Black box logo: "Brian Goodwin" in white text
- Red accent bar extending from bottom of logo (using `::after` pseudo-element)
- Navigation menu (desktop): About, Posts, Links, Projects
- Hamburger menu (mobile): Shows on screens ≤768px
- Drop shadow for depth

**CSS location:** Lines 72-135

### 2. Logo with Red Accent

The red bar is created with a CSS pseudo-element:

```css
.logo::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 8px;
    background-color: var(--accent-red);
    transform: translateY(100%);
}
```

This creates the visual connection between the logo and header.

### 3. Main Content Area (`<main class="main-content">`)

- White background contrasts with light gray page background
- Max-width: 1200px (centered)
- Contains all primary page content
- Flexible content blocks and columns

**CSS location:** Lines 145-151

### 4. Vertical Sidebar (`<aside class="vertical-sidebar">`)

**Features:**
- Rotated text (90° vertical)
- Background color changes per page using CSS custom properties
- Fixed width: 120px (desktop), 80px (mobile)

**Text rotation:**
```css
.vertical-sidebar-text {
    writing-mode: vertical-rl;
    transform: rotate(180deg);
}
```

**CSS location:** Lines 153-169

### 5. Footer (`<footer class="site-footer">`)

- Black background with white text
- Full width at bottom of page
- Centered text

**CSS location:** Lines 197-203

---

## Color Theming System

### CSS Custom Properties (Line 9-15)

```css
:root {
    --page-color: #E89B8E; /* Salmon/pink for Home page */
    --accent-red: #D9534F;
    --text-black: #000000;
    --bg-light: #E8E8E8;
    --bg-white: #FFFFFF;
}
```

### How to Change Page Colors

The `--page-color` variable controls the vertical sidebar color.

**To create different page themes:**

1. **Home page:** `--page-color: #E89B8E;` (salmon/pink)
2. **About page:** `--page-color: #7EC8E3;` (blue)
3. **Projects page:** `--page-color: #98D8A0;` (green)
4. **Posts page:** `--page-color: #F4C542;` (yellow)

Simply change this value in the `:root` selector for different pages, or use inline styles/JavaScript to switch dynamically.

---

## Content Components

### Content Block (`.content-block`)

Single-column white box for content:

```html
<div class="content-block">
    <h1>Title</h1>
    <p>Content goes here...</p>
</div>
```

### Two-Column Layout (`.two-column`)

Grid-based two-column component:

```html
<div class="two-column">
    <div class="column">
        <h3>Column One</h3>
        <p>Content...</p>
    </div>
    <div class="column">
        <h3>Column Two</h3>
        <p>Content...</p>
    </div>
</div>
```

**CSS location:** Lines 175-191

**Behavior:**
- Desktop: Two equal columns side by side
- Mobile: Stacks vertically (line 242)

---

## Responsive Behavior

### Breakpoint: 768px

**Desktop (>768px):**
- Navigation menu visible in header
- Hamburger menu hidden
- Vertical sidebar: 120px wide, part of grid layout
- Two-column layouts display side by side

**Mobile (≤768px):**
- Navigation menu hidden
- Hamburger menu visible (☰ icon)
- Vertical sidebar: 80px wide, fixed position on right
- Main content gets extra right padding (90px) to accommodate fixed sidebar
- Two-column layouts stack vertically
- Reduced padding throughout

**CSS location:** Lines 205-249

---

## Typography

Clean, minimal type scale using system fonts:

```css
font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
```

**Hierarchy:**
- H1: 2.5rem (40px)
- H2: 2rem (32px)
- H3: 1.5rem (24px)
- H4: 1.25rem (20px)
- Body: Default (16px)
- Line-height: 1.6

**CSS location:** Lines 34-70

---

## Customization Tips

### Adjusting Spacing

Main content padding:
```css
.main-content {
    padding: 3rem 2rem; /* Vertical | Horizontal */
}
```

Content block spacing:
```css
.content-block {
    padding: 2rem;
    margin-bottom: 2rem;
}
```

### Changing Colors

All main colors are defined as CSS custom properties at the top (lines 9-15). Change them in one place to update throughout.

### Adjusting the Grid

To change the max-width of content:
```css
.main-content {
    max-width: 1200px; /* Adjust this value */
}
```

To change vertical sidebar width:
```css
.vertical-sidebar {
    width: 120px; /* Desktop width */
}
```

### Shadow Intensity

Header drop shadow (line 81):
```css
box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
           /* X  Y  Blur  Color with opacity */
```

Increase the last value (0.1) for a darker shadow, or adjust blur for softer/sharper edges.

---

## File Structure

```
/Users/work/Desktop/Web Test/
├── index.html          # Main HTML file with embedded CSS
└── LAYOUT_GUIDE.md     # This documentation
```

---

## Next Steps

1. **Create additional pages:** Copy index.html and change `--page-color` for different sections
2. **Add real content:** Replace placeholder content with your actual text and images
3. **Implement navigation:** Make the nav links functional by creating corresponding pages
4. **Mobile menu:** Add JavaScript to make the hamburger menu functional
5. **Refine typography:** Choose a specific typeface if desired (consider Helvetica, Arial, or a web font like Inter or IBM Plex Sans for Swiss design authenticity)

---

## Design Philosophy Notes

**Swiss Design Principles Applied:**

- **Grid-based layouts:** CSS Grid provides invisible structure
- **Asymmetry with balance:** Logo on left, nav on right; sidebar creates visual weight
- **Limited color palette:** Black, white, gray + one accent color per page
- **Negative space:** Generous padding and margins let content breathe
- **Typography as design element:** Vertical sidebar text is both functional and decorative
- **Functional simplicity:** Every element serves a purpose

**Not included but could be added:**

- Visible grid overlays (purely decorative)
- Geometric shapes or color blocks
- More dramatic use of scale in typography
- Stricter mathematical grid proportions

---

*Layout created 2024 - Swiss-inspired personal website framework*
