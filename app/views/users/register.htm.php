<?php $this->layout = 'register' ?>
<?php $this->selectedTab = 0 ?>
<?php $this->pageTitle = __('Crie seu Mobi') ?>

<p class="tip-register">
    <strong><?php echo __('Você já tem uma conta no MeuMobi?') ?></strong><br />
    <?php echo __('%s para usar as informações que você já tem.', $this->html->link(__('Faça login aqui'), '/users/login_and_register')) ?>
</p>

<?php echo $this->form->create('/users/register', array(
    'id' => 'form-register-personal-info',
    'class' => 'form-register',
    'object' => $user
)) ?>

<fieldset>
    <h2><?php echo __('informações pessoais') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('firstname', array(
                'label' => __('Nome'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <?php echo $this->form->input('lastname', array(
                'label' => __('Sobrenome'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <?php echo $this->form->input('email', array(
                'label' => __('E-mail'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo __('Digite um endereço de e-mail válido. Uma mensagem de ativação da sua conta será enviada para o endereço informado.') ?></small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => __('Senha'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
            <small><?php echo __('A senha deve conter 6 ou mais caracteres.') ?></small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('confirm_password', array(
                'label' => __('Confirmação da senha'),
                'type' => 'password',
                'class' => 'ui-text'
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