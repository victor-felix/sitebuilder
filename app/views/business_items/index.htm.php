<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle =  e($category->title) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $category->parent()
        )) ?>
        
        <?php echo $this->html->link(__('adicionar produto'), '/business_items/add/' . $category->id, array('class' => 'ui-button highlight large add-business-item push-scene')) ?>
    </div>
    <div class="clear"></div>
</div>

<ul class="businessitems-list">
    <?php if(count($business_items)): ?>
        <?php foreach($business_items as $bi): ?>
        <li>
            <?php echo $this->html->link('', '/business_items/edit/' . $bi->id, array(
                'class' => 'photo'
            )) ?>
            <div class="info">
                <?php echo $this->html->link(e($bi->values()->title), '/business_items/edit/' . $bi->id, array('class' => 'push-scene')); ?>
                <p><?php echo e($bi->values()->description) ?></p>
            </div>
        </li>
        <?php endforeach ?>
    <?php else: ?>
        <li class="no-results"><?php echo __('Ainda não há nenhum produto cadastrado nesta categoria.') ?></li>
    <?php endif ?>
</ul>

<?php if(count($business_items)): ?>
<div class="fieldset-actions">
    <div class="grid-4 first">
        <?php echo $this->html->link(__('‹ voltar'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
    </div>
    <div class="grid-8">
        <?php echo $this->html->link(__('adicionar produto'), '/business_items/add/' . $category->id, array('class' => 'ui-button highlight large push-scene')) ?>
    </div>
    <div class="clear"></div>
</div>
<?php endif ?>