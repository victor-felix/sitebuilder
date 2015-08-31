<p>
	<?= s('Hi, <b>%s</b>. <br/><br/>You have requested the reset of your %s password.', $visitor->firstName(), $site->title) ?>
	<br />
	<br />
	<?= s('Your new password is: %s', $password) ?>
	<?= $this->element('visitors/footer', array(
		'email' => $site->email,
		'title' => $site->title
	)) ?>
</p>
