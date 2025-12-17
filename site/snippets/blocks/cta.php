<?php
$headline = $block->headline()->value();
$text = $block->text()->value();
$buttonText = $block->buttonText()->value();
$buttonUrl = $block->buttonUrl()->value();
$style = $block->style()->or('default');

// Style mapping
$styles = [
    'default' => ['bg' => '#f5f5f5', 'color' => '#333', 'button' => '#333', 'buttonText' => '#fff'],
    'primary' => ['bg' => '#e3f2fd', 'color' => '#1976d2', 'button' => '#1976d2', 'buttonText' => '#fff'],
    'success' => ['bg' => '#e8f5e9', 'color' => '#388e3c', 'button' => '#388e3c', 'buttonText' => '#fff'],
    'warning' => ['bg' => '#fff3e0', 'color' => '#f57c00', 'button' => '#f57c00', 'buttonText' => '#fff'],
];
$s = $styles[$style];
?>

<div class="block-cta" style="margin: 40px 0; padding: 40px; background: <?= $s['bg'] ?>; border-radius: 12px; text-align: center;">
    <?php if ($headline): ?>
        <h3 style="font-size: 1.8rem; margin: 0 0 15px 0; color: <?= $s['color'] ?>;">
            <?= html($headline) ?>
        </h3>
    <?php endif ?>

    <?php if ($text): ?>
        <p style="font-size: 1.1rem; color: #555; margin: 0 0 25px 0; max-width: 600px; margin-left: auto; margin-right: auto;">
            <?= html($text) ?>
        </p>
    <?php endif ?>

    <?php if ($buttonText && $buttonUrl): ?>
        <?php
        // Validate button URL - must be valid URL with http/https protocol
        $safeUrl = '#';
        if (filter_var($buttonUrl, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//i', $buttonUrl)) {
            $safeUrl = esc($buttonUrl, 'attr');
        }
        ?>
        <a href="<?= $safeUrl ?>" style="display: inline-block; background: <?= $s['button'] ?>; color: <?= $s['buttonText'] ?>; padding: 12px 30px; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 1rem;" rel="noopener noreferrer">
            <?= html($buttonText) ?>
        </a>
    <?php endif ?>
</div>
