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

  // Shared color palette (for accents)
  const palette = [
    '#E03A3E',  // red
    '#F5821F',  // orange
    '#FDB827',  // yellow
    '#009DDC',  // blue
    '#61BB46',  // green
    '#1a1a1a',  // black
    '#f5f3ef',  // off-white
    '#8a8578'   // gray
  ];

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

    // Clear canvas
    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, w, h);

    // ============================================
    // GENERATIVE ART - Grid-influenced composition
    // ============================================

    // How many shapes (15–40)
    const count = Math.floor(rand() * 26) + 15;

    // Optional grid influence (sometimes shapes cluster to a grid, sometimes fully free)
    const useGrid = rand() > 0.5;
    const gridCols = Math.floor(rand() * 4) + 3; // 3-6 columns
    const gridRows = Math.ceil(gridCols * (h / w)); // Scale rows based on aspect ratio
    const cellWidth = w / gridCols;
    const cellHeight = h / gridRows;

    for (let i = 0; i < count; i++) {
      // Color selection - theme color dominant (70%), palette accents (30%)
      let color;
      if (rand() > 0.3) {
        color = themeColor; // Red dominant
      } else {
        color = palette[Math.floor(rand() * palette.length)];
      }

      const alpha = 0.35 + rand() * 0.55; // 0.35 - 0.9
      const isCircle = rand() > 0.45;

      // Position: grid-influenced or free
      let x, y;
      if (useGrid) {
        // Snap loosely to grid with jitter
        const col = Math.floor(rand() * gridCols);
        const row = Math.floor(rand() * gridRows);
        x = col * cellWidth + cellWidth / 2 + (rand() - 0.5) * cellWidth * 0.6;
        y = row * cellHeight + cellHeight / 2 + (rand() - 0.5) * cellHeight * 0.6;
      } else {
        x = rand() * w;
        y = rand() * h;
      }

      // Size: 3% - 28% of canvas
      const maxDim = w * 0.28;
      const minDim = w * 0.03;
      const size = minDim + rand() * (maxDim - minDim);

      ctx.save();
      ctx.globalAlpha = alpha;
      ctx.fillStyle = color;

      if (isCircle) {
        ctx.beginPath();
        ctx.arc(x, y, size / 2, 0, Math.PI * 2);
        ctx.fill();
      } else {
        // Square with optional rotation
        const rotation = rand() * Math.PI * 0.5;
        ctx.translate(x, y);
        ctx.rotate(rotation);
        ctx.fillRect(-size / 2, -size / 2, size, size);
      }

      ctx.restore();
    }

    // ============================================
    // END GENERATIVE ART CODE
    // ============================================
  }

  window.addEventListener('resize', resize);
  requestAnimationFrame(resize);
})();
</script>
