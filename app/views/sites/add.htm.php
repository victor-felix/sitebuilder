<h1><?php echo __('Adicionar Novo Site') ?></h1>

<?php echo $this->form->create('/sites/add') ?>
    
    <?php echo $this->form->input('segment', array(
        'label' => __('Segmento'),
        'type' => 'select',
        'options' => $segments,
        'empty' => ''
    )) ?>
    
    <?php echo $this->form->input('theme', array(
        'label' => __('Tema'),
        'type' => 'select',
        'empty' => ''
    )) ?>
    
    <?php echo $this->form->input('title', array(
        'label' => __('Título')
    )) ?>
    
    <?php echo $this->form->input('domain', array(
        'label' => __('Endereço')
    )) ?>
    
    <?php echo $this->form->input('description', array(
        'label' => __('Descrição'),
        'type' => 'textarea'
    )) ?>
    
    <?php echo $this->form->input('address', array(
        'label' => __('Endereço'),
        'type' => 'textarea'
    )) ?>
    
    <?php echo $this->form->input('email', array(
        'label' => __('E-Mail')
    )) ?>
    
    <?php echo $this->form->input('phone', array(
        'label' => __('Telefone'),
    )) ?>
    
    <?php echo $this->form->input('website', array(
        'label' => __('Website')
    )) ?>
    
    <?php echo $this->form->input('facebook', array(
        'label' => __('Facebook')
    )) ?>
    
    <?php echo $this->form->input('twitter', array(
        'label' => __('Twitter')
    )) ?>
    
    <?php echo $this->form->input('logo', array(
        'label' => __('Logo'),
        'type' => 'file'
    )) ?>
    
    <?php echo $this->form->input('feed', array(
        'label' => __('Feed')
    )) ?>
    
<?php echo $this->form->close(__('Salvar')) ?>