<p style="padding: 0 20px; font-size: small">
    <?php echo s('Hi, <b>%s</b>. <br/><br/>You have requested the reset of your MeuMobi password. In order to do that you should click on link below.', $user->firstname()) ?>
</p>
<p style="padding: 0 20px">
    <?php echo $this->html->link($url = Mapper::url('/users/reset_password/' . $user->id . '/' . $user->token, true), $url, array('style' => 'color: #FF0000')) ?>
</p>
