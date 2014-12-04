<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo $title ?></title>
    </head>
<?php
	$logoPath = isset($site) && $site->logo() ? $site->logo()->link('200x200') : '/images/layout/logo.png';
?>
    <body bgcolor="#EFEFEF">
        <font face="Arial" color="#555">
        <table border="0" width="640" align="center">
            <tr>
                <td><br /><br /><?php echo $this->html->imagelink(MeuMobi::url($logoPath), 'http://'.$site->domain()) ?><br /><br /></td>
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
