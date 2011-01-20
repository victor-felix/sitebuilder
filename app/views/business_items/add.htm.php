<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), 'javascript:history.back()', array(
        'class' => 'ui-button large back'
    )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo __('Adicionar %s', $type->title) ?></h1>
        <p class="breadcrumb"></p>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/business_items/add', array(
    'class' => 'form-edit',
    'id' => 'form-add-businessitem'
)) ?>
    
    <?php echo $this->form->input('parent_id', array(
        'label' => false,
        'type' => 'hidden'
    )) ?>
    <fieldset>
    <h2>informações gerais</h2>
    <div class="field-group">
    <?php foreach($type->fields as $id => $field): ?>
        <div class="form-grid-460 first">
        <?php echo $this->form->input($id, array(
            'label' => __($field['title']),
            'type' => BusinessItemsTypes::$inputTypes[$field['field_type']],
            'class' => 'large ui-' . BusinessItemsTypes::$inputTypes[$field['field_type']]
        )) ?>
        </div>
    <?php endforeach ?>
    </div>
    </fieldset>

    <fieldset class="actions">
        <?php echo $this->html->link(__('‹ voltar'), 'javascript:history.back()', array(
            'class' => 'ui-button large back'
        )) ?>
        <?php echo $this->form->submit(__('Salvar'), array(
            'class' => 'ui-button red larger'
        )) ?>
    </fieldset>
<?php echo $this->form->close() ?>


