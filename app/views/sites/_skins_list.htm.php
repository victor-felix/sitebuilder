<?php
	$customSkin = null;
	if ($currentSkin) {
		foreach($skins as $k => $skin) {
			if ($skin->parentId()) {
				$currentSkin = $skin->id();
				$customSkin = $skin;
				unset($skins[$k]);
			}
		}
	} else if (reset($skins)) {
		$currentSkin = reset($skins)->id();
	}
?>
<ul class="skin-picker">
	<?php foreach($skins as $skin): ?>
		<?php
			$class = '';
			$skinId = $skin->id();
			if ($customSkin && $customSkin->parentId() == $skin->id()) {
				$skinId = $customSkin->id();
				$class .= 'custom selected';
			} else if ($skin->id() == $currentSkin) {
				$class .= 'selected';
			}
		?>
		<li class="<?php echo $class ?>" data-skin="<?php echo $skinId ?>">
			<span style="background-color: #<?php echo $skin->mainColor() ?>"></span>
		</li>
	<?php endforeach ?>
</ul>
<p class="customize-link">
	<?php echo $this->html->link(s('Customize'), '/sites/custom_theme/' . $currentSkin, array(
		'class' => 'ui-button highlight push-scene',
		'data-link' => '/sites/custom_theme/'
	)) ?>
</p>