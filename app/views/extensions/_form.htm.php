<fieldset>
	<div class="grid-4 first">
		<h2><?php echo s('extension status') ?></h2>
    </div>
	<div class="grid-8">
		<div class="form-grid-570">
			<div class="ui-switch right <?php echo $extension->enabled ? 'enabled' : '' ?>" data-target="#FormEnabled">
				<p class="on">
					<span><?php echo s('enabled') ?></span>
					<a class="ui-button" ></a>
				</p>
				
				<p class="off">
					<span><?php echo s('disabled') ?></span>
					<a class="ui-button" ></a>
				</p>
				<?php echo $this->form->input('enabled', array(
				'type' => 'hidden',
			)) ?>
			</div>
			
			<h3 class="title-3" >
				<?php echo s($extension->specification('title'))?>
				<span class="description"><?php echo s($extension->specification('description'))?></span>
			</h3>
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
