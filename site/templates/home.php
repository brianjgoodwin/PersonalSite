<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<div class="text-content">
    <?= $page->text()->kirbytext() ?>
</div>

<section style="margin-top: 40px;">
    <h3 style="margin-bottom: 15px;">Quick Links:</h3>
    <ul style="list-style: none;">
        <?php foreach ($site->children()->listed() as $item): ?>
        <li style="margin-bottom: 10px;">
            <a href="<?= $item->url() ?>" style="color: #0066cc; text-decoration: none;">
                → <?= $item->title() ?>
            </a>
        </li>
        <?php endforeach ?>
    </ul>
</section>

<?php snippet('footer') ?>
