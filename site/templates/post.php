<?php snippet('header') ?>

<article class="post">
    <!-- Post Header -->
    <header class="post-header" style="margin-bottom: 30px;">
        <h2 style="margin-bottom: 10px;">
            <?php if ($page->featured()->toBool()): ?>
                <span style="color: #ff6b35; margin-right: 8px;">★</span>
            <?php endif ?>
            <?= $page->title()->html() ?>
        </h2>

        <div style="color: #666; font-size: 0.95rem; margin-bottom: 20px;">
            <time datetime="<?= $page->date()->toDate('c') ?>">
                <?= $page->date()->toDate('F j, Y') ?>
            </time>

            <?php if ($author = $page->author()->toUser()): ?>
                · by <strong><?= $author->name()->html() ?></strong>
            <?php endif ?>

            <?php if ($page->tags()->isNotEmpty()): ?>
                <div style="margin-top: 8px;">
                    <?php foreach ($page->tags()->split() as $tag): ?>
                        <span style="display: inline-block; background: #f0f0f0; padding: 4px 10px; border-radius: 12px; font-size: 0.85rem; margin-right: 6px; margin-top: 4px;">
                            <?= html($tag) ?>
                        </span>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>

        <!-- Cover Image -->
        <?php if ($cover = $page->cover()->toFile()): ?>
            <figure style="margin: 0 0 30px 0;">
                <img
                    src="<?= $cover->crop(1200, 600)->url() ?>"
                    alt="<?= $page->title()->html() ?>"
                    style="width: 100%; height: auto; border-radius: 8px;"
                >
            </figure>
        <?php endif ?>

        <!-- Excerpt (if provided) -->
        <?php if ($excerpt = $page->excerpt()->isNotEmpty()): ?>
            <div style="font-size: 1.1rem; color: #555; font-style: italic; padding: 15px 0; border-left: 3px solid #333; padding-left: 20px; margin-bottom: 30px;">
                <?= $excerpt->html() ?>
            </div>
        <?php endif ?>
    </header>

    <!-- Post Content -->
    <div class="text-content" style="font-size: 1.05rem; line-height: 1.8;">
        <?= $page->text()->kirbytext() ?>
    </div>

    <!-- Additional Images/Files -->
    <?php if ($page->files()->count() > 1): // More than just cover image ?>
        <section style="margin-top: 40px; padding-top: 30px; border-top: 1px solid #ddd;">
            <h3 style="font-size: 1.2rem; margin-bottom: 15px;">Additional Images</h3>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 15px;">
                <?php foreach ($page->files()->filterBy('extension', 'in', ['jpg', 'jpeg', 'png', 'gif', 'webp']) as $file): ?>
                    <?php if ($file->id() !== $page->cover()->value()): // Skip cover image ?>
                        <figure style="margin: 0;">
                            <img
                                src="<?= $file->crop(400, 300)->url() ?>"
                                alt="<?= $file->alt()->or($page->title())->html() ?>"
                                style="width: 100%; height: auto; border-radius: 4px;"
                            >
                        </figure>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </section>
    <?php endif ?>

    <!-- Post Navigation -->
    <nav style="margin-top: 50px; padding-top: 30px; border-top: 2px solid #ddd; display: flex; justify-content: space-between; gap: 20px;">
        <?php if ($prev = $page->prev()): ?>
            <a href="<?= $prev->url() ?>" style="color: #0066cc; text-decoration: none; flex: 1;">
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">← Previous Post</div>
                <div style="font-weight: 500;"><?= $prev->title()->html() ?></div>
            </a>
        <?php else: ?>
            <div style="flex: 1;"></div>
        <?php endif ?>

        <a href="<?= $page->parent()->url() ?>" style="color: #666; text-decoration: none; text-align: center; padding: 0 20px;">
            <div style="font-size: 0.85rem; margin-bottom: 5px;">↑</div>
            <div style="font-weight: 500;">All Posts</div>
        </a>

        <?php if ($next = $page->next()): ?>
            <a href="<?= $next->url() ?>" style="color: #0066cc; text-decoration: none; flex: 1; text-align: right;">
                <div style="font-size: 0.85rem; color: #666; margin-bottom: 5px;">Next Post →</div>
                <div style="font-weight: 500;"><?= $next->title()->html() ?></div>
            </a>
        <?php else: ?>
            <div style="flex: 1;"></div>
        <?php endif ?>
    </nav>
</article>

<?php snippet('footer') ?>
