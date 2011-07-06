<?php $this->layout = 'register' ?>
<?php $this->selectedTab = 2 ?>
<?php $this->pageTitle = s('Create your mobi') ?>

<?php echo $this->form->create('/sites/customize_register', array(
    'id' => 'form-register-customize',
    'class' => 'form-register',
    'method' => 'file',
    'object' => $site
)) ?>

    <?php echo $this->element('sites/customize_form', array(
        'action' => 'register',
        'themes' => $themes,
        'site' => $site
    )) ?>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Save ›'), array(
        'class' => 'ui-button red large'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>
