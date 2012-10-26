<fieldset>
	<h2><?php echo s('extension status') ?></h2>
	<div class="field-group">
	   <div class="form-grid-460 first">
			<?php echo $this->form->input('enabled', array(
				'type' => 'checkbox',
				'label' => s('Enabled'),
				'value' => 1
			)) ?>
			<label for="FormEnabled" class="checkbox"><?php echo s('This extension is enabled') ?></label>
		</div>
	</div>
</fieldset>
<fieldset>
	<h2><?php echo s('settings') ?></h2>
	<div class="field-group">
		<?php foreach($extension->fields() as $field): ?>
			<div class="form-grid-460 first">
				<?php echo $this->items->input($field) ?>
			</div>
		<?php endforeach ?>
	</div>
</fieldset>
