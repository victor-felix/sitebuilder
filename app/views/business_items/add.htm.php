<h1><?php echo __('Adicionar Novo %s', $type->title) ?></h1>

<?php echo $this->form->create('/business_items/add') ?>

    <?php echo $this->form->input('parent_id', array(
        'label' => __('Categoria'),
        'type' => 'select',
        'options' => $categories
    )) ?>

    <?php foreach($type->fields as $id => $field): ?>
        <?php echo $this->form->input($id, array(
            'label' => __($field['title']),
            'type' => BusinessItemsTypes::$inputTypes[$field['field_type']]
        )) ?>
    <?php endforeach ?>
    
<?php echo $this->form->close(__('Salvar')) ?>