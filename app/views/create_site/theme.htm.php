<?php $this->pageTitle = s('Create a new Site') ?>
<?php $this->selectedTab = 1 ?>


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
		<?php echo $this->html->link(s('‹ back'), '/', array(
	        'class' => 'ui-button large',
	        'style' => ''
	    )) ?>
		<?php echo $this->form->submit(s('next step ›'), array(
			'class' => 'ui-button red larger',
			'style' => 'margin-left: 280px'
		)) ?>
	</fieldset>

<?php echo $this->form->close() ?>
