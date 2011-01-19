<?php $this->layout = 'register' ?>
<?php echo $this->form->create('/sites/edit/' . $site->id, array(
    'id' => 'form-register-site-info',
    'class' => 'form-register',
    'method' => 'file',
    'object' => $site
)) ?>

<fieldset>
    <h2>informações do negócio</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <?php echo $this->form->input('title', array(
                'label' => __('Nome da empresa'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('description', array(
                'label' => __('Descrição da empresa'),
                'type' => 'textarea',
                'class' => 'ui-textarea large',
                'maxlenght' => 500
            )) ?>
            <small>Forneça uma breve descrição sobre a empresa e suas atividades. Máximo de 500 caracteres.</small>
        </div>
        
        <div class="form-grid-460 first">
            <div class="site-mobile-url">
            <div class="input text">
                <label for="FormDomain">Endereço do site mobile</label>
                <p class="meumobi-url">
                    <span>http://</span>
                    <?php echo $this->form->input('domain', array(
                    'label' => false,
                    'div' => false,
                    'type' => 'text',
                    'class' => 'ui-text'
                    )) ?><span>.meumobi.com</span>
                </p>
                <div class="clear"></div>
            </div>
            </div>
            <small>Escolha o seu endereço com cuidado, você não poderá alterá-lo posteriormente.</small>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand">fonte de notícias rss <span>opcional</span></a>
<fieldset style="display:none">
    <h2>fonte de notícias rss</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('feed_url', array(
                'label' => __('Endereço do feed RSS'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small>RSS é um formato de arquivo disponibilizado na maioria dos sites e blogs que permite que um site ou aplicativo externo acesse suas notícias. Você pode utilizar o RSS do seu site para alimentar a seção de notícias do seu site mobi.</small>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand">localização <span>opcional</span></a>
<fieldset style="display:none">
    <h2>localização</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('street', array(
                'label' => __('Endereço'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('street', array(
                'label' => __('Número'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('complement', array(
                'label' => __('Complemento'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('zone', array(
                'label' => __('Bairro'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('city', array(
                'label' => __('Cidade'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('state', array(
                'label' => __('Estado'),
                'type' => 'select',
                'options' => array('RJ'=>'Rio de Janeiro', 'SP'=>'São Paulo'),
                'class' => 'ui-select'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('zip', array(
                'label' => __('CEP'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('country', array(
                'label' => __('País'),
                'type' => 'select',
                'options' => array('BR'=>'Brasil', 'PT'=>'Portugal'),
                'class' => 'ui-select'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand">informações de contato <span>opcional</span></a>
<fieldset style="display:none">
    <h2>informações de contato</h2>
    <div class="field-group">
        
        <div class="form-grid-220 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('phone', array(
                'label' => __('Telefone comercial'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
            <small>Ex.: (00) 0000-0000</small>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('email', array(
                'label' => __('E-mail comercial'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand">horários de funcionamento <span>opcional</span></a>
<fieldset style="display:none">
    <h2>horário de funcionamento</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('timetable', array(
                'label' => __('Horários de funcionamento'),
                'type' => 'textarea',
                'class' => 'ui-textarea large'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand">links na web <span>opcional</span></a>
<fieldset style="display:none">
    <h2>links na web</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('facebook', array(
                'label' => __('Página no Facebook'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('twitter', array(
                'label' => __('Página no Twitter'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('website', array(
                'label' => __('Endereço do website atual'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Avançar ›'), array(
        'class' => 'ui-button red large'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>