<?php $currentSite = Auth::user()->site(); ?>
<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->html->charset() ?>
		<title><?php echo $this->controller->getSegment()->title, ' - ' ,  $this->pageTitle ?></title>
		<link rel="shortcut icon" href="<?php echo Mapper::url('/images/layout/favicon.png') ?>" type="image/png" />
		<link href='http://fonts.googleapis.com/css?family=Medula+One' rel='stylesheet' type='text/css'>
		<?php echo $this->html->stylesheet('shared/base', 'shared/uikit', 'shared/categories',
			'shared/edit-forms', 'shared/businessitems', 'segment', 'shared/markitup.simple',
			'shared/markitup.xbbcode', 'shared/chosen', 'shared/themes') ?>
		<?php echo $this->html->stylesForLayout ?>
	</head>

	<body>

		<div id="header">
			<div class="global-navbar">
			
			</div>
			<div class="logo">
				<?php echo $this->html->imagelink('layout/logo.png', '/', array(
					'alt' => $this->controller->getSegment()->title
				), array(
					'class' => 'logo'
				)) ?>
			</div>
			<div class="contextual-navbar">
				<div class="navigation" id="navbar">
					<p class="business-name">
						<span><?php echo s('You\'re currently editing')?></span>
						<?php echo e($currentSite->title) ?>
					</p>
					
					<p class="site-url dynamic-text" data-max-font-size="36">
						<span>
							http://<?php echo e($currentSite->domain) ?>
						</span>
					</p>						
				</div>
				<ul class="dropdown">
					<?php if(!$currentSite->hide_categories): ?>
						<li><?php echo $this->html->link(e($currentSite->rootCategory()->title), '/categories') ?></li>
					<?php endif ?>
					<li>
						<p><?php echo s('content')?><span class="arrow"></span></p>
						<ul>
							<li><?php echo $this->html->link(s('Company Info'), '/settings') ?></li>
							<li><?php echo $this->html->link(s('News'), '/sites/news') ?></li>
						</ul>
					</li>
					<?php if(Users::ROLE_ADMIN == $currentSite->role): ?>
					<li>
						<p><?php echo s('appearance')?><span class="arrow"></span></p>
						<ul>
							<li><?php echo $this->html->link(s('themes'), '/settings/customize') ?></li>
						</ul>
					</li>
					<li>
						<p><?php echo s('settings')?><span class="arrow"></span></p>
						<ul>
							<li><?php echo $this->html->link(s('General'), '/settings/general') ?></li>
							<li><?php echo $this->html->link(s('Custom Domain'), '/settings/custom_domain') ?></li>
							<li><?php echo $this->html->link(s('Users'), '/sites/users') ?></li>
						</ul>
					</li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="clear"></div>
		</div>

		<?php echo $this->element('layouts/flash') ?>

		<div id="content">
			<?php echo $this->contentForLayout ?>
		</div>

		<?php echo $this->element('layouts/footer') ?>
		
		<div class="live-preview">
			<a href="#" class="show-action">
				<?php echo s('LIVE PREVIEW')?>
			</a>
			<div class="live-wrapp">
				<a class="close" href="#">close</a>
				<?php echo $this->element('sites/theme_preview', array('site' => $currentSite))  ?>
			</div>
		</div>
		
		<?php echo $this->html->script('shared/jquery', 'shared/main', 'shared/markitup', 'shared/async_upload', 'shared/jquery.chosen', 'shared/themes') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
