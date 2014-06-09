<p style="padding: 0 20px;">
    <?php echo s('Hi! <br /><br />You\'ve received this mail from a contact form on your %s website.', $site->title) ?>
</p>
<p style="padding: 0 20px">
	<?php echo s('Name') ?>: <?php echo $name ?><br />
	<?php echo s('Email') ?>: <?php echo $mail ?><br />
	<?php echo s('Phone') ?>: <?php echo $phone ?>
</p>
<p style="padding: 0 20px">
	<?php echo $message ?>
</p>

<p>
<?= s('Powered by') ?> <b><?= \MeuMobi::currentSegment()->title ?></b> - <a href="<?= Mapper::url('/', true) ?>" target="_blank"><?= Mapper::url('/', true) ?></a> | <b>Support:</b> <a href="mailto:contact@meumobi.com" target="_blank">contact@meumobi.com</a>
</p>
