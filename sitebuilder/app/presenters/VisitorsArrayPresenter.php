<?php

namespace app\presenters;
//TODO move to meumobi\sitebuilder\presenters\
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
		$csv .= $addLine(['id','first_name', 'last_name', 'email', 'groups']);

		foreach ($this->visitors as $visitor) {
			$values = [$visitor->id(), $visitor->firstName(), $visitor->lastName(),$visitor->email(), implode(',',$visitor->groups())];
			$csv .= $addLine($values);
		}

		return $csv;
	}
}
