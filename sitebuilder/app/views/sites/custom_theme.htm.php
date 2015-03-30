<?php
	$skins = $theme->skins();
	$custom = true;
?>
<div class="slide-header">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Customize') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/custom_theme', array(
	'id' => 'form-custom-theme',
	'class' => 'form-edit default-form',
	'method' => 'file'
)) ?>

	<?php echo $this->element('skins/form', compact('theme', 'skin', 'skins', 'site', 'custom')) ?>

	<fieldset class="actions">
		<?php echo $this->form->submit(s('Save and Continue'), array(
			'class' => 'ui-button red larger save-continue',
			'name' => 'continue',
			'value' => 1,
		)) ?>
		<?php echo $this->form->submit(s('Save'), array(
			'class' => 'ui-button red larger save',
			'name' => 'continue',
			'value' => 0,
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
