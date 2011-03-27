<?php $this->layout = 'login' ?>
<?php $this->pageTitle = __('Login em MeuMobi') ?>

<?php echo $this->form->create('/users/login', array(
    'class' => 'form-register',
    'id' => 'FormLogin'
)) ?>

<fieldset>
    <h2><?php echo __('login') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('email', array(
                'label' => __('E-Mail'),
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => __('Senha'),
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('remember', array(
                'label' => false,
                'type' => 'checkbox'
            )) ?>
            <label for="FormRemember" class="checkbox"><?php echo __('Manter conectado') ?></label>
        </div>

        <div class="form-grid-220 first">
            <?php echo $this->html->link('Esqueceu sua senha?', '/users/forgot_password', array(
                'class' => 'forgot-password'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Login'), array(
        'class' => 'ui-button red large',
        'style' => 'margin-right: 415px'
    ))?>
</fieldset>

<?php echo $this->form->close() ?>