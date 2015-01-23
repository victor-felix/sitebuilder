<?php
namespace meumobi\sitebuilder\presenters;

class VisitorGraphPresenter
{
	public static function present($data)
	{
		$json = array_map(function($values) {
			$jsonData = [];
			//TODO use functional programing
			foreach ($values as $label => $value) {
				$jsonData[] = [
					'label' => s($label),
					'value' => $value
				];
			}
			return $jsonData;
		}, $data);

		return json_encode($json);
	}
}
