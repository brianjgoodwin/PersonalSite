<?php
/**
 * RSS Feed Snippet
 * Generates an RSS 2.0 feed for blog posts
 */

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
  <channel>
    <title><?= site()->title()->esc('xml') ?></title>
    <link><?= site()->url() ?></link>
    <description>Recent posts from <?= site()->title()->esc('xml') ?></description>
    <language>en</language>
    <lastBuildDate><?= date('r') ?></lastBuildDate>
    <atom:link href="<?= url('feed.xml') ?>" rel="self" type="application/rss+xml" />

<?php
$posts = site()->find('posts');
if ($posts):
  foreach ($posts->children()->listed()->flip()->limit(20) as $article):
    $description = '';
    if ($article->body()->isNotEmpty()) {
      $description = strip_tags($article->body()->kirbytext()->value());
    }
?>
    <item>
      <title><?= $article->title()->esc('xml') ?></title>
      <link><?= $article->url() ?></link>
      <guid><?= $article->url() ?></guid>
      <pubDate><?= date('r', $article->modified()) ?></pubDate>
      <description><![CDATA[<?= $description ?>]]></description>
    </item>
<?php
  endforeach;
endif;
?>
  </channel>
</rss>
