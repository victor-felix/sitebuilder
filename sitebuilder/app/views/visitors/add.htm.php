<div class="page-heading">
	<div class="grid-4 first"><?= $this->html->link(s('‹ back'), '/visitors', [ 'class' => 'ui-button large back pop-scene']) ?>
	</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle = s('Add Visitor') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?= $this->form->create('/visitors/add/', [
	'id' => 'form-custom-theme',
	'class' => 'form-edit default-form',
	'method' => 'file'
]) ?>

<?= $this->element('visitors/form', [
	'action' => null,
	'visitor' => $visitor,
	'site' => $site
]) ?>

<fieldset class="actions">
	<?= $this->form->submit(s('Save'), [
		'class' => 'ui-button red larger save',
		'name' => 'continue',
		'value' => 0,
	]) ?>
</fieldset>
