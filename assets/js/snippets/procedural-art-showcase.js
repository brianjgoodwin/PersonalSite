/**
 * Procedural Art Showcase - Multi-Canvas Generator
 *
 * Renders multiple procedural art canvases on a single page,
 * each with its own theme color and seed.
 *
 * Configuration via data attributes on each .art-showcase-canvas:
 * - data-section: Maps to themeColors key (uses site section slugs, e.g. 'home' for red)
 * - data-seed: Numeric seed for consistent randomness
 */
(function () {
  const containers = document.querySelectorAll('.art-showcase-canvas');
  if (!containers.length) return;

  // Read the palette from CSS so index.css stays the single source of
  // truth. Resolved against :root, which is where the colours are declared.
  const rootStyle = getComputedStyle(document.documentElement);
  const cssVar = (name, fallback) =>
    rootStyle.getPropertyValue(name).trim() || fallback;

  const themeColors = {
    'home': cssVar('--color-home', '#E03A3E'),
    'about': cssVar('--color-about', '#F5821F'),
    'posts': cssVar('--color-posts', '#FDB827'),
    'projects': cssVar('--color-projects', '#009DDC'),
    'links': cssVar('--color-links', '#61BB46'),
    'purple': cssVar('--color-purple', '#8B5CF6')
  };

  const palette = [
    themeColors.home,
    themeColors.about,
    themeColors.posts,
    themeColors.projects,
    themeColors.links,
    themeColors.purple,
    '#1a1a1a',
    '#f5f3ef',
    '#8a8578'
  ];

  // Simple seedable PRNG (mulberry32)
  function mulberry32(a) {
    return function () {
      a |= 0; a = a + 0x6D2B79F5 | 0;
      let t = Math.imul(a ^ a >>> 15, 1 | a);
      t = t + Math.imul(t ^ t >>> 7, 61 | t) ^ t;
      return ((t ^ t >>> 14) >>> 0) / 4294967296;
    };
  }

  function draw(ctx, w, h, seed, themeColor) {
    const rand = mulberry32(seed);

    ctx.fillStyle = '#ffffff';
    ctx.fillRect(0, 0, w, h);

    const count = Math.floor(rand() * 26) + 15;
    const useGrid = rand() > 0.5;
    const gridCols = Math.floor(rand() * 4) + 3;
    const gridRows = Math.ceil(gridCols * (h / w));
    const cellWidth = w / gridCols;
    const cellHeight = h / gridRows;

    for (let i = 0; i < count; i++) {
      let color;
      if (rand() > 0.3) {
        color = themeColor;
      } else {
        color = palette[Math.floor(rand() * palette.length)];
      }

      const alpha = 0.35 + rand() * 0.55;
      const isCircle = rand() > 0.45;

      let x, y;
      if (useGrid) {
        const col = Math.floor(rand() * gridCols);
        const row = Math.floor(rand() * gridRows);
        x = col * cellWidth + cellWidth / 2 + (rand() - 0.5) * cellWidth * 0.6;
        y = row * cellHeight + cellHeight / 2 + (rand() - 0.5) * cellHeight * 0.6;
      } else {
        x = rand() * w;
        y = rand() * h;
      }

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
        const rotation = rand() * Math.PI * 0.5;
        ctx.translate(x, y);
        ctx.rotate(rotation);
        ctx.fillRect(-size / 2, -size / 2, size, size);
      }

      ctx.restore();
    }
  }

  const resizeFns = [];

  containers.forEach(function (container) {
    const canvas = container.querySelector('.art-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    const section = container.dataset.section;
    const seed = parseInt(container.dataset.seed, 10);
    const themeColor = themeColors[section] || '#E03A3E';

    function resize() {
      const rect = canvas.parentElement.getBoundingClientRect();
      if (rect.width === 0 || rect.height === 0) return;
      const dpr = window.devicePixelRatio || 1;
      canvas.width = rect.width * dpr;
      canvas.height = rect.height * dpr;
      ctx.scale(dpr, dpr);
      canvas.style.width = rect.width + 'px';
      canvas.style.height = rect.height + 'px';
      draw(ctx, rect.width, rect.height, seed, themeColor);
    }

    resizeFns.push(resize);
    resize();
  });

  let resizeTimeout;
  window.addEventListener('resize', function () {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(function () {
      resizeFns.forEach(function (fn) { fn(); });
    }, 150);
  });
})();
