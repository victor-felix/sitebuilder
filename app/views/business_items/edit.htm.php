<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = e($item->title) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $parent
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->items->form('/business_items/edit/' . $item->id(), $item, array(
    'class' => 'form-edit default-form',
    'id' => 'form-edit-businessitem'
)) ?>
    
    <?php echo $this->element('business_items/form', compact('item')) ?>

    <fieldset class="actions">
        <?php echo $this->html->link(s('‹ back'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
        <?php echo $this->form->submit(s('Save'), array(
            'class' => 'ui-button red larger'
        )) ?>
        <?php echo $this->html->link(
            $this->html->image('shared/categories/delete.gif') . s('Delete %s', $item->type()),
            '/business_items/delete/' . $item->id(),
            array( 'class' => 'ui-button delete has-confirm', 'data-confirm' => '#delete-confirm' )
        ) ?>
    </fieldset>
<?php echo $this->items->endform() ?>

<div id="delete-confirm" class="delete-confirm">
    <div class="wrapper">
        <p>
            <?php echo s('Really want to delete <strong>%s</strong>?', e($item->title)) ?>
        </p>
        <?php echo $this->html->link(s('Yes, delete'), '/business_items/delete/' . $item->id(), array(
            'class' => 'ui-button delete highlight'
        )) ?>
        <?php echo $this->html->link(s("No, I don\'t"), '#', array( 'class' => 'ui-button' )) ?>
    </div>
</div>
 