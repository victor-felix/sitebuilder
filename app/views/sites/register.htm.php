<?php $this->layout = 'register' ?>
<?php echo $this->form->create('/sites/register/' . $site->id, array(
    'id' => 'form-register-site-info',
    'class' => 'form-register',
    'object' => $site
)) ?>

    <?php echo $this->element('sites/edit_form', array(
        'page' => 'register'
    )) ?>

<fieldset class="actions">
    <?php echo $this->form->submit(__('Avançar ›'), array(
        'class' => 'ui-button red large'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>