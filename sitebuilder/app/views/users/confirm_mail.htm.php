<p>
    <?= s('Hi, <b>%s</b>. <br/><br/>You\'ve received this email to confirm your %s Sign-up. Please click on link below to confirm your email adress.', $user->firstname(), $segment->title) ?>
</p>
<br /><br />
<p>
    <?= $this->html->link(s('Click here to confirm your email address') ,"/users/confirm/{$user->id}/{$user->token}", [], true) ?>
</p>
