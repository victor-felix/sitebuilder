<?php echo $this->form->create('/sites/edit/' . $site->id, array(
    'id' => 'form-register-personal-info',
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
                'class' => 'ui-textarea large'
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
            <?php echo $this->form->input('feed', array(
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

<fieldset>
    <h2>logotipo</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional">Opcional</span>
            <?php echo $this->form->input('logo', array(
                'label' => __('Logotipo'),
                'type' => 'file',
                'class' => 'ui-text large'
            )) ?>
            <small>Para melhor aparência do logotipo no seu site mobi, recomendamos utilizar uma imagem com fundo transparente, no formato GIF ou PNG. Tamanho máximo 500kb.</small>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2>temas</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <small style="margin: 0 0 15px 0">Você pode customizar a aparência de seu site mobi para deixá-lo com a cara de sua empresa. Escolha um dos temas abaixo e depois personalize-o com as cores da sua empresa.</small>
            
            <div class="theme-picker">
                <h3>Escolha um tema</h3>
                <ul>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /><span>Tema 1</span></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /><span>Tema 2</span></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /><span>Tema 3</span></a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <?php echo $this->form->input('theme', array(
                'label' => false,
                'div' => false,
                'type' => 'hidden'
            )) ?>
            
            <div class="skin-picker">
                <h3>Personalize o tema</h3>
                <ul>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                    <li><a href="#"><img src="http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif" alt="blank" /></a></li>
                </ul>
                <div class="clear"></div>
            </div>
            <?php echo $this->form->input('skin', array(
                'label' => false,
                'div' => false,
                'type' => 'hidden'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
<?php echo $this->form->submit(__('Finalizar ›'), array(
    'class' => 'ui-button red large'
)) ?>
</fieldset>

<?php echo $this->form->close() ?>

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