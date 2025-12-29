<?php snippet('header') ?>

<?php snippet('breadcrumb') ?>

<section class="content blog">

  <h1><?= $page->title()->html() ?></h1>
  <?= $page->body()->kirbytext() ?>

  <?php
  $articles = $page->children()->listed()->flip()->paginate(5);
  foreach($articles as $article):
  ?>

  <article>
    <h2><a href="<?= $article->url() ?>"><?= $article->title()->html() ?></a></h2>
    <?= $article->body()->kirbytext() ?>
  </article>

  <?php endforeach ?>

  <?php if ($articles->pagination()->hasPages()): ?>
    <nav class="pagination" aria-label="Posts pagination">

      <?php if ($articles->pagination()->hasNextPage()): ?>
      <a class="next" href="<?= $articles->pagination()->nextPageURL() ?>" aria-label="Go to older posts">
        ‹ older posts
      </a>
      <?php endif ?>

      <?php if ($articles->pagination()->hasPrevPage()): ?>
      <a class="prev" href="<?= $articles->pagination()->prevPageURL() ?>" aria-label="Go to newer posts">
        newer posts ›
      </a>
      <?php endif ?>

    </nav>
  <?php endif ?>

</section>

<?php snippet('footer') ?>