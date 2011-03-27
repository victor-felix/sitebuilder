<p style="padding: 0 20px; font-size: small">
    <?php echo __('Olá, <b>%s</b>. <br/><br/>Estamos enviando esse e-mail para confirmar seu cadastro no MeuMobi. Para isso, clique ou copie e cole o link abaixo e seu cadastro será ativado automaticamente.', $user->firstname()) ?>
</p>
<p style="padding: 0 20px">
    <?php echo $this->html->link($url = Mapper::url('/users/confirm/' . $user->id . '/' . $user->token, true), $url, array('style' => 'color: #FF0000')) ?>
</p>