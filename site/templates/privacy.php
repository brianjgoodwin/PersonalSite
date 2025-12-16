<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<div class="text-content">
    <?= $page->text()->kirbytext() ?>
</div>

<?php snippet('footer') ?>
