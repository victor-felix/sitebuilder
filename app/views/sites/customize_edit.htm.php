<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = __('Customização') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/customize_edit', array(
    'id' => 'form-edit-customize',
    'class' => 'form-edit',
    'method' => 'file',
    'object' => $site
)) ?>

    <?php echo $this->element('sites/customize_form', array(
        'action' => 'edit',
        'themes' => $themes,
        'skins' => $skins,
        'site' => $site
    )) ?>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Salvar'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
