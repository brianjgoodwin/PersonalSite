<?php
/**
 * Unified Layout Snippet
 *
 * Based on prototype's relational grid system
 * Supports flexible primary/secondary column content
 *
 * Usage:
 *   snippet('layout', [
 *     'primary' => function() {
 *       // Primary column content
 *     },
 *     'secondary' => function() {
 *       // Secondary column content (optional)
 *     },
 *     'showBreadcrumb' => true,
 *     'layoutClass' => 'layout-one-column', // Optional layout modifier class
 *   ])
 *
 * If no secondary column is provided, primary column will span full width
 */

$showBreadcrumb = $showBreadcrumb ?? false;
$primary = $primary ?? null;
$secondary = $secondary ?? null;
$layoutClass = $layoutClass ?? '';
?>

<div class="site-main <?= esc($layoutClass, 'attr') ?>">
  <?php if ($showBreadcrumb): ?>
    <?php snippet('breadcrumb') ?>
  <?php endif ?>

  <div class="col-primary">
    <?php if (is_callable($primary)): ?>
      <?php $primary() ?>
    <?php endif ?>
  </div>

  <?php if ($secondary): ?>
  <aside class="col-secondary">
    <?php if (is_callable($secondary)): ?>
      <?php $secondary() ?>
    <?php endif ?>
  </aside>
  <?php endif ?>
</div>
