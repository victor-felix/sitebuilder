<div class="theme-preview">
	<div class="wrapp">
		<div class="load"></div>
		<iframe id="theme-frame"
			src=""
			data-url="<?php if ($site->id) echo "http://{$site->domain}"; else echo MeuMobi::currentSegment()->sitePreviewUrl() ?>"
			<?php if ($autoload) echo 'data-autoload="1"' ?>></iframe>
	</div>
</div>
