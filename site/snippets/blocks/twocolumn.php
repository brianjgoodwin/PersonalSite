<?php
$left = $block->columnLeft()->value();
$right = $block->columnRight()->value();
?>

<div class="block-twocolumn" style="margin: 30px 0;">
    <div class="two-column" style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
        <div class="column-left">
            <?= kirbytext($left) ?>
        </div>
        <div class="column-right">
            <?= kirbytext($right) ?>
        </div>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .block-twocolumn .two-column {
        grid-template-columns: 1fr !important;
    }
}
</style>
