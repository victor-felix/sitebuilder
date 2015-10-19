<p>
	<?= s('Hi! <br /><br />You have received this mail from a contact form on your %s website.', $site->title) ?>
</p>
<br /><br />
<p>
	<?= s('Name') ?>: <?= $name ?>
	<br /><?= s('Email') ?>: <?= $mail ?>
	<?php if($phone): ?>
		<br /><?= s('Phone') ?>: <?= $phone ?>
	<?php endif ?>
</p>
<br /><br />
<p>
	<?= $message ?>
</p>
<br /><br />
<p>
	<?= s('Powered by') ?> <b><?= $segment->title ?></b> - <?= $this->html->link('/', null, [], true, 'MeuMobi') ?> | <b>Support:</b> <?= $this->html->link(s('contact@meumobi.com'), 'mailto:' . s('contact@meumobi.com'), ['target' => '_blank'], true) ?>
</p>
