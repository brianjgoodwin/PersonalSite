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
                'description' => 'Only the best posts! At least they\'re free.',
                'color' => 'posts'
            ],
            [
                'slug' => 'projects',
                'title' => 'Projects',
                'description' => 'Art. Apps. Experiments.',
                'color' => 'projects'
            ],
            [
                'slug' => 'links',
                'title' => 'Links',
                'description' => 'Blogrolls are back! You heard it here.',
                'color' => 'links'
            ]
        ];

        foreach ($cardData as $data):
            $cardPage = page($data['slug']);
            if (!$cardPage) continue; // Skip if page doesn't exist
        ?>
        <a href="<?= $cardPage->url() ?>"
           class="card"
           data-theme="<?= html($data['slug']) ?>"
           aria-label="<?= html($data['title']) ?>: <?= html($data['description']) ?>">
            <h3 class="card-title"><?= html($data['title']) ?></h3>
            <p class="card-description"><?= html($data['description']) ?></p>
        </a>
        <?php endforeach ?>
    </nav>
</div>

<?php snippet('footer') ?>
