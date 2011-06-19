<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = __('Configurações') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/edit/' . $site->id, array(
    'id' => 'form-edit-site-info',
    'class' => 'form-edit',
    'object' => $site,
    'method' => 'file'
)) ?>

    <?php echo $this->element('sites/edit_form', array(
        'action' => 'edit',
        'site' => $site,
        'countries' => $countries
    )) ?>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Salvar'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
