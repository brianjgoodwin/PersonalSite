<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title><?= $page->title() ?> | <?= $site->title() ?></title>

    <?= css('assets/css/site.css') ?>

    <style>
        <?php
        // Get page color - check current page first, then parent
        $pageColor = $page->pagecolor()->value();
        if (empty($pageColor) && $page->parent()) {
            $pageColor = $page->parent()->pagecolor()->value();
        }

        // Set the page color custom property
        if (!empty($pageColor)) {
            echo ":root {\n";
            echo "    --page-color: rgb($pageColor);\n";
            echo "}\n";
        }
        ?>
    </style>
</head>
<body>
    <header class="site-header">
        <div class="header-inner">
            <!-- Logo with active state on home page -->
            <div class="site-logo <?php e($page->isHomePage(), 'active') ?>">
                <a href="<?= $site->url() ?>"><?= $site->title() ?></a>
            </div>

            <div class="nav-wrapper">
                <!-- Hamburger menu toggle for mobile (CSS-only) -->
                <input type="checkbox" id="menu-toggle" class="menu-toggle-checkbox">
                <label for="menu-toggle" class="menu-toggle" aria-label="Toggle menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>

                <!-- Main navigation -->
                <nav class="main-nav">
                    <ul>
                        <?php foreach ($site->children()->listed() as $item): ?>
                        <li>
                            <a href="<?= $item->url() ?>" <?php e($item->isOpen(), 'class="active"') ?>>
                                <?= $item->title() ?>
                            </a>
                        </li>
                        <?php endforeach ?>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main page wrapper with grid (includes content + sidebar) -->
    <div class="page-wrapper">
        <!-- Main content area -->
        <main class="main-content">
