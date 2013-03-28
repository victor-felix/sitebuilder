<?php

class LanguageHelper extends Helper
{
	public function link($text, $url = null, $attr = array())
	{
		if ($language = $this->controller->param('locale')) {
			$url = '/' . $language . $url;
		}

		return $this->html->link($text, $url, $attr);
	}

	public function imagelink($src, $url, $img_attr = array(), $attr = array())
	{
		$image = $this->html->image($src, $img_attr);
		return $this->link($image, $url, $attr);
	}
}
