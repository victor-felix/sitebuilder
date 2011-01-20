<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), 'javascript:history.back()', array(
        'class' => 'ui-button large back'
    )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo __('adicionar categoria') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/categories/add', array(
    'class' => 'form-edit'
)) ?>

<fieldset>
    <h2>categoria</h2>
    <div class="field-group">
        <div class="form-grid-460 first">
        <?php echo $this->form->input('title', array(
            'label' => __('Título'),
            'class' => 'ui-text large'
        )) ?>
        </div>

        <div class="form-grid-460 first">
        <?php echo $this->form->input('parent_id', array(
            'label' => __('Pai'),
            'type' => 'select',
            'options' => $parents,
            'class' => 'ui-select'
        )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Salvar'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>