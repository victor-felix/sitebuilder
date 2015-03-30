<div class="slide-header">
	<div class="grid-4 first">
		<?= $this->html->link(s('‹ back'), '/visitors', [ 'class' => 'ui-button large back pop-scene']) ?>
	</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle = s('Edit Visitor') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?= $this->form->create('/visitors/edit/' . $visitor->id(), [
	'id' => 'form-custom-theme',
	'class' => 'form-edit default-form item-form',
]) ?>

<?= $this->element('visitors/form', [
	'action' => null,
	'visitor' => $visitor,
	'site' => $site
]) ?>

<fieldset class="actions">
  <?= $this->html->link(s('‹ back'), '/visitors', ['class' => 'ui-button large back pop-scene']) ?>
	<?= $this->form->submit(s('Save'), [
		'class' => 'ui-button red larger',
		'name' => 'continue',
		'value' => 0,
	]) ?>
		<?= $this->html->link('<i class="icons fa fa-trash"></i> ' . s('Remove visitor'),
		'/visitors/delete/' . $visitor->id(), [
			'class' => 'ui-button delete has-confirm',
			'data-confirm' => '#delete-confirm'
	]) ?>
	<?= $this->html->link(s('Reset password'), '/visitors/reset/' . $visitor->id(), ['class' => 'ui-button reset']) ?>
</fieldset>
<?= $this->form->close() ?>

<div id="delete-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?= s('Really want to delete <strong>%s</strong>?', e($visitor->email())) ?>
		</p>
		<?= $this->html->link(s('Yes, delete'), '/visitors/delete/' . $visitor->id(), [
			'class' => 'ui-button ajax-request go-back highlight'
		]) ?>
		<?= $this->html->link(s('No, I don\'t'), '#', ['class' => 'ui-button']) ?>
	</div>
</div>
