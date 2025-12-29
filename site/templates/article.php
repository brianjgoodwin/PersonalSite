<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<section class="content article">
  <article>
    <h1><?= $page->title()->html() ?></h1>
    <?= $page->body()->kirbytext() ?>

    <a href="<?= $page->parent()->url() ?>">Back to Posts</a>

  </article>
</section>

<?php snippet('footer') ?>