<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->pageTitle ?></title>

		<?php echo $this->html->stylesheet('base', 'uikit', 'categories', 'edit-forms', 'businessitems'); ?>
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
			            <p>Olá <strong><?php echo Auth::user()->firstname() ?></strong></p>
			            <?php echo $this->html->link('sair ›', '/logout') ?>
			        </div>
			    </div>
			    <ul>
			        <li><?php echo $this->html->link(Auth::user()->site()->rootCategory()->title, '/categories') ?></li>
			        <li><?php echo $this->html->link('Configurações', '/settings') ?></li>
			        <li><?php echo $this->html->link('Customização', '/settings/customize') ?></li>
			        <li><?php echo $this->html->link('Minha Conta', '/settings/account') ?></li>
			    </ul>
			</div>
			<div class="clear"></div>
		</div>
	
	    <div id="content">
        <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element("layouts/footer") ?>
        
        <?php echo $this->html->script('jquery', 'main') ?>
    </body>
</html>