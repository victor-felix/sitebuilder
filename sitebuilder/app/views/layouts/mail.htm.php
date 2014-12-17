<?php $logo = isset($site) && $site->logo() ? $site->logo()->link('200x200') : false ?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo isset($title) ? $title : MeuMobi::currentSegment()->title?></title>
    </head>
    <body bgcolor="#EFEFEF">
        <font face="Arial" color="#555">
        <table border="0" width="640" align="center">
            <tr>
                <td><br /><br />
                    <?php if($logo): ?>
                        <img src="<?= MeuMobi::url($logo) ?>" alt="<?= $site->title ?>" />
                    <?php else: ?>
                        <h1><?= $site->title ?></h1>
                    <?php endif ?>
                <br /><br /></td>
            </tr>
            <tr>
                <td bgcolor="#FFFFFF">
                    <?php echo $this->contentForLayout ?>
                </td>
            </tr>
        </table>
        </font>
    </body>
</html>
