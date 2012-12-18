<?php $this->selectedTab = 2 ?>
<?php $this->pageTitle = s('Create your Mobi') ?>

<?php echo $this->form->create(null, array(
	'id' => 'form-register-site-info',
	'class' => 'form-register default-form',
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
		<?php echo $this->html->link(s('‹ back'), '/signup/theme', array(
	        'class' => 'ui-button large',
	        'style' => ''
	    )) ?>
		<?php echo $this->form->submit(s('finish ›'), array(
			'class' => 'ui-button red larger',
			'style' => 'margin-left: 215px'
		)) ?>
	</fieldset>

<?php echo $this->form->close() ?>
