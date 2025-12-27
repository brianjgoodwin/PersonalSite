<?php snippet('header') ?>

<h1><?= $page->title()->html() ?></h1>
<div>
    <?= $page->body()->kirbytext() ?>
</div>

<?php snippet('footer') ?>
