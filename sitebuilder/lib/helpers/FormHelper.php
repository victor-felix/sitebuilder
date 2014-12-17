<?php

class FormHelper extends Helper {
    protected $stack = array();

    public function create($action = null, $options = array()) {
        $options += array(
            'method' => 'post',
            'action' => Mapper::url($action)
        );

        $object = array_unset($options, 'object');
        array_push($this->stack, $object);

        if($options['method'] == 'file'):
            $options['method'] = 'post';
            $options['enctype'] = 'multipart/form-data';
        endif;

        return $this->html->openTag('form', $options);
    }

    public function close($submit = null, $attributes = array()) {
        array_pop($this->stack);
        $form = $this->html->closeTag('form');

        if(!is_null($submit)):
            $form = $this->submit($submit, $attributes) . $form;
        endif;

        return $form;
    }

    public function submit($text, $attributes = array()) {
        $attributes += array(
            'type' => 'submit',
            'tag' => 'button'
        );
        switch(array_unset($attributes, 'tag')):
            case 'image':
                $attributes['alt'] = $text;
                $attributes['type'] = 'image';
                $attributes['src'] = $this->assets->image($attributes['src']);
            case 'input':
                $attributes['value'] = $text;
                return $this->html->tag('input', '', $attributes, true);
            default:
                return $this->html->tag('button', $text, $attributes);
        endswitch;
    }

    public function select($name, $options = array()) {
        $options += array(
            'name' => $name,
            'options' => array(),
            'value' => null,
            'empty' => false
        );

        $select_options = array_unset($options, 'options');
        $select_value = array_unset($options, 'value');

        if(($empty = array_unset($options, 'empty')) !== false):
            $keys = array_keys($select_options);
            if(is_array($empty)):
                $empty_keys = array_keys($empty);
                $key = $empty_keys[0];
                $values = array_merge($empty, $select_options);
            else:
                $key = $empty;
                $values = array_merge(array($empty), $select_options);
            endif;
            array_unshift($keys, $key);
            $select_options = array_combine($keys, $values);
        endif;

        $content = '';
        foreach($select_options as $key => $value):
            $option = array('value' => $key);
						if($select_value instanceof \lithium\data\collection\DocumentArray) {
								if($select_value->first(function($i) use ($key) {
									return $i == $key;
								})) {
										$option['selected'] = true;
								}
						}
						else if($select_value instanceof \lithium\data\entity\Document) {
						}
						else {
								if((string) $key === (string) $select_value):
										$option['selected'] = true;
								endif;
						}
            $content .= $this->html->tag('option', $value, $option);
        endforeach;

        return $this->html->tag('select', $content, $options);
    }

    public function radio($name, $options = array()) {
        $options += array(
            'options' => array(),
            'value' => null,
            'legend' => Inflector::camelize($name)
        );
        $radio_options = array_unset($options, 'options');
        $radio_value = array_unset($options, 'value');
        if($legend = array_unset($options, 'legend')):
            $content = $this->html->tag('legend', $legend);
        endif;

        $content = '';
        foreach($radio_options as $key => $value):
            $radio_attr = array(
                'type' => 'radio',
                'value' => $key,
                'id' => Inflector::camelize($name . '_' . $key),
                'name' => $name
            );
            if((string) $key === (string) $radio_value):
                $radio_attr['checked'] = true;
            endif;
            $for = array('for' => $radio_attr['id']);
            $content .= $this->html->tag('input', '', $radio_attr, true);
            $content .= $this->html->tag('label', $value, $for);
        endforeach;

        return $this->html->tag('fieldset', $content);
    }

    public function input($name, $options = array()) {
        $options += array(
            'name' => $name,
            'type' => 'text',
            'id' => 'Form' . Inflector::camelize($name),
            'label' => Inflector::humanize($name),
            'div' => true,
            'value' => null,
            'class' => ''
        );

        if(is_null($options['value'])) {
            if(!in_array($options['type'], array('password', 'checkbox'))) {
                $options['value'] = $this->value($name);
            }
            else if($options['type'] == 'checkbox') {
                $options['value'] = 1;
            }
        }

        if($options['type'] == 'checkbox' && $this->value($name) == $options['value']) {
            $options['checked'] = true;
        }

        if($this->error($name)) {
            $options['class'] .= ' error';
        }

        $label = array_unset($options, 'label');
        $div = array_unset($options, 'div');
        $type = $options['type'];

        switch($options['type']):
            case 'select':
                unset($options['type']);
                $input = $this->select($name, $options);
                break;
            case 'radio':
                $options['legend'] = $label;
                $label = false;
                $input = $this->radio($name, $options);
                break;
            case 'date':
                $input = $this->date($name, $options);
                $options['id'] = $options['id'] . 'D';
                break;
            case 'textarea':
                unset($options['type']);
                $value = Sanitize::html(array_unset($options, 'value'));
                $input = $this->html->tag('textarea', $value, $options);
                break;
            case 'hidden':
                $div = $label = false;
            default:
                if($name == 'password'):
                    $options['type'] = 'password';
                endif;
                $options['value'] = Sanitize::html($options['value']);
                $input = $this->html->tag('input', '', $options, true);
        endswitch;

        if($type == 'checkbox'):
             $input = $this->input($name, array(
                 'type' => 'hidden',
                 'value' => '0',
                 'id' => false
             )) . $input;
         endif;

        if($label):
            $for = array('for' => $options['id']);
            $input = $this->html->tag('label', $label, $for) . $input;
        endif;

        if($div):
            $input = $this->div($div, $input, $type);
        endif;

        if($error = $this->error($name)) {
            $input .= $this->html->tag('p', $error, array(
                'class' => 'error'
            ));
        }

        return $input;
    }
    protected function div($class, $content, $type) {
        $attr = array(
            'class' => 'input ' . $type
        );

        if(is_array($class)):
            $attr = $class + $attr;
        elseif(is_string($class)):
            $attr['class'] .= ' ' . $class;
        endif;

        return $this->html->tag('div', $content, $attr);
    }

    protected function model() {
        $object = end($this->stack);

        if(is_object($object)) {
            return $object;
        }

        return false;
    }

	protected function value($name)
	{
		$value = '';
		if ($model = $this->model()) {
			if ($model->hasAttribute($name)) {
				$value = $model->{$name};
			} else if ($model->hasGetter($name)) {
				$value = $model->{$name}();
			}

			if ($value instanceof MongoDate) {
				$timezone = date_default_timezone_get();
				date_default_timezone_set($this->view->controller->getCurrentSite()->timezoneId());
				$value = date('Y-m-d\TH:i', $value->sec);
				date_default_timezone_set($timezone);
			}
		}

		return $value;
	}

    protected function error($name) {
        if($model = $this->model()):
            $errors = $this->model()->errors();
            if(array_key_exists($name, $errors)) {
                return is_array($errors[$name]) ? reset($errors[$name]) : $errors[$name];
            }
        endif;

        return '';
    }
}
