<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = e($item->values()->title) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $parent
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->items->form('/business_items/edit/' . $item->id, $item, array(
    'class' => 'form-edit skip-slide',
    'id' => 'form-edit-businessitem'
)) ?>
    
    <?php echo $this->element('business_items/form', compact('item')) ?>

    <fieldset class="actions">
        <?php echo $this->html->link(__('‹ voltar'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
        <?php echo $this->form->submit(__('Salvar'), array(
            'class' => 'ui-button red larger'
        )) ?>
        <?php echo $this->html->link(
            $this->html->image('shared/categories/delete.gif') . __('Apagar %s', $item->typeName()),
            '/business_items/delete/' . $item->id,
            array( 'class' => 'ui-button delete' )
        ) ?>
    </fieldset>
<?php echo $this->items->endform() ?>

<div class="delete-confirm">
    <div class="wrapper">
        <p>
            <?php echo __('Deseja realmente apagar <strong>%s</strong>?', e($item->title)) ?>
        </p>
        <?php echo $this->html->link(__('Sim, apagar'), '/business_items/delete/' . $item->id, array(
            'class' => 'ui-button delete highlight'
        )) ?>
        <?php echo $this->html->link(__('Não, voltar'), '#', array( 'class' => 'ui-button' )) ?>
    </div>
</div>
