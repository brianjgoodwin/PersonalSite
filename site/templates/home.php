<?php snippet('header') ?>

<div class="home-layout">
    <div class="home-intro">
        <h1><?= $page->title()->html() ?></h1>
        <div class="home-text">
            <?= $page->body()->kirbytext() ?>
        </div>
    </div>

    <nav class="home-cards" aria-label="Site sections">
        <h2 class="visually-hidden">Explore the site</h2>
        <?php
        // Define cards with their data - use Kirby page helpers for URLs
        $cardData = [
            [
                'slug' => 'posts',
                'title' => 'Posts',
                'description' => 'Writing about technology, design, and things I find interesting.',
                'color' => 'posts'
            ],
            [
                'slug' => 'projects',
                'title' => 'Projects',
                'description' => 'Things I\'ve built, experiments, and works in progress.',
                'color' => 'projects'
            ],
            [
                'slug' => 'links',
                'title' => 'Links',
                'description' => 'A curated collection of websites, newsletters, and resources I enjoy.',
                'color' => 'links'
            ]
        ];

        foreach ($cardData as $data):
            $cardPage = page($data['slug']);
            if (!$cardPage) continue; // Skip if page doesn't exist
        ?>
        <a href="<?= $cardPage->url() ?>"
           class="card card-<?= esc($data['color']) ?>"
           aria-label="<?= esc($data['title']) ?>: <?= esc($data['description']) ?>">
            <h3 class="card-title"><?= esc($data['title']) ?></h3>
            <p class="card-description"><?= esc($data['description']) ?></p>
        </a>
        <?php endforeach ?>
    </nav>
</div>

<?php snippet('footer') ?>
