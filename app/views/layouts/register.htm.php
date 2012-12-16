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
        </div>
        
        <div id="content">
            <?php if(!isset($this->showTitle) || (isset($this->showTitle) && $this->showTitle)): ?>
                <div class="head">
                    <h1><?php echo s('Create your Mobi') ?></h1>
                    <ul class="steps">
                        <li <?php if($this->selectedTab == 0): ?>class="current"<?php endif ?>>
                            <?php echo s('Personal details') ?>
                        </li>
                        <li <?php if($this->selectedTab == 1): ?>class="current"<?php endif ?>>
                            <?php echo s('Customization') ?>
                        </li>
                        <li <?php if($this->selectedTab == 2): ?>class="current"<?php endif ?>>
                            <?php echo s('Business details') ?>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            <?php endif ?>
            
            <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element('layouts/footer') ?>
        
        <?php echo $this->html->script('shared/jquery', 'shared/main',  'shared/async_upload', 'shared/themes') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
