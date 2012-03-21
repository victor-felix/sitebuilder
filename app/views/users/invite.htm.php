<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(s('‹ back'), '/sites/users', array('class' => 'ui-button large back pop-scene')) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle =  s('Invite user') ?></h1>
        <p class="breadcrumb">Index / Users /</p>
    </div>
    <div class="clear"></div>
</div>
    <?php echo $this->form->create(Mapper::here(), array(
        'class' => 'form-edit',
        'id' => 'form-add-businessitem'
    )) ?>
    <fieldset>
        <h2><?php echo s('common settings') ?></h2>
        <div class="field-group">
            <div class="form-grid-460">
                <?php echo $this->form->input('emails', array(
                    'label' => s('Emails of users'),
                    'type' => 'textarea',
                    'class' => 'ui-textarea large',
                    'maxlenght' => 500
                )) ?>
                <small><?php echo s('Add the emails of the invited users, separated by comma.') ?></small>
            </div>
        </div>
    </fieldset>
    <fieldset class="actions">
        <?php echo $this->html->link(s('‹ back'), '/sites/users', array('class' => 'ui-button large back pop-scene')) ?>
        <?php echo $this->form->submit(s('Invite'), array('class' => 'ui-button red larger')) ?>
    </fieldset>
<?php echo $this->form->close() ?>