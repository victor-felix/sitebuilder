<?php

class ItemsHelper extends Helper
{
	protected $types = array(
		'default' => array(
			'type' => 'text',
			'class' => 'ui-text'
		),
		'string' => array(),
		'multistring' => array(
			'type' => 'multistring',
			'class' => 'ui-text ui-text-multi'
		),
		'text' => array(
			'type' => 'textarea'
		),
		'richtext' => array(
			'type' => 'textarea',
			'class' => 'ui-textarea markitup'
		),
		'boolean' => array(
			'type' => 'checkbox',
			'class' => 'ui-checkbox'
		),
		'select' => array(
			'type' => 'select',
			'class' => 'ui-select large'
		),
		'radio' => array(
			'type' => 'radio',
		),
		'datetime' => array(
			'type' => 'datetime-local',
			'class' => 'ui-text'
		)
	);
	protected $item;

	public function __construct($view)
	{
		parent::__construct($view);

		$site = $view->controller->getCurrentSite();

		$this->setupRelatedType($site);
		$this->setupGroupsType($site);
	}

	public function form($url, $item, $attr)
	{
		$this->item = $item;
		$attr += array(
			'method' => 'file',
			'object' => $item
		);
		return $this->form->create($url, $attr);
	}

	public function endform()
	{
		return $this->form->close();
	}

	public function input($name)
	{
		$field = $this->item->field($name);
		$defaults = [ 'label' => s($field->title) ];

		$type = (array) $field->type;
		$type = $type[0];
		if (is_array($this->types[$type])) {
			$type_attr = $this->types[$type];
		} else {
			$type_attr = $this->types[$type]($field->type);
		}

		$params = (array) $field;
		unset($params['type'], $params['title']);
		$attr = array_merge($defaults, $this->types['default'], $type_attr,
			$params);

		$attr['class'] .= ' large';

		return $this->form->input($name, $attr);
	}

	protected function setupRelatedType($site)
	{
		$this->types['related'] = function($type) use ($site) {
			$classname = '\app\models\items\\' . $type[1];
			$conditions = [
				'type' => Inflector::underscore($type[1]),
				'site_id' => $site->id,
			];

			$items = $classname::find('all', [
				'conditions' => $conditions,
				'order' => 'title'
			]);

			$options = [];

			foreach ($items as $item) {
				$options[$item->id()] = $item->title;
			}

			return [
				'name' => 'related[]',
				'type' => 'select',
				'multiple' => true,
				'options' => $options,
				'class' => 'multiselect'
			];
		};
	}

	protected function setupGroupsType($site)
	{
		$this->types['groups'] = function($type) use ($site) {
			$groups = $site->availableVisitorsGroups();
			$options = $options;

			foreach ($groups as $group) {
				$options[$group] = $group;
			}

			return [
				'name' => 'groups[]',
				'type' => 'select',
				'multiple' => true,
				'options' => $options,
				'class' => 'multiselect',
			];
		};
	}
}
