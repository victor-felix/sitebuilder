<div class="slide-header">
    <div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/categories/edit/' . $category->id, array('class' => 'ui-button large back pop-scene')) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = e($extension->specification('title')) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $category
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->items->form('/extensions/edit/' . $extension->id(), $extension, array(
    'class' => 'form-edit default-form',
    'id' => 'form-edit-businessitem'
)) ?>
    
    <?php echo $this->element('extensions/form', compact('extension')) ?>

    <fieldset class="actions">
        <?php echo $this->html->link(s('‹ back'), '/categories/edit/' . $category->id, array('class' => 'ui-button large back pop-scene')) ?>
        <?php echo $this->form->submit(s('Save'), array(
            'class' => 'ui-button red larger'
        )) ?>
    </fieldset>
<?php echo $this->items->endform() ?> 