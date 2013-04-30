<li class="level-<?php echo $level ?>" data-catid="<?php echo $category->id ?>" data-parentid="<?php echo $category->parent_id ?>">
	<?php if($level == 1): ?>
		<?php echo $this->html->link('', '/categories/add/' . $category->id, array(
			'class' => 'ui-button ui-button-add highlight push-scene'
		)) ?>
	<?php endif ?>

	<span class="title edit-in-place" data-saveurl="/categories/edit/<?php echo $category->id ?>" title="<?php echo s('click to edit') ?>">
		<?php echo e($category->title) ?>
	</span>

	<div class="controls">
		<?php echo $this->html->link(s('down'), '/categories/movedown/' . $category->id, array(
			'class' => 'ui-button highlight'
		)) ?>

		<?php echo $this->html->link(s('up'), '/categories/moveup/' . $category->id, array(
			'class' => 'ui-button highlight left-join'
		)) ?>

		<?php if(!$category->hasFeed()): ?>
			<?php echo $this->html->link(s('add item'), '/business_items/add/' . $category->id, array(
				'class' => 'ui-button highlight push-scene'
			)) ?>

			<?php echo $this->html->link(s('manage items'), '/business_items/index/' . $category->id, array(
				'class' => 'ui-button manage push-scene left-join'
			)) ?>
		<?php else: ?>
			<em><?php echo s('auto category') ?></em>
		<?php endif ?>

		<?php echo $this->html->link(s('options'), '/categories/edit/' . $category->id, array(
			'class' => 'ui-button manage push-scene'
		)) ?>
	</div>

	<div class="children-count"><?php echo $category->childrenCount() ?></div>

	<div class="confirm">
		<div class="wrapper">
			<p>
				<?php echo s('Really want to delete <strong>%s</strong>?', e($category->title)) ?>
				<small><?php echo s('All itens and sub-categories related will be deleted') ?></small>
			</p>
			<?php echo $this->html->link(s('Yes, delete'), '/categories/delete/' . $category->id, array(
				'class' => 'ui-button ajax-request go-back highlight'
			)) ?>
			<?php echo $this->html->link(s('No, I don\'t'), '#', array( 'class' => 'ui-button' )) ?>
		</div>
	</div>
</li>

<?php if(array_key_exists($category->id, $categories)): ?>
	<?php foreach($categories[$category->id] as $subcategory): ?>
		<?php echo $this->element('categories/item', array(
			'level' => $level + 1,
			'category' => $subcategory,
			'categories' => $categories
		)) ?>
	<?php endforeach ?>
<?php endif ?>
