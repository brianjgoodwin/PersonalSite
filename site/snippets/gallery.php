<?php
/**
 * Horizontal Scroll Gallery Snippet
 * Displays images in a horizontally scrollable gallery with modal viewer
 *
 * Usage: <?php snippet('gallery', ['images' => $page->images()]) ?>
 *
 * UNTESTED: this snippet has never rendered. It only triggers on images
 * whose FILE TEMPLATE is `gallery-image` (set by uploading into the Panel's
 * "Gallery Images" section), and no content has one -- project images are
 * cover images. Check the result in a browser the first time you use it.
 * See "Untested code paths" in README.md.
 */

$images = $images ?? $page->images()->template('gallery-image');

// Only render if there are images
if ($images->count() === 0) return;
?>

<div class="gallery">
  <div class="gallery-scroll" role="region" aria-label="Image gallery (<?= $images->count() ?> images)">
    <?php foreach ($images as $image): ?>
      <button
        class="gallery-item"
        type="button"
        aria-label="View larger: <?= $image->alt()->or($image->filename())->html() ?>"
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
