<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = s('My Account') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/users/edit', array(
    'id' => 'form-edit-personal-info',
    'class' => 'form-edit default-form',
    'object' => $user
)) ?>

<fieldset>
    <h2><?php echo s('Personal details') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('firstname', array(
                'label' => s('First Name'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220">
            <?php echo $this->form->input('lastname', array(
                'label' => s('Last Name'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-460">
            <?php echo $this->form->input('email', array(
                'label' => s('E-mail'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('Type a valid E-mail address.') ?></small>
        </div>
        
        <div class="form-grid-460 first">
            <?php echo $this->form->input('password', array(
                'label' => s('Password'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
            <small><?php echo s('To keep your current password, keep empty password fields.') ?> <?php echo s('The password should contain at least 6 characters.') ?></small>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('confirm_password', array(
                'label' => s('Confirm password'),
                'type' => 'password',
                'class' => 'ui-text'
            )) ?>
        </div>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Save'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>