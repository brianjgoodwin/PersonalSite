<?php
/**
 * Procedural Art Sidebar
 *
 * Generates canvas-based geometric art using the current page's theme color
 * Uses page URL as seed for consistency across visits
 *
 * Features:
 * - Grid-influenced composition with 15-40 shapes
 * - Mix of circles and rotated squares
 * - Theme color dominant (70%) with palette accents (30%)
 * - Page-seeded randomness (same art on each visit to same page)
 * - Height adapts to primary column content on desktop
 * - Fixed max-height (3X base-unit) on mobile
 */

// Get current section for theme color
if ($page->isHomePage()) {
    $section = $page;
} else {
    $section = $page;
    while ($section->parent() && !$section->parent()->isHomePage()) {
        $section = $section->parent();
    }
}
$sectionSlug = $section->slug();

// Generate seed from page URL for consistency
$seed = crc32($page->url());
?>

<div class="art-container" data-section="<?= esc($sectionSlug, 'attr') ?>" data-seed="<?= esc($seed, 'attr') ?>">
  <canvas id="art-canvas"></canvas>
</div>

<?= js('assets/js/snippets/procedural-art.js', ['defer' => true]) ?>
