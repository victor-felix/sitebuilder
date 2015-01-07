<?php echo $this->form->create($action, array(
	'class' => 'form-edit skip-slide default-form',
	'object' => $category,
	'method' => 'file'
)) ?>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('category') ?></h2>
		</div>
	</div>

	<div class="grid-8">
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('title', array(
				'label' => s('Name of category'),
				'class' => 'ui-text large'
			)) ?>
		</div>
		<?php if($site->hasManyTypes()): ?>
			<div class="form-grid-460 first populate-based manual import">
				<?php if (!$category->id): ?>
					<?php echo $this->form->input('type', array(
						'label' => s('Content Type'),
						'type' => 'select',
						'class' => 'ui-select large item-types',
						'options' => Segments::listItemTypesFor($site->segment)
					)) ?>
					<small><?php echo s('The type of content defined which content could be inserted on category, it couldn\'t be updated after creation') ?></small>
				<?php else: ?>
					<?php echo $this->form->input('type', array(
						'label' => s('Content Type'),
						'type' => 'text',
						'class' => 'ui-text large disabled',
						'disabled' => true,
					)) ?>
				<?php endif ?>
			</div>
		<?php endif ?>

		<?php if($category->parent()): ?>
			<?php echo $this->form->input('parent_id', array(
				'type' => 'hidden',
				'value' => $category->parent()->id
			)) ?>
		<?php endif ?>

	</div>
	</div>
</fieldset>

<?php echo $this->element('categories/extensions', compact('category')) ?>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('notification') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php echo $this->form->input('notification', array(
					'type' => 'checkbox',
					'label' => null,
					'value' => 1
				)) ?>
				<label for="FormVisibility" class="checkbox"><?php echo s('This category sends push notifications for the users') ?></label>
			</div>
		</div>
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('visibility') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php echo $this->form->input('visibility', array(
					'type' => 'checkbox',
					'label' => null,
					'value' => 1
				)) ?>
				<label for="FormVisibility" class="checkbox"><?php echo s('This category is visible for any user') ?></label>
			</div>
		</div>
	</div>
</fieldset>


<?php if(MeuMobi::currentSegment()->fullOptions()): ?>
<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('advanced options') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">

			<div class="form-grid-460 first populate-based import import_method">
				<label><?php echo s('This category allows the importing and exporting of data in CSV format. <br/>Use recommended for advanced users only') ?></label>
				<br>
				<?php echo $this->html->link(s('Import CSV file'), '', array(
					'class' => 'ui-button js-expand-target',
					'data-target' => 'div.file-import'
				)) ?>
				<?php if(!is_null($category->id)): ?>
					<?php echo $this->html->link(s('Export CSV file'), '/api/' . $site->domain() . '/export/' . $category->id, array('class' => 'ui-button')) ?>
				<?php endif ?>
				<div class="file-import">
					<?php echo $this->form->input('import', array(
						'label' => null,
						'type' => 'file',
						'class' => 'ui-select large',
					)) ?>
					<br>

					<label><?php echo s('Method of import') ?></label>
					<?php echo $this->form->input('import_method', array(
						'type' => 'radio',
						'value' => 0,
						'options' => array(s('Inclusive'), s('Exclusive'))
					)) ?>
				</div>
			</div>

		</div>
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('icon') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php echo $this->form->input('icon', array(
					'type' => 'checkbox',
					'label' => null,
					'value' => 1
				)) ?>
				<label for="FormIcon" class="checkbox"><?php echo s('This category has an icon') ?></label>
			</div>
		</div>
	</div>
</fieldset>
<?php endif ?>
<fieldset class="actions">
	<?php echo $this->form->submit(s('Save'), array(
		'class' => 'ui-button red larger'
	)) ?>
	<?php if($category->id): ?>

		<?php echo $this->html->link($this->html->image('shared/categories/delete.gif') . s('Delete category'), '/categories/delete/' . $category->id, array(
			'class' => 'ui-button delete has-confirm','data-confirm' => '#delete-confirm'
		)) ?>

		<?php echo $this->html->link($this->html->image('shared/categories/delete.gif') . s('Delete all items'), '/categories/delete_all_items/' . $category->id, array(
			'class' => 'ui-button delete delete-items has-confirm','data-confirm' => '#delete-items-confirm'
		)) ?>
	<?php endif ?>
</fieldset>
<?php echo $this->form->close() ?>

<?php if($category->id): ?>
<div id="delete-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?php echo s('Really want to delete the <strong>%s</strong> category?', e($category->title)) ?>
			<br />
			<?php echo s('This will also delete all items and subcategories') ?>
		</p>
		<?php echo $this->html->link(s('Yes, delete'), '/categories/delete/' . $category->id, array(
			'class' => 'ui-button ajax-request go-back highlight'
		)) ?>
		<?php echo $this->html->link(s('No, I don\'t'), '#', array( 'class' => 'ui-button' )) ?>
	</div>
</div>

<div id="delete-items-confirm" class="confirm">
	<div class="wrapper">
		<p>
			<?php echo s('Really want to delete all items from <strong>%s</strong> category?', e($category->title)) ?>
		</p>
		<?php echo $this->html->link(s('Yes, delete'), '/categories/delete_all_items/' . $category->id, array(
			'class' => 'ui-button ajax-request go-back highlight'
		)) ?>
		<?php echo $this->html->link(s('No, I don\'t'), '#', array( 'class' => 'ui-button' )) ?>
	</div>
</div>
<?php endif ?>
