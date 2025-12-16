<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<div class="text-content">
    <?= $page->text()->kirbytext() ?>
</div>

<!-- This is the About page template -->
<!-- You can customize this to show different content -->

<?php snippet('footer') ?>
