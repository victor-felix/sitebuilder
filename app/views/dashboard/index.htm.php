<a id="feedback" href="http://<?php echo e($site->domain) ?>/landing-page" target="_blank" class="feedback"><?php echo s("Check your Meumobi's App landing page: %s", 'http://' . $site->domain ) ?>/landing-page</a>
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
			<h2><?php echo s('welcome to your mobile site') ?></h2>
			<p><?php echo s('keep improving your mobile site') ?></p>
			<p id="qr-code"><img src="http://api.qrserver.com/v1/create-qr-code/?size=100x100&data=http://<?php echo e($site->domain) ?>" /></p>
		</div>
		<ul class="featured-list">
			<?php $i = 0; $fildsets = MeuMobi::currentSegment()->enableFieldSet ? MeuMobi::currentSegment()->enableFieldSet : array() ?>
			<?php foreach ($fildsets as $item ): ?>
			<?php 
				echo $this->element('dashboard/'.$item);
				$i++;
				if($i>4)
					break;
			?>
			<?php endforeach; ?>
			<?php if ($category): ?>
			<li id="categories">
				<a class="link" href="<?php echo Mapper::url('/categories') ?>">
					<span class="icon"></span>
					<h3><?php echo s('edit content') ?></h3>
					<small><?php echo s('you can edit your menu, products, stores or news') ?></small>
					<span class="arrow"></span>
				</a>
			</li>
			<?php endif ?>
			<li id="add-content" class="open" style="display:none;">
				<div class="link">
					<span class="icon"></span>
					<h3><?php echo s('create new content') ?></h3>
					<small><?php echo s('you can add a restaurant menu, products, services, etc') ?></small>
					<span class="arrow open"></span>
				</div>
				<p class="placeholder-links">
					<a href="<?php echo Mapper::url('/placeholder_creator/menu') ?>">
						<?php echo $this->html->image('/images/shared/dashboard/icon-menu.png', array(
							'alt' => s('menu')
						)) ?>
						<?php echo s('menu') ?>
					</a>

					<a href="<?php echo Mapper::url('/placeholder_creator/stores') ?>">
						<?php echo $this->html->image('/images/shared/dashboard/icon-stores.png', array(
							'alt' => s('stores')
						)) ?>
						<?php echo s('stores') ?>
					</a>

					<a id="products" href="<?php echo Mapper::url('/placeholder_creator/products') ?>">
						<?php echo $this->html->image('/images/shared/dashboard/icon-products.png', array(
							'alt' => s('products')
						)) ?>
						<?php echo s('products') ?>
					</a>

					<a href="<?php echo Mapper::url('/placeholder_creator/news') ?>">
						<?php echo $this->html->image('/images/shared/dashboard/icon-news.png', array(
							'alt' => s('news')
						)) ?>
						<?php echo s('news') ?>
					</a>
				</p>
			</li>
		</ul>
		<div class="domain">
			<p><?php echo s('you can access anytime from your mobile phone') ?></p>
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
