<?php snippet('header') ?>

<h2><?= $page->title() ?></h2>

<?php if ($intro = $page->intro()->isNotEmpty()): ?>
<div class="text-content" style="margin-bottom: 40px;">
    <?= $page->intro()->kirbytext() ?>
</div>
<?php endif ?>

<?php
// Get link sections from structure field
$linkSections = $page->linkSections()->toStructure();
?>

<?php if ($linkSections->count() > 0): ?>
    <?php foreach ($linkSections as $section): ?>
        <div class="link-list" style="margin-bottom: 50px;">
            <h3 class="link-list-title" style="font-size: 1.5rem; margin-bottom: 10px; border-bottom: 2px solid #333; padding-bottom: 10px;">
                <?= $section->sectionTitle()->html() ?>
            </h3>

            <?php if ($section->sectionDescription()->isNotEmpty()): ?>
                <p style="color: #666; margin-bottom: 20px; font-size: 0.95rem;">
                    <?= $section->sectionDescription()->html() ?>
                </p>
            <?php endif ?>

            <?php
            // Get links from this section
            $links = $section->links()->toStructure();
            ?>

            <?php if ($links->count() > 0): ?>
                <ul class="link-list-items" style="list-style: none; padding: 0;">
                    <?php foreach ($links as $link): ?>
                        <li style="margin-bottom: 20px; padding: 15px; border: 1px solid #e0e0e0; border-radius: 8px; <?= $link->featured()->toBool() ? 'background: #fffbf0; border-color: #ffd700;' : 'background: #fafafa;' ?>">
                            <div style="display: flex; align-items: start; gap: 10px;">
                                <?php if ($link->featured()->toBool()): ?>
                                    <span style="color: #ff6b35; font-size: 1.2rem;">★</span>
                                <?php endif ?>

                                <div style="flex: 1;">
                                    <a href="<?= $link->url() ?>" target="_blank" rel="noopener noreferrer" style="color: #0066cc; text-decoration: none; font-weight: 600; font-size: 1.1rem;">
                                        <?= $link->title()->html() ?>
                                        <span style="font-size: 0.8rem; color: #999; margin-left: 5px;">↗</span>
                                    </a>

                                    <?php if ($link->description()->isNotEmpty()): ?>
                                        <p class="link-description" style="margin: 8px 0 0 0; color: #555; font-size: 0.9rem; line-height: 1.5;">
                                            <?= $link->description()->html() ?>
                                        </p>
                                    <?php endif ?>

                                    <?php if ($link->tags()->isNotEmpty()): ?>
                                        <div style="margin-top: 10px;">
                                            <?php foreach ($link->tags()->split() as $tag): ?>
                                                <span style="display: inline-block; background: #e8e8e8; color: #666; padding: 3px 8px; border-radius: 10px; font-size: 0.75rem; margin-right: 5px; margin-top: 3px;">
                                                    <?= html($tag) ?>
                                                </span>
                                            <?php endforeach ?>
                                        </div>
                                    <?php endif ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach ?>
                </ul>
            <?php else: ?>
                <p style="color: #999; font-style: italic;">No links in this section yet.</p>
            <?php endif ?>
        </div>
    <?php endforeach ?>
<?php else: ?>
    <div class="text-content">
        <p>No link sections created yet. Add some through the Panel!</p>
    </div>
<?php endif ?>

<?php snippet('footer') ?>
