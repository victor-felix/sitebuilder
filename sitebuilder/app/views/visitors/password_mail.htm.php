<?= s('visitors/mail/add.body', $first_name, $site->title) ?>
<br /><br />
Suas informações de acesso são:
<br />
<strong>email:</strong> <?= $email ?>
<br />
<strong>senha:</strong> <?= $password ?>
<?= s('visitors/mail.footer', $site->email, $site->title) ?>
