<?php
$url = $block->url()->value();
$caption = $block->caption()->value();
$embedUrl = null;
$error = null;

// Parse the URL first
$parsedUrl = parse_url($url);

// Reject if not a valid URL or missing required parts
if (!$parsedUrl || !isset($parsedUrl['scheme']) || !isset($parsedUrl['host'])) {
    $error = 'Invalid video URL. Please provide a valid YouTube or Vimeo URL.';
}
// Reject if not http/https protocol
elseif (!preg_match('/^https?$/i', $parsedUrl['scheme'])) {
    $error = 'Invalid protocol. Only http and https URLs are supported.';
}
// Check if domain is YouTube or Vimeo
elseif (!preg_match('/^(www\.)?(youtube\.com|youtu\.be|vimeo\.com)$/i', $parsedUrl['host'])) {
    $error = 'Only YouTube and Vimeo videos are supported. Domain: ' . esc($parsedUrl['host'], 'html');
}
else {
    // Convert YouTube/Vimeo URLs to embed URLs
    if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . esc($matches[1], 'attr');
    } elseif (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
        $embedUrl = 'https://www.youtube.com/embed/' . esc($matches[1], 'attr');
    } elseif (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
        $embedUrl = 'https://player.vimeo.com/video/' . esc($matches[1], 'attr');
    } else {
        $error = 'Could not parse YouTube or Vimeo video URL. Please check the URL format.';
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
