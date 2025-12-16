<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<div class="page-content">
    <?php
    // Check if page uses blocks field or old text field
    if ($page->blocks()->isNotEmpty()):
        // Render blocks
        foreach ($page->blocks()->toBlocks() as $block):
            echo $block;
        endforeach;
    elseif ($page->text()->isNotEmpty()):
        // Fallback to old text field
        ?>
        <div class="text-content">
            <?= $page->text()->kirbytext() ?>
        </div>
        <?php
    endif;
    ?>
</div>

<?php snippet('footer') ?>
