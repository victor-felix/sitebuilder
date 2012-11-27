<div class="theme-preview">
	<div class="wrapp">
		<?php //$url = Mapper::url('/sites/preview'); ?>
		<?php $url = "http://{$site->domain}" ?>
		<?php $url = "http://santacasajf.meumobi.com" ?>
		<iframe 
			id="theme-frame" 
			src="" 
			data-url="<?php echo $url ?>"
			<?php if ($autoload) echo 'data-autoload="1"'; ?>
			width="320px" 
			height="480px"></iframe>
	</div>
</div>