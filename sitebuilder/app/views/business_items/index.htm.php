<div class="slide-header">
		<div class="grid-4 first">
			<?= $this->buttons->popScene(s('‹ back'), '/categories') ?>
    </div>
    <div class="grid-8">
        <h1><?= $this->pageTitle =  e($category->title) ?></h1>
        <?= $this->element('common/breadcrumbs', array(
            'category' => $category->parent()
        )) ?>

        <?= $this->buttons->pushScene(s('add item'), '/business_items/add/' . $category->id) ?>
    </div>
    <div class="clear"></div>
</div>

<ul class="businessitems-list paginate-items">
    <?php if(count($items)): ?>
        <?php foreach($items as $bi): ?>
        <li>
            <?php if($image = $bi->image()): ?>
                <?= $this->html->imagelink($image->link('80x80'), '/business_items/edit/' . $bi->id(), array(), array(
                    'class' => 'photo push-scene'
                )) ?>
            <?php else: ?>
                <?= $this->html->link('', '/business_items/edit/' . $bi->id(), array(
                    'class' => 'photo push-scene'
                )) ?>
            <?php endif ?>
            <div class="info">
                <?= $this->html->link(e($bi->title), '/business_items/edit/' . $bi->id(), array('class' => 'push-scene')) ?>
                
                <span class="move-controls">
                	<?= $this->html->link(s('up'), '/business_items/move_up/' . $bi->id(), array('class' => 'move-up')) ?>
                	<?= $this->html->link(s('down'), '/business_items/move_down/' . $bi->id(), array('class' => 'move-down')) ?>
                </span>
                
                <p><?= $this->bbcode->strip($bi->description) ?></p>
            </div>
        </li>
        <?php endforeach ?>
    <?php else: ?>
        <li class="no-results"><?= s('No items available on this category') ?></li>
    <?php endif ?>
</ul>
<?php if(count($items)): ?>
<ul id="pagination" class="pagination-wrapp">
    <li>
        <?= $this->LithiumPagination->previous('<<') ?>
    </li>
    <li>
        <?= $this->LithiumPagination->numbers() ?>
    </li>
    <li>
        <?= $this->LithiumPagination->next('>>') ?>
    </li>
</ul>

<div class="slide-footer">
    <div class="grid-4 first">
        <?= $this->buttons->popScene(s('‹ back'), '/categories') ?>
    </div>
    <div class="grid-8">
        <?= $this->buttons->pushScene(s('add item'), '/business_items/add/' . $category->id) ?>
    </div>
    <div class="clear"></div>
</div>
<?php endif ?>
 
