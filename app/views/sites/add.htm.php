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
        
        <div class="form-grid-220">
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

<a href="#" class="fieldset-expand">informações do negócio</a>

<fieldset>
    <h2>informações do negócio</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <?php echo $this->form->input('title', array(
                'label' => __('Nome da empresa'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('description', array(
                'label' => __('Descrição da empresa'),
                'type' => 'textarea',
                'class' => 'ui-textarea large',
                'empty' => '',
                'maxlenght' => 500
            )) ?>
            <small>Forneça uma breve descrição sobre a empresa e suas atividades. Máximo de 500 caracteres.</small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('domain', array(
                'label' => __('Endereço do site mobile'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>fonte de notícias rss</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('feed', array(
                'label' => __('Endereço do feed RSS'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
            <small>RSS é um formato de arquivo disponibilizado na maioria dos sites e blogs que permite que um site ou aplicativo externo acesse suas notícias. Você pode utilizar o RSS do seu site para alimentar a seção de notícias do seu site mobi.</small>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>localização</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('street', array(
                'label' => __('Endereço'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('street', array(
                'label' => __('Número'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('complement', array(
                'label' => __('Complemento'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('zone', array(
                'label' => __('Bairro'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('city', array(
                'label' => __('Cidade'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('state', array(
                'label' => __('Estado'),
                'type' => 'select',
                'options' => array('RJ'=>'Rio de Janeiro', 'SP'=>'São Paulo'),
                'class' => 'ui-select',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('zip', array(
                'label' => __('CEP'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('country', array(
                'label' => __('País'),
                'type' => 'select',
                'options' => array('BR'=>'Brasil', 'PT'=>'Portugal'),
                'class' => 'ui-select',
                'empty' => ''
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>informações de contato</h2>
    <div class="field-group">
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('phone', array(
                'label' => __('Telefone comercial'),
                'type' => 'text',
                'class' => 'ui-text',
                'empty' => ''
            )) ?>
            <small>Ex.: (00) 0000-0000</small>
        </div>
        
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('email', array(
                'label' => __('E-mail comercial'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>horário de funcionamento</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('timetable', array(
                'label' => __('Horários de funcionamento'),
                'type' => 'textarea',
                'class' => 'ui-textarea large',
                'empty' => ''
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>links na web</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('facebook', array(
                'label' => __('Página no Facebook'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('twitter', array(
                'label' => __('Página no Twitter'),
                'type' => 'text',
                'class' => 'ui-text large',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('website', array(
                'label' => __('Endereço do website atual'),
                'type' => 'text',
                'class' => 'ui-text large',
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