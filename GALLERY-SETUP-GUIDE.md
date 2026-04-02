# Gallery Setup Guide

## Quick Summary

Your horizontal scroll gallery is now implemented! This guide covers:
1. How to use the new gallery fields in the Panel
2. How to update existing projects to use the new fields
3. Troubleshooting common issues

---

## Issue 1: Navigation in File Edit View ✅ FIXED

**Problem:** When editing a gallery image in the Panel, there's no obvious back button.

**Solution:**
- The Panel's **breadcrumb navigation** at the top of the page lets you navigate back
- I've added an info message in the file edit view reminding you to use the breadcrumbs
- The breadcrumb trail shows: Site > Projects > [Project Name] > [Image Name]
- Click any part of the breadcrumb to navigate back

**Alternative:** Press `ESC` key to close the file panel and return to the project page.

---

## Issue 2: Existing Projects Missing New Fields ✅ FIXED

**Problem:** Your two existing projects (Jot Text Editor and Journal Prompts) don't show the new gallery/cover image fields.

**Why This Happens:**
When you update a blueprint, existing content files don't automatically get the new fields. The fields only appear when you edit the page in the Panel.

### Option A: Update via Panel (Recommended - Easy)

1. **Open the Kirby Panel** (http://localhost:8000/panel or https://brianjgoodwin.net/panel)

2. **Navigate to Projects** → **Jot - Text Editor**

3. **The new fields will appear automatically:**
   - Breadcrumb Title (optional)
   - Gallery Images section (for horizontal scroll gallery)
   - Cover Image (for the project card thumbnail on Projects page)

4. **Click "Save"** - This writes the new fields to the content file

5. **Repeat for "Journal Prompts"**

That's it! The projects now have all the new fields.

---

### Option B: Manual Content File Update (Advanced)

If you prefer to edit content files directly:

**File:** `content/3_projects/jot-text-editor/jot-text-editor.txt`

Add these fields after the `Body` field:

```
----

Breadcrumbtitle:

----

Coverimage:
```

The fields will be empty until you upload images via the Panel.

**Do this for both:**
- `content/3_projects/jot-text-editor/jot-text-editor.txt`
- `content/3_projects/journal-prompts/journal-prompts.txt`

---

## Using the Gallery

### Adding Images to a Project

1. **Edit a project page** in the Panel
2. **Scroll to "Gallery Images" section**
3. **Drag & drop images** or click to upload
4. **For each image:**
   - Add **Alt Text** (required for accessibility)
   - Add **Caption** (optional - shown in expanded view)
5. **Drag images to reorder** them in the gallery
6. **Save the project**

### Adding a Cover Image (Project Card Thumbnail)

1. **Edit a project page** in the Panel
2. **Scroll to the sidebar** (right column)
3. **Find "Cover Image"** field
4. **Upload a square image** (120×120px recommended)
5. This image appears on the Projects listing page
6. **Save the project**

---

## How the Gallery Works

### On the Frontend:

**Thumbnail Row:**
- Images display in a horizontal scrollable row
- 240px height on desktop, 160px on mobile
- Smooth scroll with mouse/trackpad
- Touch-friendly swipe on mobile devices

**Modal Viewer:**
- Click any thumbnail to open full-size view
- Previous/Next buttons to navigate
- Close with × button, ESC key, or click backdrop
- Captions appear below images (if added)

**Keyboard Navigation:**
- Tab to thumbnail, Enter/Space to open
- Arrow Left/Right to navigate images
- ESC to close
- Fully accessible for screen readers

---

## File Size & Format Recommendations

### Gallery Images:
- **Format:** JPEG (photos), PNG (screenshots with transparency), WebP (modern browsers)
- **Max size:** 10MB per image
- **Recommended width:** 1200-2000px for high-DPI displays
- **Aspect ratio:** Any - gallery preserves original ratios

### Cover Images:
- **Format:** JPEG or PNG
- **Recommended size:** 240×240px (displays at 120×120px, but 2× for retina screens)
- **Aspect ratio:** Square (1:1)
- **Max size:** 5MB

---

## Troubleshooting

### Gallery doesn't appear on project page
- **Check:** Did you upload images to "Gallery Images" section?
- **Check:** Did you save the project after uploading?
- **Check:** Are images using the `gallery-image` template?
  - In the Panel, click an image and verify template is set correctly

### Cover image not showing on Projects page
- **Check:** Did you upload to "Cover Image" field (in sidebar)?
- **Check:** Did you save the project?
- **Note:** Cover images are separate from gallery images

### Modal won't open when clicking thumbnails
- **Check browser console** for JavaScript errors
- **Verify:** gallery.js is loading (check Network tab in DevTools)
- **Clear cache:** The gallery.js file might be cached with old version

### Images are wrong size
- Gallery automatically scales images to fit 240px height (desktop) or 160px (mobile)
- Width is automatic based on original aspect ratio
- Use `object-fit: contain` to preserve full image

---

## Next Steps

1. ✅ Update existing projects via Panel (Option A above)
2. ✅ Upload some gallery images to test
3. ✅ Add cover images for project cards
4. Test the gallery on different devices
5. Verify accessibility with keyboard navigation
6. Push to production and clear cache

---

## Files Modified

**Blueprints:**
- `site/blueprints/pages/project.yml` - Added gallery & cover image fields
- `site/blueprints/files/gallery-image.yml` - Created gallery image template

**Templates:**
- `site/templates/project.php` - Added gallery rendering

**Assets:**
- `assets/css/index.css` - Gallery styles (~215 lines)
- `assets/js/gallery.js` - Gallery functionality (~2.5kb)

**Snippets:**
- `site/snippets/gallery.php` - Reusable gallery component
- `site/snippets/header.php` - Conditionally loads gallery.js

---

Last Updated: 2026-04-01
