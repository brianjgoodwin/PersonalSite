<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<?php if ($page->blocks()->isNotEmpty()): ?>
    <?= $page->blocks()->toBlocks() ?>
<?php endif ?>

<?php
// Get all published posts, sorted by date (newest first)
$posts = $page->children()->listed()->sortBy('date', 'desc');
?>

<?php if ($posts->count() > 0): ?>
    <div class="posts-list">
        <?php foreach ($posts as $post): ?>
            <article class="post-preview" style="margin-bottom: 30px;">
                <div class="post-preview-header">
                    <h3 class="post-preview-title">
                        <a href="<?= $post->url() ?>">
                            <?php if ($post->featured()->toBool()): ?>
                                <span style="color: #ff6b35; margin-right: 5px;">★</span>
                            <?php endif ?>
                            <?= $post->title()->html() ?>
                        </a>
                    </h3>
                    <span class="post-preview-date">
                        <?= $post->date()->toDate('F j, Y') ?>
                        <?php if ($author = $post->author()->toUser()): ?>
                            · by <?= $author->name() ?>
                        <?php endif ?>
                        <?php if ($post->tags()->isNotEmpty()): ?>
                            · Tagged: <?= $post->tags()->html() ?>
                        <?php endif ?>
                    </span>
                </div>

                <?php if ($cover = $post->cover()->toFile()): ?>
                    <div style="margin: 15px 0;">
                        <a href="<?= $post->url() ?>">
                            <img
                                src="<?= $cover->crop(800, 400)->url() ?>"
                                alt="<?= $post->title()->html() ?>"
                                style="width: 100%; height: auto; border-radius: 8px;"
                            >
                        </a>
                    </div>
                <?php endif ?>

                <?php if ($excerpt = $post->excerpt()->isNotEmpty()): ?>
                    <div class="post-preview-excerpt">
                        <?= $excerpt->html() ?>
                    </div>
                <?php else: ?>
                    <div class="post-preview-excerpt">
                        <?= $post->text()->excerpt(200) ?>
                    </div>
                <?php endif ?>

                <a href="<?= $post->url() ?>" class="post-preview-link">
                    Read more →
                </a>
            </article>
        <?php endforeach ?>
    </div>
<?php else: ?>
    <div class="text-content">
        <p>No posts published yet. Check back soon!</p>
    </div>
<?php endif ?>

<?php snippet('footer') ?>
