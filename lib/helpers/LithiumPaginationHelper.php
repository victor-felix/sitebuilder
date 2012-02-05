<?php

class LithiumPaginationHelper extends Helper{
	
	protected $params;
	
	public function getParams() {
		if(!$this->params){
			$this->params = $this->view->controller->get('paginate');
		}
		return $this->params;
	}

	public function numbers($options = array()) {
		$options += array(
			'modulus' => 3,
			'separator' => ' ',
			'tag' => 'span',
			'current' => 'current'
		);
		
		if($this->pages() < 2) {
			return '';
		}
		
		$numbers = array();
		$start = max($this->page() - $options['modulus'], 1);
		$end = min($this->page() + $options['modulus'], $this->pages());

		for($i = $start; $i <= $end; $i++) {
			if($i == $this->page()) {
				$attributes = array('class' => $options['current']);
				$number = $i;
			} else {
				$attributes = array();
				$number = $this->html->link($i, array('page' => $i));
			}
			$numbers[] = $this->html->tag($options['tag'], $number, $attributes);
		}
		return implode($options['separator'], $numbers);
	}

	public function next($text, $attr = array()) {
        if($this->hasNext()) {
            return $this->html->link($text, array(
                'page' => $this->page() + 1
            ), $attr);
        }
    }
    
    public function previous($text, $attr = array()) {
        if($this->hasPrevious()) {
            return $this->html->link($text, array(
                'page' => $this->page() - 1
            ), $attr);
        }
    }
	
	public function first($text, $attr = array()) {
        if($this->hasPrevious()) {
            return $this->html->link($text, array(
                'page' => 1
            ), $attr);
        }
    }
    
    public function last($text, $attr = array()) {
        if($this->hasNext()) {
            return $this->html->link($text, array(
                'page' => $this->pages()
            ), $attr);
        }
    }

	public function hasNext() {
        if($this->getParams()) {
            return $this->page() < $this->pages();
        }
    }

    public function hasPrevious() {
        if($this->getParams()) {
            return $this->page() > 1;
        }
    }

	public function page() {
		if($this->getParams()) {
			return $this->getParams()->page;
		}
	}


	public function pages() {
		if($this->getParams()) {
			return ceil($this->getParams()->total / $this->getParams()->limit);
		}
	}


	public function records() {
		if($this->getParams()) {
			return $this->getParams()->total;
		}
	}
}
