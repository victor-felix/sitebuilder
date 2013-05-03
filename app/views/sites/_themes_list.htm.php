<?php
	$currentTheme = $site->theme ? $site->theme : reset($themes)->name();
	$currentSkin = $site->skin ? $site->skin : reset($themes)->skins()[0]->id();
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
								$customSkin = null;
								if ($currentTheme == $theme->id()) {
									$currentThemeSkin = $currentSkin;
									foreach ($skins as $skin) {
										if ($skin->parentId()) {
											$customSkin = $skin->parentId();
										}
									}
								} else {
									$currentThemeSkin = reset($skins)->id();
								}
							?>

							<?php foreach($skins as $skin): ?>
								<?php
									$class = '';
									if ($skin->id() == $currentThemeSkin) {
										$class .= 'selected';
										if ($skin->id() == $customSkin) {
											$class .= 'custom';
										}
									}
								?>
								<li class="<?php echo $class ?>" data-skin="<?php echo $skin->id() ?>">
									<span style="background-color: #<?php echo $skin->mainColor() ?>"></span>
								</li>
							<?php endforeach ?>
						</ul>
						<p class="customize-link">
							<?php echo $this->html->link(s('Customize'), '/sites/custom_theme/' . $currentThemeSkin, array(
								'class' => 'ui-button highlight push-scene',
								'data-link' => '/sites/custom_theme/'
							)) ?>
						</p>
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
