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
		<?php
			$groups = $site->availableVisitorsGroups();
			echo $this->form->input('groups[]', [
				'type' => 'select',
				'label' => s('Groups'),
				'multiple' => true,
				'data-allow-add' => true,
				'class' => 'multiselect large',
				'id' => 'FormGroups',
				'options' => array_combine($groups, $groups),
				'value' => $visitor->groups()
			]) ?>
		</div>
	<?php if (!$visitor->id()): ?>
		<div class="form-grid-460 first">
			<?= $this->form->input('default_password', [
				'type' => 'checkbox',
				'label' => s('Use default password: "%s"', Inflector::slug($site->title, '')),
				'value' => 1
			]) ?>
		</div>
	<?php endif ?>
	</div>
</fieldset>
