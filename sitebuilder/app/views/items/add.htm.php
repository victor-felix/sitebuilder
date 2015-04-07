<div class="slide-header">
	<div class="grid-4 first"><?= $this->html->link(s('‹ back'), '/categories/index/' . $parent->id, ['class' => 'ui-button large back pop-scene']) ?>
	</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle =  s('Add %s', $item->type()) ?></h1>
		<?= $this->element('common/breadcrumbs', [
			'category' => $parent
		]) ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->items->form(null, $item, [
	'class' => 'form-edit default-form item-form',
	'id' => 'form-add-businessitem'
]) ?>

<?= $this->element('items/form', compact('item')) ?>

<div class="slide-footer">
	<div class="grid-4 first">
		<?= $this->buttons->popScene(s('‹ back'), '/categories/index/' . $parent->id) ?>
	</div>
	<div class="grid-8">
		<?= $this->buttons->submit() ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->items->endform() ?>
