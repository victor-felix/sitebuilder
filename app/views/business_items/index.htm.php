<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle =  e($category->title) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $category->parent()
        )) ?>

        <?php echo $this->html->link(s('add item'), '/business_items/add/' . $category->id, array('class' => 'ui-button highlight large add-business-item push-scene')) ?>
    </div>
    <div class="clear"></div>
</div>

<ul class="businessitems-list paginate-items">
    <?php if(count($items)): ?>
        <?php foreach($items as $bi): ?>
        <li>
            <?php if($image = $bi->image()): ?>
                <?php echo $this->html->imagelink($image->link('80x80'), '/business_items/edit/' . $bi->id(), array(), array(
                    'class' => 'photo push-scene'
                )) ?>
            <?php else: ?>
                <?php echo $this->html->link('', '/business_items/edit/' . $bi->id(), array(
                    'class' => 'photo push-scene'
                )) ?>
            <?php endif ?>
            <div class="info">
                <?php echo $this->html->link(e($bi->title), '/business_items/edit/' . $bi->id(), array('class' => 'push-scene')) ?>
                
                <span class="move-controls">
                	<?php echo $this->html->link(s('up'), '/business_items/move_up/' . $bi->id(), array('class' => 'move-up')) ?>
                	<?php echo $this->html->link(s('down'), '/business_items/move_down/' . $bi->id(), array('class' => 'move-down')) ?>
                </span>
                
                <p><?php echo $this->bbcode->strip($bi->description) ?></p>
            </div>
        </li>
        <?php endforeach ?>
    <?php else: ?>
        <li class="no-results"><?php echo s('No items available on this category') ?></li>
    <?php endif ?>
</ul>
<?php if(count($items)): ?>
<ul id="pagination" class="pagination-wrapp">
    <li>
        <?php echo $this->LithiumPagination->previous('<<') ?>
    </li>
    <li>
        <?php echo $this->LithiumPagination->numbers() ?>
    </li>
    <li>
        <?php echo $this->LithiumPagination->next('>>') ?>
    </li>
</ul>

<div class="fieldset-actions">
    <div class="grid-4 first">
        <?php echo $this->html->link(s('‹ back'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
    </div>
    <div class="grid-8">
        <?php echo $this->html->link(s('add item'), '/business_items/add/' . $category->id, array('class' => 'ui-button highlight large push-scene')) ?>
    </div>
    <div class="clear"></div>
</div>
<?php endif ?>
 
