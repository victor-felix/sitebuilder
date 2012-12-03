<fieldset>
	<h2><?php echo s('details about your business') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<?php echo $this->form->input('title', array(
				'label' => s('Name of business'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
		</div>

		<div class="form-grid-460">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('description', array(
				'id' => 'businessDescription',
				'label' => s('Description of business'),
				'type' => 'textarea',
				'class' => 'ui-textarea large',
				'maxlenght' => 500
			)) ?>
			<small><?php echo s('Give a brief description baout your business and related activities. Remaining <span id="businessCounter" >500</span> chars.') ?></small>
		</div>

		<div class="form-grid-460 first">
			<div class="site-mobile-url">
				<div class="input text">
					<label for="FormSlug"><?php echo s('url of mobile site') ?></label>
					<p class="meumobi-url">
						<span>http://</span>
						<?php echo $this->form->input('slug', array(
							'label' => false,
							'div' => false,
							'type' => 'text',
							'class' => 'ui-text' . ($action == 'edit' ? ' disabled' : ''),
							'disabled' => $action == 'edit'
						)) ?><span>.<?php echo MeuMobi::domain() ?></span>
					</p>
					<div class="clear"></div>
				</div>
			</div>
			<?php if($action == 'register'): ?>
				<small><?php echo s("Be careful, you couldn't change your url later") ?></small>
			<?php else: ?>
				<small><?php echo s("You can't change the url of your mobile site") ?></small>
			<?php endif ?>
		</div>
	</div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Location') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
	<h2><?php echo s('Location') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('street', array(
				'label' => s('Street'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
		</div>

		<div class="form-grid-220 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('number', array(
				'label' => s('Number'),
				'type' => 'text',
				'class' => 'ui-text'
			)) ?>
		</div>

		<div class="form-grid-220">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('complement', array(
				'label' => s('Complement'),
				'type' => 'text',
				'class' => 'ui-text'
			)) ?>
		</div>

		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('zone', array(
				'label' => s('District'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
		</div>

		<div class="form-grid-220 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('country_id', array(
				'label' => s('Country'),
				'type' => 'select',
				'empty' => array(''),
				'options' => $countries,
				'class' => 'ui-select'
			)) ?>
		</div>

		<div class="form-grid-220">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('state_id', array(
				'label' => s('State'),
				'type' => 'select',
				'class' => 'ui-select',
				'options' => $states,
				'empty' => array('')
			)) ?>
		</div>

		<div class="form-grid-220 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('city', array(
				'label' => s('City'),
				'type' => 'text',
				'class' => 'ui-text'
			)) ?>
		</div>

		<div class="form-grid-220">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('zip', array(
				'label' => s('zip'),
				'type' => 'text',
				'class' => 'ui-text'
			)) ?>
		</div>
	</div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Contact') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
	<h2><?php echo s('Contact') ?></h2>
	<div class="field-group">

		<div class="form-grid-220 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('phone', array(
				'label' => s('Phone'),
				'type' => 'text',
				'class' => 'ui-text'
			)) ?>
			<small><?php echo s('Ex.: (00) 0000-0000') ?></small>
		</div>

		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('email', array(
				'label' => s('Mail'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
		</div>
	</div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Open hours') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
	<h2><?php echo s('Open hours') ?></h2>
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
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Your links on web') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
	<h2><?php echo s('Your links on web') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('facebook', array(
				'label' => s('Facebook Page'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
			<small><?php echo s('Ex: http://www.facebook.com/username/') ?></small>
		</div>

		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('twitter', array(
				'label' => s('Twitter Page'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
			<small><?php echo s('Ex: http://www.twitter.com/username/') ?></small>
		</div>

		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('website', array(
				'label' => s('Url of your current website'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
			<small><?php echo s('Ex: http://www.yourwebsite.com/') ?></small>
		</div>
	</div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Photos of Business') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
	<h2><?php echo s('Photos of Business') ?></h2>
	<div class="field-group">
		<?php if($site->id && $images = $site->photos()): ?>
			<?php foreach($images as $i => $image): $class = $i % 3 ? '' : 'first' ?>
				<div class="<?php echo $class ?> picture-upload-container done" style="background-image: url(<?php echo $image->link('139x139') ?>)">
					<?php echo $this->html->link('', '/images/delete/' . $image->id, array(
						'class' => 'close'
					)) ?>
										<?php echo $this->form->input('image['.$image->id.'][title]', array(
							'label' => false,
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
			<iframe src="about:blank" id="iframe_<?php echo time(); ?>"></iframe>
			<div class="default">
		<div class="icon_upload"></div>
		<?php echo s('add photo'); ?>
		</div>
			<div class="wait"><?php echo s('uploading photo...'); ?></div>
			<?php echo $this->form->input('image[photo]', array(
				'label' => false,
				'type' => 'file',
				'class' => 'ui-text large picture-upload'
			)) ?>
			<?php echo $this->form->input('image[ID][title]', array(
				'label' => false,
				'class' => 'ui-text large'
			)) ?>
		</div>

		<a href="#" class="duplicate-previous">more</a>
	</div>
</fieldset>
