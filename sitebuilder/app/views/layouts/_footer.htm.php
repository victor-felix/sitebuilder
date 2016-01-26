<div class="footer">
	<div class="container">
		<div class="links">
			<a class="logo" href="#"><?php echo s('MeuMobi') ?></a>
      <ul>
				<?php if(MeuMobi::currentSegment()->aboutUsUrl): ?> 
				<li>
					<a href="<?php echo MeuMobi::currentSegment()->aboutUsUrl ?>" target="_blank"><?php echo s('About Us') ?></a>
				</li>
				<?php endif ?>
				<?php if(MeuMobi::currentSegment()->blogUrl): ?> 
				<li>
					<a href="<?php echo MeuMobi::currentSegment()->blogUrl ?>" target="_blank"><?php echo s('Our Blog') ?></a>
				</li>
				<?php endif ?>
        <li><a href="/docs/api"><?php echo s('Api Documentation') ?></a></li>
      </ul>
			<p class="copy">
				<span class="border"></span>
				<?php echo s('&copy;%s MeuMobi. All rights reserved', @date("Y")) ?>
			</p>
		</div>
		<div class="contact">
			<div>
				<p class="upper"><?php echo s('Contact Us') ?></p>
				<?php if(MeuMobi::currentSegment()->contactMail): ?>
					<p>
						<span><?php echo MeuMobi::currentSegment()->contactMail ?></span>
					</p>
				<?php endif ?>
				<?php if(MeuMobi::currentSegment()->contactPhone): ?>
					<p>
						<span><?php echo MeuMobi::currentSegment()->contactPhone ?></span>
					</p>
				<?php endif ?>
			</div>
		</div>
		<div class="social">
			<span class="upper"><?php echo s('Find us on') ?></span>
			<?php if(MeuMobi::currentSegment()->contactFacebook): ?>
			<a class="face" href="<?php echo MeuMobi::currentSegment()->contactFacebook ?>"><?php echo s('facebook') ?></a>
			<?php endif ?>
			<?php if(MeuMobi::currentSegment()->contactTwitter): ?>
			<a class="twitter" href="<?php echo MeuMobi::currentSegment()->contactTwitter ?>"><?php echo s('twitter') ?></a>
			<?php endif ?>
		</div>
	</div>
</div>
<?php if (Config::read('App.environment') == 'production'): ?>
	<?php if (MeuMobi::currentSegment()->analytics): ?>
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '<?php echo MeuMobi::currentSegment()->analytics ?>']);
		_gaq.push(['_setDomainName', '.meumobi.com']);
		_gaq.push(['_trackPageview']);
	
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	<?php endif ?>
	<?php if (Config::read('App.support')): ?>
	<script type="text/javascript">
	/* Zopim Code */
	window.$zopim||(function(d,s){var z=$zopim=function(c){z._.push(c)},$=z.s=
	d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
	_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute('charset','utf-8');
	$.src='//cdn.zopim.com/?<?php echo Config::read('App.support') ?>';z.t=+new Date;$.
	type='text/javascript';e.parentNode.insertBefore($,e)})(document,'script');
	/* Zopim Code */
	
	//support link toggle
	$zopim(function() {
		$(document).ready(function() {
			$('#support-link, a#support').click(function(){
				$zopim.livechat.window.toggle();
			});
		});
	});
	</script>
	<?php endif ?>
<?php endif ?>
