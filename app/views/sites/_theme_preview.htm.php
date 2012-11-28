<div class="theme-preview">
	<div class="wrapp">
		<?php $url = "http://{$site->domain}" ?>
		<div class="load"></div>
		<iframe 
			id="theme-frame" 
			src="" 
			data-url="<?php echo $url ?>"
			<?php if ($autoload) echo 'data-autoload="1"'; ?>></iframe>
	</div>
</div>