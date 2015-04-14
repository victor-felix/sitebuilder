<p>
	Olá,
	<br />
	<br />
	Você foi convidado para testar o Aplicativo Infobox de Comunicação Interna da Siemens. Por meio dele, divulgaremos notícias importantes sobre a empresa, de forma fácil, privada e segura.
	<br />
	<br /> 
	O App Infobox é compatível com smartphones Android e iOS. Através do celular, abra este email e click no link a seguir para iniciar a instalação: <a href="https://build.phonegap.com/apps/843971/install">https://build.phonegap.com/apps/843971/install</a>
	<br />
	<br />
	Se preferir, faça o download usando o QR code seguinte:
	<br />
	<img class="qr-code" src="https://chart.googleapis.com/chart?chs=116x116&amp;cht=qr&amp;chl=https://build.phonegap.com/apps/843971/install/z7yxG25z65W7pzxAve5E&amp;chld=L|1&amp;choe=UTF-8">
	<br />
	<br />
	Se encontrar dificuldades para instalar InfoBox Segue o passo a passo seguinte: 
	<ul>
		<li>
			<?= $this->html->link(Mapper::url('/docs/Como_Instalar_InfoBox-iOS.pdf', true), 'Como instalar InfoBox no iOS') ?>
		</li>
		<li>
			<?= $this->html->link(Mapper::url('/docs/Como_Instalar_InfoBox-Android.pdf', true), 'Como instalar InfoBox no Android') ?>
		</li>
	</ul>
	<br />
	Suas informações de acesso são:
	<br />
	<strong>email:</strong> <?= $email ?>
	<br />
	<strong>senha:</strong> <?= $password ?>
	<?= $this->element('visitors/footer') ?>
</p>
