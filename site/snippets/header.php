<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title()->html() ?> | <?= $site->title()->html() ?></title>
    <?php
    // Cache busting: use file modification time as version
    $cssPath = kirby()->root('assets') . '/css/index.css';
    $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : '1';
    $cssUrl = url('assets/css/index.css') . '?v=' . $cssVersion;

    $menuJsPath = kirby()->root('assets') . '/js/menu.js';
    $menuJsVersion = file_exists($menuJsPath) ? filemtime($menuJsPath) : '1';
    $menuJsUrl = url('assets/js/menu.js') . '?v=' . $menuJsVersion;

    $breadcrumbJsPath = kirby()->root('assets') . '/js/breadcrumb-nav.js';
    $breadcrumbJsVersion = file_exists($breadcrumbJsPath) ? filemtime($breadcrumbJsPath) : '1';
    $breadcrumbJsUrl = url('assets/js/breadcrumb-nav.js') . '?v=' . $breadcrumbJsVersion;

    // Gallery JavaScript (only load on project pages)
    $galleryJsPath = kirby()->root('assets') . '/js/gallery.js';
    $galleryJsVersion = file_exists($galleryJsPath) ? filemtime($galleryJsPath) : '1';
    $galleryJsUrl = url('assets/js/gallery.js') . '?v=' . $galleryJsVersion;
    $isProjectPage = $page->intendedTemplate()->name() === 'project';
    ?>

    <!-- Preload critical CSS for faster rendering -->
    <link rel="preload" href="<?= $cssUrl ?>" as="style">

    <!-- Versioned assets with automatic cache busting -->
    <link href="<?= $cssUrl ?>" rel="stylesheet">
    <script defer src="<?= $menuJsUrl ?>"></script>
    <script defer src="<?= $breadcrumbJsUrl ?>"></script>
    <?php if ($isProjectPage): ?>
    <script defer src="<?= $galleryJsUrl ?>"></script>
    <?php endif ?>

    <!-- 100% privacy-first analytics -->
    <script async src="https://scripts.simpleanalyticscdn.com/latest.js"></script>

</head>
<?php
// Determine the current section (top-level page) for theming
if ($page->isHomePage()) {
    $section = $page;
} else {
    // Find the topmost parent that is a direct child of home
    $section = $page;
    while ($section->parent() && !$section->parent()->isHomePage()) {
        $section = $section->parent();
    }
}
$sectionSlug = $section->slug();
?>
<body data-section="<?= $sectionSlug ?>">
    <!-- Skip link - first focusable element for keyboard users -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Live region for screen reader announcements -->
    <div aria-live="polite" aria-atomic="true" class="sr-only" id="menu-status"></div>

    <header class="header">
        <a class="logo" href="<?= $site->url() ?>" <?= $page->isHomePage() ? 'aria-current="page"' : '' ?>><?= $site->title()->html() ?></a>

        <!-- Mobile menu toggle button -->
        <button class="menu-toggle" aria-expanded="false" aria-controls="main-menu" aria-label="Toggle navigation menu">
            <span class="menu-toggle-icon" aria-hidden="true"></span>
        </button>

        <nav class="menu" id="main-menu" aria-label="Main">
            <ul>
                <?php foreach ($site->children()->listed() as $item): ?>
                <li>
                    <a
                        href="<?= $item->url() ?>"
                        <?= $item->isOpen() ? 'aria-current="page"' : '' ?>
                    >
                        <?= $item->title()->html() ?>
                    </a>
                </li>
                <?php endforeach ?>
            </ul>
        </nav>

    </header>
    <main id="main-content">