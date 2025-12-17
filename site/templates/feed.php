<?php

// Get the posts page and all published posts
$postsPage = page('posts');
$posts = $postsPage ? $postsPage->children()->listed()->sortBy('date', 'desc')->limit(20) : [];

// Pre-load all authors to avoid N+1 queries
$authorIds = $posts->pluck('author', null, true);
$authors = [];
foreach ($authorIds as $id) {
    if ($id && $user = kirby()->user($id)) {
        $authors[$id] = $user;
    }
}

// Set content type header
header('Content-Type: application/rss+xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= $site->title()->xml() ?></title>
    <link><?= $site->url() ?></link>
    <description><?= $site->title()->xml() ?> - Blog Posts</description>
    <language>en-us</language>
    <atom:link href="<?= url('feed') ?>" rel="self" type="application/rss+xml" />
    <lastBuildDate><?= date('r') ?></lastBuildDate>

    <?php foreach ($posts as $post): ?>
    <item>
      <title><?= $post->title()->xml() ?></title>
      <link><?= $post->url() ?></link>
      <guid isPermaLink="true"><?= $post->url() ?></guid>
      <pubDate><?= $post->date()->toDate('r') ?></pubDate>

      <?php
      $authorId = $post->author()->value();
      if ($authorId && isset($authors[$authorId])):
          $author = $authors[$authorId];
      ?>
      <author><?= $author->email()->xml() ?> (<?= $author->name()->xml() ?>)</author>
      <?php endif ?>

      <description><![CDATA[
        <?php if ($cover = $post->cover()->toFile()): ?>
          <img src="<?= $cover->url() ?>" alt="<?= $post->title()->xml() ?>" style="max-width: 100%; height: auto; margin-bottom: 1em;">
        <?php endif ?>

        <?php if ($excerpt = $post->excerpt()->isNotEmpty()): ?>
          <?= $excerpt->html() ?>
        <?php else: ?>
          <?= $post->text()->excerpt(300) ?>
        <?php endif ?>
      ]]></description>

      <?php if ($post->tags()->isNotEmpty()): ?>
        <?php foreach ($post->tags()->split() as $tag): ?>
      <category><?= html($tag) ?></category>
        <?php endforeach ?>
      <?php endif ?>
    </item>
    <?php endforeach ?>
  </channel>
</rss>
