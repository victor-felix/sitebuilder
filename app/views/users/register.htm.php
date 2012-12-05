<?php $this->layout = 'register' ?>
<?php $this->selectedTab = 0 ?>
<?php $this->pageTitle = s('Create your Mobi') ?>
<?php $invite_token = isset($invite_token) ? $invite_token : '' ?>

<p class="tip-register">
    <strong><?php echo s('Did you already have a MeuMobi account?') ?></strong><br />
    <?php echo s('%s tu use your existing account', $this->html->link(s('Log in here'), '/users/login_and_register/' . $invite_token)) ?>
</p>

<?php echo $this->form->create('/users/register/' . $invite_token, array(
    'id' => 'form-register-personal-info',
    'class' => 'form-register form-edit',
    'object' => $user
)) ?>

<fieldset>
    <div class="grid-4">
        <div class="tip">
			<h2 class="greater"><?php echo s('Personal information') ?></h2>
		</div>
    </div>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('firstname', array(
                'label' => s('First Name'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <?php echo $this->form->input('lastname', array(
                'label' => s('Last Name'),
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
            <small><?php echo s('Type a valid email address. An activation message should be sent to the informed address') ?></small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => s('Password'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
            <small style="width: 300px;">
            		<?php echo s('Your password needs to be at least 6 characters long.') ?>
					<br>
            		<?php echo s('Tip: to choose a safer password, avoid simple words and use uppercase and lowercasa letters and numbers.') ?>
            </small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('confirm_password', array(
                'label' => s('Confirm your password'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
        </div>
    </div>
    <?php 
        if ($invite_token) {
            echo $this->form->input('invite_token', array(
            		'type' => 'hidden',
            		'value' => $invite_token,
            ));
        } 
    ?>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Continue ›'), array(
        'class' => 'ui-button red large'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
