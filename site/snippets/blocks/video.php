<?php
$url = $block->url()->value();
$caption = $block->caption()->value();

// Convert YouTube/Vimeo URLs to embed URLs
if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
} elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
    $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
} elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
    $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
} else {
    $embedUrl = $url;
}
?>

<?php if ($url): ?>
<div class="block-video" style="margin: 30px 0;">
    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
        <iframe
            src="<?= $embedUrl ?>"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
            allowfullscreen
        ></iframe>
    </div>

    <?php if ($caption): ?>
        <p style="text-align: center; color: #666; font-size: 0.9rem; margin-top: 10px; font-style: italic;">
            <?= html($caption) ?>
        </p>
    <?php endif ?>
</div>
<?php endif ?>
