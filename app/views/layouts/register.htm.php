<!DOCTYPE html>
<html lang="<?php echo $language ?>">
	<head>
		<?php echo $this->html->charset() ?>
		<title><?php echo $this->controller->getSegment()->title, ' - ' , $this->pageTitle ?></title>
		<link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
		<?php echo $this->html->stylesheet('shared/base', 'shared/register', 'shared/edit-forms', 'shared/uikit', 'shared/themes','segment') ?>
	</head>

	<body class="register">
		<div class="wrapper">
		<div id="header">
			<?php echo $this->language->imagelink('layout/logo.png', '/', array(
				'alt' => $this->controller->getSegment()->title
			), array(
				'class' => 'logo'
			)) ?>

			<?php if(!Auth::loggedIn()): ?>
				<p class="login right">
					<?php echo s('Already have an account?');?>
					<?php echo $this->language->link(s('Sign in ›'), '/users/login') ?>
				</p>
			<?php endif ?>

			<div class="head">
				<h1><?php echo s('Start your free trial in 3 simple steps') ?></h1>
				<ul class="steps">
					<li <?php if($this->selectedTab == 0): ?>class="current"<?php endif ?>>
						<h3>1</h3>
						<?php echo s('Enter your personal information') ?>
					</li>
					<li <?php if($this->selectedTab == 1): ?>class="current"<?php endif ?>>
						<h3>2</h3>
						<?php echo s('Choose a theme for your mobile site') ?>
					</li>
					<li <?php if($this->selectedTab == 2): ?>class="current"<?php endif ?>>
						<h3>3</h3>
						<?php echo s('Enter your business description') ?>
					</li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>

		<div class="content-wrapp">
			<div id="content">
				<?php echo $this->contentForLayout ?>
			</div>
		</div>

		<?php echo $this->element('layouts/footer') ?>
		</div>
		<?php echo $this->html->script('shared/jquery', 'shared/support_chat', 'shared/jquery.formrestrict', 'shared/jquery.alphanumeric', 'shared/main',  'shared/async_upload', 'shared/themes') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
