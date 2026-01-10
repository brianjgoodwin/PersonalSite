<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page->title()->html() ?> | <?= $site->title()->html() ?></title>
    <?= css('assets/css/index.css') ?>
    
</head>
<body>
    <!-- Skip link - first focusable element for keyboard users -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="header">
        <a class="logo" href="<?= $site->url() ?>" <?= $page->isHomePage() ? 'aria-current="page"' : '' ?>><?= $site->title()->html() ?></a>

        <nav class="menu" aria-label="Main">
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