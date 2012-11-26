<?php 
	$currentTheme = $site->theme ? $site->theme : $themes[0]->_id;
	$currentSkin = $site->skin ? $site->skin : key($themes[0]->colors);
?>

	<div class="themes">
		<div class="form-grid-570 first">
			<div class="tip-big">
				<h2><?php echo s('pick up a theme')?></h2>
				<p style="margin: 0 0 15px 0"><?php echo s('give your mobile a custom look with one of the themes bellow. choose the one that fits you best.') ?></p>
			</div>
			<div class="theme-picker">
				<ul>
					<?php foreach($themes as $theme): ?>
						<li class="<?php if($theme->_id == $currentTheme) echo 'selected'?>" data-theme="<?php echo $theme->_id ?>">
							<p class="thumbs">
							<?php foreach ($theme->thumbnails as $thumbnail): ?>
								<?php echo $this->html->image(Themes::thumbPath($thumbnail)) ?>
							<?php endforeach ?>
							</p>
							<span class="title"><?php echo $theme->name ?></span>
							
							<ul class="skin-picker">
								<?php 
								$skins = array_keys((array) $theme->colors);
								$currentThemeSkin = $currentTheme == $theme->_id && in_array($currentSkin, $skins)
													? $currentSkin
													: reset($skins);
								?>
								
								<?php foreach($skins as $skin): ?>
								<li class="<?php if($skin == $currentThemeSkin) echo 'selected';?>" data-skin="<?php echo $skin ?>">
									<span style="background-color:#<?php echo $skin ?>"></span>
								</li>
								<?php endforeach ?>
							</ul>
						</li>
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
	</div>
