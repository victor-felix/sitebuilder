<h1><?php echo __('Adicionar Nova Categoria') ?></h1>

<?php echo $this->form->create('/categories/add') ?>

    <?php echo $this->form->input('title', array(
        'label' => __('Título')
    )) ?>

    <?php echo $this->form->input('parent_id', array(
        'label' => __('Pai'),
        'type' => 'select',
        'options' => $parents
    )) ?>
    
<?php echo $this->form->close(__('Salvar')) ?>