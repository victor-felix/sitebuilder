<div class="theme-preview">
	<div class="wrapp">
		<?php $url = "http://{$site->domain}" ?>
		<iframe 
			id="theme-frame" 
			src="" 
			data-url="<?php echo $url ?>"
			<?php if ($autoload) echo 'data-autoload="1"'; ?>
			width="330px" 
			height="480px"></iframe>
	</div>
</div>