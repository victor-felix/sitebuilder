<h1>Edit Site <?php echo $site->title ?></h1>

<?php echo $this->form->create('/sites/edit/' . $site->id) ?>
    
    <?php echo $this->form->input('title', array(
        'value' => $site->title
    )) ?>
    <?php echo $this->form->input('domain', array(
        'value' => $site->domain
    )) ?>
    <?php echo $this->form->input('description', array(
        'value' => $site->description,
        'type' => 'textarea'
    )) ?>
    <?php echo $this->form->input('address', array(
        'value' => $site->address,
        'type' => 'textarea'
    )) ?>
    <?php echo $this->form->input('email', array(
        'value' => $site->email
    )) ?>
    <?php echo $this->form->input('phone', array(
        'value' => $site->phone
    )) ?>
    <?php echo $this->form->input('website', array(
        'value' => $site->website
    )) ?>
    <?php echo $this->form->input('facebook', array(
        'value' => $site->facebook
    )) ?>
    <?php echo $this->form->input('twitter', array(
        'value' => $site->twitter
    )) ?>
    <?php echo $this->form->input('logo') ?>
    <?php echo $this->form->input('feed', array(
        'value' => $site->feed()
    )) ?>
    
<?php echo $this->form->close('Save') ?>