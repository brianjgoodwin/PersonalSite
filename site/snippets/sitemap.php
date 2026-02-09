<?php
/**
 * Sitemap Snippet
 *
 * Generates an XML sitemap for search engines
 * - Filters pages based on sitemap.ignore config
 * - Uses depth-based priority calculation
 */

// Get pages to ignore from config
$ignore = option('sitemap.ignore', ['error']);

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
// Add homepage with top priority
?>
  <url>
    <loc><?= site()->url() ?></loc>
    <lastmod><?= site()->modified('c') ?></lastmod>
    <priority>1.0</priority>
  </url>
<?php
// Add all listed pages recursively
foreach (site()->index()->listed() as $page):
    // Skip pages in ignore list
    if (in_array($page->uri(), $ignore)) continue;

    // Calculate priority based on page depth
    // Homepage = 1.0, level 1 = 0.5, level 2 = 0.25, etc.
    $priority = number_format(0.5 / $page->depth(), 1);
?>
  <url>
    <loc><?= html($page->url()) ?></loc>
    <lastmod><?= $page->modified('c') ?></lastmod>
    <priority><?= $priority ?></priority>
  </url>
<?php endforeach ?>
</urlset>
