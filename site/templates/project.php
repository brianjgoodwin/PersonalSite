<?php snippet('header') ?>

<?php
// Get layout option from blueprint (defaults to two-column)
$layout = $page->layout()->isNotEmpty() ? $page->layout()->value() : 'two-column';
$layoutClass = 'layout-' . $layout;
?>

<?php snippet('layout', [
  'showBreadcrumb' => true,
  'layoutClass' => $layoutClass,

  'primary' => function() use ($page) { ?>
    <article class="project-content">
      <h1><?= $page->title()->html() ?></h1>
      <?= $page->body()->kirbytext() ?>

      <?php
      // Display gallery if images exist
      $galleryImages = $page->images()->template('gallery-image');
      if ($galleryImages->count() > 0):
      ?>
        <?php snippet('gallery', ['images' => $galleryImages]) ?>
      <?php endif ?>

      <?php
      // Query related posts (articles linked to this project)
      $relatedPosts = site()
        ->find('posts')
        ->children()
        ->listed()
        ->filterBy('relatedProject', $page->uri())
        ->sortBy('date', 'desc')
        ->limit(5);

      // Display related posts if any exist
      if ($relatedPosts->count() > 0):
      ?>
        <section class="related-posts">
          <h2>Related Posts</h2>
          <div class="related-posts-grid">
            <?php foreach ($relatedPosts as $post): ?>
              <article class="related-post-card">
                <h3 class="related-post-title">
                  <a href="<?= $post->url() ?>"><?= $post->title()->html() ?></a>
                </h3>
                <time class="related-post-date" datetime="<?= $post->date()->toDate('c') ?>">
                  <?= $post->date()->toDate('F j, Y') ?>
                </time>
                <?php if ($post->excerpt()->isNotEmpty()): ?>
                  <p class="related-post-excerpt"><?= $post->excerpt()->html() ?></p>
                <?php else: ?>
                  <p class="related-post-excerpt"><?= $post->body()->excerpt(150) ?></p>
                <?php endif ?>
              </article>
            <?php endforeach ?>
          </div>
        </section>
      <?php endif ?>

      <?php if ($parent = $page->parent()): ?>
      <p class="back-link"><a href="<?= $parent->url() ?>">← Back to <?= $parent->title()->html() ?></a></p>
      <?php endif ?>
    </article>
  <?php },

  'secondary' => function() use ($page) { ?>
    <?php snippet('procedural-art') ?>
  <?php }
]) ?>

<?php snippet('footer') ?>
