<?php $this->layout = "login"; ?>

<?php echo $this->form->create('/users/login', array(
    'class' => 'form-register',
    'id' => 'FormLogin'
)) ?>
<fieldset>
    <h2>login</h2>
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
    </div>
</fieldset>
<fieldset class="actions">
    <?php echo $this->form->submit('Login', array(
        'class' => 'ui-button red large',
        'style' => 'margin-right: 415px'
    ))?>
</fieldset>
<?php echo $this->form->close() ?>