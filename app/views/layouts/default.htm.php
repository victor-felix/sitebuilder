<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>MeuMobi - <?php echo $this->pageTitle ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url('/images/layout/favicon.png') ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/base', 'shared/uikit', 'shared/categories',
            'shared/edit-forms', 'shared/businessitems', 'segment', 'shared/markitup.simple',
            'shared/markitup.xbbcode', 'shared/chosen') ?>
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
                <div class="navigation" id="navbar">
                    <div class="sites">
                        <p class="business-name"><span><?php echo e(Auth::user()->site()->title) ?></span></p>
                        <p class="share">
                            http://<?php echo e(Auth::user()->site()->domain) ?>
                            <!-- *
                            <a id="share_site" href="<?php echo e(Auth::user()->site()->domain) ?>"><?php echo s('share url') ?></a> -->
                        </p>
                        <div class="site-switcher">
                            <p><?php echo s('My mobi sites');?></p>
                            <ul>
                                <li><a href="#">
                                    <span class="site-name">
                                        <span><?php echo e(Auth::user()->site()->title) ?></span>
                                        <small>http://<?php echo e(Auth::user()->site()->domain) ?></small>
                                    </span>
                                    <span class="status current"><?php echo s('current site')?></span>
                                </a></li>
                                <?php foreach (Auth::user()->sites(true) as $site): ?>
                                <li>
	                                <a href="<?php echo Mapper::url('/users/change_site/'.$site->id) ?>" >
	                                    <span class="site-name">
	                                        <span><?php echo e($site->title) ?></span>
	                                        <small><?php echo e($site->domain) ?></small>
	                                    </span>
	                                    <span class="status edit"><?php echo s('edit site ›')?></span>
	                                </a>
                                </li>
                                <?php endforeach; ?>
                                <li class="new"><a href="<?php echo Mapper::url('/sites/add') ?>"><?php echo s('Create a new mobi ›') ?></a></li>

                            </ul>
                        </div>
                    </div>
                    <div class="user">
                        <p><?php echo s('Hi <strong>%s</strong>', e(Auth::user()->firstname())) ?></p>
                        <ul>
                            <li><?php echo $this->html->link(s('My Account'), '/settings/account') ?></li>
                            <li><?php echo $this->html->link(s('Log out ›'), '/logout') ?></li>
                        </ul>
                        <!-- <?php echo $this->html->link(s('Log out ›'), '/logout') ?> -->
                    </div>
                </div>
                <ul>
                    <?php if(!Auth::user()->site()->hide_categories): ?>
                        <li><?php echo $this->html->link(e(Auth::user()->site()->rootCategory()->title), '/categories') ?></li>
                    <?php endif ?>
                    <li><?php echo $this->html->link(s('Settings'), '/settings') ?></li>
                    <li><?php echo $this->html->link(s('Customization'), '/settings/customize') ?></li>
                    <!-- <li><?php echo $this->html->link(s('My Account'), '/settings/account') ?></li> -->
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

        <?php echo $this->html->script('shared/jquery', 'shared/main', 'shared/markitup', 'shared/async_upload', 'shared/jquery.chosen', 'shared/paginate') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
