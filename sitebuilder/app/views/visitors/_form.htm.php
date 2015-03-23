<fieldset>
	<h2><?= s('First Name') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('first_name', [
				'type' => 'text',
				'label' => s('First Name'),
				'class' => 'ui-text'
			]) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?= s('Last Name') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('last_name', [
				'type' => 'text',
				'label' => s('Last Name'),
				'class' => 'ui-text'
			]) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?= s('Email') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('email', [
				'type' => 'text',
				'label' => s('E-mail address'),
				'class' => 'ui-text'
			]) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?= s('Password') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('password', [
				'type' => 'password',
				'label' => s('Password'),
				'class' => 'ui-text'
			]) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?= s('Groups') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('groups', [
				'type' => 'text',
				'label' => s('Groups'),
				'class' => 'ui-text'
			]) ?>
			<small><?= s('comma separated, eg. Visitors, Editors') ?></small>
		</div>
	</div>
</fieldset>

