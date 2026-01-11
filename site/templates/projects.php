<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<div class="projects-layout">
    <div class="projects-content">
        <h1><?= $page->title()->html() ?></h1>
        <?= $page->body()->kirbytext() ?>

        <?php foreach ($page->children() as $project): ?>
            <article>
            <h2><a href="<?= $project->url() ?>"><?= $project->title()->html() ?></a></h2>
            </article>
        <?php endforeach ?>
    </div>
</div>

<?php snippet('footer') ?>
