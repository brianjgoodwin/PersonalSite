<?php
/**
 * Button Component
 *
 * Usage: snippet('components/button', [
 *   'text' => 'Click Me',
 *   'url' => '/contact',
 *   'style' => 'primary'  // primary, secondary, success, danger
 * ])
 */

$text = $text ?? 'Button';
$url = $url ?? '#';
$style = $style ?? 'primary';

$styles = [
    'primary' => 'background: #1976d2; color: white;',
    'secondary' => 'background: #757575; color: white;',
    'success' => 'background: #388e3c; color: white;',
    'danger' => 'background: #d32f2f; color: white;',
    'outline' => 'background: transparent; color: #1976d2; border: 2px solid #1976d2;'
];
?>

<a href="<?= $url ?>" style="display: inline-block; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; transition: opacity 0.2s; <?= $styles[$style] ?? $styles['primary'] ?>">
    <?= html($text) ?>
</a>
