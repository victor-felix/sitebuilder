<div class="slide-header">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = s('General') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/general/' . $site->id, array(
    'id' => 'form-general-site-info',
    'class' => 'form-edit default-form',
    'object' => $site,
)) ?>

<fieldset>
	<h2><?php echo s('PushWoosh App') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('pushwoosh_app_id', array(
				'label' => s('PushWoosh App id'),
				'type' => 'text',
				'placeholder' => '00000-00000',
				'class' => 'ui-text large'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Private site') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('private', array(
				'label' => s('Private'),
				'type' => 'checkbox',
				'class' => ''
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Online Css Token') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('css_token', array(
				'label' => s('Token'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Google Analytics') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('google_analytics', array(
				'label' => s('Use comma to separate resp. website and mobile App tracking IDs'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
			<small><?php echo s('example: UA-50482698-1, UA-22519238-6') ?></small>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Date & Time') ?></h2>
	<div class="field-group">
		<div class="form-grid-220 first">
			<?php echo $this->form->input('timezone', array(
				'label' => s('Timezone'),
				'type' => 'select',
				'class' => 'ui-select',
				'options' => $site->timezones()
			)) ?>
		</div>

		<div class="form-grid-220">
			<?php echo $this->form->input('date_format', array(
				'label' => s('Date format'),
				'type' => 'select',
				'class' => 'ui-select',
				'options' => $site->dateFormats()
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Language') ?></h2>
	<div class="field-group">
		<div class="form-grid-220 first">
			<?php echo $this->form->input('language', array(
				'label' => s('Language'),
				'type' => 'select',
				'class' => 'ui-select',
				'options' => ['auto' => 'Auto', 'en' => 'English', 'pt' => 'Português']
			)) ?>
		</div>
	</div>
</fieldset>
<fieldset class="actions">
    <?php echo $this->form->submit(s('Save'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
