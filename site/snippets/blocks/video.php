<?php
$url = $block->url()->value();
$caption = $block->caption()->value();
$embedUrl = null;
$error = null;

// Validate URL first - must be valid URL with http/https protocol
if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/^https?:\/\//i', $url)) {
    $error = 'Invalid video URL. Please provide a valid YouTube or Vimeo URL.';
} else {
    // Convert YouTube/Vimeo URLs to embed URLs
    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . esc($matches[1], 'attr');
    } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . esc($matches[1], 'attr');
    } elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
        $embedUrl = 'https://player.vimeo.com/video/' . esc($matches[1], 'attr');
    } else {
        // For non-YouTube/Vimeo URLs, only allow specific trusted domains
        $allowedDomains = ['youtube.com', 'www.youtube.com', 'youtube-nocookie.com', 'player.vimeo.com'];
        $parsedUrl = parse_url($url);

        if (!isset($parsedUrl['host']) || !in_array($parsedUrl['host'], $allowedDomains)) {
            $error = 'Only YouTube and Vimeo videos are supported.';
        } else {
            $embedUrl = esc($url, 'attr');
        }
    }
}
?>

<?php if ($embedUrl): ?>
<div class="block-video" style="margin: 30px 0;">
    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
        <iframe
            src="<?= $embedUrl ?>"
            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
            allowfullscreen
            sandbox="allow-scripts allow-same-origin allow-presentation"
        ></iframe>
    </div>

    <?php if ($caption): ?>
        <p style="text-align: center; color: #666; font-size: 0.9rem; margin-top: 10px; font-style: italic;">
            <?= html($caption) ?>
        </p>
    <?php endif ?>
</div>
<?php elseif ($error): ?>
<div style="padding: 20px; background: #ffebee; border-left: 4px solid #d32f2f; border-radius: 4px; margin: 30px 0;">
    <strong style="color: #d32f2f;">Video Error:</strong> <?= esc($error, 'html') ?>
</div>
<?php endif ?>
