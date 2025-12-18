<?php
/**
 * Vertical Sidebar Snippet (Placeholder)
 *
 * This will be fully implemented in Phase 2
 * For now, using existing sidebar
 */

// Determine which page to check for white text
$pageToCheck = ($page->parent() && !$page->isHomePage()) ? $page->parent() : $page;

// Pages that should have white text
$whiteTextPages = ['home', 'projects', 'privacy'];
$useWhiteText = $pageToCheck->isHomePage() ||
               in_array(strtolower($pageToCheck->slug()), $whiteTextPages) ||
               in_array(strtolower($pageToCheck->title()), $whiteTextPages);
?>
<aside class="vertical-sidebar">
    <div class="vertical-sidebar-text <?php e($useWhiteText, 'white-text') ?>">
        <?php
        // Display parent page title if this is a child page
        // Otherwise display current page title
        if ($page->parent() && !$page->isHomePage()) {
            echo $page->parent()->title();
        } else {
            echo $page->title();
        }
        ?>
    </div>
</aside>
