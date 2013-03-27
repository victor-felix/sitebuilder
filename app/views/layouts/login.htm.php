<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->html->charset() ?>
		<title><?php echo $this->pageTitle ?></title>
		<link rel="shortcut icon" href="<?php echo Mapper::url('/images/layout/favicon.png') ?>" type="image/png" />
		<?php echo $this->html->stylesheet('shared/base', 'shared/edit-forms', 'shared/register', 'shared/uikit', 'shared/login', 'segment') ?>
	</head>
	<body class="login">
		<div class="wrapper">
			<div id="header">
				<?php echo $this->language->imagelink('layout/logo.png', '/', array(
					'alt' => $this->controller->getSegment()->title,
					'title' =>  $this->controller->getSegment()->title
				), array('class' => 'logo')) ?>
			</div>

			<?php echo $this->element('layouts/flash') ?>

			<div class="content-wrapp">
				<div id="content">
					<?php echo $this->contentForLayout ?>
				</div>
			</div>

			<?php echo $this->element('layouts/footer') ?>
		</div>
		<?php echo $this->html->script('shared/jquery', 'shared/support_chat', 'shared/main') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
