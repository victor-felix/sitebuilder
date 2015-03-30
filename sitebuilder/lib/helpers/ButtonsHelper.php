<?php

class ButtonsHelper extends Helper
{
	public function pushScene($label, $url)
	{
		return $this->html->link($label, $url, [
			'class' => 'ui-button large push-scene',
		]);
	}

	public function rowPushScene($label, $url)
	{
		return $this->html->link($label, $url, [
			'class' => 'ui-button push-scene',
		]);
	}

	public function popScene($label, $url)
	{
		return $this->html->link($label, $url, [
			'class' => 'ui-button large pop-scene'
		]);
	}

	public function submit()
	{
		return $this->form->submit(s('Save'), [
			'class' => 'ui-button red larger'
		]);
	}

	public function delete($label, $url, $confirm)
	{
		return $this->html->link('<i class="fa fa-lg fa-trash"></i> ' . $label,	$url, [
			'class' => 'ui-button delete has-confirm',
			'data-confirm' => $confirm
		]);
	}
}
