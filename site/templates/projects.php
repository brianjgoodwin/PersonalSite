<?php snippet('header') ?>

    <h1><?= $page->title()->html() ?></h1>
    <div>
        <?= $page->body()->kirbytext() ?>

        <?php foreach ($page->children() as $project): ?>
            <article>
            <h2><a href="<?= $project->url() ?>"><?= $project->title()->html() ?></a></h2>
            </article>
        <?php endforeach ?>
        
    </div>

    <?php snippet('footer') ?>
