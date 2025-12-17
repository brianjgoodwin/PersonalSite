<?php
/**
 * Column Grid Block
 * Flexible 1-4 column grid system with responsive behavior
 */

// Get grid settings
$numColumns = (int)$block->columns()->value();
$columnRatio = $block->columnRatio()->value();
$customRatio = $block->customRatio()->value();
$gap = $block->gap()->value();
$verticalSpacing = $block->verticalSpacing()->value();

// Determine column ratio (grid-template-columns value)
$gridColumns = '';
if ($columnRatio === 'custom' && !empty($customRatio)) {
    // Parse custom ratio like "2-1-1-3" into "2fr 1fr 1fr 3fr"
    $ratios = explode('-', $customRatio);
    $gridColumns = implode(' ', array_map(function($r) { return trim($r) . 'fr'; }, $ratios));
} elseif ($columnRatio === 'equal') {
    // Equal columns: "1fr 1fr 1fr..."
    $gridColumns = implode(' ', array_fill(0, $numColumns, '1fr'));
} else {
    // Preset ratios like "2-1" or "2-1-1"
    $ratios = explode('-', $columnRatio);
    $gridColumns = implode(' ', array_map(function($r) { return trim($r) . 'fr'; }, $ratios));
}

// Map gap values to CSS
$gapMap = [
    'small' => '1rem',
    'medium' => '2rem',
    'large' => '3rem'
];
$gapValue = $gapMap[$gap] ?? '2rem';

// Map vertical spacing to CSS
$spacingMap = [
    'none' => '0',
    'small' => '1rem',
    'medium' => '2rem',
    'large' => '3rem'
];
$topBottomMargin = $spacingMap[$verticalSpacing] ?? '2rem';

// Helper function to get column data
function getColumnData($block, $colNum) {
    $prefix = "col{$colNum}_";

    return [
        'content' => $block->{$prefix . 'content'}(),
        'padding' => $block->{$prefix . 'padding'}()->value(),
        'background' => $block->{$prefix . 'background'}()->value(),
        'customBg' => $block->{$prefix . 'customBg'}()->value(),
        'shadow' => $block->{$prefix . 'shadow'}()->value(),
        'valign' => $block->{$prefix . 'valign'}()->value(),
        'mobileShow' => $block->{$prefix . 'mobileShow'}()->toBool(),
        'mobileOrder' => (int)$block->{$prefix . 'mobileOrder'}()->value()
    ];
}

// Collect column data
$columns = [];
for ($i = 1; $i <= $numColumns; $i++) {
    $columns[] = getColumnData($block, $i);
}

// Map padding values
$paddingMap = [
    'none' => '0',
    'small' => '1rem',
    'medium' => '2rem',
    'large' => '3rem'
];

// Map shadow values (Brutalist 225° angle: -Xpx Ypx)
$shadowMap = [
    'none' => 'none',
    'light' => '-2px 2px 0px rgba(0, 0, 0, 0.1)',
    'medium' => '-3px 3px 0px rgba(0, 0, 0, 0.2)',
    'strong' => '-4px 4px 0px rgba(0, 0, 0, 0.3)'
];

// Vertical alignment map
$valignMap = [
    'top' => 'flex-start',
    'center' => 'center',
    'bottom' => 'flex-end'
];
?>

<div class="block-columngrid" style="margin: <?= $topBottomMargin ?> 0;">
    <div class="columngrid-container" style="
        display: grid;
        grid-template-columns: <?= $gridColumns ?>;
        gap: <?= $gapValue ?>;
    ">
        <?php foreach ($columns as $index => $col): ?>
            <?php
            // Determine background color
            $bgColor = '';
            if ($col['background'] === 'pagecolor') {
                $bgColor = 'background-color: var(--page-color);';
            } elseif ($col['background'] === 'white') {
                $bgColor = 'background-color: var(--bg-white);';
            } elseif ($col['background'] === 'custom' && !empty($col['customBg'])) {
                $bgColor = 'background-color: rgb(' . $col['customBg'] . ');';
            }

            // Build column styles
            $columnStyles = [
                'display: flex',
                'flex-direction: column',
                'justify-content: ' . ($valignMap[$col['valign']] ?? 'flex-start'),
                'padding: ' . ($paddingMap[$col['padding']] ?? '0'),
                $bgColor,
                'box-shadow: ' . ($shadowMap[$col['shadow']] ?? 'none')
            ];

            $columnClasses = ['columngrid-column'];
            if (!$col['mobileShow']) {
                $columnClasses[] = 'mobile-hide';
            }
            ?>

            <div
                class="<?= implode(' ', $columnClasses) ?>"
                style="<?= implode('; ', array_filter($columnStyles)) ?>; order: <?= $index + 1 ?>;"
                data-mobile-order="<?= $col['mobileOrder'] ?>"
            >
                <?php if ($col['content']->isNotEmpty()): ?>
                    <?= $col['content']->toBlocks() ?>
                <?php endif ?>
            </div>
        <?php endforeach ?>
    </div>
</div>

<style>
/* Mobile responsive behavior */
@media (max-width: 768px) {
    .block-columngrid .columngrid-container {
        grid-template-columns: 1fr !important;
        gap: 0 !important; /* Blocks touch on mobile */
    }

    /* Hide columns marked as mobile-hide */
    .block-columngrid .mobile-hide {
        display: none !important;
    }

    /* Reorder columns based on data-mobile-order */
    <?php foreach ($columns as $index => $col): ?>
        .block-columngrid .columngrid-column:nth-child(<?= $index + 1 ?>) {
            order: <?= $col['mobileOrder'] ?>;
        }
    <?php endforeach ?>
}
</style>
