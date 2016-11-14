<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?= s('category') ?></h2>
		</div>
	</div>

	<div class="grid-8">
	<div class="field-group">
		<div class="form-grid-460 first">
			<?= $this->form->input('title', array(
				'label' => s('Name of category'),
				'class' => 'ui-text large'
			)) ?>
		</div>
		<?php if($site->hasManyTypes()): ?>
			<div class="form-grid-460 first populate-based manual import">
				<?php if (!$category->id): ?>
					<?= $this->form->input('type', array(
						'label' => s('Content Type'),
						'type' => 'select',
						'class' => 'ui-select large item-types',
						'options' => Segments::listItemTypesFor($site->segment)
					)) ?>
					<small><?= s('The type of content defined which content could be inserted on category, it couldn\'t be updated after creation') ?></small>
				<?php else: ?>
					<?= $this->form->input('type', array(
						'label' => s('Content Type'),
						'type' => 'text',
						'class' => 'ui-text large disabled',
						'disabled' => true,
					)) ?>
				<?php endif ?>
			</div>
		<?php endif ?>

		<?php if($category->parent()): ?>
			<?= $this->form->input('parent_id', array(
				'type' => 'hidden',
				'value' => $category->parent()->id
			)) ?>
		<?php endif ?>

	</div>
	</div>
</fieldset>

<?= $this->element('categories/extensions', compact('category')) ?>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?= s('notification') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?= $this->form->input('notification', array(
					'type' => 'checkbox',
					'label' => s('This category sends push notifications for the users'),
					'value' => 1
				)) ?>
			</div>
		</div>
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?= s('visibility') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?= $this->form->input('visibility', array(
					'type' => 'checkbox',
					'label' => s('This category is visible for any user'),
					'value' => 1
				)) ?>
			</div>
			<div class="form-grid-460 first">
				<?= $this->form->input('latest_feed_eligible', array(
					'type' => 'checkbox',
					'label' => s('This category will be shown in the feed of latest news'),
					'value' => 1
				)) ?>
			</div>
		</div>
	</div>
</fieldset>

<?php if(MeuMobi::currentSegment()->fullOptions()): ?>
<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?= s('advanced options') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">

			<div class="form-grid-460 first populate-based import import_method">
				<label><?= s('This category allows the importing and exporting of data in CSV format. <br/>Use recommended for advanced users only') ?></label>
				<br>
				<?= $this->html->link(s('Import CSV file'), '', array(
					'class' => 'ui-button js-expand-target',
					'data-target' => 'div.file-import'
				)) ?>
				<?php if(!is_null($category->id)): ?>
					<?= $this->html->link(s('Export CSV file'), '/api/' . $site->domain() . '/export/' . $category->id, array('class' => 'ui-button')) ?>
				<?php endif ?>
				<div class="file-import">
					<?= $this->form->input('import', array(
						'label' => null,
						'type' => 'file',
						'class' => 'ui-select large',
					)) ?>
					<br>

					<?= $this->form->input('import_method', array(
						'label' => s('Method of import'),
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
			<h2><?= s('icon') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?= $this->form->input('icon', array(
					'type' => 'checkbox',
					'label' => s('This category has an icon'),
					'value' => 1
				)) ?>
			</div>
		</div>
	</div>
</fieldset>
<?php endif ?>
