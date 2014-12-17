<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language ?>" lang="<?php echo $language ?>">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php echo s('Create professional websites for all mobile devices easily. Advanced features such as store locator for your chain stores.') ?>" />
		<meta name="keywords" content="<?php echo s('mobile website, mobilize website, design mobile website, create mobile website, build mobile website, business, menu, restaurant') ?>" />
		<title><?php echo s('Create a mobile website easily') . ' | ' . $this->controller->getSegment()->title ?></title>
		<link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
		<?php echo $this->html->stylesheet('shared/bootstrap.min', 'shared/home', 'shared/dropdown') ?>
	</head>

	<body>
		<div class="header container">
			<p class="logo pull-left">
				<img alt="<?php echo $this->controller->getSegment()->title ?>" src="/images/layout/logo.png" />
				<span class="border"></span>
			</p>
			<div class="pull-right">
				<div class="call pull-left">
					<span><?php echo s('need convincing? call us') ?></span>
					<b><?php echo s('+55 21 2499.3744') ?></b>
					<?php echo $this->element('common/language', array('currentLanguage' => $language)) ?>
				</div>
				<p class="login pull-left">
					<?php echo $this->language->link(s('sign up now'), '/signup/user', array(
							'class' => 'active'
					)) ?>
					<?php echo s('or') ?>
					<?php echo $this->language->link( s('Sign in ›'), '/users/login') ?>
				</p>
			</div>
		</div>

		<?php echo $this->contentForLayout ?>

		<?php echo $this->element('layouts/footer') ?>

		<?php echo $this->html->script('shared/jquery', 'shared/jquery.carouFredSel-6.1.0-packed','shared/jquery.touchSwipe.min', 'shared/jquery.ba-throttle-debounce.min', 'shared/home') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
