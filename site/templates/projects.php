<?php snippet('header') ?>

<?php snippet('layout', [
  'showBreadcrumb' => true,

  'primary' => function() use ($page) { ?>
    <h1><?= $page->title()->html() ?></h1>
    <?= $page->body()->kirbytext() ?>

    <nav class="home-cards" aria-label="Projects">
      <?php foreach ($page->children() as $project): ?>
        <?php
        // Get cover image if it exists
        $coverImage = $project->coverImage()->toFile();
        ?>
        <a href="<?= $project->url() ?>"
           class="card card-project"
           aria-label="<?= $project->title()->html() ?><?= $project->description()->isNotEmpty() ? ': ' . $project->description()->html() : '' ?>">
          <div class="card-image-box" aria-hidden="true">
            <?php if ($coverImage): ?>
              <img
                src="<?= $coverImage->crop(240, 240)->url() ?>"
                alt=""
                loading="lazy"
              >
            <?php endif ?>
          </div>
          <div class="card-content">
            <h2 class="card-title"><?= $project->title()->html() ?></h2>
            <?php if ($project->description()->isNotEmpty()): ?>
              <p class="card-description"><?= $project->description()->html() ?></p>
            <?php endif ?>
          </div>
        </a>
      <?php endforeach ?>
    </nav>
  <?php },

  'secondary' => function() { ?>
    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
