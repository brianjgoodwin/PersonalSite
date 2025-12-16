<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<?php if ($intro = $page->intro()->isNotEmpty()): ?>
<div class="text-content" style="font-style: italic; color: #666; margin-bottom: 30px;">
    <?= $page->intro()->kirbytext() ?>
</div>
<?php endif ?>

<?php if ($updated = $page->updated()->isNotEmpty()): ?>
<div style="font-size: 0.9rem; color: #666; margin-bottom: 30px;">
    Last updated: <strong><?= $page->updated()->toDate('F j, Y') ?></strong>
</div>
<?php endif ?>

<div class="text-content">
    <?= $page->text()->kirbytext() ?>
</div>

<div style="margin-top: 50px; padding-top: 30px; border-top: 1px solid #ddd; font-size: 0.9rem; color: #666;">
    <p>
        This is a <a href="https://nownownow.com/about" style="color: #0066cc;">now page</a>,
        inspired by <a href="https://sive.rs/nowff" style="color: #0066cc;">Derek Sivers</a>.
        It's a snapshot of what I'm currently focused on.
    </p>
</div>

<?php snippet('footer') ?>
