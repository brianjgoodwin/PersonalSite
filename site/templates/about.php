<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<div class="about-layout">
    <div class="about-left">
        <h1><?= $page->title()->html() ?></h1>
        <div class="about-bio">
            <?= $page->leftColumn()->kirbytext() ?>
        </div>
    </div>

    <div class="about-right">
        <div class="about-links">
            <?= $page->rightColumn()->kirbytext() ?>
        </div>
    </div>
</div>

<?php snippet('footer') ?>
