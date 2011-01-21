<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>MeuMobi - <?php echo $this->pageTitle ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png"); ?>" type="image/png" />
		<?php echo $this->html->stylesheet('base', 'uikit', 'categories', 'edit-forms', 'businessitems'); ?>
    </head>
    
    <body>
	
		<div id="header">
		    <div class="logo">
			    <?php echo $this->html->link($this->html->image('layout/logo.png', array('class'=>'MeuMobi')), '/', array('class'=>'logo')) ?>
			</div>
			<div class="menu">
			    <div class="navigation">
			        <p class="business-name"><?php echo Sanitize::html(Auth::user()->site()->title) ?></h1>
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
	
	    <?php if($success = Session::flash('success')): ?>
	    <a href="#" id="success-feedback"><?php echo $success ?></a>
	    <?php endif ?>
	    
	    <?php if($error = Session::flash('error')): ?>
	    <a href="#" id="error-feedback"><?php echo $error ?></a>
	    <?php endif ?>
	
	    <div id="content">
        <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element("layouts/footer") ?>
        
        <?php echo $this->html->script('jquery', 'main') ?>
    </body>
</html>