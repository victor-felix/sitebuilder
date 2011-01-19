<?php echo $this->form->create('/users/register', array(
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
            <?php echo $this->form->input('confirm_password', array(
                'label' => __('Confirmação da senha'),
                'type' => 'password',
                'class' => 'ui-text',
                'empty' => ''
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