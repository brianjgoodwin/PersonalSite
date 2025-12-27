<?php
/**
 * Sitemap Snippet
 *
 * Generates an XML sitemap for search engines
 */

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php
// Add homepage
?>
  <url>
    <loc><?= site()->url() ?></loc>
    <lastmod><?= site()->modified('c') ?></lastmod>
    <priority>1.0</priority>
  </url>
<?php
// Add all listed pages recursively
foreach (site()->index()->listed() as $page):
?>
  <url>
    <loc><?= $page->url() ?></loc>
    <lastmod><?= $page->modified('c') ?></lastmod>
    <priority>0.8</priority>
  </url>
<?php endforeach ?>
</urlset>
