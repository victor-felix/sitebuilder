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
	    			<span>need convincing? call us</span>
	    			+55 21 4042.7270
	    		</p>
	    		<p class="login pull-left">
		    		<a class="active" href="#">sign up now</a>
		    		or
		    		<a href="#">sign in</a>
	    		</p>
    		</div>
    	</div>
    	<?php echo $this->contentForLayout ?>
		<div class="footer">
			<div class="container">
				<div class="links" >
					<a class="logo" href="#">Meumobi</a>
					<a href="#">About Us</a>
					<a href="#">Our Blog</a>
					<a href="#">Support</a>
					<p class="copy"><?php echo s('&copy;2011 MeuMobi. All rights reserved') ?></p>
				</div>
				<div class="contact">
					<div>
					<p class="upper">Contact Us</p>
					<p>
						email
						<span>contact@meumobi.com</span>
					</p>
					
					<p>phone
						<span>+55 21 4042.7270</span>	
					</p>
					</div>
				</div>
				<div class="social">
					<span class="upper">Find us on</span>
					<a class="face" href="#">facebook</a>
					<a class="twitter" href="#">twitter</a>
				</div>
			</div>
		</div>
        <?php //echo $this->element('layouts/footer') ?>

        <?php echo $this->html->script() ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
