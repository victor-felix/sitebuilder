<?xml version="1.0"?>
<rss version="2.0">
   <channel>
      <title><?php echo Sanitize::html($site->title) ?></title>
      <link><?php echo $site->link() ?></link>
      <description><?php echo Sanitize::html($site->title) ?></description>
      <?php foreach($items as $item): ?>
        <item>
            <title><?php echo Sanitize::html($item['title']) ?></title>
            <link><?php echo $item['link'] ?: 'http://meumobi.com' ?></link>
            <description><![CDATA[<?php echo $item['description'] ?>]]></description>
            <pubDate><?php echo date(DATE_RSS, $item['pubdate']) ?></pubDate>
            <guid><?php echo $item['guid'] ?: 'http://meumobi.com/' ?></guid>
        </item>
      <?php endforeach ?>
   </channel>
</rss>
