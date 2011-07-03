<?php $this->layout = 'login' ?>
<?php $this->pageTitle = s('Retrieve your Password') ?>

<?php echo $this->form->create('/users/forgot_password', array(
    'class' => 'form-register',
    'id' => 'FormLogin',
    'object' => $user
)) ?>

<fieldset>
    <h2><?php echo s('Retrieve your Password') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('email', array(
                'label' => s('E-Mail'),
                'class' => 'ui-text'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Retrieve your Password'), array(
        'class' => 'ui-button red large',
        'style' => 'margin-right: 415px'
    ))?>
</fieldset>

<?php echo $this->form->close() ?>