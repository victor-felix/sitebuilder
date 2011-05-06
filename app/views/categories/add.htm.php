<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = __('Adicionar Categoria') ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $parent
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/categories/add', array(
    'class' => 'form-edit',
    'object' => $category
)) ?>

<fieldset>
    <h2><?php echo __('categoria') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <?php echo $this->form->input('title', array(
                'label' => __('Título'),
                'class' => 'ui-text large'
            )) ?>
        </div>

        <?php if($site->hasManyTypes()): ?>
            <div class="form-grid-460 first">
                <?php echo $this->form->input('type', array(
                    'label' => __('Tipo'),
                    'type' => 'select',
                    'class' => 'ui-select large',
                    'options' => Segments::listItemTypesFor($site->segment)
                )) ?>
            </div>
        <?php endif ?>

        <?php echo $this->form->input('parent_id', array(
            'type' => 'hidden',
            'value' => $parent->id
        )) ?>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Salvar'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
