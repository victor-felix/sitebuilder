<fieldset>
	<div class="grid-4 first">
		<div class="tip">
			<h2 class="greater"><?php echo s('Your business description') ?></h2>
			<p>
				<?php echo s('We need some basic information about your business to start shaping up your mobile website') ?>
				<?php if($action == 'register'): ?>
					<br />
					<br />
					<small><?php echo s('you´ll be able to change and add other information later') ?></small>
				<?php endif ?>
			</p>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<?php echo $this->form->input('title', array(
					'label' => s('Business Name'),
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
				<span class="optional">
					<?php echo s('<span id="businessCounter">500</span> left') ?>
				</span>
				<?php echo $this->form->input('description', array(
					'id' => 'businessDescription',
					'label' => s('Description of business'),
					'type' => 'textarea',
					'class' => 'ui-textarea large greater',
					'maxlength' => 500
				)) ?>
				<small>
					<?php echo s('Give the users a brief description of what your business is, what it does, when it was founded, what your main services or products are, and so on.') ?>
				</small>
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
				<small><?php echo s('If you wish to use a custom domain, you can configure one after this wizard.<br /> In the admin panel you will find instructions on haw to proceed') ?></small>
			</div>
		</div>
	</div>
</fieldset>

<fieldset id="business-address">
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('Location') ?></h2>
			<p>
				<img class="icon" src="/images/shared/sites/icon-location.png" />
				<span>
					<?php echo s('Add a map of your business location to your mobile site') ?>
				</span>
				<br />
				<small>
					<?php echo s('A map integrated with Google Maps will appear on your mobile site, allowing customers to easily find the address.<br /><br />If your business has multiple locations, you\'ll be able to add all addresses as items in a category.') ?>
				</small>
			</p>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('address', array(
					'label' => s('Address'),
					'type' => 'textarea',
					'class' => 'ui-textarea large'
				)) ?>
			</div>
		</div>
	</div>
</fieldset>

<fieldset id="business-contact">
	<div class="grid-4 first">
		<div class="tip">
			<h2><?php echo s('Contact Information') ?></h2>
			<p>
				<img class="icon" src="/images/shared/sites/icon-call.png" />
				<span>
					<?php echo s('Add a click-to-call button on your mobile site') ?>
				</span>
			</p>
		</div>
	</div>

	<div class="grid-8">
		<div class="field-group">
			<div class="form-grid-220 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('phone', array(
					'label' => s('Phone number'),
					'type' => 'text',
					'class' => 'ui-text'
				)) ?>
				<small><?php echo s('Ex.: (00) 0000-0000') ?></small>
			</div>

			<div class="form-grid-460 first">
				<span class="optional"><?php echo s('Optional') ?></span>
				<?php echo $this->form->input('email', array(
					'label' => s('E-mail address'),
					'type' => 'text',
					'class' => 'ui-text large'
				)) ?>
			</div>
		</div>
	</div>
</fieldset>
