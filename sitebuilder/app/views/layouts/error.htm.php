<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->html->charset() ?>
		<title>:( <?php echo s('Page not found') ?> | <?php echo MeuMobi::currentSegment()->title ?></title>
		<meta name="robots" content="noindex, nofollow">
		<link rel="shortcut icon" href="<?php echo Mapper::url("/images/layout/favicon.png") ?>" type="image/png" />
		<?php echo $this->html->stylesheet('shared/base', 'shared/register', 'shared/uikit', 'segment') ?>
	</head>
	<body class="error">
		<div class="wrapper">
			<div id="header">
				<?php echo $this->html->link($this->html->image('layout/logo.png', array('alt'=> MeuMobi::currentSegment()->title)), '/', array('class'=>'logo')) ?>
			</div>
			<div class="content-wrapp">
				<div id="content">
					<div class="registration-finished">
						<?php echo $this->html->image('shared/layout/error.png', array(
							'alt' => s('Page not found')
						)) ?>
						<h2><?php echo s('Page not found') ?></h2>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<?php echo $this->element("layouts/footer") ?>
		</div>
		<?php echo $this->html->script('shared/jquery', 'shared/main') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
