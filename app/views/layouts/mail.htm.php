<?php
	$logoPath = isset($site) && $site->logo() ? $site->logo()->link('200x200') : '/images/layout/logo.png';
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo isset($title) ? $title : MeuMobi::currentSegment()->title?></title>
    </head>
    <body bgcolor="#EFEFEF">
        <font face="Arial" color="#555">
        <table border="0" width="640" align="center">
            <!--tr>
                <td><br /><br /><img src="<?= MeuMobi::url($logoPath) ?>" alt="<?= $site->title ?>" /><br /><br /></td>
            </tr-->
            <tr>
                <td bgcolor="#FFFFFF">
                    <?php echo $this->contentForLayout ?>
                </td>
            </tr>
        </table>
        </font>
    </body>
</html>
