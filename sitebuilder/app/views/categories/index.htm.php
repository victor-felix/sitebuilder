<?php if(!$this->controller->isXhr()): ?>
	<div id="slide-container">
	<div class="slide-elem" rel="/categories">
<?php endif ?>

<div class="slide-header">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?= $this->pageTitle = MeuMobi::currentSegment()->root ?></h1>
		<?= $this->buttons->pushScene(s('Add Category'), '/categories/add') ?>
	</div>
	<div class="clear"></div>
</div>

<div id="categories-list">
	<div class="grid-4 first">
		<div class="tip">
			<h4><?= s('Tip') ?></h4>
			<p><?= s('Use panel on right of screen to manage your items. You can create categories and subcategories to organize your items') ?></p>
		</div>
	</div>
	<div class="grid-8">
		<ul class="categories-list">
			<?php if(isset($categories[null])) foreach($categories[null] as $category): ?>
				<?= $this->element('categories/item', array(
					'level' => 1,
					'category' => $category,
					'categories' => $categories
				)) ?>
			<?php endforeach ?>
		</ul>
	</div>
	<div class="clear"></div>
</div>

<div class="slide-footer">
  <div class="grid-8 grid-offset-4">
		<?= $this->buttons->pushScene(s('Add Category'), '/categories/add') ?>
	</div>
</div>

<?php if(!$this->controller->isXhr()): ?>
	</div>
	</div>
<?php endif ?>
