<?php
/**
 * Horizontal Scroll Gallery Snippet
 * Displays images in a horizontally scrollable gallery with modal viewer
 *
 * Usage: <?php snippet('gallery', ['images' => $page->images()]) ?>
 */

$images = $images ?? $page->images()->template('gallery-image');

// Only render if there are images
if ($images->count() === 0) return;
?>

<div class="gallery">
  <div class="gallery-scroll" role="region" aria-label="Image gallery">
    <?php foreach ($images as $image): ?>
      <button
        class="gallery-item"
        type="button"
        aria-label="View larger: <?= $image->alt()->or($image->filename())->html() ?>"
        tabindex="0"
      >
        <img
          src="<?= $image->url() ?>"
          alt="<?= $image->alt()->html() ?>"
          <?php if ($image->caption()->isNotEmpty()): ?>
            data-caption="<?= $image->caption()->html() ?>"
          <?php endif ?>
          loading="lazy"
        >
      </button>
    <?php endforeach ?>
  </div>
</div>
