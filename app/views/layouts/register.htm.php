<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->pageTitle ?></title>

		<?php echo $this->html->stylesheet('register', 'uikit'); ?>
    </head>
    
    <body>
	
		<div id="header">
			<?php echo $this->html->link($this->html->image('layout/logo.png', array('class'=>'MeuMobi')), '/', array('class'=>'logo')); ?>
	    </div>
	    
	    <div id="content">
    	    <div class="head">
    	        <h1>crie seu mobi</h1>
    	        <ul class="steps">
    	            <li class="current">informações pessoais</li>
    	            <li>informações do negócio</li>
    	            <li>customização e logotipo</li>
    	        </ul>
    	        <div class="clear"></div>
    	    </div>
    	    
            <?php echo $this->contentForLayout ?>
        </div>
        
        <div id="footer">
            <div id="footer-wrapper">
                s
            <div>
        </div>
        
        <?php echo $this->html->script('jquery', 'main') ?>
    </body>
</html>