<h1><?php echo __('Editar o site %s', $site->title) ?></h1>

<?php echo $this->form->create('/sites/edit/' . $site->id) ?>
    
    <?php echo $this->form->input('theme', array(
        'label' => __('Tema'),
        'type' => 'select',
        'options' => $themes,
        'value' => $site->theme
    )) ?>
    
    <?php echo $this->form->input('title', array(
        'label' => __('Título'),
        'value' => $site->title
    )) ?>
    
    <?php echo $this->form->input('domain', array(
        'label' => __('Domínio'),
        'value' => $site->domain
    )) ?>
    
    <?php echo $this->form->input('description', array(
        'label' => 'Descrição',
        'type' => 'textarea',
        'value' => $site->description
    )) ?>
    
    <?php echo $this->form->input('address', array(
        'label' => __('Endereço'),
        'type' => 'textarea',
        'value' => $site->address
    )) ?>
    
    <?php echo $this->form->input('email', array(
        'label' => __('E-Mail'),
        'value' => $site->email
    )) ?>
    
    <?php echo $this->form->input('phone', array(
        'label' => __('Telefone'),
        'value' => $site->phone
    )) ?>
    
    <?php echo $this->form->input('website', array(
        'label' => __('Website'),
        'value' => $site->website
    )) ?>
    
    <?php echo $this->form->input('facebook', array(
        'label' => __('Facebook'),
        'value' => $site->facebook
    )) ?>
    
    <?php echo $this->form->input('twitter', array(
        'label' => __('Twitter'),
        'value' => $site->twitter
    )) ?>
    
    <?php echo $this->form->input('logo', array(
        'label' => __('Logo'),
        'type' => 'file'
    )) ?>
    
    <?php echo $this->form->input('feed', array(
        'label' => __('Feed'),
        'value' => $site->feed()->link
    )) ?>
    
<?php echo $this->form->close(__('Salvar')) ?>