<?php $currentSite = Auth::user()->site() ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $language ?>" lang="<?php echo $language ?>">
	<head>
		<?php echo $this->html->charset() ?>
		<title><?php echo $this->pageTitle . ' | ' . $this->controller->getSegment()->title; ?></title>
		<meta name="robots" content="noindex, nofollow">
		<link rel="shortcut icon" href="<?php echo Mapper::url('/images/layout/favicon.png') ?>" type="image/png" />
		<?php echo $this->html->stylesheet('shared/base', 'shared/uikit', 'shared/categories',
			'shared/edit-forms', 'shared/businessitems', 'shared/dashboard', 'segment', 'shared/markitup.simple',
			'shared/markitup.xbbcode', 'shared/chosen', 'shared/themes') ?>
		<?php echo $this->html->stylesForLayout ?>
	</head>

	<body>
		<div class="wrapper">
			<div class="global-navbar">
				<div class="container">
					<p class="dash left"><img src="/images/shared/layout/meumobi-icon.png"/></p>
					<ul class="sites dropdown left">
						<li>
							<p><?php echo e($currentSite->title) ?></p>
							<ul>
								<li>
									<small><?php echo s('Select one site to edit it')?></small>
								</li>
								<?php foreach (Auth::user()->sites() as $site): ?>
									<?php if ($site->id == $currentSite->id):?>
									<li class="current">
										<?php echo e($site->title) ?>
										<span><?php echo s('Currently editing');?></span>
									</li>
									<?php else: ?>
									<li>
										<a href="<?php echo Mapper::url('/users/change_site/'.$site->id) ?>" >
											<?php echo e($site->title) ?>
										</a>
									</li>
									<?php endif;?>
								<?php endforeach; ?>
								<?php if (Users::ROLE_ADMIN == $currentSite->role): ?>
									<li class="new"><a href="<?php echo Mapper::url('/create_site/theme') ?>"><?php echo s('new mobile site...') ?></a></li>
								<?php endif; ?>
							</ul>
						</li>
					</ul>
					<ul class="user dropdown right">
						<li>
							<p><span class="icon"></span><?php echo e(Auth::user()->firstname()) ?></p>
							<ul>
								<li><?php echo $this->html->link(s('My Account'), '/users/edit') ?></li>
								<!-- li><?php echo $this->html->link(s('Dashboard'), '/dashboard/index') ?></li -->
								<li><?php echo $this->html->link(s('Log out ›'), '/users/logout') ?></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div id="header">
				<div class="logo">
					<?php echo $this->html->imagelink('layout/logo.png', '/dashboard', array(
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
						<li><?php echo $this->html->link(s('dashboard'), '/dashboard') ?></li>
						<li>
							<p><?php echo s('content')?><span class="arrow"></span></p>
							<ul>
								<li><?php echo $this->html->link(s('About Business'), '/sites/business_info') ?></li>
								<?php if(!MeuMobi::currentSegment()->hideCategories): ?>
									<li><?php echo $this->html->link(MeuMobi::currentSegment()->root, '/categories') ?></li>
								<?php endif ?>
								<li><?php echo $this->html->link(s('News'), '/sites/news') ?></li>
							</ul>
						</li>
						<?php if(Users::ROLE_ADMIN == $currentSite->role): ?>
						<li>
							<p><?php echo s('appearance')?><span class="arrow"></span></p>
							<ul>
								<li><?php echo $this->html->link(s('themes'), '/sites/theme') ?></li>
							</ul>
						</li>
						<li>
							<p><?php echo s('settings')?><span class="arrow"></span></p>
							<ul>
								<li><?php echo $this->html->link(s('General'), '/sites/general') ?></li>
								<li><?php echo $this->html->link(s('Custom Domain'), '/sites/custom_domain') ?></li>
								<?php if (MeuMobi::currentSegment()->enableMultiUsers()): ?>
								<li><?php echo $this->html->link(s('Users'), '/sites/users') ?></li>
								<?php endif ?>
							</ul>
						</li>
						<?php endif; ?>
					</ul>
				</div>
				<div class="clear"></div>
			</div>
	
			<?php echo $this->element('layouts/flash') ?>
			<div class="content-wrapp">
				<div id="content">
					<?php echo $this->contentForLayout ?>
				</div>
			</div>
			<?php echo $this->element('layouts/footer') ?>
		
		</div>
		<div class="live-preview">
			<a href="#" class="show-action">
				<?php echo s('LIVE PREVIEW')?>
			</a>
			<div class="live-wrapp">
				<a class="close" href="#">close</a>
				<?php echo $this->element('sites/theme_preview', array('site' => $currentSite))  ?>
			</div>
		</div>
		
		<div class="support">
			<a id="support-link" href="#"><?php echo s('support') ?></a>
		</div>
		
		<?php echo $this->html->script('shared/jquery', 'shared/jquery.formrestrict', 'shared/jquery.alphanumeric', 'shared/modernizr.custom.placeholder.js', 'shared/support_chat', 'shared/main', 'shared/markitup', 'shared/async_upload', 'shared/jquery.chosen', 'shared/themes') ?>
		<?php echo $this->html->scriptsForLayout ?>
	</body>
</html>
