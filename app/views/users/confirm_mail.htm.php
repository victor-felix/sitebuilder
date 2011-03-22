<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo __('[MeuMobi] Confirmação de Cadastro') ?></title>
    </head>
    
    <body bgcolor="#EFEFEF">
        <font face="Arial" color="#555">
        <table border="0" width="640" align="center">
            <tr>
                <td><br /><br /><?php echo $this->html->imagelink(Mapper::url('/images/layout/logo.png', true), Mapper::url("/", true), array(), array(), true) ?><br /><br /></td>
            </tr>
            <tr>
                <td bgcolor="#FFFFFF">
                    <p style="padding: 0 20px; font-size: small"><?php echo __('Olá, <b>%s</b>. <br/><br/>Estamos enviando esse e-mail para confirmar seu cadastro no MeuMobi. Para isso, clique ou copie e cole o link abaixo e seu cadastro será ativado automaticamente.', $user->firstname()); ?></p>
                    <p style="padding: 0 20px"><?php echo $this->html->link($url = Mapper::url('/users/confirm/' . $user->id . '/' . $user->token, true), $url, array('style' => 'color: #FF0000')) ?></p>
                </td>
            </tr>
        </table>
        </font>
    </body>
</html>