<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '#BACK', array(
        'class' => 'ui-button large back'
    )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo __($business_item->values()->title) ?></h1>
        <p class="breadcrumb">Cardápio / Pratos / Entradas</p>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/business_items/edit/' . $business_item->id, array(
    'class' => 'form-edit',
    'id' => 'form-edit-businessitem'
)) ?>
    
    <?php echo $this->form->input('parent_id', array(
        'label' => false,
        'type' => 'hidden',
        'value' => $business_item->parent_id
    )) ?>
    
    <fieldset>
        <h2>informações gerais</h2>
        <div class="field-group">
        <?php foreach($type->fields as $id => $field): ?>
            <div class="form-grid-460 first">
            <?php echo $this->form->input($id, array(
                'label' => __($field['title']),
                'type' => BusinessItemsTypes::$inputTypes[$field['field_type']],
                'class' => 'large ui-' . BusinessItemsTypes::$inputTypes[$field['field_type']],
                'value' => $business_item->values()->{$id}
            )) ?>
            </div>
        <?php endforeach ?>
        </div>
    </fieldset>

    <fieldset class="actions">
        <?php echo $this->html->link(__('‹ voltar'), '#BACK', array(
            'class' => 'ui-button large back'
        )) ?>
        <?php echo $this->form->submit(__('Salvar'), array(
            'class' => 'ui-button red larger'
        )) ?>
        <?php echo $this->html->link($this->html->image('categories/delete.gif').__('Apagar %s', $type->title), '#BACK', array(
            'class' => 'ui-button delete'
        )) ?>
    </fieldset>
<?php echo $this->form->close() ?>

<div class="delete-confirm">
    <div class="wrapper">
        <p>Deseja realmente apagar <strong><?php echo __($business_item->values()->title) ?></strong>? <small>Todos os produtos e subcategorias associados serão apagados.</small></p>
        <?php echo $this->html->link('Sim, apagar', '/business_items/delete/'.$business_item->id, array(
            'class' => 'ui-button delete highlight'
        )); ?>
        <?php echo $this->html->link('Não, voltar', '#', array(
            'class' => 'ui-button'
        )); ?>
    </div>
</div>
