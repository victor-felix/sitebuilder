<?php $currentSite = Auth::user()->site() ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?= $language ?>" lang="<?= $language ?>">
	<head>
		<?= $this->html->charset() ?>
		<title><?= $this->pageTitle . ' | ' . $this->controller->getSegment()->title ?></title>
		<meta name="robots" content="noindex, nofollow">
		<link rel="shortcut icon" href="<?= MeuMobi::url('/images/layout/favicon.png') ?>" type="image/png" />
		<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
		<?= $this->html->stylesheet('shared/base', 'shared/uikit', 'shared/categories',
			'shared/edit-forms', 'shared/businessitems', 'shared/dashboard', 'segment', 'shared/markitup.simple',
			'shared/markitup.xbbcode', 'shared/select2.min', 'shared/jquery.dataTables',
			'shared/themes', 'shared/colorpicker','shared/morris') ?>
		<?= $this->html->stylesForLayout ?>
	</head>

	<body>
		<div class="wrapper">
			<div class="global-navbar">
				<div class="container">
					<p class="dash left"><img src="/images/shared/layout/meumobi-icon.png"/></p>
					<ul class="sites dropdown left">
						<li>
							<p><?= e($currentSite->title) ?></p>
							<ul>
								<li>
									<small><?= s('Select one site to edit it') ?></small>
								</li>
								<?php foreach (Auth::user()->sites() as $site): ?>
									<?php if ($site->id == $currentSite->id):?>
									<li class="current">
										<?= e($site->title) ?>
										<span><?= s('Currently editing') ?></span>
									</li>
									<?php else: ?>
									<li>
										<?= $this->html->link(e($site->title), "/users/change_site/$site->id") ?>
									</li>
									<?php endif ?>
								<?php endforeach ?>
								<?php if (Users::ROLE_ADMIN == $currentSite->role): ?>
									<li class="new">
										<?= $this->html->link(s('new mobile site...'), '/create_site/theme') ?>
									</li>
								<?php endif ?>
							</ul>
						</li>
					</ul>
					<ul class="user dropdown right">
						<li>
							<p><span class="icon"></span><?= e(Auth::user()->firstname()) ?></p>
							<ul>
								<li><?= $this->html->link(s('My Account'), '/users/edit') ?></li>
								<!-- li><?= $this->html->link(s('Dashboard'), '/dashboard/index') ?></li -->
								<li><?= $this->html->link(s('Log out ›'), '/users/logout') ?></li>
							</ul>
						</li>
					</ul>
				</div>
			</div>
			<div id="header">
				<div class="logo">
					<?= $this->html->imagelink('layout/logo.png', '/dashboard', array(
						'alt' => $this->controller->getSegment()->title
					), array(
						'class' => 'logo'
					)) ?>
				</div>
				<div class="contextual-navbar">
					<div class="navigation" id="navbar">
						<div class="navigation-wrapp">
							<p class="business-name">
								<span><?= s('You\'re currently editing') ?></span>
								<?= $this->string->truncate(e($currentSite->title), 15) ?>
								<span id="site-id">#<?= $currentSite->id ?></span>
							</p>
							<?php if (!MeuMobi::currentSegment()->downloadAppUrl): ?>
							<div class="right">
								<p class="site-url dynamic-text" data-max-font-size="36">
									<span>
										http://<?= e($currentSite->domain()) ?>
									</span>
								</p>
								<p class="share-link">
									<a id="share" class="popup-link" href="#"><?= s('share') ?> &#8250;</a>
								</p>
							</div>
							<?php endif ?>
						</div>
					</div>
					<ul class="dropdown">
						<li><?= $this->html->link(s('dashboard'), '/dashboard') ?></li>
						<li>
							<p><?= s('content') ?><span class="arrow"></span></p>
							<ul>
								<li><?= $this->html->link(s('About Business'), '/sites/business_info') ?></li>
								<?php if(!MeuMobi::currentSegment()->hideCategories): ?>
									<li><?= $this->html->link(MeuMobi::currentSegment()->root, '/categories') ?></li>
								<?php endif ?>
								<?php if(MeuMobi::currentSegment()->isEnabledFieldSet('news')): ?>
								<li><?= $this->html->link(s('News'), '/sites/news') ?></li>
								<?php endif ?>
							</ul>
						</li>
						<?php if(Users::ROLE_ADMIN == $currentSite->role): ?>
						<li>
							<p><?= s('appearance') ?><span class="arrow"></span></p>
							<ul>
								<li><?= $this->html->link(s('themes'), '/sites/theme') ?></li>
								<li><?= $this->html->link(s('customization'), '/sites/custom_theme/' . $currentSite->skin ) ?></li>
								<li><?= $this->html->link(s('application'), '/sites/application') ?></li>
							</ul>
						</li>
						<li>
							<p><?= s('settings') ?><span class="arrow"></span></p>
							<ul>
								<li><?= $this->html->link(s('General'), '/sites/general') ?></li>
								<li><?= $this->html->link(s('Custom Domain'), '/sites/custom_domain') ?></li>
								<?php if (MeuMobi::currentSegment()->enableMultiUsers()): ?>
								<li><?= $this->html->link(s('Users'), '/sites/users') ?></li>
								<?php endif ?>
							</ul>
						</li>
						<?php endif ?>
					</ul>
				</div>
				<div class="clear"></div>
			</div>

			<?= $this->element('layouts/flash') ?>
			<div class="content-wrapp">
				<div id="content">
					<?= $this->contentForLayout ?>
				</div>
			</div>
			<?= $this->element('layouts/footer') ?>

		</div>
		<div class="live-preview">
			<a href="#" class="show-action">
				<?= s('LIVE PREVIEW') ?>
			</a>
			<div class="live-wrapp">
				<a class="close" href="#">close</a>
				<?= $this->element('sites/theme_preview', array('site' => $currentSite))  ?>
			</div>
		</div>
<?php if (Config::read('App.environment') == 'production'
			&& Config::read('App.support')): ?>
		<div class="support">
			<a id="support-link" href="#"><?= s('support') ?></a>
		</div>
<?php endif ?>
		<div class="popup-wrapper">
			<div id="#share-links" class="popup share-links">
				<div class="header">
					<h2><?= s('share') . ' ' . $this->string->truncate(e($currentSite->title), 15) ?></h2>
					<a href="#" class="close"><img src="/images/shared/themes/icon-close.png" alt="close" /></a>
				</div>
				<div class="links">
					<p><?= s('Share with your friends and customers your mobile website through the links below:') ?></p>
					<!-- AddThis Button BEGIN -->
					<div class="addthis_toolbox addthis_default_style addthis_32x32_style" addthis:url="http://<?= e($currentSite->domain()) ?>" addthis:title="<?= e($currentSite->title) ?>">
					<a class="addthis_button_preferred_1"></a>
					<a class="addthis_button_preferred_2"></a>
					<a class="addthis_button_preferred_3"></a>
					<a class="addthis_button_preferred_4"></a>
					<a class="addthis_button_compact"></a>
					<a class="addthis_counter addthis_bubble_style"></a>
					</div>
					<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=xa-51669ebc52cbd678"></script>
					<!-- AddThis Button END -->
				</div>
			</div>
		</div>
		<?= $this->html->script('shared/jquery', 'shared/jquery.formrestrict',
			'shared/jquery.alphanumeric', 'shared/jquery.dataTables', 'shared/modernizr.custom.placeholder',
			'shared/colorpicker', 'shared/raphael-min', 'shared/morris.min', 'shared/select2.min',
			'shared/main', 'shared/markitup', 'shared/async_upload', 'shared/themes') ?>
		<?= $this->html->scriptsForLayout ?>
	</body>
</html>
