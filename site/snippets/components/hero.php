<?php
/**
 * Hero Section Component
 *
 * Usage: snippet('components/hero', [
 *   'title' => 'Welcome',
 *   'subtitle' => 'This is the subtitle',
 *   'image' => $someImage,     // Optional background image
 *   'buttonText' => 'Get Started',  // Optional
 *   'buttonUrl' => '/about'    // Optional
 * ])
 */

$title = $title ?? '';
$subtitle = $subtitle ?? '';
$image = $image ?? null;
$buttonText = $buttonText ?? null;
$buttonUrl = $buttonUrl ?? null;
?>

<div class="hero" style="position: relative; padding: 80px 20px; text-align: center; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 12px; margin-bottom: 40px; <?= $image ? 'background-image: url(' . $image->url() . '); background-size: cover; background-position: center;' : '' ?>">
    <?php if ($image): ?>
        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.4); border-radius: 12px;"></div>
    <?php endif ?>

    <div style="position: relative; z-index: 1; max-width: 800px; margin: 0 auto;">
        <?php if ($title): ?>
            <h1 style="font-size: 3rem; margin: 0 0 20px 0; font-weight: bold;">
                <?= html($title) ?>
            </h1>
        <?php endif ?>

        <?php if ($subtitle): ?>
            <p style="font-size: 1.3rem; margin: 0 0 30px 0; opacity: 0.95;">
                <?= html($subtitle) ?>
            </p>
        <?php endif ?>

        <?php if ($buttonText && $buttonUrl): ?>
            <a href="<?= $buttonUrl ?>" style="display: inline-block; background: white; color: #333; padding: 15px 40px; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 1.1rem;">
                <?= html($buttonText) ?>
            </a>
        <?php endif ?>
    </div>
</div>
