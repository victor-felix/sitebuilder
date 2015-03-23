<fieldset>
	<h2><?= s('common settings') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('first_name', [
				'type' => 'text',
				'label' => s('First Name'),
				'class' => 'ui-text large',
				'value' => $visitor->firstName()
			]) ?>
		</div>

		<div class="form-grid-460 first">
			<?= $this->form->input('last_name', [
				'type' => 'text',
				'label' => s('Last Name'),
				'class' => 'ui-text large',
				'value' => $visitor->lastName()
			]) ?>
		</div>

		<div class="form-grid-460 first">
			<?= $this->form->input('email', [
				'type' => 'text',
				'label' => s('E-mail address'),
				'class' => 'ui-text large',
				'value' => $visitor->email()
			]) ?>
		</div>

		<div class="form-grid-460 first">
			<?= $this->form->input('groups', [
				'type' => 'text',
				'label' => s('Groups'),
				'class' => 'ui-text large',
				'value' => implode($visitor->groups(), ', ')
			]) ?>
			<small><?= s('comma separated, eg. Visitors, Editors') ?></small>
		</div>
</fieldset>
