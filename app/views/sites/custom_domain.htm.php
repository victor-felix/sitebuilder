<?php $custom = $site->custom_domain() ?>

<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = s('Custom Domain') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/custom_domain/' . $site->id, array(
    'id' => 'form-general-site-info',
    'class' => 'form-edit default-form',
    'object' => $site,
    //'method' => 'file'
)) ?>

<fieldset>
	<div class="grid-4 first">
        <div class="tip">
			<h2 class="greater"><?php echo s('Custom the domain of your mobile site') ?></h2>
			<p>
				<?php echo s('Follow the steps to customize your mobile site domain and redirect your mobile traffic') ?>
			</p>
		</div>
    </div>
    
    <div class="grid-8"> 
		<div class="field-group">
			<div class="form-grid-460 first">
				<p class="label"><?php echo s('This is the address of your mobile site:') ?></p>
				<p class="meumobi-url">
					<span>http://<?php echo $site->defaultDomain() ?></span>
				</p>
				
				<div class="input text domains">
					<p class="label"><?php echo s('Enter your custom domains') ?>:</p>
					<?php foreach ($site->domains() as $id => $domain): 
						if($domain != $site->slug.'.'.MeuMobi::domain()):
					?>
					<p class="clear">
						<?php echo $this->form->input("domains[$id]", array(
							'label' => false,
							'div' => false,
							'type' => 'text',
							'class' => 'ui-text large',
							'value' =>  $domain,
						)) ?>
						<?php echo $this->html->link(s('delete domain'), "/domains/delete/$id") ?>
						<small><?php echo s('Ex.: m.yourcompany.com') ?></small>
					</p>
					<?php 
					endif;
					endforeach; ?>
					<p class="clear">
						<?php echo $this->form->input('domains[]', array(
							'label' => false,
							'div' => false,
							'type' => 'text',
							'class' => 'ui-text large'
						)) ?>
						<small><?php echo s('Ex.: m.yourcompany.com') ?></small>
					</p>
					<a href="#" class="js-duplicate-previous ui-button"><?php echo s('add one more domain') ?></a>
					<div class="clear"></div>
				</div>
				
				
				<p class="label"><?php echo s('Now visit your website hosting provider and create a CNAME record with these values:') ?></p>
				
				<p class="custom-domain-setup">
					<small style="display: inline;"><?php echo s('alias') ?>: </small>
					<span class="current-custom-domain"><?php echo $custom ? $custom : 'm.yourcompany.com';?></span>
					<br>
					<small style="display: inline;"><?php echo s('destination') ?>: </small>
					<span><?php echo $site->defaultDomain() ?></span>
				</p>
				
			</div>
		</div>
	</div>
</fieldset>

<fieldset>
	<div class="grid-4 first">
        <div class="tip">
			<h2 class="greater"><?php echo s('Redirect mobile visitors to your mobile site') ?></h2>
			<p>
				<?php echo s('People will type your current website on their phones and will be redirect to your mobile address') ?>
			</p>
		</div>
    </div>
    
    <div class="grid-8">
    	<div class="field-group">
    		<div class="form-grid-460">
	    		<p class="label"><?php echo s('Copy the code below into the html of your regular website\'s homepage(ex: index.html) to automatically redirect mobile visitors to your mobile site.') ?></p>
				<br>
				<p class="label">
				<?php echo s('Paste this code into the &lt;head&gt; tag on your site. You only need to do this once.') ?>
				</p>
				
				<br>
				<br>
				
				<p class="label">
				<?php echo s('<b>If you have a MeuMobi domain</b>, copy this code:') ?>
				</p>
				
				<p class="code"><?php 
					$script = '<script type="text/javascript" 
									src="http://meumobi.com/static/redirect.js">
								</script>
								<script type="text/javascript">
									RedirectToMeuMobi("http://%s");
								</script>';
					 $script = e($script);
					 echo sprintf($script, "<b>{$site->defaultDomain()}</b>");
				?>
				</p>
				
				<br>
				<br>
				
				<p class="label">
				<?php echo s('<b>if you created a custom domain</b>, copy this code:') ?>
				</p>
				
				<p class="code"><?php 
					$script = '<script type="text/javascript" 
									src="http://meumobi.com/static/redirect.js">
								</script>
								<script type="text/javascript">
									RedirectToMeuMobi("http://%s");
								</script>';
					$script = e($script);
					$str = $custom ? $custom : 'm.yourcompany.com';
					echo sprintf($script, "<b class='current-custom-domain'>{$str}</b>");
				?></pre>
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