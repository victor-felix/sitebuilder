<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Themes') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->create(null, array(
	'id' => 'form-edit-customize',
	'class' => 'form-edit default-form',
	'method' => 'file',
	'object' => $site
)) ?>

	<?php echo $this->element('sites/customize_form', array(
		'action' => 'edit',
		'themes' => $themes,
		'site' => $site
	)) ?>

	<fieldset class="actions">
		<?php echo $this->form->submit(s('Save'), array(
			'class' => 'ui-button red larger'
		)) ?>
	</fieldset>
<?php echo $this->form->close() ?>
<div id="confirm-remove-skin" class="confirm">
	<div class="wrapper">
		<p>
			<?php echo s('Selecting a different color palette will delete your customized one. Are you sure?') ?>
		</p>
		<?php echo $this->html->link(s('Yes, change'), '/skins/delete_custom/' . $site->skin, array(
			'class' => 'ui-button highlight ajax-request'
		)) ?>
		<?php echo $this->html->link(s('No, I don\'t'), '#', array( 'class' => 'ui-button' )) ?>
	</div>
</div>