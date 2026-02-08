<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <h1><?= $page->title()->html() ?></h1>
    <div>
      <?= $page->body()->kirbytext() ?>
    </div>
  <?php },

  'secondary' => function() { ?>
    <!-- Empty secondary column - maintains two-column layout -->
  <?php }
]) ?>

<?php snippet('footer') ?>
