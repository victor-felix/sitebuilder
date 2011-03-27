<p style="padding: 0 20px; font-size: small">
    <?php echo __('Olá, <b>%s</b>. <br/><br/>Você solicitou a redefinição de sua senha. Para isso, clique ou copie e cole o link abaixo e você poderá gerar uma nova senha.', $user->firstname()) ?>
</p>
<p style="padding: 0 20px">
    <?php echo $this->html->link($url = Mapper::url('/users/reset_password/' . $user->id . '/' . $user->token, true), $url, array('style' => 'color: #FF0000')) ?>
</p>