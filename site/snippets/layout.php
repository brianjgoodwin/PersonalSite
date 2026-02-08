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
 *   ])
 *
 * If no secondary column is provided, primary column will span full width
 */

$showBreadcrumb = $showBreadcrumb ?? false;
$primary = $primary ?? null;
$secondary = $secondary ?? null;
?>

<div class="site-main">
  <div class="col-left-pad"></div>

  <div class="col-primary">
    <?php if ($showBreadcrumb): ?>
      <?php snippet('breadcrumb') ?>
    <?php endif ?>

    <?php if (is_callable($primary)): ?>
      <?php $primary() ?>
    <?php endif ?>
  </div>

  <div class="col-center-pad"></div>

  <?php if ($secondary): ?>
  <aside class="col-secondary">
    <?php if (is_callable($secondary)): ?>
      <?php $secondary() ?>
    <?php endif ?>
  </aside>
  <?php endif ?>

  <div class="col-right-pad"></div>
</div>
