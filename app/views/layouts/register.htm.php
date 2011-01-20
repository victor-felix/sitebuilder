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
    	            <li <?php if(!isset($this->selectedTab) || $selectedTabclass == 0): ?>class="current"<?php endif ?>>informações pessoais</li>
    	            <li <?php if(isset($this->selectedTab) && $selectedTabclass == 1): ?>class="current"<?php endif ?>>informações do negócio</li>
    	            <li <?php if(isset($this->selectedTab) && $selectedTabclass == 2): ?>class="current"<?php endif ?>>customização e logotipo</li>
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