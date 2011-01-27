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
			        <p class="business-name"><?php echo e(Auth::user()->site()->title) ?></h1>
			        <div class="user">
			            <p><?php echo __('Olá <strong>%s</strong>', e(Auth::user()->firstname())) ?></p>
			            <?php echo $this->html->link(__('sair ›'), '/logout') ?>
			        </div>
			    </div>
			    <ul>
			        <li><?php echo $this->html->link(e(Auth::user()->site()->rootCategory()->title), '/categories') ?></li>
			        <li><?php echo $this->html->link(__('Configurações'), '/settings') ?></li>
			        <li><?php echo $this->html->link(__('Customização'), '/settings/customize') ?></li>
			        <li><?php echo $this->html->link(__('Minha Conta'), '/settings/account') ?></li>
			    </ul>
			</div>
			<div class="clear"></div>
		</div>
	
	    <?php if($success = Session::flash('success')): ?>
    	    <a href="#" id="success-feedback"><?php echo __($success) ?></a>
	    <?php endif ?>
	    
	    <?php if($error = Session::flash('error')): ?>
    	    <a href="#" id="error-feedback"><?php echo __($error) ?></a>
	    <?php endif ?>

	    <div id="content">
            <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element("layouts/footer") ?>
        
        <?php echo $this->html->script('jquery', 'main') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>