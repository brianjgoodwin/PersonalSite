<?php
/**
 * Card Component
 *
 * Usage: snippet('components/card', [
 *   'title' => 'Card Title',
 *   'description' => 'Card description text',
 *   'image' => $someImage,  // Optional Kirby image object
 *   'link' => '/some-url',   // Optional
 *   'date' => '2024-01-01'   // Optional
 * ])
 */

$title = $title ?? '';
$description = $description ?? '';
$image = $image ?? null;
$link = $link ?? null;
$date = $date ?? null;
$featured = $featured ?? false;
?>

<div class="card" style="border: 1px solid #ddd; border-radius: 8px; overflow: hidden; transition: box-shadow 0.2s ease; <?= $featured ? 'border-color: #ffd700; background: #fffbf0;' : '' ?>">
    <?php if ($image): ?>
        <div class="card-image">
            <?php if ($link): ?>
                <a href="<?= $link ?>">
                    <img src="<?= $image->crop(600, 400)->url() ?>" alt="<?= html($title) ?>" width="600" height="400" style="width: 100%; height: auto; display: block;">
                </a>
            <?php else: ?>
                <img src="<?= $image->crop(600, 400)->url() ?>" alt="<?= html($title) ?>" width="600" height="400" style="width: 100%; height: auto; display: block;">
            <?php endif ?>
        </div>
    <?php endif ?>

    <div class="card-content" style="padding: 20px;">
        <?php if ($date): ?>
            <span class="card-date" style="display: block; color: #666; font-size: 0.9rem; margin-bottom: 10px;">
                <?= $date ?>
            </span>
        <?php endif ?>

        <h3 class="card-title" style="font-size: 1.4rem; margin-bottom: 10px;">
            <?php if ($link): ?>
                <a href="<?= $link ?>" style="color: #333; text-decoration: none;">
                    <?= html($title) ?>
                </a>
            <?php else: ?>
                <?= html($title) ?>
            <?php endif ?>
        </h3>

        <?php if ($description): ?>
            <p class="card-description" style="color: #555; line-height: 1.6; margin-bottom: 15px;">
                <?= html($description) ?>
            </p>
        <?php endif ?>

        <?php if ($link): ?>
            <a href="<?= $link ?>" class="card-link" style="color: #0066cc; text-decoration: none; font-size: 0.9rem;">
                Read more →
            </a>
        <?php endif ?>
    </div>
</div>
