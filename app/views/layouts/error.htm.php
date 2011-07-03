<?php if(Config::read('App.environment') == 'development'): ?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $exception->getMessage() ?> - Spaghetti* Framework</title>
        <style type="text/css">
            body { background: #23201E; font: 14px Helvetica, Arial, sans-serif; margin: 0; padding: 30px; }
            h1 { color: #9c0; font: lighter 36px "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, sans-serif; margin: 0; }
            dl { background: #3D3734; font-size: 16px; padding: 20px; }
            dt { color: #9c0; }
            dd { background: #322D2B; color: #fff; margin: 5px 0 15px; padding: 20px; }
            dd, dl, pre { -moz-border-radius: 4px; -webkit-border-radius: 4px; border-radius: 4px; }
            pre, code { color: #fff; font: 12px Monaco, Consolas, 'Courier New', monospace; background: #322D2B; margin: 0; overflow: auto; }
            code { margin: 0px 4px; }
        </style>
    </head>
    
    <body>
        <h1><?php echo $exception->getMessage() ?></h1>
        <dl>
            <dt>Details:</dt>
            <dd><?php echo $exception->getDetails() ?></dd>
            <dt>Stack Trace:</dt>
            <dd><pre><?php echo $exception ?></pre></dd>
        </dl>
    </body>
</html>
<?php else: ?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title>:( <?php echo s('Page not found') ?> - MeuMobi</title>
        <link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
        <?php echo $this->html->stylesheet('shared/register', 'shared/uikit') ?>
    </head>

    <body>
        <div id="header">
            <?php echo $this->html->link($this->html->image('layout/logo.png', array('alt'=>'MeuMobi')), '/', array('class'=>'logo')); ?>
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
    </body>
</html>
<?php endif ?>