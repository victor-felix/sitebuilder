<?php $this->pageTitle = s('dashboard') ?>

<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle ?></h1>
	</div>
	<div class="clear"></div>
</div>
<div class="dashboard">
	<div class="wrapp">
		<div class="tip-big">
			<h2><?php echo s('welcome to your mobile site')?></h2>
			<p><?php echo s('use the tools below to keep improving your mobile site') ?></p>
		</div>
		<ul class="list">
			<li id="photos">
				<a href="<?php echo Mapper::url('/sites/business_info#business-photos') ?>">
					<span class="icon"></span>
					<h3><?php echo s('add photos of your company')?></h3>
					<small><?php echo s('let your customers see what your business looks like')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li id="social">
				<a href="<?php echo Mapper::url('/sites/business_info#business-social') ?>">
					<span class="icon"></span>
					<h3><?php echo s('your social links')?></h3>
					<small><?php echo s('facebook page, twitter, website address')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li id="address">
				<a href="<?php echo Mapper::url('/sites/business_info#business-address') ?>">
					<span class="icon"></span>
					<h3><?php echo s('your address')?></h3>
					<small><?php echo s('full address adds a map on your mobile site')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li id="contact">
				<a href="<?php echo Mapper::url('/sites/business_info#business-contact') ?>">
					<span class="icon"></span>
					<h3><?php echo s('your contacts')?></h3>
					<small><?php echo s('phone numbers and email address')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li id="feed">
				<a href="<?php echo Mapper::url('/sites/news') ?>">
					<span class="icon"></span>
					<h3><?php echo s('add a news feed')?></h3>
					<small><?php echo s('use your website RSS to display news')?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<li id="categories">
				<a href="<?php echo Mapper::url('/categories') ?>">
					<span class="icon"></span>
					<h3><?php echo s('manage other content') ?></h3>
					<small><?php echo s('you can add a restaurant menu, products, services, etc') ?></small>
					<span class="arrow"></span>
				</a>
				<?php if ($category): ?>
					<?php echo $this->html->link(s('Edit your %s', $category->title), '/categories') ?>
				<?php else: ?>
					<?php echo s('Create a %s, %s or %s.',
						$this->html->link(s('menu'), '/placeholder_creator/menu'),
						$this->html->link(s('products'), '/placeholder_creator/products'),
						$this->html->link(s('news'), '/placeholder_creator/news')
					) ?>
				<?php endif ?>
			</li>
		</ul>
		<div class="domain">
			<p><?php echo s('you can access anytime from your mobile phone')?></p>
			<?php echo $this->html->link('http://' . e($site->domain),
				'http://' . e($site->domain), array('target' => 'blank')) ?>
		</div>
	</div>
	<?php echo $this->element('sites/theme_preview', array(
		'site' => $site,
		'autoload' => true
	)) ?>
	<p class="clear"></p>
</div>
