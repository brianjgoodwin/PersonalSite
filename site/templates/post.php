<?php
/**
 * Template: Blog Post
 *
 * Uses Tschichold asymmetric layout (3:2 ratio grid)
 * Left-aligned content column with generous right margin
 *
 * Layout class: .layout-asymmetric .layout-post
 */

snippet('header');
?>

<article class="layout-asymmetric layout-post">
  <!-- Post Header -->
  <header class="post-header">
    <div class="post-meta">
      <time datetime="<?= $page->date()->toDate('c') ?>">
        <?= $page->date()->toDate('F j, Y') ?>
      </time>

      <?php if ($author = $page->author()->toUser()): ?>
        <span class="post-author">by <?= $author->name()->esc() ?></span>
      <?php endif ?>

      <span class="post-reading-time">
        <?= $page->readingTime() ?> min read
      </span>
    </div>

    <h1 class="post-title"><?= $page->title()->esc() ?></h1>

    <?php if ($page->description()->isNotEmpty()): ?>
      <p class="post-description">
        <?= $page->description()->esc() ?>
      </p>
    <?php endif ?>
  </header>

  <!-- Cover Image (if exists) -->
  <?php if ($cover = $page->cover()->toFile()): ?>
    <figure class="post-cover">
      <img
        src="<?= $cover->resize(1200)->url() ?>"
        srcset="<?= $cover->srcset([600, 1200, 1800, 2400]) ?>"
        sizes="(min-width: 1200px) 1200px, 100vw"
        alt="<?= $cover->alt()->esc() ?>"
        loading="eager"
      />
      <?php if ($cover->caption()->isNotEmpty()): ?>
        <figcaption class="post-cover__caption">
          <?= $cover->caption()->kt() ?>
          <?php if ($cover->credit()->isNotEmpty()): ?>
            <span class="post-cover__credit">
              Photo: <?= $cover->credit()->esc() ?>
            </span>
          <?php endif ?>
        </figcaption>
      <?php endif ?>
    </figure>
  <?php endif ?>

  <!-- Post Content (Blocks Field) -->
  <div class="post-content">
    <div class="blocks">
      <?php foreach ($page->content()->toBlocks() as $block): ?>
        <div class="block block--<?= $block->type() ?>" id="<?= $block->id() ?>">
          <?= $block ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>

  <!-- Post Footer -->
  <footer class="post-footer">
    <!-- Tags -->
    <?php if ($page->tags()->isNotEmpty()): ?>
      <div class="post-tags">
        <span class="post-tags__label">Tagged:</span>
        <ul class="post-tags__list">
          <?php foreach ($page->tags()->split(',') as $tag): ?>
            <li>
              <a href="<?= url('blog/tag/' . urlencode($tag)) ?>">
                <?= esc($tag) ?>
              </a>
            </li>
          <?php endforeach ?>
        </ul>
      </div>
    <?php endif ?>

    <!-- Previous/Next Post Navigation -->
    <?php
    $prevPost = $page->prevListed();
    $nextPost = $page->nextListed();
    ?>
    <?php if ($prevPost || $nextPost): ?>
      <nav class="post-navigation" aria-label="Post navigation">
        <?php if ($prevPost): ?>
          <a href="<?= $prevPost->url() ?>" rel="prev" class="post-navigation__prev">
            <span class="post-navigation__label">Previous</span>
            <span class="post-navigation__title">
              <?= $prevPost->title()->esc() ?>
            </span>
          </a>
        <?php endif ?>

        <?php if ($nextPost): ?>
          <a href="<?= $nextPost->url() ?>" rel="next" class="post-navigation__next">
            <span class="post-navigation__label">Next</span>
            <span class="post-navigation__title">
              <?= $nextPost->title()->esc() ?>
            </span>
          </a>
        <?php endif ?>
      </nav>
    <?php endif ?>

    <!-- Back to Blog -->
    <a href="<?= page('blog')->url() ?>" class="post-footer__back">
      ← Back to Blog
    </a>
  </footer>
</article>

<?php snippet('footer'); ?>
