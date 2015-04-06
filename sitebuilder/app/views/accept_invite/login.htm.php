<?php $this->layout = 'login' ?>
<?php $this->pageTitle = s('Log in') ?>

<?php echo $this->form->create(null, array(
	'class' => 'form-register default-form',
	'id' => 'FormLogin'
)) ?>

<fieldset>
	<div class="field-group">
		<div class="form-grid-220 first">
			<?php echo $this->form->input('email', array(
				'label' => s('E-mail'),
				'class' => 'ui-text'
			)) ?>
		</div>
		
		<div class="form-grid-220 first">
			<?php echo $this->form->input('password', array(
				'label' => s('Password'),
				'class' => 'ui-text'
			)) ?>
		</div>
		
		<div class="form-grid-220 first">
			<?php echo $this->form->input('remember', array(
				'label' => s('Remember me'),
				'type' => 'checkbox'
			)) ?>
		</div>
		<div class="form-grid-220 first">
			<?php
				echo $this->html->link(s('Don\'t have account? Click here to register'), "/accept_invite/signup/{$token}", array(
					'class' => 'no-account'
				));
			?>
		</div>
	</div>
</fieldset>

<fieldset class="actions">
	<?php echo $this->form->submit(s('Log in'), array(
		'class' => 'ui-button large',
		'style' => 'float: left'
	)) ?>
	
	 <?php echo $this->html->link(s('Forgot password?'), '/users/forgot_password', array(
		'class' => 'forgot-password'
	)) ?>
</fieldset>

<?php echo $this->form->close() ?>
