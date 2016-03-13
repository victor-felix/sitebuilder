<?php

class LithiumPaginationHelper extends Helper
{
	protected $params;

	public function getParams()
	{
		if (!$this->params) {
			$this->params = $this->view->controller->get('paginate');
			$this->params->instance = new $this->params->class();
		}
		return $this->params;
	}

	public function numbers($options = array())
	{
		$options += array(
			'modulus' => 3,
			'separator' => ' ',
			'tag' => 'span',
			'current' => 'current'
		);

		if ($this->pages() < 2) {
			return '';
		}

		$numbers = array();
		$start = max($this->page() - $options['modulus'], 1);
		$end = min($this->page() + $options['modulus'], $this->pages());

		$n = ($options['modulus'] * 2) + 1;
		if ($end < $n) {
			$end = $n < $this->pages() ? $n : $this->pages();
		}

		for ($i = $start; $i <= $end; $i++) {
			if ($i == $this->page()) {
				$attributes = array('class' => $options['current']);
				$number = $i;
			} else {
				$attributes = array();
				$number = $this->html->link($i, $this->getUrl('?page=' . $i));
			}
			$numbers[] = $this->html->tag($options['tag'], $number, $attributes);
		}
		return implode($options['separator'], $numbers);
	}

	public function next($text, $attr = array())
	{
		if ($this->hasNext()) {
			return $this->html->link($text, $this->getUrl('?page=' . ($this->page() + 1)), $attr);
		}
	}

	public function previous($text, $attr = array())
	{
		if ($this->hasPrevious()) {
			return $this->html->link($text, $this->getUrl('?page=' . ($this->page() - 1)), $attr);
		}
	}

	public function first($text, $attr = array())
	{
		if ($this->hasPrevious()) {
			return $this->html->link($text, $this->getUrl('?page=1'), $attr);
		}
	}

	public function last($text, $attr = array())
	{
		if ($this->hasNext()) {
			return $this->html->link($text, $this->getUrl('?page=' . $this->pages()), $attr);
		}
	}

	public function hasNext()
	{
		if ($this->getParams()) {
			return $this->page() < $this->pages();
		}
	}

	public function hasPrevious()
	{
		if ($this->getParams()) {
			return $this->page() > 1;
		}
	}

	public function page()
	{
		if ($this->getParams()) {
			return $this->getParams()->page;
		}
	}

	public function pages()
	{
		if ($this->getParams()) {
			return $this->getParams()->pages;
		}
	}

	public function records()
	{
		if ($this->getParams()) {
			return $this->getParams()->total;
		}
	}

	public function sort($params = array())
	{
		if (!$this->getParams()) {
			return false;
		}

		$fields = array();
		$instance = $this->getParams()->instance;
		$value = $this->view->controller->param('order') ? Mapper::here() : false;

		foreach ($instance->fields(null) as $field) {
			$url = Mapper::url( array('order' => $field) );
			$fields[$url] = $instance->field(null, $field)->title;
		}

		$params += array(
				'value' => Mapper::here(),
				'empty' =>	array( Mapper::url( array('order' => null) ) => '' ),
				'onchange' => 'self.location=this.value',
				'options' => $fields,
		);

		return $this->form->select( 'sort', $params);
	}

	public function limit($range = array(), $params = array())
	{
		if (!$this->getParams()) {
			return false;
		}

		$range += array(10, 20, 30);
		$value = $this->view->controller->param('limit') ? Mapper::here() : false;

		foreach ($range as $limit) {
			$url = Mapper::url( array('limit' => $limit) );
			$options[$url] = $limit;

			if (!$value && $limit == $this->getParams()->limit) {
				$value = $url;
			}
		}

		$params += array(
				'value' => $value,
				'onchange' => 'self.location=this.value',
				'options' => $options,
		);

		return $this->form->select( 'limit', $params);
	}

	protected function getUrl($query)
	{
		$currentUrl = Mapper::here();
		$questionMarkPos = strpos($currentUrl, '?');

		if ($questionMarkPos !== false) {
			$currentUrl = substr($currentUrl, 0, $questionMarkPos);
		}

		return $currentUrl . $query;
	}
}
