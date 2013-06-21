<ul id="color-picker-<?php echo $skin->parentId() ? $skin->parentId() : $skin->id() ?>" class="color-picker <?php if ($hide) echo 'hidden' ?>">
	<?php if (!$custom): ?>
	<li>
		<span><?php echo s('Main Color') ?></span>
		<span class="color" data-color="main_color" data-value="#<?php echo $skin->mainColor() ?>" style="background-color: #<?php echo $skin->mainColor() ?>"></span>
	</li>
	<?php endif ?>
	<?php $colorCount = 1 ?>
	<?php foreach($skin->colors() as $name => $color): ?>
	<?php if ($color): ?>
		<li>
			<span><?php echo s('color') . ' #' . $colorCount++ ?></span>
			<span class="color" data-color="<?php echo $name ?>" data-value="<?php echo $this->string->pad($color, 7, substr($color, -1)) ?>" style="background-color: <?php echo $color ?>"></span>
		</li>
	<?php endif ?>
	<?php endforeach ?>
</ul>