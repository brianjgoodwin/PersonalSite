<?php snippet('header') ?>

<article class="page" role="article" aria-labelledby="page-title">
    <!-- Page Header -->
    <header class="page-header">
        <h1 class="page-title" id="page-title"><?= $page->title()->esc() ?></h1>
    </header>

    <!-- Page Content -->
    <div class="page-content">
        <?php if ($page->blocks()->isNotEmpty()): ?>
            <?php foreach ($page->blocks()->toBlocks() as $block): ?>
                <div class="block block--<?= $block->type() ?>" id="<?= $block->id() ?>">
                    <?= $block ?>
                </div>
            <?php endforeach ?>
        <?php elseif ($page->text()->isNotEmpty()): ?>
            <!-- Fallback for old text field -->
            <div class="block block--text">
                <?= $page->text()->kirbytext() ?>
            </div>
        <?php endif ?>
    </div>
</article>

<?php snippet('footer') ?>
