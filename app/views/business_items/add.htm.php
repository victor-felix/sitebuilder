<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle =  __('Adicionar %s', $type->title) ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $parent
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/business_items/add', array(
    'class' => 'form-edit skip-slide',
    'id' => 'form-add-businessitem',
    'method' => 'file',
    'object' => $business_item
)) ?>

    <?php echo $this->element('business_items/form', compact('parent', 'type', 'business_item')) ?>

    <fieldset class="actions">
        <?php echo $this->html->link(__('‹ voltar'), '/categories/index/' . $parent->id, array('class' => 'ui-button large back pop-scene')) ?>
        <?php echo $this->form->submit(__('Salvar'), array('class' => 'ui-button red larger')) ?>
    </fieldset>

<?php echo $this->form->close() ?>