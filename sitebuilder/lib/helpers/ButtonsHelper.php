<?php

class ButtonsHelper extends Helper
{
	public function pushScene($label, $url) {
		return $this->html->link($label, $url, array(
			'class' => 'ui-button large push-scene',
		));
	}

	public function rowPushScene($label, $url) {
		return $this->html->link($label, $url, array(
			'class' => 'ui-button push-scene',
		));
	}

	public function popScene($label, $url) {
		return $this->html->link($label, $url, array(
			'class' => 'ui-button large pop-scene'
		));
	}

}
