<?php $this->layout = 'register' ?>
<?php echo $this->form->create('/sites/customize_register', array(
    'id' => 'form-register-customize',
    'class' => 'form-register',
    'method' => 'file',
    'object' => $site
)) ?>

    <?php echo $this->element('sites/customize_form', array(
        'action' => 'register',
        'themes' => $themes,
        'skins' => $skins,
        'site' => $site
    )) ?>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Finalizar ›'), array(
        'class' => 'ui-button red large'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
