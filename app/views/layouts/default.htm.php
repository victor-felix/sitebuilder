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
			<div class="logo">
				<?php echo $this->html->imagelink('layout/logo.png', '/', array(
					'alt' => $this->controller->getSegment()->title
				), array(
					'class' => 'logo'
				)) ?>
			</div>
			<div class="contextual-navbar">
				<div class="navigation" id="navbar">
					<div class="sites">
						<p class="business-name"><span><?php echo e($currentSite->title) ?></span></p>
						<p class="share">
							http://<?php echo e($currentSite->domain) ?>
							<!-- *
							<a id="share_site" href="<?php echo e($currentSite->domain) ?>"><?php echo s('share url') ?></a> -->
						</p>
						
						<div class="site-switcher">
							<p><?php echo s('My mobi sites');?></p>
							<ul>
								<li><a href="#">
									<span class="site-name">
										<span><?php echo e($currentSite->title) ?></span>
										<small>http://<?php echo e($currentSite->domain) ?></small>
									</span>
									<span class="status current"><?php echo s('current site')?></span>
								</a></li>
								<?php foreach (Auth::user()->sites(true) as $site): ?>
								<li>
									<a href="<?php echo Mapper::url('/users/change_site/'.$site->id) ?>" >
										<span class="site-name">
											<span><?php echo e($site->title) ?></span>
											<small><?php echo e($site->domain) ?></small>
										</span>
										<span class="status edit"><?php echo s('edit site ›')?></span>
									</a>
								</li>
								<?php endforeach; ?>
								<?php if (Users::ROLE_USER != $currentSite->role): ?>
								<li class="new"><a href="<?php echo Mapper::url('/sites/add') ?>"><?php echo s('Create a new mobi ›') ?></a></li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
					<div class="user">
						<p><?php echo s('Hi <strong>%s</strong>', e(Auth::user()->firstname())) ?></p>
						<ul>
							<li><?php echo $this->html->link(s('My Account'), '/settings/account') ?></li>
							<!-- li><?php echo $this->html->link(s('Dashboard'), '/dashboard/index') ?></li -->
							<li><?php echo $this->html->link(s('Log out ›'), '/logout') ?></li>
						</ul>
						<!-- <?php echo $this->html->link(s('Log out ›'), '/logout') ?> -->
					</div>
				</div>
				<ul class="dropdown">
					<?php if(!$currentSite->hide_categories): ?>
						<li><?php echo $this->html->link(e($currentSite->rootCategory()->title), '/categories') ?></li>
					<?php endif ?>
					<li>
						<p><?php echo s('content')?><span class="arrow"></span></p>
						<ul>
							<li><?php echo $this->html->link(s('conpany info'), '/settings') ?></li>
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
							<li><?php echo $this->html->link(s('general'), '/settings/general') ?></li>
							<li><?php echo $this->html->link(s('custom domain'), '/settings/custom_domain') ?></li>
							<li><?php echo $this->html->link(s('users'), '/sites/users') ?></li>
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
