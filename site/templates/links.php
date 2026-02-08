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
      <div class="links-columns">
        <div class="links-column">
          <?= $page->leftLinks()->kirbytext() ?>
        </div>
        <div class="links-column">
          <?= $page->rightLinks()->kirbytext() ?>
        </div>
      </div>
    </div>

    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
