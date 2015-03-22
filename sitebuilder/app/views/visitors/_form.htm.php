<fieldset>
	<h2><?php echo s('First Name') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('first_name', array(
				'type' => 'text',
				'label' => s('First Name'),
				'class' => 'ui-text'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Last Name') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('last_name', array(
				'type' => 'text',
				'label' => s('Last Name'),
				'class' => 'ui-text'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Email') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('email', array(
				'type' => 'text',
				'label' => s('E-mail address'),
				'class' => 'ui-text'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Groups') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('groups', array(
				'type' => 'text',
				'label' => s('Groups'),
				'class' => 'ui-text'
			)) ?>
			<small><?php echo s('comma separated, eg. Visitors, Editors') ?></small>
		</div>
	</div>
</fieldset>

