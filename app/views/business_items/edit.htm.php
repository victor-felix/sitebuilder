<h1><?php echo __('Editar %s', $type->title) ?></h1>

<?php echo $this->form->create('/business_items/edit/' . $business_item->id) ?>

    <?php echo $this->form->input('parent_id', array(
        'label' => __('Categoria'),
        'type' => 'select',
        'options' => $categories,
        'value' => $business_item->parent_id
    )) ?>

    <?php foreach($type->fields as $id => $field): ?>
        <?php echo $this->form->input($id, array(
            'label' => __($field['title']),
            'type' => BusinessItemsTypes::$inputTypes[$field['field_type']],
            'value' => $business_item->values()->{$id},
        )) ?>
    <?php endforeach ?>
    
<?php echo $this->form->close(__('Salvar')) ?>