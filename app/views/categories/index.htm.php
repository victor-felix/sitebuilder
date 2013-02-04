<?php if(!$this->controller->isXhr()): ?>
	<div id="slide-container">
	<div class="slide-elem" rel="/categories">
<?php endif ?>

<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = MeuMobi::currentSegment()->root ?></h1>
	</div>
	<div class="clear"></div>
</div>

<div id="categories-list">
	<div class="grid-4 first">
		<div class="tip">
			<h4><?php echo s('Tip') ?></h4>
			<p><?php echo s('Use panel on right of screen to manage your items. You can create categories and subcategories to organize your items') ?></p>
		</div>
	</div>

	<div class="grid-8">
		<ul class="categories-list">
			<?php foreach($categories[null] as $category): ?>
				<?php echo $this->element('categories/item', array(
					'level' => 1,
					'category' => $category,
					'categories' => $categories
				)) ?>
			<?php endforeach ?>
		</ul>

		<?php echo $this->html->link(s('Add Category'), '/categories/add', array(
			'class' => 'ui-button large add push-scene',
			'style' => 'margin-bottom: 40px'
		)) ?>
	</div>

	<div class="clear"></div>
</div>

<?php if(!$this->controller->isXhr()): ?>
	</div>
	</div>
<?php endif ?>
