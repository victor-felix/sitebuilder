<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button smaller')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button small')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button large')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button larger')); ?>

<br/><br/><hr /><br/><br/>

<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button red smaller')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button red small')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button red')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button red large')); ?>
<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button red larger')); ?>

<?php echo $this->html->link('Cardápio', '/categories', array('class'=>'ui-button disabled')); ?>

<br/><br/><hr /><br/><br/>

<?php echo $this->form->input('name', array(
    'class'=>'ui-text'
)); ?>

<br/>

<?php echo $this->form->input('name', array(
    'class'=>'ui-text large'
)); ?>

<br/>

<?php echo $this->form->input('name', array(
    'class'=>'ui-text large error'
)); ?>

<br/>

<?php echo $this->form->input('name', array(
    'class'=>'ui-text large disabled',
    'disabled' => true
)); ?>

<br/>

<?php echo $this->form->input('selec', array(
    'type' => 'select',
    'options' => array(
        'a' => 'cat',
        'b' => 'dog'
    ),
    'class'=>'ui-select'
)); ?>

<br />

<?php echo $this->form->input('selec', array(
    'type' => 'select',
    'options' => array(
        'a' => 'cat',
        'b' => 'dog'
    ),
    'class'=>'ui-select large'
)); ?>

<br/><br/><hr /><br/><br/>

<?php echo $this->form->submit('selec', array(
    'type' => 'submit',
    'class'=>'ui-button'
)); ?>

<br/><br/><hr /><br/><br/>

<?php echo $this->form->input('asas', array(
    'type' => 'textarea',
    'class'=>'ui-textarea'
)); ?>

<br />

<?php echo $this->form->input('asas', array(
    'type' => 'textarea',
    'class'=>'ui-textarea large'
)); ?>
