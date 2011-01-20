<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>MeuMobi Restaurant - Seu restaurante na Web móvel em 3 minutos!</title>

		<?php echo $this->html->stylesheet('home', 'uikit'); ?>
    </head>
    
    <body>
		<div id="header">
		    <div class="logo">
			    <?php echo $this->html->link($this->html->image('layout/logo.png', array('alt'=>'MeuMobi')), '/', array('class'=>'logo')) ?>
			</div>
			<p class="login">
			    <?php echo $this->html->link('Efetue login', '/login') ?> ou <?php echo $this->html->link('Cadastre-se', '/register') ?>
			</p>
			
			<div class="get-started">
			    <h2>Seu restaurante na palma da mão em menos de 3 minutos.</h2>
			    <p class="subtitle">MeuMobi Restaurant coloca o seu negócio na
                Internet móvel em instantes.</p>
                <?php echo $this->html->link('crie seu mobi já!', '/register') ?>
			</div>
			
			<div id="slideshow">
			    <?php echo $this->html->image('home/slides/iphone.png'); ?>
			    <?php echo $this->html->image('home/slides/blackberry.png'); ?>
			    <?php echo $this->html->image('home/slides/android.png'); ?>
			</div>
            
			<div class="clear"></div>
		</div>
	
	    <div id="content">
        <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element("layouts/footer") ?>
        
        <?php echo $this->html->script('jquery', 'jquery.cycle.all.min.js') ?>
        <script type="text/javascript">
        $('#slideshow').cycle({'fx': 'scrollUp'});
        </script>
    </body>
</html>