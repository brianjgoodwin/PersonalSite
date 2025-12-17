<?php
$images = $block->images()->toFiles();
$columns = $block->columns()->or('3');
?>

<?php if ($images->count() > 0): ?>
<div class="block-gallery" style="margin: 30px 0;">
    <div style="display: grid; grid-template-columns: repeat(<?= $columns ?>, 1fr); gap: 15px;">
        <?php foreach ($images as $index => $image): ?>
            <figure style="margin: 0;">
                <img
                    src="<?= $image->crop(600, 400)->url() ?>"
                    alt="<?= $image->alt()->or($image->filename()) ?>"
                    width="600"
                    height="400"
                    <?php if ($index >= 4): ?>loading="lazy"<?php endif ?>
                    style="width: 100%; height: auto; border-radius: 4px; display: block;"
                >
            </figure>
        <?php endforeach ?>
    </div>

    <?php if ($caption = $block->caption()->isNotEmpty()): ?>
        <figcaption style="text-align: center; color: #666; font-size: 0.9rem; margin-top: 10px; font-style: italic;">
            <?= esc($block->caption()->value(), 'html') ?>
        </figcaption>
    <?php endif ?>
</div>
<?php endif ?>
