<?php if ($site->private): ?>
<li id="visitors">
	<a class="link" href="<?php echo Mapper::url('/visitors') ?>">
		<i class="icons fa fa-4x fa-users"></i>
		<h3><?php echo s('Visitors') ?></h3>
		<small><?php echo s('manage your visitors') ?></small>
		<i class="arrow fa fa-4x fa-angle-right"></i>
	</a>
</li>
<?php endif ?>
