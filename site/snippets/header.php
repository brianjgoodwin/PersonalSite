<?php
/**
 * Global Header
 *
 * Handles <head>, body class, and common elements
 * Body class automatically switches based on template
 */

// Get layout class from page method (defined in config)
$layoutClass = method_exists($page, 'layoutClass') ? $page->layoutClass() : 'layout-default';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?= $page->title()->esc() ?><?php if (!$page->isHomePage()): ?> | <?= $site->title()->esc() ?><?php endif ?></title>

  <?php if ($page->description()->isNotEmpty()): ?>
    <meta name="description" content="<?= $page->description()->esc() ?>">
  <?php endif ?>

  <?php
  // CSS with cache busting
  $cssFile = 'assets/css/main.css';
  $cssPath = kirby()->root('index') . '/' . $cssFile;
  $cssVersion = file_exists($cssPath) ? filemtime($cssPath) : time();
  ?>
  <link rel="stylesheet" href="<?= url($cssFile) ?>?v=<?= $cssVersion ?>">

  <?php
  // Keep existing site.css for now (will be phased out)
  if (file_exists(kirby()->root('index') . '/assets/css/site.css')):
  ?>
    <?= css('assets/css/site.css') ?>
  <?php endif ?>
</head>
<body class="<?= $layoutClass ?>">

  <!-- Navigation -->
  <?php snippet('navigation') ?>

  <!-- Vertical Sidebar -->
  <?php snippet('sidebar') ?>

  <!-- Main content wrapper -->
  <main class="main-content">
