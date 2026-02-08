<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <article class="article-content">
      <h1><?= $page->title()->html() ?></h1>
      <?= $page->body()->kirbytext() ?>

      <?php if ($parent = $page->parent()): ?>
      <p class="back-link"><a href="<?= $parent->url() ?>">← Back to <?= $parent->title()->html() ?></a></p>
      <?php endif ?>
    </article>
  <?php },

  'secondary' => function() { ?>
    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>