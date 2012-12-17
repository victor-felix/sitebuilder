<?php $this->pageTitle = s('Create a new Site') ?>

<?php echo $this->form->create(null, array(
	'id' => 'form-register-customize',
	'class' => 'form-register default-form',
	'method' => 'file',
	'object' => $site
)) ?>

	<?php echo $this->element('sites/customize_form', array(
		'action' => 'register',
		'themes' => $themes,
		'site' => $site
	)) ?>

	<fieldset class="actions">
	<?php echo $this->form->submit(s('Save ›'), array(
		'class' => 'ui-button red larger'
	)) ?>
	</fieldset>

<?php echo $this->form->close() ?>
