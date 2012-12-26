<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo s('home/index.pagetitle') ?></title>
		<link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
		<?php echo $this->html->stylesheet('shared/bootstrap.min', 'shared/home') ?>
	</head>

	<body>
		<div class="header container">
			<h1 class="pull-left">
				<img alt="MeuMobi" src="/images/shared/home/logo.png" />
				<span class="border"></span>
			</h1>
			<div class="pull-right">
				<p class="call pull-left">
					<span><?php echo s('need convincing? call us')?></span>
					<b><?php echo s('+55 21 4042.7270')?></b>
				</p>
				<p class="login pull-left">
					<?php echo $this->html->link(s('sign up now'), '/signup/user', array(
							'class' => 'active'
						)) ?>
						<?php echo s('or')?>
					<?php echo $this->html->link( s('Sign in ›'), '/users/login', array(
						'class' => ''
					)) ?>
				</p>
			</div>
		</div>

		<?php echo $this->contentForLayout ?>
		
		<?php echo $this->element('layouts/footer') ?>
		
		<?php echo $this->html->script('shared/jquery', 'shared/jquery.carouFredSel-6.1.0-packed','shared/jquery.touchSwipe.min', 'shared/jquery.ba-throttle-debounce.min', 'shared/home') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
