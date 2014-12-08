<?xml version="1.0"?>
<rss version="2.0">
   <channel>
      <title><?php echo Sanitize::html($site->title) ?></title>
      <link><?php echo $site->link() ?></link>
      <?php foreach($items as $item): ?>
        <item>
            <title><?php echo $item['title'] ?></title>
            <link><?php echo $item['link'] ?></link>
            <description><![CDATA[<?php echo $item['description'] ?>]]></description>
            <pubDate><?php echo $item['pubdate'] ?></pubDate>
            <guid><?php echo $item['guid'] ?></guid>
        </item>
      <?php endforeach ?>
   </channel>
</rss>
