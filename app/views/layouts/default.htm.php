<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->pageTitle ?></title>

		<?php echo $this->html->stylesheet('base', 'uikit'); ?>
    </head>
    
    <body>
	
		<div id="header">
		    <div class="logo">
			    <?php echo $this->html->link($this->html->image('layout/logo.png', array('class'=>'MeuMobi')), '/', array('class'=>'logo')) ?>
			</div>
			<div class="menu">
			    <div class="navigation">
			        <p class="business-name">Balada Mix</h1>
			        <div class="user">
			            <p>Olá <strong>Rafael</strong></p>
			            <?php echo $this->html->link('sair ›', '/') ?>
			        </div>
			    </div>
			    <ul>
			        <li><?php echo $this->html->link('Cardápio', '/categories') ?></li>
			        <li><?php echo $this->html->link('Configurações', '/settings') ?></li>
			        <li><?php echo $this->html->link('Customização', '/settings/customization') ?></li>
			        <li><?php echo $this->html->link('Minha Conta', '/settings/account') ?></li>
			    </ul>
			</div>
			<div class="clear"></div>
		</div>
	
	    <div id="content">
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