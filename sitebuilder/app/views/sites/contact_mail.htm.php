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
	<?= s('Powered by') ?> <b><?= MeuMobi::currentSegment()->title ?></b> - <?= $this->html->link('/', null, [], true) ?> | <b>Support:</b> <a href="mailto:contact@meumobi.com" target="_blank">contact@meumobi.com</a>
</p>
