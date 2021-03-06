<?php
	if (!$currentSkin && reset($skins)) {
		$currentSkin = reset($skins);
	}
?>
<ul class="skin-picker">
	<?php foreach($skins as $skin): ?>
		<?php
			$class = '';
			$customSkinId = null;
			if ($skin->parentId()) {
				continue;
			}
			if ($skin->id() == $currentSkin->id()) {
				$class .= 'selected';
			}
			if ($currentSkin->parentId() == $skin->id()) {
				$class .= 'custom selected';
				$customSkinId = $currentSkin->id();
			}
		?>
		<li class="<?php echo $class ?>" data-skin="<?php echo $skin->id() ?>" data-custom="<?php echo $customSkinId ?>">
			<span style="background-color: #<?php echo $skin->mainColor() ?>"></span>
		</li>
	<?php endforeach ?>
</ul>
<?php if ($currentSkin && $customizeLink): ?>
<p class="customize-link">
	<?php echo $this->html->link(s('Customize'), '/sites/custom_theme/' . $currentSkin->id(), array(
		'class' => 'ui-button highlight push-scene',
		'data-link' => '/sites/custom_theme/'
	)) ?>
</p>
<?php endif ?>