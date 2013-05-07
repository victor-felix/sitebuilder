<?php
	if (!$currentSkin && reset($skins)) {
		$currentSkin = reset($skins);
	}
?>
<ul class="skin-picker">
	<?php foreach($skins as $skin): ?>
		<?php
			$class = '';
			$skinId = $skin->id();
			if ($skin->id() == $currentSkin->id()) {
				if ($skin->parentId()) {
					continue;
				}
				$class .= 'selected';
			}
			if ($currentSkin->parentId() == $skin->id()) {
				$class .= 'custom selected';
				$skinId = $currentSkin->id();
			}
		?>
		<li class="<?php echo $class ?>" data-skin="<?php echo $skinId ?>">
			<span style="background-color: #<?php echo $skin->mainColor() ?>"></span>
		</li>
	<?php endforeach ?>
</ul>
<p class="customize-link">
	<?php echo $this->html->link(s('Customize'), '/sites/custom_theme/' . $currentSkin->id(), array(
		'class' => 'ui-button highlight push-scene',
		'data-link' => '/sites/custom_theme/'
	)) ?>
</p>