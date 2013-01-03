<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->controller->getSegment()->title, ' - ' , $this->pageTitle ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/base', 'shared/register', 'shared/edit-forms', 'shared/uikit', 'shared/themes','segment') ?>
    </head>
    
    <body>
        <div id="header">
            <?php echo $this->html->imagelink('layout/logo.png', '/', array(
                'alt' => $this->controller->getSegment()->title
            ), array(
                'class' => 'logo'
            )) ?>
            
            <?php  if($this->selectedTab == 0): ?>
            <p class="login right">
	            <?php echo s('Already have an account?');?>
	            <?php 
	            	 echo $this->html->link(s('Sign in ›'), '/users/login',  array(
		                'class' => ''
		            ));
				?>
            </p>
            <?php endif;?>
            
            <!-- dint understand this conditional, but keep it anyway -->
            <?php if(!isset($this->showTitle) || (isset($this->showTitle) && $this->showTitle)): ?>
                <div class="head">
                    <h1><?php echo s('Start your free trial in 3 simple steps') ?></h1>
                    <ul class="steps">
                        <li <?php if($this->selectedTab == 0): ?>class="current"<?php endif ?>>
                        	<h3>1</h3>
                            <?php echo s('Enter your personal information') ?>
                        </li>
                        <li <?php if($this->selectedTab == 1): ?>class="current"<?php endif ?>>
                            <h3>2</h3>
                            <?php echo s('Choose a theme for your mobile site') ?>
                        </li>
                        <li <?php if($this->selectedTab == 2): ?>class="current"<?php endif ?>>
                            <h3>3</h3>
                            <?php echo s('Enter your business description') ?>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            <?php endif ?>
        </div>
        
        <div id="content">
            
            <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element('layouts/footer') ?>
        
        <?php echo $this->html->script('shared/jquery', 'shared/support_chat', 'shared/jquery.formrestrict', 'shared/jquery.alphanumeric', 'shared/main',  'shared/async_upload', 'shared/themes') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
