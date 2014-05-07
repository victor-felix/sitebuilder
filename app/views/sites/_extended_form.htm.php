<?php if (MeuMobi::currentSegment()->isEnabledFieldSet('stocks')): ?>
<fieldset id="stocks">
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('Stocks') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('stock_symbols', array(
					'label' => s('Symbols'),
					'type' => 'text',
					'class' => 'ui-text large'
				)) ?>
				<small><?php echo s('You can use several codes separated by commas') ?></small>
			</div>
		</div>
	</div>
</fieldset>
<?php endif; ?>
<?php if (MeuMobi::currentSegment()->isEnabledFieldSet('timetable')): ?>
<fieldset id="timeable">
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('Open hours') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('timetable', array(
					'label' => s('Open hours'),
					'type' => 'textarea',
					'class' => 'ui-textarea large'
				)) ?>
			</div>
		</div>
	</div>
</fieldset>
<?php endif; ?>
<?php if (MeuMobi::currentSegment()->isEnabledFieldSet('weblinks')): ?>
<fieldset id="business-social">
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('Your links on web') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('facebook', array(
					'label' => s('Facebook Page'),
					'type' => 'text',
					'class' => 'ui-text large',
					'value' => $site->facebook ? $site->facebook : 'http://'
				)) ?>
				<small><?php echo s('Ex: http://facebook.com/username') ?></small>
			</div>

			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('twitter', array(
					'label' => s('Twitter Page'),
					'type' => 'text',
					'class' => 'ui-text large',
					'value' => $site->twitter ? $site->twitter : 'http://'
				)) ?>
				<small><?php echo s('Ex: http://twitter.com/username') ?></small>
			</div>

			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('website', array(
					'label' => s('URL of your current website'),
					'type' => 'text',
					'class' => 'ui-text large',
					'value' => $site->website ? $site->website : 'http://'
				)) ?>
				<small><?php echo s('Ex: http://www.yourwebsite.com') ?></small>
			</div>
		</div>
	</div>
</fieldset>
<?php endif ?>
<?php if (MeuMobi::currentSegment()->isEnabledFieldSet('photos')): ?>
<fieldset id="business-photos">
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('Your business photos') ?></h2>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<?php if($site->id && $images = $site->photos()): ?>
				<?php foreach($images as $i => $image): $class = $i % 3 ? '' : 'first' ?>
					<div class="<?php echo $class ?> picture-upload-container done" style="background-image: url(<?php echo $image->link('139x139') ?>)">
						<?php echo $this->html->link('', '/images/delete/' . $image->id, array(
							'class' => 'close'
						)) ?>
						<?php echo $this->form->input('image[' . $image->id . '][title]', array(
							'label' => false,
							'placeholder' => s('edit subtitle'),
							'class' => 'ui-text large',
							'value' => $image->title
						)) ?>
					</div>
				<?php endforeach ?>
			<?php endif ?>

			<?php $class = (isset($i) ? $i + 1 : 0) % 3 ? '' : 'first' ?>
			<div class="<?php echo $class ?> picture-upload-container" data-url="/images/add.htm">
				<input type="hidden" name="image[foreign_key]" value="<?php echo $site->id() ?>" />
				<input type="hidden" name="image[model]" value="SitePhotos" />
				<a class="close"></a>
				<iframe src="about:blank" id="iframe_<?php echo time() ?>"></iframe>
				<div class="default">
					<div class="icon_upload"></div>
					<?php echo s('add photo') ?>
				</div>
				<div class="wait"><?php echo s('uploading photo...') ?></div>
				<?php echo $this->form->input('image[photo]', array(
					'label' => false,
					'type' => 'file',
					'class' => 'ui-text large picture-upload'
				)) ?>
				<?php echo $this->form->input('image[ID][title]', array(
					'label' => false,
					'placeholder' => s('edit subtitle'),
					'class' => 'ui-text large'
				)) ?>
			</div>

			<a href="#" class="duplicate-previous"><?php echo s('more') ?></a>
		</div>
	</div>
</fieldset>
<?php endif; ?>