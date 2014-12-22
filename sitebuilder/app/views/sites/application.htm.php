<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('application') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/application/' . $site->id, array(
	'class' => 'form-edit default-form',
	'object' => $site,
	'method' => 'file'
)) ?>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('splash screen for iPhone') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php if($site->splashScreen()): ?>
					<?php echo $this->html->image($site->splashScreen()->link(), array(
						'class' => 'logo'
					)) ?>
					<?php echo $this->html->link(s('delete icon'), '/images/delete/' . $site->splashScreen()->id) ?>
				<?php endif ?>
				<div class="form-grid-460 first">
					<span class="optional"><?php echo s('Optional') ?></span>
					<?php echo $this->form->input('splashScreen', array(
						'label' => s('splash screen for iPhone'),
						'type' => 'file',
						'class' => 'ui-text large'
					)) ?>
					<small><?php echo s('The recommended dimensions for image are %s height and %s width', '640px', '1096px') ?></small>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('icon for iPhone') ?></h2>
			<p>
				<?php echo s('Allow your visitors to quickly return to your site adding an app-like icon to their mobile iPhone home screens') ?>
			</p>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php if($site->appleTouchIcon()): ?>
					<?php echo $this->html->image($site->appleTouchIcon()->link(), array(
						'class' => 'logo'
					)) ?>
					<?php echo $this->html->link(s('delete icon'), '/images/delete/' . $site->appleTouchIcon()->id) ?>
				<?php endif ?>
				<div class="form-grid-460 first">
					<span class="optional"><?php echo s('Optional') ?></span>
					<?php echo $this->form->input('appleTouchIcon', array(
						'label' => s('icon for iPhone'),
						'type' => 'file',
						'class' => 'ui-text large'
					)) ?>
					<small><?php echo s('The recommended dimensions for image are %s height and %s width', '114px', '114px') ?></small>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Android app') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('android_app_id', array(
				'label' => s('App id'),
				'type' => 'text',
				'placeholder' => 'com.meumobi.myapp',
				'class' => 'ui-text large'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('Ios app') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('ios_app_id', array(
				'label' => s('App id'),
				'type' => 'text',
				'placeholder' => '123456789',
				'class' => 'ui-text large'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h2><?php echo s('App Version') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('latest_app_version', array(
				'label' => s('App Version'),
				'type' => 'text',
				'placeholder' => '1.0.0',
				'class' => 'ui-text large'
			)) ?>
		</div>
	</div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Save'), array('class' => 'ui-button red larger')) ?>
</fieldset>
<?php echo $this->form->close() ?>
