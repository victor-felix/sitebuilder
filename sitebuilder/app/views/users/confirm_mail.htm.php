<p style="padding: 0 20px; font-size: small">
    <?php echo s('Hi, <b>%s</b>. <br/><br/>You\'ve received this mail to confirm your MeuMobi Sign-up. Please click on link below to confirm your mail adress.', $user->firstname()) ?>
</p>
<p style="padding: 0 20px">
    <?php echo $this->html->link($url = Mapper::url('/users/confirm/' . $user->id . '/' . $user->token, true), $url, array('style' => 'color: #FF0000')) ?>
</p>