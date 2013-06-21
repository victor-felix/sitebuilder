<fieldset style="position: relative;">
	<div class="themes">
		<div class="tip-big">
			<h2><?php echo s('customize your theme') ?></h2>
		</div>
		<div class="customize-theme">
			<ul class="featured-list">
				<li class="open">
					<div class="link">
						<span class="icon"></span>
						<h3><?php echo s('appearance') ?></h3>
						<small><?php echo s('Edit the colors of your mobile site.') ?></small>
						<span class="arrow open"></span>
					</div>
					<div class="content">
						<p class="title"><?php echo $theme->name() ?></p>
						<?php if($custom): ?>
						<?php echo $this->element('sites/skins_list', array(
							'skins' => $skins,
							'currentSkin' => $skin,
							'customizeLink' => false,
						)) ?>
						<?php endif ?>
						<?php foreach ($skin->assets() as $name => $asset): ?>
							<div class="form-grid-460">
							<?php echo $this->form->input("uploaded_assets[{$name}]", array(
								'type' => 'file',
								'label' => $name,
								'class' => 'ui-text'
							)) ?>
							</div>
						<?php endforeach ?>
						<div class="colors-wrap">
							<?php if ($custom): ?>
							<?php foreach ($skins as $themeSkin): ?>
								<?php
									if ($themeSkin->id() == $skin->parentId()
										|| $themeSkin->parentId() && $themeSkin->id() != $skin->id()) {
										continue;
									}
								?>
								<?php echo $this->element('skins/colors_list', array(
									'skin' => $themeSkin,
									'hide' => ($skin->id() != $themeSkin->id()),
									'custom' => $custom,
								)) ?>
							<?php endforeach ?>
						<?php else: ?>
							<?php echo $this->element('skins/colors_list', array(
								'skin' => $skin,
								'hide' => false,
								'custom' => $custom,
							)) ?>
						<?php endif ?>
						</div>
					</div>
				</li>
			</ul>
		</div>
	</div>

	<?php echo $this->form->input('main_color', array(
		'type' => 'hidden',
		'value' => '#' . $skin->mainColor(),
		'id' => 'main_color'
	)) ?>
	<?php echo $this->form->input('parent_id', array(
		'type' => 'hidden',
		'value' => $custom ? $skin->id() : null,
		'id' => 'parent_id'
	)) ?>
	<?php foreach ($skin->colors() as $name => $color) {
		if ($color) {
			echo $this->form->input("colors[{$name}]", array(
				'type' => 'hidden',
				'value' => $color,
				'id' => $name
			));
		}
	} ?>

	<?php echo $this->element('sites/theme_preview', array(
		'site' => $site,
		'autoload' => true,
		'skin' => $skin->id(),
	)) ?>
</fieldset>
