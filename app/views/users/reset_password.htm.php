<?php $this->layout = 'login' ?>
<?php $this->pageTitle = __('Redefinir sua senha do MeuMobi') ?>

<?php echo $this->form->create('', array(
    'class' => 'form-register',
    'id' => 'FormLogin',
    'object' => $user
)) ?>

<fieldset>
    <h2><?php echo __('redefinir senha') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => __('Nova Senha'),
                'class' => 'ui-text',
                'value' => ''
            )) ?>
        </div>

        <div class="form-grid-220 first">
            <?php echo $this->form->input('confirm_password', array(
                'label' => __('Confirmar Nova Senha'),
                'class' => 'ui-text',
                'type' => 'password'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Redefinir Senha'), array(
        'class' => 'ui-button red large',
        'style' => 'margin-right: 415px'
    ))?>
</fieldset>

<?php echo $this->form->close() ?>