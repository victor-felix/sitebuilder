<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>:( <?php echo s('Page not found') ?> | <?php echo MeuMobi::currentSegment()->title ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/register', 'shared/uikit') ?>
    </head>

    <body>
        <div id="header">
            <?php echo $this->html->link($this->html->image('layout/logo.png', array('alt'=> MeuMobi::currentSegment()->title)), '/', array('class'=>'logo')); ?>
        </div>

        <div id="content" style="background:none;-webkit-box-shadow: none;-moz-box-shadow: none;border: 0">
            <div class="registration-finished" style="padding-bottom: 10px">
                <?php echo $this->html->image('shared/layout/error.png', array(
                    'alt' => s('Page not found')
                )) ?>
                <h2><?php echo s('Page not found') ?></h2>
                <div class="clear"></div>
            </div>
        </div>

        <?php echo $this->element("layouts/footer") ?>
        <?php echo $this->html->script('shared/jquery', 'shared/main') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
