<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <h1><?= $page->title()->html() ?></h1>
    <?= $page->body()->kirbytext() ?>

    <?php foreach ($page->children() as $project): ?>
      <article>
        <h2><a href="<?= $project->url() ?>"><?= $project->title()->html() ?></a></h2>
      </article>
    <?php endforeach ?>
  <?php },

  'secondary' => function() { ?>
    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
