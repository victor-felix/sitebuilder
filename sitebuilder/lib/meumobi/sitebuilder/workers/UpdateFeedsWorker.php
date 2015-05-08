<?php
//TODO order and rename methods
//TODO clean code, arrays, indentation etc.
//TODO refactor old imported Articles methods

namespace meumobi\sitebuilder\workers;

require_once 'lib/simplepie/SimplePie.php';
require_once 'lib/dom/SimpleHtmlDom.php';
require_once 'lib/utils/Video.php';

use SimplePie;
use Mapper;
use DOMDocument;
use HTMLPurifier;
use HTMLPurifier_Config;
use Filesystem;
use lithium\data\Connections;
use app\models\Items;
use meumobi\sitebuilder\repositories\RecordNotFoundException;//TODO move exceptions for a more generic namespace
use app\models\extensions\Rss;
use app\models\items\Articles;
use Video;

class UpdateFeedsWorker extends Worker
{
	const ARTICLES_TO_KEEP = 50;

	protected $category;
	protected $extension;
	protected $blacklist = ['gravatar.com'];
	protected $stats = [
		'total_articles' => 0,
		'total_images' => 0,
		'failed_images' => 0,
	];

	public function perform()
	{
		$this->logger()->info('updating feeds', [
			'priority' => $this->getPriority()
		]);

		$ids = $this->getExtensionsIds();
		array_walk($ids, [$this, 'updateFromFeed']);
		$this->logger()->info('finished updating feeds', $this->stats);

		exit('oiiiii');
	}

	protected function updateFromFeed($extensionId)
	{
		try {
			$this->extension = $this->getExtension($extensionId);
			$this->category = $this->getCategory($this->extension);
			$feed = $this->getFeed();
			$this->updateArticles($feed);
			$this->removeOldArticles();

			$this->category->updated = date('Y-m-d H:i:s');
			$this->category->save();

			$this->extension->priority = self::PRIORITY_LOW;
			$this->extension->save();
		} catch (\Exception $e) {
			echo $e->getTraceAsString();	
		}
	}

	protected function getExtension($id)
	{
		return $extension = Rss::find('first', array('conditions' => array(
			'_id' => $id,
		)));
	}

	protected function getCategory($extension)
	{
		try {
			return Rss::category($extension);
		} catch (RecordNotFoundException $e) {
			$extensionData = $extension->to('array');
			Rss::remove(array('_id' => $extension->_id));
			throw new Exception('Invalid extension removed from database: ' . print_r($extensionData, 1));
		}
	}

	protected function updateArticles($feed)
	{
		try {
			$feedItems =  array_reverse($feed->get_items());
			foreach ($feedItems as $feedItem) {
				$item = $this->getItem($feedItem);
				$this->updateArticle($item, $feedItem);
				$this->stats['total_articles'] += 1;
			}
		} catch(Exception $e) {
			// do nothing if the feed fails for any reason
		}
	}

	protected function getItem($feedItem)
	{
		$classname = '\app\models\items\\' . \Inflector::camelize($this->category->type);
		$item = $classname::find('first', [
			'conditions' => [
				'parent_id' => $this->category->id,
				'guid' => $feedItem->get_id()
			]
		]);
		if (!$item)	{
			$item = $classname::create();
		}
		return $item;
	}

	protected function updateArticle($item, $feedItem)
	{
		$images = $this->getArticleImages($feedItem);

		//remove captions from description
		$remove = array();
		foreach ($images as $img) {
			if (is_array($img) && $img['alt']) {
				$remove[] = "<p>{$img['alt']}</p>";
			}
		}

		$author = $feedItem->get_author();
		$medias = $this->getArticleMedias($feedItem);
		$data = array(
			'site_id' => $this->category->site_id,
			'parent_id' => $this->category->id,
			'guid' => $this->filterGuid($feedItem->get_id()),
			'link' => $feedItem->get_link(),
			'title' => strip_tags($feedItem->get_title()),
			'description' => $this->cleanupHtml($feedItem, $remove, $this->extension->use_html_purifier),
			'pubdate' => gmdate('Y-m-d H:i:s', $feedItem->get_date('U')),
			'author' => $author ? $author->get_name() : '',
			'format' => 'html',
			'type' => $this->category->type,
			'medias' => $medias
		);

		$item->set($data);
		$item->save();

		foreach ($images as $image) {
			$imageAlt = '';
			if (is_array($image)) {
				$imageAlt = $image['alt'];
				$image = $image['src'];
			}
			$image = $this->getImageUrl($image, $item['guid']);
			$result = Model::load('Images')->download($item, $image, array(
				'url' => $image,
				'title' => $imageAlt,
				'visible' => 1
			));

			if ($result) {
				$this->stats['total_images'] += 1;
			} else {
				$$this->stats['failed_images'] += 1;
			}
		}
	}

	protected function getPurifier()
	{
		$config = HTMLPurifier_Config::createDefault();
		$config->set('Cache.SerializerPath', Filesystem::path(APP_ROOT . '/tmp/cache/html_purifier'));
		$config->set('HTML.Allowed', 'b,i,br,p,strong');
		return new HTMLPurifier($config);
	}

	protected function cleanupHtml($feedItem, $strToRemove = false, $purify = true)
	{
		$html = $feedItem->get_content();
		if ($purify) {
			$purifier = $this->getPurifier();
			$html = $purifier->purify($html);
		}
		$html = mb_convert_encoding($html, 'UTF-8', mb_detect_encoding($html));
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');

		if ($strToRemove) {
			$html = str_replace($strToRemove, '', (string)$html);
		}

		if(!empty($html)) {
			$doc = new DOMDocument();
			$doc->loadHTML($html);
			$body = $doc->getElementsByTagName('body')->item(0);
			$results = '';

			foreach($body->childNodes as $node) {
				if($node->nodeType == XML_TEXT_NODE) {
					$content = trim($node->textContent);
					if($content) {
						$new_node = $doc->createElement('p', $content);
						$node = $new_node;
					}
				}
				if($node->nodeType == XML_ELEMENT_NODE) {
					$results .= $doc->saveHTML($node) . PHP_EOL;
				}
			}
		}
		else {
			$results = '';
		}

		return $results;
	}

	protected function getArticleImages($feedItem)
	{
		$images = $this->getEnclosureImages($feedItem);
		$imagesAreInvalid = empty($images) || (is_array($images) && count($images) == 1 && !$images[0]);

		if($imagesAreInvalid) {
			if ($image = $feedItem->get_item_tags(SIMPLEPIE_NAMESPACE_RSS_20, 'image')) {
				$images = (array)$image[0]['data'];
			} else {
				$images = $this->getContentImages($feedItem);
			}
		}

		foreach($images as $k => $image) {
			if (is_array($image)) {
				$image = $image['src'];
			}
			if($this->isBlackListed($image)) {
				unset($images[$k]);
			}
		}

		return $images;
	}

	protected function getArticleMedias($feedItem)
	{
			$medias = [];
			foreach($feedItem->get_enclosures() as $enclosure) {
				if ($enclosure->get_link())//stackoverflow.com/questions/4053664/simplepie-includes-phantom-enclosures-that-dont-exist
					$medias[] = [
						'url' => $enclosure->get_link(),
						'type' => $enclosure->get_type(),
						'title' => html_entity_decode($enclosure->get_title(), ENT_QUOTES, 'UTF-8'),
						'length' => $enclosure->get_length(),
						'thumbnails' => $enclosure->get_thumbnails(),
					];
			}
			$medias = array_merge($medias, $this->getContentVideos($feedItem));
			//try to generate video thumbs if none is set
			return array_map(function($media) {
				if (!$media['thumbnails'])
					$media['thumbnails'] = Video::getThumbnails($media['url']); //return thumbnails if the url is from a video
				return $media;
			}, $medias);
	}

	protected function getContentVideos($feedItem)
	{
		$videos = [];
		$dom = new \DOMDocument('1.0', 'UTF-8');
		@$dom->loadHtml('<?xml encoding="UTF-8">' . $feedItem->get_content());
		$xpath = new \DOMXPath($dom);
		$nodes = $xpath->query('//iframe[contains(@src,"youtube") 
			or contains(@src,"dailymotion") 
			or contains(@src,"canalplus")
			or contains(@src,"gfycat")
			or contains(@src,"vimeo")]');
		if ($nodes->length) {
			foreach ($nodes as $iframe) {
					$videos[] = [
					'url' => $iframe->getAttribute('src'),
					'type' => 'text/html',
					'title' => '',
					'thumbnails' => [],
					'length' => null,
					];	
			}
		}
		return $videos;	
	}

	protected function getContentImages($feedItem)
	{
		$dom = new \DOMDocument('1.0', 'UTF-8');
		@$dom->loadHtml('<?xml encoding="UTF-8">' . $feedItem->get_content());
		$xpath = new \DOMXPath($dom);
		$images = array();
		$src = array();

		$nodes = $xpath->query('//a[@rel="lightbox"]');
		if ($nodes->length) {
			foreach ($nodes as $img) {
				$src []= $img->getAttribute('src');
				$images []= empty($src)?$img->getAttribute('href'):$src;
			}
			return $images;
		}

		$nodes = $xpath->query('//img[contains(@src, "wp-content/uploads")]|//img[contains(@src, "/photos/")] ');
		if ($nodes->length) {
			foreach ($nodes as $img) {
				$images []= array( 
					'src' => $img->getAttribute('src'),  
					'alt' => $img->getAttribute('alt')
				);
			}
		}

		return $images;
	}

	protected function getEnclosureImages($feedItem)
	{
		$images = array();
		$enclosures = $feedItem->get_enclosures();
		if(is_null($enclosures)) return $images;

		foreach($enclosures as $enclosure) {
			$medium = $enclosure->get_medium();
			if(!$medium || $medium == 'image') {
				$images []= $enclosure->get_link();
			}
		}
		return $images;
	}

	protected function filterGuid($guid)
	{
		$guid = preg_replace('%;jsessionid=[\w\d]+%', '', $guid);

		if(preg_match('%rj\.gov\.br%', $guid)) {
			$guid = preg_replace('%\.lportal.*articleId=%', '?articleId=', $guid);
		}

		return $guid;
	}

	protected function getImageUrl($url, $articleUrl)
	{
		if(Mapper::isRoot($url)) {
			$domain = parse_url($articleUrl, PHP_URL_HOST);
			$url = 'http://' . $domain . $url;
		}
		else if(preg_match('%^(http://download.rj.gov.br/imagens/\d+/\d+/\d+.jpg)%', $url, $output)) {
			return $output[0];
		}

		return $url;
	}

	protected function isBlackListed($link)
	{
		foreach($this->blacklist as $i) {
			$pattern = preg_quote($i);
			if(preg_match('%' . $pattern . '%', $link)) {
				return true;
			}
		}

		return false;
	}

	protected function removeOldArticles()
	{
		$conditions = array(
			'site_id' => $this->extension->site_id,
			'parent_id' => $this->extension->category_id
		);

		$count = Articles::find('count', array('conditions' => $conditions));

		if ($count > self::ARTICLES_TO_KEEP) {
			$ids = array_keys(Articles::find('list', array(
				'conditions' => $conditions,
				'limit' => $count - self::ARTICLES_TO_KEEP,
				'order' => array('pubdate' => 'ASC')
			)));

			if ($ids) {
				Articles::remove(array('_id' => $ids));
				$this->stats['removed_articles'] = count($ids);
			}
		}
	}

	protected function getFeed()
	{
		$feed = new SimplePie();
		$feed->enable_cache(false);
		$feed->set_feed_url($this->extension->url);
		$strip_htmltags = $feed->strip_htmltags;
		array_splice($strip_htmltags, array_search('iframe', $strip_htmltags), 1);
		$feed->strip_htmltags($strip_htmltags);
		$feed->init();
		return $feed;
	}

	protected function getExtensionsIds()
	{
		$extensions = Rss::find('all', [
			'conditions' => [
				'extension' => 'rss',
				'enabled' => 1,
				'priority' => $this->getPriority()
			],
			'fields' => [
				'_id',
			]
		])->to('array');

		return array_map(function($row) {
			return $row['_id'];
		}, $extensions); 	
	}

	protected function getPriority()
	{
		return $this->job()->params['priority'];
	}
}

