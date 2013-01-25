<div class="page-heading">
	<div class="grid-4 first">&nbsp;</div>
	<div class="grid-8">
		<h1><?php echo $this->pageTitle = s('Basic Info') ?></h1>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->create(null, array(
	'id' => 'form-edit-site-info',
	'class' => 'form-edit default-form',
	'object' => $site,
	'method' => 'file'
)) ?>
	<?php echo $this->element('sites/edit_form', array(
		'action' => 'edit',
		'site' => $site,
		'countries' => $countries,
		'states' => $states
	)) ?>

	<fieldset class="actions">
		<?php echo $this->form->submit(s('Save'), array(
			'class' => 'ui-button red larger'
		)) ?>
		<?php if (Users::ROLE_ADMIN == $site->role && count(Auth::user()->sites()) > 1): ?>
		<?php echo $this->html->link($this->html->image('shared/categories/delete.gif') . s('Delete site'), '/sites/remove/' . $site->id, array(
			'class' => 'ui-button delete has-confirm','data-confirm' => '#delete-confirm'
		)) ?>
		<?php endif; ?>
	</fieldset>

<?php echo $this->form->close() ?>

<div id="delete-confirm" class="delete-confirm">
	<div class="wrapper">
		<p>
			<?php echo s('Do you really want to delete this site?') ?>
			<br />
			<?php echo s('This will also delete all items and categories') ?>
		</p>
		<?php echo $this->html->link(s('Yes, delete'), '/sites/remove/' . $site->id, array(
			'class' => 'ui-button delete highlight'
		)) ?>
		<?php echo $this->html->link(s('No, I don\'t'), '#', array('class' => 'ui-button')) ?>
	</div>
</div>
