<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = s('General') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/general/' . $site->id, array(
    'id' => 'form-general-site-info',
    'class' => 'form-edit',
    'object' => $site,
    //'method' => 'file'
)) ?>

<fieldset>
	<h2><?php echo s('details about your business') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			
			<div class="site-mobile-custom-domain">
				<div class="input checkbox">
					<?php echo $this->form->input('custom_domain', array(
						'label' => false,
						'div' => false,
						'type' => 'checkbox',
						'class' => 'ui-checkbox'
					)) ?>
				</div>
				<label for="FormCustomDomain" class="checkbox"><?php echo s('use a custom domain name') ?></label>

				<div class="input text">
					<?php foreach ($site->domains() as $id => $domain): 
						if($domain != $site->slug.'.'.MeuMobi::domain()):
					?>
					<p class="meumobi-url clear">
						<span>http://</span>
						<?php echo $this->form->input("domains[$id]", array(
							'label' => false,
							'div' => false,
							'type' => 'text',
							'class' => 'ui-text',
							'value' =>  $domain,
						)) ?>
					</p>
					<?php 
					endif;
					endforeach; ?>
					<p class="meumobi-url clear">
						<span>http://</span>
						<?php echo $this->form->input('domains[]', array(
							'label' => false,
							'div' => false,
							'type' => 'text',
							'class' => 'ui-text'
						)) ?>
					</p>
					<a href="#" class="js-duplicate-previous"><?php echo s('add domain')?></a>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Save'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>