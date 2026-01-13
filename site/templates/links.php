<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<div class="links-layout">
    <div class="links-intro">
        <?= $page->introText()->kirbytext() ?>
    </div>

    <div class="links-columns">
        <div class="links-column">
            <?= $page->leftLinks()->kirbytext() ?>
        </div>
        <div class="links-column">
            <?= $page->rightLinks()->kirbytext() ?>
        </div>
    </div>
</div>

<?php snippet('footer') ?>
