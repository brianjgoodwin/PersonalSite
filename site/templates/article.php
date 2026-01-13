<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<div class="article-layout">
  <article class="article-content">
    <h1><?= $page->title()->html() ?></h1>
    <?= $page->body()->kirbytext() ?>

    <?php if ($parent = $page->parent()): ?>
    <p class="back-link"><a href="<?= $parent->url() ?>">← Back to <?= $parent->title()->html() ?></a></p>
    <?php endif ?>
  </article>
</div>

<?php snippet('footer') ?>