<div class="slide-header">
    <div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle =  s('Add %s', $item->type()) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $parent
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->items->form(null, $item, array(
    'class' => 'form-edit default-form item-form',
    'id' => 'form-add-businessitem'
)) ?>

    <?php echo $this->element('business_items/form', compact('item')) ?>

    <fieldset class="actions">
        <?php echo $this->html->link(s('‹ back'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
        <?php echo $this->form->submit(s('Save'), array('class' => 'ui-button red larger')) ?>
    </fieldset>

<?php echo $this->items->endform() ?> 
