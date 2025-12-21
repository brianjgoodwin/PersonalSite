<?php
/**
 * Navigation Snippet (Placeholder)
 *
 * This will be fully implemented in Phase 2
 * For now, using existing header navigation
 */
?>
<a href="#main-content" class="skip-link">Skip to main content</a>
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
            <nav class="main-nav" aria-label="Main navigation">
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
