<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <div class="links-intro">
      <?= $page->introText()->kirbytext() ?>
    </div>
  <?php },

  'secondary' => function() use ($page) { ?>
    <div class="sidebar-box">
      <?= $page->secondaryBox()->kirbytext() ?>
    </div>

    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
