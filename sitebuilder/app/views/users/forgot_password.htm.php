<?php $this->layout = 'login' ?>
<?php $this->pageTitle = s('Retrieve your Password') ?>

<?php echo $this->form->create('/users/forgot_password', array(
    'class' => 'form-register default-form',
    'id' => 'FormPassword',
    'object' => $user
)) ?>

<fieldset>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('email', array(
                'label' => s('E-mail'),
                'class' => 'ui-text'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Retrieve your Password'), array(
        'class' => 'ui-button large',
        'style' => 'float:none;'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
