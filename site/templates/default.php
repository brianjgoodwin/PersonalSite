<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<h1><?= $page->title()->html() ?></h1>
<div>
    <?= $page->body()->kirbytext() ?>
</div>

<?php snippet('footer') ?>
