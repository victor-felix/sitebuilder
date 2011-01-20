<?php echo $this->form->create('/users/login') ?>
    <?php echo $this->form->input('email', array(
        'label' => __('E-Mail')
    )) ?>

    <?php echo $this->form->input('password', array(
        'label' => __('Senha')
    )) ?>


<?php echo $this->form->close('Login') ?>