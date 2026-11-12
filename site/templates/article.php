<?php snippet('header') ?>

<?php
/**
 * Article layout — prototype.
 *
 * Does not use snippet('layout'): that snippet emits a fixed
 * breadcrumb + .col-primary + aside.col-secondary shape, and the title
 * here has to span both tracks, which a grid child cannot do from inside
 * .col-primary. Predicted in #44.
 *
 * Shape:
 *   breadcrumb        spans
 *   title             spans (capped, see --title-measure)
 *   meta | body       body is the RIGHT track, meta the left
 *
 * Mobile stacks title -> meta -> body via DOM order, so no `order`
 * property is needed and reading order matches visual order.
 *
 * Two variants, selected per post in the blueprint:
 *   essay  two-column, centred reading ribbon (default)
 *   plain  one column at the measure, metadata stacked above the body.
 *          For technical writing, where code blocks want the full column
 *          rather than a narrow track. Same markup, modifier class only.
 */
$layout = $page->layout()->or('essay')->value();
?>
<div class="article-layout article-layout--<?= esc($layout, 'attr') ?>">

  <?php snippet('breadcrumb') ?>

  <h1 class="article-title"><?= $page->title()->html() ?></h1>

  <div class="article-meta">
    <p class="article-byline"><?= esc(option('site.author', 'Brian Goodwin')) ?></p>
    <?php if ($page->date()->isNotEmpty()): ?>
      <p class="article-date">
        <time datetime="<?= $page->date()->toDate('c') ?>">
          <?= $page->date()->toDate('F j, Y') ?>
        </time>
      </p>
    <?php endif ?>
  </div>

  <article class="article-body flow">
    <?= $page->body()->kirbytext() ?>

    <?php if ($parent = $page->parent()): ?>
      <p class="back-link"><a href="<?= $parent->url() ?>">&larr; Back to <?= $parent->title()->html() ?></a></p>
    <?php endif ?>
  </article>

</div>

<?php snippet('footer') ?>
