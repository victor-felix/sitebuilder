<fieldset>
    <h2><?php echo __('informações do negócio') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <?php echo $this->form->input('title', array(
                'label' => __('Nome da empresa'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('description', array(
                'label' => __('Descrição da empresa'),
                'type' => 'textarea',
                'class' => 'ui-textarea large',
                'maxlenght' => 500
            )) ?>
            <small><?php echo __('Forneça uma breve descrição sobre a empresa e suas atividades. Máximo de 500 caracteres.') ?></small>
        </div>
        
        <div class="form-grid-460 first">
            <div class="site-mobile-url">
            <div class="input text">
                <label for="FormDomain"><?php echo __('Endereço do site mobile') ?></label>
                <p class="meumobi-url">
                    <span>http://</span>
                    <?php echo $this->form->input('domain', array(
                        'label' => false,
                        'div' => false,
                        'type' => 'text',
                        'class' => 'ui-text' . ($action == 'edit' ? ' disabled' : ''),
                        'disabled' => $action == 'edit'
                    )) ?><span>.meumobi.com</span>
                </p>
                <div class="clear"></div>
            </div>
            </div>
            <?php if($action == 'register'): ?>
                <small><?php echo __('Escolha o seu endereço com cuidado, você não poderá alterá-lo posteriormente.') ?></small>
            <?php else: ?>
                <small><?php echo __('Você não pode alterar o endereço de seu site mobi.') ?></small>
            <?php endif ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo __('fonte de notícias rss') ?> <span><?php echo __('opcional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo __('fonte de notícias rss') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('feed_url', array(
                'label' => __('Endereço do feed RSS'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo __('RSS é um formato de arquivo disponibilizado na maioria dos sites e blogs que permite que um site ou aplicativo externo acesse suas notícias. Você pode utilizar o RSS do seu site para alimentar a seção de notícias do seu site mobi.') ?></small>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo __('localização') ?> <span><?php echo __('opcional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo __('localização') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('street', array(
                'label' => __('Endereço'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('number', array(
                'label' => __('Número'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('complement', array(
                'label' => __('Complemento'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('zone', array(
                'label' => __('Bairro'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('city', array(
                'label' => __('Cidade'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('state', array(
                'label' => __('Estado'),
                'type' => 'select',
                'options' => Config::read('States'),
                'class' => 'ui-select',
                'empty' => ''
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('zip', array(
                'label' => __('CEP'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('country', array(
                'label' => __('País'),
                'type' => 'select',
                'options' => Config::read('Countries'),
                'class' => 'ui-select'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo __('informações de contato') ?> <span><?php echo __('opcional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo __('informações de contato') ?></h2>
    <div class="field-group">
        
        <div class="form-grid-220 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('phone', array(
                'label' => __('Telefone comercial'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
            <small><?php echo __('Ex.: (00) 0000-0000') ?></small>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('email', array(
                'label' => __('E-mail comercial'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo __('horários de funcionamento') ?> <span><?php echo __('opcional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo __('horário de funcionamento') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('timetable', array(
                'label' => __('Horários de funcionamento'),
                'type' => 'textarea',
                'class' => 'ui-textarea large'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo __('links na web') ?> <span><?php echo __('opcional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo __('links na web') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('facebook', array(
                'label' => __('Página no Facebook'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo __('Ex: http://www.facebook.com/seuusuario/') ?></small>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('twitter', array(
                'label' => __('Página no Twitter'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo __('Ex: http://www.twitter.com/seuusuario/') ?></small>
        </div>
        
        <div class="form-grid-460 first">
            <span class="optional"><?php echo __('Opcional') ?></span>
            <?php echo $this->form->input('website', array(
                'label' => __('Endereço do website atual'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo __('Ex: http://www.seusite.com.br/') ?></small>
        </div>
    </div>
</fieldset>