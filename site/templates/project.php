<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <article class="project-content">
      <h1><?= $page->title()->html() ?></h1>
      <?= $page->body()->kirbytext() ?>

      <?php
      // Display gallery if images exist
      $galleryImages = $page->images()->template('gallery-image');
      if ($galleryImages->count() > 0):
      ?>
        <?php snippet('gallery', ['images' => $galleryImages]) ?>
      <?php endif ?>

      <?php if ($parent = $page->parent()): ?>
      <p class="back-link"><a href="<?= $parent->url() ?>">← Back to <?= $parent->title()->html() ?></a></p>
      <?php endif ?>
    </article>
  <?php },

  'secondary' => function() use ($page) { ?>
    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
