<div class="slide-header">
	<div class="grid-4 first"><?= $this->html->link(s('‹ back'), '/categories', [ 'class' => 'ui-button large back pop-scene' ]) ?>
	</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle = s('Edit Category') ?></h1>
		<?= $this->element('common/breadcrumbs', [
			'category' => $category
		]) ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->form->create(null, [
	'class' => 'form-edit skip-slide default-form',
	'object' => $category,
	'method' => 'file'
]) ?>

<?= $this->element('categories/form', [
	'category' => $category,
	'site' => $site
]) ?>

<div class="slide-footer">
	<div class="grid-4 first">
		<?= $this->buttons->popScene(s('‹ back'), '/categories') ?>
	</div>
	<div class="grid-8">
		<?= $this->buttons->submit() ?>
		<?= $this->buttons->delete(s('Delete all items'), '/categories/delete_all_items/' . $category->id, '#delete-items-confirm') ?>
		<?= $this->buttons->delete(s('Delete category'), '/categories/delete/' . $category->id, '#delete-confirm')  ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->form->close() ?>

<div id="delete-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?= s('Really want to delete the <strong>%s</strong> category?', e($category->title)) ?>
			<br />
			<?= s('This will also delete all items and subcategories') ?>
		</p>
		<?= $this->html->link(s('Yes, delete'), '/categories/delete/' . $category->id, [
			'class' => 'ui-button ajax-request go-back highlight'
		]) ?>
		<?= $this->html->link(s('No, I don\'t'), '#', [ 'class' => 'ui-button' ]) ?>
	</div>
</div>

<div id="delete-items-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?= s('Really want to delete all items from <strong>%s</strong> category?', e($category->title)) ?>
		</p>
		<?= $this->html->link(s('Yes, delete'), '/categories/delete_all_items/' . $category->id, [
			'class' => 'ui-button ajax-request go-back highlight'
		]) ?>
		<?= $this->html->link(s('No, I don\'t'), '#', [ 'class' => 'ui-button' ]) ?>
	</div>
</div>
