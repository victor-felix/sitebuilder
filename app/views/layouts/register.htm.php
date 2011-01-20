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
	        <?php if(!isset($this->showTitle) || (isset($this->showTitle) && $this->showTitle)): ?>
    	    <div class="head">
    	        <h1>crie seu mobi</h1>
    	        <ul class="steps">
    	            <li class="current">informações pessoais</li>
    	            <li>informações do negócio</li>
    	            <li>customização e logotipo</li>
    	        </ul>
    	        <div class="clear"></div>
    	    </div>
    	    <?php endif ?>
    	    
            <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element("layouts/footer") ?>
        
        <?php echo $this->html->script('jquery', 'main') ?>
    </body>
</html>