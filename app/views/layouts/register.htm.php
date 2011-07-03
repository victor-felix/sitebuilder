<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>MeuMobi - <?php echo $this->pageTitle ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/register', 'shared/uikit', 'segment') ?>
    </head>
    
    <body>
        <div id="header">
            <?php echo $this->html->imagelink('layout/logo.png', '/', array(
                'alt' => 'MeuMobi'
            ), array(
                'class' => 'logo'
            )) ?>
        </div>
        
        <div id="content">
            <?php if(!isset($this->showTitle) || (isset($this->showTitle) && $this->showTitle)): ?>
                <div class="head">
                    <h1><?php echo s('Create your Mobi') ?></h1>
                    <ul class="steps">
                        <li <?php if(!isset($this->selectedTab) || $this->selectedTab == 0): ?>class="current"<?php endif ?>>
                            <?php echo s('Personal details') ?>
                        </li>
                        <li <?php if(isset($this->selectedTab) && $this->selectedTab == 1): ?>class="current"<?php endif ?>>
                            <?php echo s('Business details') ?>
                        </li>
                        <li <?php if(isset($this->selectedTab) && $this->selectedTab == 2): ?>class="current"<?php endif ?>>
                            <?php echo s('Customization and logo') ?>
                        </li>
                    </ul>
                    <div class="clear"></div>
                </div>
            <?php endif ?>
            
            <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element('layouts/footer') ?>
        
        <?php echo $this->html->script('shared/jquery', 'shared/main') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
