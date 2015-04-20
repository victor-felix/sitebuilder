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

	public function rowMoveUp($url)
	{
		return $this->html->link('<i class="fa fa-lg fa-sort-up"></i>', $url, [
			'class' => 'move-up ui-button',
		]);
	}

	public function rowMoveDown($url)
	{
		return $this->html->link('<i class="fa fa-lg fa-sort-down"></i>', $url, [
			'class' => 'move-down ui-button',
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
		return $this->html->link('<i class="fa fa-lg fa-trash-o"></i> ' . $label,	$url, [
			'class' => 'ui-button delete has-confirm',
			'data-confirm' => $confirm
		]);
	}
}
