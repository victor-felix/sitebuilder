<h1>Add New Site</h1>

<?php echo $this->form->create('/sites/add') ?>
    
    <?php echo $this->form->input('title') ?>
    <?php echo $this->form->input('domain') ?>
    <?php echo $this->form->input('description', array(
        'type' => 'textarea'
    )) ?>
    <?php echo $this->form->input('address', array(
        'type' => 'textarea'
    )) ?>
    <?php echo $this->form->input('email') ?>
    <?php echo $this->form->input('phone') ?>
    <?php echo $this->form->input('website') ?>
    <?php echo $this->form->input('facebook') ?>
    <?php echo $this->form->input('twitter') ?>
    <?php echo $this->form->input('logo') ?>
    <?php echo $this->form->input('feed') ?>
    
<?php echo $this->form->close('Save') ?>