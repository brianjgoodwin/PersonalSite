<?php
$text = $block->text()->value();
$citation = $block->citation()->value();
?>

<?php if ($text): ?>
<blockquote class="block-quote" style="margin: 30px 0; padding: 20px 30px; border-left: 4px solid #333; background: #f9f9f9; font-style: italic; font-size: 1.1rem; color: #555;">
    <p style="margin: 0;">
        "<?= html($text) ?>"
    </p>

    <?php if ($citation): ?>
        <footer style="margin-top: 10px; font-size: 0.9rem; font-style: normal; color: #666;">
            — <?= html($citation) ?>
        </footer>
    <?php endif ?>
</blockquote>
<?php endif ?>
