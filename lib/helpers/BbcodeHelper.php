<?php


class BbcodeHelper extends Helper
{
	public function parse($text)
	{
			$parser = new \Decoda\Decoda($text, [
				'xhtmlOutput' => true, 
				'lineBreaks' => true, 
				'escapeHtml' => false	
			]);
			$parser->defaults();
			//$parser->whitelist('b', 'i', 'color', 'url', 'big', 'small');
			return $parser->parse();
	}

	public function strip($text)
	{
			$text = $this->parse($text);
			$text = Sanitize::html($text, false);
			return htmlspecialchars_decode($text);
	}
}
