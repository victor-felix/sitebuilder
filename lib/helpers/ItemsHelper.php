<?php

class ItemsHelper extends Helper {
    protected $types = array(
        'default' => array(
            'type' => 'text',
            'class' => 'ui-text'
        ),
        'string' => array(),
        'richtext' => array(
            'type' => 'textarea',
            'class' => 'ui-textarea markitup'
        )
    );
    protected $item;

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

        $attr = array_merge($defaults, $this->types['default'],
            $this->types[$field->type]);
        $attr['class'] .= ' large';

        return $this->form->input($name, $attr);
    }
}
