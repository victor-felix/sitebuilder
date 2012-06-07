<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->pageTitle ?></title>
        <link rel="shortcut icon" href="<?php echo Mapper::url('/images/layout/favicon.png') ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/register', 'shared/uikit', 'shared/login', 'segment') ?>
    </head>
    <body>

        <div id="header">
        <?php if(Users::signupIsEnabled()): ?>
            <?php echo $this->html->imagelink('layout/logo.png', '/', array(
                'alt' => 'MeuMobi'
            ), array(
                'class' => 'logo'
            )) ?>
           <?php else: ?>
            <h2 class="logo"><?php echo $this->controller->getSegment()->title ?></h2>
            <?php endif; ?>
        </div>

        <?php echo $this->element('layouts/flash') ?>

        <div id="content">
            <?php echo $this->contentForLayout ?>
        </div>

        <?php echo $this->element('layouts/footer') ?>

        <?php echo $this->html->script('shared/jquery', 'shared/main') ?>
        <?php echo $this->html->scriptsForLayout ?>
    </body>
</html>
