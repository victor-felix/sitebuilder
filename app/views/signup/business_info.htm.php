<?php $this->selectedTab = 2 ?>
<?php $this->pageTitle = s('Create your Mobi') ?>

<?php echo $this->form->create(null, array(
	'id' => 'form-register-site-info',
	'class' => 'form-register',
	'object' => $site,
	'method' => 'file'
)) ?>

	<?php echo $this->element('sites/edit_form', array(
		'action' => 'register',
		'site' => $site,
		'countries' => $countries,
		'states' => $states
	)) ?>

	<fieldset class="actions">
		<?php echo $this->form->submit(s('Continue'), array(
			'class' => 'ui-button red large'
		)) ?>
	</fieldset>

<?php echo $this->form->close() ?>
