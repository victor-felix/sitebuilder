<?= s('visitors/mail/add.body', $site->title) ?>
<br /><br />
Suas informações de acesso são:
<br />
<strong>email:</strong> <?= $email ?>
<br />
<strong>senha:</strong> <?= $password ?>
<?= $this->element('visitors/footer', array(
	'email' => $site->email,
	'title' => $site->title
)) ?>
