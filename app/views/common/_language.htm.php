<p><?= s('Choose your language:') ?></p>
<ul>
	<?php foreach(I18n::availableLanguages() as $language): ?>
		<li><?= $this->html->link(s($language), '/' . $language) ?></li>
	<?php endforeach ?>
</ul>
