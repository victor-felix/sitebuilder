<div class="slide-header">
	<div class="grid-4 first">
		<?php echo $this->html->link(s('‹ back'), '/categories/index/' . $parent->id, array(
			'class' => 'ui-button large back pop-scene'
		)) ?>
	</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = e($item->title) ?></h1>
		<?php echo $this->element('common/breadcrumbs', array(
			'category' => $parent
		)) ?>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->items->form('/business_items/edit/' . $item->_id, $item, array(
	'class' => 'form-edit default-form item-form',
	'id' => 'form-edit-businessitem'
)) ?>

	<?php echo $this->element('business_items/form', compact('item')) ?>

	<div class="slide-footer">
			<div class="grid-4 first">
					<?= $this->buttons->popScene(s('‹ back'), '/categories/index/' . $parent->id) ?>
			</div>
			<div class="grid-8">
					<?= $this->buttons->submit() ?>
					<?= $this->buttons->delete(s('Delete item'), '/business_items/delete/' . $item->_id, '#delete-confirm')  ?>
			</div>
			<div class="clear"></div>
	</div>

<?php echo $this->items->endform() ?>

<div id="delete-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?php echo s('Really want to delete <strong>%s</strong>?', e($item->title)) ?>
		</p>
		<?php echo $this->html->link(s('Yes, delete'), '/business_items/delete/' . $item->_id, array(
			'class' => 'ui-button ajax-request go-back highlight'
		)) ?>
		<?php echo $this->html->link(s('No, I don\'t'), '#', array('class' => 'ui-button')) ?>
	</div>
</div>
