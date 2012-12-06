<fieldset>
	<div class="grid-4 first">
        <div class="tip">
			<h2 class="greater"><?php echo s('Your business description') ?></h2>
			<p>
				<?php echo s('We need some basic information about your business to start shaping up your mobile website') ?>
				<?php if($action == 'register'): ?>
				<br>
				<br>
				<small><?php echo s("you´ll be able to change and add other information later") ?></small>
				<?php endif ?>
			</p>
		</div>
    </div>
    
    <div class="grid-8"> 
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php echo $this->form->input('title', array(
					'label' => s('Company Name'),
					'type' => 'text',
					'class' => 'ui-text large greater'
				)) ?>
			</div>
			
			<div class="form-grid-460 first">
		        <?php if($site->logo()): ?>
		            <?php echo $this->html->image($site->logo()->link('200x200'), array(
		                'class' => 'logo'
		            )) ?>
		            <?php echo $this->html->link(s('delete logo'), '/images/delete/' . $site->logo()->id) ?>
		        <?php endif ?>
		        <div class="form-grid-460 first">
		            <span class="optional"><?php echo s('Optional') ?></span>
		            <?php echo $this->form->input('logo', array(
		                'label' => s('Logo'),
		                'type' => 'file',
		                'class' => 'ui-text large'
		            )) ?>
		            <small><?php echo s('To improve appearence of logo on your mobi site, we recommend to use an image on GIF or PNG with transparent background. Max size 50kb') ?></small>
		        </div>
		    </div>
			
			<div class="form-grid-460">
				<span class="optional"><?php echo s('<span id="businessCounter" >500</span> left') ?></span>
				<?php echo $this->form->input('description', array(
					'id' => 'businessDescription',
					'label' => s('Description of business'),
					'type' => 'textarea',
					'class' => 'ui-textarea large',
					'maxlenght' => 500
				)) ?>
				<small><?php echo s('Give the users a brief description of what your business is, what it does, when it was founded, what your main services or products are, and so on.') ?></small>
			</div>
	
			<div class="form-grid-460 first">
				<div class="site-mobile-url">
					<div class="input text">
						<label for="FormSlug"><?php echo s('Address of mobile site') ?></label>
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
					<small><?php echo s("If you wish to use a custom domain, you can configure one after this wizard.<br>
								In the admin panel you will find instructions on haw to proceed") ?></small>
			</div>
		</div>
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
        <div class="tip">
			<h2><?php echo s('Location') ?></h2>
			<p>
				<img class="icon" src="/images/shared/sites/icon-location.png" />
				<span>
					<?php echo s('Add a map of your location to your mobile site') ?>
				</span>
				<?php if(1 || $action == 'register'): ?>
				<br>
				<small><?php echo s('If your company has multiple offices or addresses, you will be able to add the other in the admin panel') ?></small>
				<?php endif ?>
			</p>
		</div>
    </div>
    
    <div class="grid-8">
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
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
        <div class="tip">
			<h2><?php echo s('Contact Information') ?></h2>
			<p>
				<img class="icon" src="/images/shared/sites/icon-call.png" />
				<span>
					<?php echo s('Add a click to call button on your mobile site') ?>
				</span>
			</p>
		</div>
    </div>
    
    <div class="grid-8"> 
	    <div class="field-group">
			<div class="form-grid-220 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('phone', array(
					'label' => s('Commercial telephone'),
					'type' => 'text',
					'class' => 'ui-text'
				)) ?>
				<small><?php echo s('Ex.: (00) 0000-0000') ?></small>
			</div>
	
			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('email', array(
					'label' => s('Commercial email address'),
					'type' => 'text',
					'class' => 'ui-text large'
				)) ?>
			</div>
		</div>
	</div>
</fieldset>

<!-- full form start here -->
<?php if($action != 'register'): ?>

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

<fieldset>
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
	</div>
</fieldset>

<fieldset>
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
	</div>
</fieldset>
<?php endif;?>