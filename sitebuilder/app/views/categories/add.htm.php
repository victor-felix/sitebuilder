<div class="slide-header">
	<div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
	</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Add Category') ?></h1>
		<?php echo $this->element('common/breadcrumbs', array(
			'category' => $category->parent()
		)) ?>
	</div>
	<div class="clear"></div>
</div>

<?= $this->form->create(null, [
	'class' => 'form-edit skip-slide default-form',
	'object' => $category,
	'method' => 'file'
]) ?>

<?php echo $this->element('categories/form', array(
	'category' => $category,
	'site' => $site
)) ?>

<div class="slide-footer">
	<div class="grid-4 first">
		<?= $this->buttons->popScene(s('‹ back'), '/categories') ?>
	</div>
	<div class="grid-8">
		<?= $this->buttons->submit() ?>
	</div>
	<div class="clear"></div>
</div>
<?= $this->form->close() ?>
