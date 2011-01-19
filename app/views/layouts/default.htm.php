<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->pageTitle ?></title>

		<?php echo $this->html->stylesheet('base'); ?>
    </head>
    
    <body>
	
		<div id="header">
		    <div class="logo">
			    <?php echo $this->html->link($this->html->image('layout/logo.png', array('class'=>'MeuMobi')), '/', array('class'=>'logo')); ?>
			</div>
			<div class="menu">
			    <div class="navigation">
			        <h1>Balada Mix</h1>
			        <div class="user">
			            <p>Olá <strong>Rafael</strong></p>
			            <?php echo $this->html->link('sair ›', '/'); ?>
			        </div>
			    </div>
			    <ul>
			        <li><?php echo $this->html->link('Cardápio', '/'); ?></li>
			        <li><?php echo $this->html->link('Configurações', '/'); ?></li>
			        <li><?php echo $this->html->link('Customização', '/'); ?></li>
			        <li><?php echo $this->html->link('Minha Conta', '/'); ?></li>
			    </ul>
			</div>
			<div class="clear"></div>
		</div>
	
	    <div id="content">
        <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->html->script('jquery', 'main') ?>
    </body>
</html>