<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->html->charset() ?>
        <title><?php echo __('[MeuMobi] Confirmação de Cadastro') ?></title>
    </head>
    
    <body>
        <p><?php echo __('Olá, %s. Estamos enviando esse e-mail para confirmar seu cadastro no MeuMobi. Para isso, clique no link abaixo e seu cadastro será confirmado automaticamente.', $user->firstname()) ?></p>
        <p><?php echo $this->html->link($url = Mapper::url('/users/confirm/' . $user->id . '/' . $user->token, true), $url) ?></p>
    </body>
</html>