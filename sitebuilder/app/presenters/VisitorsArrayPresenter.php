<?php

namespace app\presenters;

class VisitorsArrayPresenter {
	protected $visitors = [];

	public function __construct($visitors)
	{
		if ($visitors)
			$this->visitors = $visitors;
	}

	public function toCSV()
	{
		$csv = '';
		$addLine = function($item) {
			return '"' . implode('","', $item) . '"' . "\n";
		};

		//Add csv field titles
		$csv .= $addLine(['id','email', 'groups']);

		foreach ($this->visitors as $visitor) {
			$values = [$visitor->id(), $visitor->email(), implode(',',$visitor->groups())];
			$csv .= $addLine($values);
		}

		return $csv;
	}
}
