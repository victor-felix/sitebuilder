<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $this->pageTitle ?></title>
    </head>
    
    <body>
        <?php echo $this->contentForLayout ?>
        
        <?php echo $this->html->script('jquery', 'main') ?>
    </body>
</html>