        </main>

        <!-- Vertical sidebar with page name -->
        <aside class="vertical-sidebar">
            <div class="vertical-sidebar-text">
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
    </div>

    <footer class="site-footer">
        <div class="footer-content">
            <p>&copy; <?= date('Y') ?> <?= $site->title() ?></p>
            <a href="<?= url('privacy') ?>">Privacy</a>
        </div>
    </footer>
</body>
</html>
