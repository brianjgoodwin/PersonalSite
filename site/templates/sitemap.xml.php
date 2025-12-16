<?php

// Set content type header
header('Content-Type: text/xml; charset=utf-8');

// Recursive function to get all listed pages
function getPages($pages, &$result = []) {
    foreach ($pages as $page) {
        // Only include listed pages (exclude drafts, unlisted pages, and certain pages)
        if ($page->isListed() && !in_array($page->intendedTemplate()->name(), ['feed', 'sitemap.xml', 'error'])) {
            $result[] = $page;
        }

        // Recursively get children
        if ($page->hasChildren()) {
            getPages($page->children()->listed(), $result);
        }
    }
    return $result;
}

// Get all pages
$allPages = getPages($site->children());

// Add home page
array_unshift($allPages, $site->homePage());

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <?php foreach ($allPages as $p): ?>
  <url>
    <loc><?= html($p->url()) ?></loc>
    <lastmod><?= $p->modified('c') ?></lastmod>
    <priority><?= $p->isHomePage() ? '1.0' : '0.8' ?></priority>
    <changefreq><?= $p->intendedTemplate()->name() === 'posts' || $p->intendedTemplate()->name() === 'post' ? 'weekly' : 'monthly' ?></changefreq>
  </url>
  <?php endforeach ?>
</urlset>
