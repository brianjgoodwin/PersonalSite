<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<div class="article-layout">
  <article class="article-content">
    <h1><?= $page->title()->html() ?></h1>
    <?= $page->body()->kirbytext() ?>

    <p class="back-link"><a href="<?= $page->parent()->url() ?>">← Back to Posts</a></p>
  </article>
</div>

<?php snippet('footer') ?>