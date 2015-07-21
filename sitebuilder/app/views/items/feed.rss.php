<?xml version="1.0"?>
<rss version="2.0">
   <channel>
      <title><?= e($site->title) ?></title>
      <link><?= $site->link() ?></link>
      <description><?= e($site->title) ?></description>
      <?php foreach($items as $item): ?>
        <item>
            <title><?= e($item['title']) ?></title>
            <link><?= $item['link'] ?></link>
            <description><![CDATA[<?= $item['description'] ?>]]></description>
            <pubDate><?= $item['published'] ?></pubDate>
            <guid><?= $item['guid'] ?></guid>
        </item>
      <?php endforeach ?>
   </channel>
</rss>
