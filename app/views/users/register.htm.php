<?php $this->layout = 'register' ?>
<?php $this->selectedTab = 0 ?>
<?php $this->pageTitle = s('Create your Mobi') ?>

<p class="tip-register">
    <strong><?php echo s('Did you already have a MeuMobi account?') ?></strong><br />
    <?php echo s('%s tu use your existing account', $this->html->link(s('Log in here'), '/users/login_and_register')) ?>
</p>

<?php echo $this->form->create('/users/register', array(
    'id' => 'form-register-personal-info',
    'class' => 'form-register',
    'object' => $user
)) ?>

<fieldset>
    <h2><?php echo s('Personal details') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('firstname', array(
                'label' => s('Firstname'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <?php echo $this->form->input('lastname', array(
                'label' => s('Lastname'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <?php echo $this->form->input('email', array(
                'label' => s('E-mail'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('Type a valid E-mail address.') ?><?php echo s('An activation message should be sent') ?></small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => s('Password'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
            <small><?php echo s('The password should contain at least 6 characters.') ?></small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('confirm_password', array(
                'label' => s('Confirm your password'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Continue ›'), array(
        'class' => 'ui-button red large'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
