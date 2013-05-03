<?php
	$currentTheme = $site->theme ? $site->theme : reset($themes)->name();
	$skins = reset($themes)->skins();
	$currentSkin = $site->skin ? $site->skin : reset($skins)->id();
?>

<div class="themes">
	<div class="tip-big">
		<h2><?php echo s('pick up a theme') ?></h2>
		<p><?php echo s('give your mobile a custom look with one of the themes bellow. choose the one that fits you best.') ?></p>
	</div>
	<div class="theme-picker">
		<ul>
			<?php foreach (array_chunk($themes, 3) as $theme_line): ?>
				<?php foreach ($theme_line as $theme): ?>
					<li class="<?php if($theme->id() == $currentTheme) echo 'selected'?>" data-theme="<?php echo $theme->id() ?>">
						<p class="thumbs">
							<?php foreach ($theme->thumbnails() as $thumbnail): ?>
								<?php echo $this->html->image($thumbnail) ?>
							<?php endforeach ?>
						</p>
						<span class="title"><?php echo $theme->name() ?></span>

						<ul class="skin-picker">
							<?php
								$skins = $theme->skins();
								$currentThemeSkin = null;
								if ($currentTheme == $theme->id()) {
									$currentThemeSkin = $currentSkin;
								} elseif(reset($skins)) {
									$currentThemeSkin = reset($skins)->id();
								}
							?>

							<?php foreach($skins as $skin): ?>
								<li class="<?php if($skin->id() == $currentThemeSkin) echo 'selected' ?>" data-skin="<?php echo $skin->id() ?>">
									<span style="background-color: #<?php echo $skin->mainColor() ?>"></span>
								</li>
							<?php endforeach ?>
						</ul>
					</li>
				<?php endforeach ?>
				<li class="clear"></li>
			<?php endforeach ?>
		</ul>
		<div class="clear"></div>
	</div>

	<?php echo $this->form->input('theme', array(
		'type' => 'hidden',
		'value' => $currentTheme,
		'id' => 'theme'
	)) ?>

	<?php echo $this->form->input('skin', array(
		'type' => 'hidden',
		'value' => $currentSkin,
		'id' => 'skin'
	)) ?>
</div>
