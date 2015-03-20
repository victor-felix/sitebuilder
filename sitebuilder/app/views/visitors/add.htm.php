<div class="page-heading">
	<div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/visitors', array( 'class' => 'ui-button large back pop-scene' )) ?>
	</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Add Visitor') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->create('/skins/add/' . $theme->id(), array(
	'id' => 'form-custom-theme',
	'class' => 'form-edit default-form',
	'method' => 'file'
)) ?>

<?php echo $this->element('visitors/form', array(
	'action' => null,
	'visitor' => $visitor,
	'site' => $site
)) ?>

<fieldset class="actions">
	<?php echo $this->form->submit(s('Save'), array(
		'class' => 'ui-button red larger save',
		'name' => 'continue',
		'value' => 0,
	)) ?>
</fieldset>


