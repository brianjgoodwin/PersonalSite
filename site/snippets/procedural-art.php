<?php
/**
 * Procedural Art Sidebar
 *
 * Generates canvas-based geometric art using the current page's theme color
 * Uses page URL as seed for consistency across visits
 *
 * Features:
 * - 3 simple geometric shapes per page
 * - Color from current section's theme (Apple Rainbow colors)
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

<div class="art-container" data-section="<?= $sectionSlug ?>" data-seed="<?= $seed ?>">
  <canvas id="art-canvas"></canvas>
</div>

<script>
(function () {
  const container = document.querySelector('.art-container');
  const canvas = document.getElementById('art-canvas');
  if (!canvas || !container) return;

  const ctx = canvas.getContext('2d');
  const section = container.dataset.section;
  const seed = parseInt(container.dataset.seed, 10);

  // Theme colors (Apple Rainbow palette)
  const themeColors = {
    'home': '#E03A3E',
    'about': '#F5821F',
    'posts': '#FDB827',
    'projects': '#009DDC',
    'links': '#61BB46'
  };

  const themeColor = themeColors[section] || '#E03A3E';

  function resize() {
    const rect = canvas.parentElement.getBoundingClientRect();
    const dpr = window.devicePixelRatio || 1;
    canvas.width = rect.width * dpr;
    canvas.height = rect.height * dpr;
    ctx.scale(dpr, dpr);
    canvas.style.width = rect.width + 'px';
    canvas.style.height = rect.height + 'px';
    draw(rect.width, rect.height);
  }

  // Simple seedable PRNG (mulberry32)
  function mulberry32(a) {
    return function () {
      a |= 0; a = a + 0x6D2B79F5 | 0;
      let t = Math.imul(a ^ a >>> 15, 1 | a);
      t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t;
      return ((t ^ t >>> 14) >>> 0) / 4294967296;
    };
  }

  function draw(w, h) {
    const rand = mulberry32(seed);

    // Background
    ctx.fillStyle = '#eae6dd';
    ctx.fillRect(0, 0, w, h);

    // Draw 3 simple geometric shapes using theme color
    for (let i = 0; i < 3; i++) {
      const shapeType = Math.floor(rand() * 3);
      const x = rand() * w;
      const y = (rand() * 0.8 + 0.1 * i) * h; // Distribute vertically
      const size = (rand() * 0.3 + 0.2) * Math.min(w, h);

      ctx.fillStyle = themeColor;
      ctx.globalAlpha = rand() * 0.3 + 0.5; // Vary opacity (0.5-0.8)

      if (shapeType === 0) {
        // Circle
        ctx.beginPath();
        ctx.arc(x, y, size / 2, 0, Math.PI * 2);
        ctx.fill();
      } else if (shapeType === 1) {
        // Rectangle
        ctx.fillRect(x - size / 2, y - size / 2, size, size);
      } else {
        // Triangle
        ctx.beginPath();
        ctx.moveTo(x, y - size / 2);
        ctx.lineTo(x + size / 2, y + size / 2);
        ctx.lineTo(x - size / 2, y + size / 2);
        ctx.closePath();
        ctx.fill();
      }
    }

    ctx.globalAlpha = 1;
  }

  window.addEventListener('resize', resize);
  requestAnimationFrame(resize);
})();
</script>
