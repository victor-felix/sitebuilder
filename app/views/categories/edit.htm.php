<h1><?php echo __('Editar Categoria %s', $category->title) ?></h1>

<?php echo $this->form->create('/categories/edit/' . $category->id) ?>

    <?php echo $this->form->input('title', array(
        'label' => __('Título'),
        'value' => $category->title
    )) ?>

    <?php echo $this->form->input('parent_id', array(
        'label' => __('Pai'),
        'type' => 'select',
        'options' => $parents,
        'value' => $category->parent_id
    )) ?>
    
<?php echo $this->form->close(__('Salvar')) ?>