<?php $this->layout = 'login' ?>
<?php $this->pageTitle = s('Log in') ?>
<?php $invite_token = isset($invite_token) ? $invite_token : '' ?>

<?php echo $this->form->create(Mapper::here(), array(
    'class' => 'form-register',
    'id' => 'FormLogin'
)) ?>

<fieldset>
    <h2><?php echo s('Log in') ?></h2>
    <div class="field-group">
        <div class="form-grid-220 first">
            <?php echo $this->form->input('email', array(
                'label' => s('E-Mail'),
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('password', array(
                'label' => s('Senha'),
                'class' => 'ui-text'
            )) ?>
        </div>
        
        <div class="form-grid-220 first">
            <?php echo $this->form->input('remember', array(
                'label' => false,
                'type' => 'checkbox'
            )) ?>
            <label for="FormRemember" class="checkbox"><?php echo s('Remember me') ?></label>
        </div>

        <div class="form-grid-220 first">
            <?php
            if ($invite_token) {
                echo $this->html->link(s('Don\'t have account? Click here to register'), '/users/register/' . $invite_token, array(
                    'class' => 'no-account'
                ));
            }
            ?>
           
        </div>
    </div>
    <?php 
        if ($invite_token) {
            echo $this->form->input('invite_token', array(
            		'type' => 'hidden',
            		'value' => $invite_token,
            ));
        } 
    ?>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Log in'), array(
        'class' => 'ui-button large',
        'style' => 'margin-left: 235px; float: left;'
    ))?>
    
     <?php echo $this->html->link(s('Forgot password?'), '/users/forgot_password', array(
                'class' => 'forgot-password'
            )) ?>
</fieldset>

<?php echo $this->form->close() ?>