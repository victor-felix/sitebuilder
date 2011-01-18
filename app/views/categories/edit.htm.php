<h1><?php echo __('Editar Categoria %s', $category->title) ?></h1>

<?php echo $this->form->create('/categories/edit/' . $category->id) ?>

    <?php echo $this->form->input('title', array(
        'label' => __('Título'),
        'value' => $category->title
    )) ?>

    <!-- TODO parent category -->    
    
<?php echo $this->form->close(__('Salvar')) ?>