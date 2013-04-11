<fieldset>
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
					'class' => 'ui-text large'
				)) ?>
				<small><?php echo s('Ex: http://facebook.com/username') ?></small>
			</div>

			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('twitter', array(
					'label' => s('Twitter Page'),
					'type' => 'text',
					'class' => 'ui-text large'
				)) ?>
				<small><?php echo s('Ex: http://twitter.com/username') ?></small>
			</div>

			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('website', array(
					'label' => s('URL of your current website'),
					'type' => 'text',
					'class' => 'ui-text large'
				)) ?>
				<small><?php echo s('Ex: http://www.yourwebsite.com') ?></small>
			</div>
		</div>
	</div>
</fieldset>

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

<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('icon for IPhone') ?></h2>
			<p>
				<?php echo s('Allow your visitors to quickly retur to your site adding an app-like icon to their mobile phone home screens') ?>
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
						'label' => s('icon for IPhone'),
						'type' => 'file',
						'class' => 'ui-text large'
					)) ?>
					<small><?php echo s('The recommended dimensions for image are 124px height and 124px width') ?></small>
				</div>
			</div>
		</div>
	</div>
</fieldset>