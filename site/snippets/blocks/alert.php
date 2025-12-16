<?php
$text = $block->text()->value();
$type = $block->type()->or('info');

// Alert type mapping
$types = [
    'info' => ['bg' => '#e3f2fd', 'border' => '#1976d2', 'icon' => 'ℹ️'],
    'success' => ['bg' => '#e8f5e9', 'border' => '#388e3c', 'icon' => '✓'],
    'warning' => ['bg' => '#fff3e0', 'border' => '#f57c00', 'icon' => '⚠️'],
    'error' => ['bg' => '#ffebee', 'border' => '#d32f2f', 'icon' => '✕'],
];
$t = $types[$type];
?>

<?php if ($text): ?>
<div class="block-alert" style="margin: 30px 0; padding: 20px 20px 20px 50px; background: <?= $t['bg'] ?>; border-left: 4px solid <?= $t['border'] ?>; border-radius: 4px; position: relative;">
    <span style="position: absolute; left: 18px; top: 50%; transform: translateY(-50%); font-size: 1.2rem;">
        <?= $t['icon'] ?>
    </span>
    <div style="color: #333;">
        <?= kirbytext($text) ?>
    </div>
</div>
<?php endif ?>
