<?php $this->layout = 'login' ?>
<?php $this->pageTitle = __('Recuperar sua senha do MeuMobi') ?>

<?php echo $this->form->create('/users/forgot_password', array(
    'class' => 'form-register',
    'id' => 'FormLogin'
)) ?>

<fieldset>
    <h2><?php echo __('recuperar senha') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('email', array(
                'label' => __('E-Mail'),
                'class' => 'ui-text'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Recuperar Senha'), array(
        'class' => 'ui-button red large',
        'style' => 'margin-right: 415px'
    ))?>
</fieldset>

<?php echo $this->form->close() ?>