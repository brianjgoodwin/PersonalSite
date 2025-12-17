# Column Grid Block

A flexible, responsive grid component for creating 1-4 column layouts with powerful per-column controls.

## Features

- **1-4 columns** with equal or asymmetric width ratios
- **Nested blocks** - each column can contain images, text, markdown, or other blocks
- **Brutalist drop shadows** (225° angle, consistent with site design)
- **Page color integration** - columns can inherit the page color
- **Mobile responsive** - gaps disappear, columns stack, reorder, or hide
- **Vertical stacking** - mix images and text within a single column

## Grid Settings

### Number of Columns
Choose 1-4 columns

### Column Width Ratios
**Presets:**
- Equal: All columns same width
- 2:1, 1:2, 3:1, 1:3 (for 2 columns)
- 2:1:1, 1:2:1, 1:1:2 (for 3 columns)
- 2:1:1:1, 1:2:1:1, 1:1:2:1, 1:1:1:2 (for 4 columns)
- Custom: Enter your own ratio (e.g., "2-1-1-3")

### Gap Between Columns
- Small: 1rem
- Medium: 2rem (default)
- Large: 3rem

**Mobile behavior:** Gap disappears on mobile (blocks touch)

### Vertical Spacing
Top and bottom margins for the entire grid:
- None, Small (1rem), Medium (2rem), Large (3rem)

## Column Settings

Each column has independent controls:

### Content
Use Kirby's block editor to add:
- Images
- Text/Markdown
- Other blocks (including nested column grids!)

Stack multiple content types vertically within one column.

### Padding
- None (default)
- Small: 1rem
- Medium: 2rem
- Large: 3rem

### Background
- None (transparent)
- **Page Color**: Inherits the page's custom color (brutalist, full strength)
- White
- Custom: Enter RGB values (e.g., "100, 100, 100")

### Drop Shadow
Brutalist 225° shadows:
- None
- Light: -2px 2px, 10% opacity
- Medium: -3px 3px, 20% opacity
- Strong: -4px 4px, 30% opacity

### Vertical Alignment
How content aligns within the column:
- Top (default)
- Center
- Bottom

### Mobile Behavior

**Show on Mobile:** Toggle to hide specific columns on small screens

**Mobile Order:** Set order (1-4) for how columns stack on mobile
- Columns reorder based on this number
- Use this to prioritize important content first on mobile

## Examples

### Two-Column Text Layout
- Columns: 2
- Ratio: Equal
- Gap: Medium
- Both columns: Add markdown blocks

### Hero Section with Image + Text
- Columns: 2
- Ratio: 1:2
- Gap: Large
- Column 1: Image with strong shadow
- Column 2: Markdown text, medium padding

### Three-Column Feature Grid
- Columns: 3
- Ratio: Equal
- Gap: Medium
- Each column: Page color background, medium padding, light shadow

### Nested Layout
- Columns: 2
- Ratio: 2:1
- Column 1: Add another Column Grid block (nested!)
- Column 2: Text content

### Mobile Reordering
- Columns: 3
- Column 1: Mobile Order = 2
- Column 2: Mobile Order = 1 (appears first on mobile)
- Column 3: Mobile Order = 3

## Tips

1. **Empty columns:** You can leave columns empty - they'll maintain grid structure
2. **Nested grids:** Column grids can contain other column grids for complex layouts
3. **Markdown friendly:** Write in markdown blocks within columns
4. **Minimal design:** Leave backgrounds/shadows off for clean, minimal layouts
5. **Page color:** Use brutalist page color backgrounds for strong visual impact

## Files

- Blueprint: `site/blueprints/blocks/columngrid.yml`
- Snippet: `site/snippets/blocks/columngrid.php`
- CSS: Added to `assets/css/site.css` (lines 285-312)
