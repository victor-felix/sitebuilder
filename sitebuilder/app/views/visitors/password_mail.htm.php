<?= s('visitors/mail/add.body') ?>
<ul>
	<li>
		<?= $this->html->link('Como instalar InfoBox no iOS', Mapper::url('/docs/Como_Instalar_InfoBox-iOS.pdf', true)) ?>
	</li>
	<li>
		<?= $this->html->link('Como instalar InfoBox no Android', Mapper::url('/docs/Como_Instalar_InfoBox-Android.pdf', true)) ?>
	</li>
</ul>
<br />
Suas informações de acesso são:
<br />
<strong>email:</strong> <?= $email ?>
<br />
<strong>senha:</strong> <?= $password ?>
<?= $this->element('visitors/footer') ?>
