<div class="slide-header">
	<div class="grid-4 first"><?= $this->html->link(s('‹ back'), '/categories/edit/' . $category->id, ['class' => 'ui-button large back pop-scene']) ?>
	</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle = e($extension->specification('title')) ?></h1>
		<?= $this->element('common/breadcrumbs', [
			'category' => $category
		]) ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->items->form('/extensions/edit/' . $extension->id(), $extension, [
	'class' => 'form-edit default-form',
	'id' => 'form-edit-businessitem'
]) ?>

<?= $this->element('extensions/form', compact('extension')) ?>

<div class="slide-footer">
	<div class="grid-4 first">
		<?= $this->buttons->popScene(s('‹ back'), '/categories/edit/' . $category->id) ?>
	</div>
	<div class="grid-8">
		<?= $this->buttons->submit() ?>
		<?= $this->buttons->delete(s('Delete extension'), '/extensions/delete/' . $extension->id(), '#delete-confirm') ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->items->endform() ?>

<div id="delete-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?= s('Really want to delete the extension?') ?>
		</p>
		<?= $this->html->link(s('Yes, delete'), '/extensions/delete/' . $extension->id(), [
			'class' => 'ui-button ajax-request go-back highlight'
		]) ?>
		<?= $this->html->link(s('No, I don\'t'), '#', ['class' => 'ui-button']) ?>
	</div>
</div>
