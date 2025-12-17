<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<?php if ($page->blocks()->isNotEmpty()): ?>
    <?= $page->blocks()->toBlocks() ?>
<?php endif ?>

<?php snippet('footer') ?>
