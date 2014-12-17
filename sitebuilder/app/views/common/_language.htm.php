<ul class="dropdown language">
	<li>
		<p><?= s('language') ?>: <b><?= s($currentLanguage) ?></b></p>
		<ul>
			<li>
				<small><?= s('select a language') ?></small>
			</li>
			<?php foreach(I18n::availableLanguages() as $language): ?>
				<?php if ($language == $currentLanguage): ?>
					<li class="current">
						<?= s($language) ?>
						<span><?= s('selected') ?></span>
					</li>
				<?php else: ?>
					<li><?= $this->html->link(s($language), '/' . $language) ?></li>
				<?php endif ?>
			<?php endforeach ?>
		</ul>
	</li>
</ul>