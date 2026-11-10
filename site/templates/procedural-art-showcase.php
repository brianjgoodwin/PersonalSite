<?php snippet('header') ?>

<div class="art-showcase page-grid-wide">
  <?php snippet('breadcrumb') ?>

  <h1 class="art-showcase-title"><?= $page->title()->html() ?></h1>

  <?php
  // 'section' values map to themeColors keys in procedural-art-showcase.js
  // (site section slugs for the 5 existing colors, plus 'purple' for the 6th)
  $sections = [
    ['key' => 'red',    'section' => 'home',     'label' => 'Red'],
    ['key' => 'orange', 'section' => 'about',    'label' => 'Orange'],
    ['key' => 'yellow', 'section' => 'posts',    'label' => 'Yellow'],
    ['key' => 'blue',   'section' => 'projects', 'label' => 'Blue'],
    ['key' => 'green',  'section' => 'links',    'label' => 'Green'],
    ['key' => 'purple', 'section' => 'purple',   'label' => 'Purple'],
  ];

  foreach ($sections as $s):
    $titleField = $s['key'] . 'Title';
    $bodyField  = $s['key'] . 'Body';
    $seed = crc32($s['key'] . '-art');
  ?>
  <section class="art-showcase-section">
    <div class="art-showcase-text">
      <h2><?= $page->$titleField()->or($s['label'])->html() ?></h2>
      <?= $page->$bodyField()->kirbytext() ?>
    </div>
    <div class="art-showcase-canvas art-container"
         data-section="<?= esc($s['section'], 'attr') ?>"
         data-seed="<?= esc($seed, 'attr') ?>">
      <canvas class="art-canvas"></canvas>
    </div>
  </section>
  <?php endforeach ?>
</div>

<?php
$jsPath = kirby()->root('assets') . '/js/snippets/procedural-art-showcase.js';
$jsVersion = file_exists($jsPath) ? filemtime($jsPath) : '1';
$jsUrl = url('assets/js/snippets/procedural-art-showcase.js') . '?v=' . $jsVersion;
?>
<script defer src="<?= $jsUrl ?>"></script>

<?php snippet('footer') ?>
