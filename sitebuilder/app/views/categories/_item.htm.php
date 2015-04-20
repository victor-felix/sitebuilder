<li class="level-<?= $level ?>" data-catid="<?= $category->id ?>" data-parentid="<?= $category->parent_id ?>">
	<?php if($level == 1): ?>
		<?= $this->html->link('', '/categories/add/' . $category->id, array(
			'class' => 'ui-button ui-button-add highlight push-scene'
		)) ?>
	<?php endif ?>

	<span class="title edit-in-place" data-saveurl="/categories/edit/<?= $category->id ?>" title="<?= s('click to edit') ?>">
		<?= e($category->title) ?>
	</span>

	<div class="controls">
		<?= $this->buttons->rowMoveDown('/categories/movedown/' . $category->id) ?>
		<?= $this->buttons->rowMoveUp('/categories/moveup/' . $category->id) ?>

		<?php if(!$category->hasFeed()): ?>
			<?= $this->html->link(s('add item'), '/items/add/' . $category->id, array(
				'class' => 'ui-button highlight push-scene'
			)) ?>

			<?= $this->html->link(s('manage items'), '/items/index/' . $category->id, array(
				'class' => 'ui-button manage push-scene left-join'
			)) ?>
		<?php else: ?>
			<em><?= s('auto category') ?></em>
		<?php endif ?>

		<?= $this->html->link(s('options'), '/categories/edit/' . $category->id, array(
			'class' => 'ui-button manage push-scene'
		)) ?>
	</div>

	<div class="children-count"><?= $category->childrenCount() ?></div>

	<div class="confirm">
		<div class="wrapper">
			<p>
				<?= s('Really want to delete <strong>%s</strong>?', e($category->title)) ?>
				<small><?= s('All itens and sub-categories related will be deleted') ?></small>
			</p>
			<?= $this->html->link(s('Yes, delete'), '/categories/delete/' . $category->id, array(
				'class' => 'ui-button ajax-request go-back highlight'
			)) ?>
			<?= $this->html->link(s('No, I don\'t'), '#', array( 'class' => 'ui-button' )) ?>
		</div>
	</div>
</li>

<?php if(array_key_exists($category->id, $categories)): ?>
	<?php foreach($categories[$category->id] as $subcategory): ?>
		<?= $this->element('categories/item', array(
			'level' => $level + 1,
			'category' => $subcategory,
			'categories' => $categories
		)) ?>
	<?php endforeach ?>
<?php endif ?>
