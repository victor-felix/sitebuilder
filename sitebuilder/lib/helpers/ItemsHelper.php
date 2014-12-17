<?php
use meumobi\sitebuilder\repositories\VisitorsRepository;

class ItemsHelper extends Helper
{
	protected $types = array(
		'default' => array(
			'type' => 'text',
			'class' => 'ui-text'
		),
		'string' => array(),
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
		'datetime' => array(
			'type' => 'datetime-local',
			'class' => 'ui-text'
		)
	);
	protected $item;

	public function __construct($view) {
		parent::__construct($view);
		$siteId = $view->controller->getCurrentSite()->id;
		$this->types['related'] = function($type) use ($siteId) {
			$classname = '\app\models\items\\' . $type[1];
			$conditions = array(
				'type' => Inflector::underscore($type[1]),
				'site_id' => $siteId
			);
			$items = $classname::find('all',
				array(
					'conditions' => $conditions,
					'order' => 'title'
				));
			$options = array();
			foreach ($items as $item) {
				$options[$item->id()] = $item->title;
			}
			return array(
				'name' => 'related[]',
				'type' => 'select',
				'multiple' => true,
				'options' => $options,
				'class' => 'chosen'
			);
		};
		$this->addGroupsField($siteId);
	}

	public function form($url, $item, $attr) {
		$this->item = $item;
		$attr += array(
			'method' => 'file',
			'object' => $item
		);
		return $this->form->create($url, $attr);
	}

	public function endform() {
		return $this->form->close();
	}

	public function input($name) {
		$field = $this->item->field($name);
		$defaults = array(
			'label' => $field->title
		);
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

	protected function addGroupsField($siteId) {
		$this->types['groups'] = function($type) use ($siteId) {
			$repository = new VisitorsRepository();
			$groups = $repository->findAvailableGroupsBySite($siteId);
			$options = array();
			foreach ($groups as $group) {
				$options[$group] = $group;//set the group name as value
			}
			return array(
				'name' => 'groups[]',
				'type' => 'select',
				'multiple' => true,
				'options' => $options,
				'class' => 'chosen'
			);
		};
	}
}
