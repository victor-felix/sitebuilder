<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo s('home/index.pagetitle') ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/home', 'shared/uikit', 'segment') ?>
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

            <p class="login">
                <?php echo $this->html->link(s('Sign-in'), '/login') ?> <?php echo __('ou') ?> <?php echo $this->html->link(s('Sign-up'), '/register') ?>
            </p>

            <div class="get-started">
                <h2><?php echo s('home/index.title') ?></h2>
                <p class="subtitle"><?php echo s('home/index.subtitle') ?></p>
                <?php echo $this->html->link(s('create your mobi now!'), '/register') ?>
            </div>

            <div id="slideshow">
                <?php echo $this->html->image('home/slides/iphone.png'); ?>
                <?php echo $this->html->image('home/slides/blackberry.png'); ?>
                <?php echo $this->html->image('home/slides/android.png'); ?>
            </div>

            <div class="clear"></div>
            <div id="login-window">
                <p><?php echo $this->html->link(s('Sign-in'), '/login') ?></p>
                <?php echo $this->form->create('/users/login') ?>
                    <?php echo $this->form->input('email', array(
                        'label' => s('E-Mail'),
                        'class' => 'ui-text'
                    )) ?>
                    
                    <?php echo $this->form->input('password', array(
                        'label' => s('Password'),
                        'class' => 'ui-text'
                    )) ?>
                    
                    <?php echo $this->form->input('remember', array(
                        'label' => false,
                        'type' => 'checkbox'
                    )) ?>
                    
                    <label for="FormRemember" class="checkbox"><?php echo s('Remember me') ?></label>
                    
                    <?php echo $this->html->link(s('Forgot password?'), '/users/forgot_password', array(
                        'class' => 'forgot-password'
                    )) ?>
                    
                    <?php echo $this->form->submit(s('Sign-in'), array(
                        'class' => 'ui-button red'
                    ))?>
                    
                    <?php echo $this->html->link(s('cancel'), '#', array(
                        'class' => 'cancel'
                    )) ?>
                <?php echo $this->form->close() ?>
            </div>
        </div>

        <div id="content">
            <?php echo $this->contentForLayout ?>
        </div>

        <?php echo $this->element('layouts/footer') ?>

        <?php echo $this->html->script('shared/jquery', 'shared/jquery.cycle', 'shared/home') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
