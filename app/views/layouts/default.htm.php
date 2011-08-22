<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>MeuMobi - <?php echo $this->pageTitle ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url('/images/layout/favicon.png') ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/base', 'shared/uikit', 'shared/categories',
            'shared/edit-forms', 'shared/businessitems', 'segment', 'shared/markitup.simple',
            'shared/markitup.xbbcode') ?>
        <?php echo $this->html->stylesForLayout ?>
    </head>
    
    <body>
    
        <div id="header">
            <div class="logo">
                <?php echo $this->html->imagelink('layout/logo.png', '/', array(
                    'alt' => 'MeuMobi'
                ), array(
                    'class' => 'logo'
                )) ?>
            </div>
            <div class="menu">
                <div class="navigation">
                    <p class="business-name"><?php echo e(Auth::user()->site()->title) ?></h1>
                    <div class="user">
                        <p><?php echo s('Hi <strong>%s</strong>', e(Auth::user()->firstname())) ?></p>
                        <?php echo $this->html->link(s('Log out ›'), '/logout') ?>
                    </div>
                </div>
                <ul>
                    <?php if(!Auth::user()->site()->hide_categories): ?>
                        <li><?php echo $this->html->link(e(Auth::user()->site()->rootCategory()->title), '/categories') ?></li>
                    <?php endif ?>
                    <li><?php echo $this->html->link(s('Settings'), '/settings') ?></li>
                    <li><?php echo $this->html->link(s('Customization'), '/settings/customize') ?></li>
                    <li><?php echo $this->html->link(s('My Account'), '/settings/account') ?></li>
                </ul>
            </div>
            <div class="clear"></div>
        </div>
    
        <?php if($success = Session::flash('success')): ?>
            <a href="#" id="success-feedback"><?php echo s($success) ?></a>
        <?php endif ?>
        
        <?php if($error = Session::flash('error')): ?>
            <a href="#" id="error-feedback"><?php echo s($error) ?></a>
        <?php endif ?>

        <div id="content">
            <?php echo $this->contentForLayout ?>
        </div>
        
        <?php echo $this->element('layouts/footer') ?>
        
        <?php echo $this->html->script('shared/jquery', 'shared/main', 'shared/markitup', 'shared/async_upload') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
