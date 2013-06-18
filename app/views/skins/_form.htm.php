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
						<div class="colors-wrap">
							<ul id="color-picker" class="color-picker">
								<li>
									<span><?php echo s('Main Color') ?></span>
									<span class="color" data-color="main_color" data-value="#<?php echo $skin->mainColor() ?>" style="background-color: #<?php echo $skin->mainColor() ?>"></span>
								</li>
								<?php foreach ($skin->colors() as $name => $color): ?>
									<?php if ($color): ?>
										<li>
											<span><?php echo s($name) ?></span>
											<span class="color" data-color="<?php echo $name ?>" data-value="<?php echo $this->string->pad($color, 7, substr($color, -1)) ?>" style="background-color: <?php echo $color ?>"></span>
										</li>
									<?php endif ?>
								<?php endforeach ?>
							</ul>
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

	<?php foreach ($skin->colors() as $name => $color) {
		if ($color) {
			echo $this->form->input("colors[{$name}]", array(
				'type' => 'hidden',
				'value' => $color,
				'id' => $name
			));
		}
	} ?>

	<?php foreach ($skin->assets() as $name => $asset) {
		echo $this->form->input("uploaded_assets[{$name}]", array(
			'type' => 'file',
		));
	} ?>

	<?php echo $this->element('sites/theme_preview', array(
		'site' => $site,
		'autoload' => true,
		'skin' => $skin->id(),
	)) ?>
</fieldset>
