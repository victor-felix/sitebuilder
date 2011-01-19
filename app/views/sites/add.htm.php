<?php echo $this->form->create('/sites/add', array(
    'id' => 'form-register-personal-info',
    'class' => 'form-register',
    'method' => 'file'
)) ?>

<fieldset>
    <h2>informações pessoais</h2>
    <div class="field-group">
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('firstname', array(
                'label' => __('Nome'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220 last">
            <?php echo $this->form->input('lastname', array(
                'label' => __('Sobrenome'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('email', array(
                'label' => __('E-mail'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
            <small>Digite um endereço de e-mail válido. Uma mensagem de ativação da sua conta será enviada para o endereço informado.</small>
        </div>
        
        <div class="form-grid-460">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('email', array(
                'label' => __('E-mail'),
                'type' => 'text',
                'class' => 'ui-text large error',
                'empty' => ''
            )) ?>
            <p class="error">endereço de e-mail inválido </p>
            <small>Digite um endereço de e-mail válido. Uma mensagem de ativação da sua conta será enviada para o endereço informado.</small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => __('Senha'),
                'type' => 'password',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
            <small>A senha deve conter 6 ou mais caracteres.</small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password2', array(
                'label' => __('Confirmação da senha'),
                'type' => 'password',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
    </div>
</fieldset>

<?php echo $this->form->close(__('Avançar'), array(
    'class' => 'ui-button large'
)) ?>


<!--
<?php echo $this->form->create('/sites/add', array(
    'method' => 'file'
)) ?>
    
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
-->