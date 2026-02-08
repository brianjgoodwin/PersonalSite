<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <h1><?= $page->title()->html() ?></h1>
    <div class="about-bio">
      <?= $page->leftColumn()->kirbytext() ?>
    </div>
  <?php },

  'secondary' => function() use ($page) { ?>
    <div class="about-links">
      <?= $page->rightColumn()->kirbytext() ?>
    </div>

    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
