<?php
/**
 * RSS Feed Snippet
 * Generates an RSS 2.0 feed for blog posts
 * Includes full HTML content via content:encoded
 */

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/">
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
    // Use excerpt if available, otherwise first 280 chars of body
    $description = '';
    if ($article->excerpt()->isNotEmpty()) {
      $description = $article->excerpt()->esc('xml');
    } elseif ($article->body()->isNotEmpty()) {
      $description = $article->body()->excerpt(280)->esc('xml');
    }

    // Full HTML content for feed readers that support it
    $content = $article->body()->kirbytext()->value();

    // Use published date if available, fall back to modified date
    $pubDate = $article->date()->isNotEmpty()
      ? $article->date()->toDate('r')
      : date('r', $article->modified());
?>
    <item>
      <title><?= $article->title()->esc('xml') ?></title>
      <link><?= $article->url() ?></link>
      <guid isPermaLink="true"><?= $article->url() ?></guid>
      <pubDate><?= $pubDate ?></pubDate>
      <description><?= $description ?></description>
      <content:encoded><![CDATA[<?= $content ?>]]></content:encoded>
    </item>
<?php
  endforeach;
endif;
?>
  </channel>
</rss>
