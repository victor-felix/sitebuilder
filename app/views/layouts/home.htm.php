<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <title><?php echo s('home/index.pagetitle') ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/bootstrap.min', 'shared/home') ?>
    </head>

    <body>
    	<div class="header container">
    		<h1 class="pull-left">
    			<img alt="MeuMobi" src="/images/shared/home/logo.png" />
    			<span class="border"></span>
    		</h1>
    		<div class="pull-right">
	    		<p class="call pull-left">
	    			<span><?php echo s('need convincing? call us')?></span>
	    			<b><?php echo s('+55 21 4042.7270')?></b>
	    		</p>
	    		<p class="login pull-left">
		    		<?php
						echo $this->html->link(s('sign up now'), '/users/register/' . $invite_token, array(
							'class' => 'active'
						));
					?>
		    		<?php echo s('or')?>
		    		<?php
						echo $this->html->link( s('sign in ›'), '/users/login/' . $invite_token, array(
							'class' => ''
						));
					?>
	    		</p>
    		</div>
    	</div>
    	<?php echo $this->contentForLayout ?>
		<div class="footer">
			<div class="container">
				<div class="links" >
					<a class="logo" href="#"><?php echo s('MeuMobi')?></a>
					<a href="#"><?php echo s('About Us')?></a>
					<a href="#"><?php echo s('Our Blog')?></a>
					<a href="#"><?php echo s('Support')?></a>
					<p class="copy"><?php echo s('&copy;2011 MeuMobi. All rights reserved') ?></p>
				</div>
				<div class="contact">
					<div>
					<p class="upper"><?php echo s('Contact Us')?></p>
					<p>
						<?php echo s('email')?>
						<span><?php echo s('contact@meumobi.com')?></span>
					</p>
					
					<p><?php echo s('phone')?>
						<span><?php echo s('+55 21 4042.7270')?></span>	
					</p>
					</div>
				</div>
				<div class="social">
					<span class="upper"><?php echo s('Find us on')?></span>
					<a class="face" href="http://www.facebook.com/meumobi"><?php echo s('facebook')?></a>
					<a class="twitter" href="http://twitter.com/MeuMobi"><?php echo s('twitter')?></a>
				</div>
			</div>
			<script type="text/javascript">

			  var _gaq = _gaq || [];
			  _gaq.push(['_setAccount', 'UA-22519238-3']);
			  _gaq.push(['_setDomainName', '.meumobi.com']);
			  _gaq.push(['_trackPageview']);
			
			  (function() {
			    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
			  })();
			
			</script>
		</div>
        <?php //echo $this->element('layouts/footer') ?>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <?php echo $this->html->script('shared/jquery', 'shared/jquery.carouFredSel-6.1.0-packed','shared/jquery.touchSwipe.min', 'shared/jquery.ba-throttle-debounce.min', 'shared/home') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
