<div class="slide-header">
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
		'site' => $site
	)) ?>

	<?php echo $this->element('sites/extended_form', array(
		'site' => $site
	)) ?>

<div class="slide-footer">
	<div class="grid-8 grid-offset-4">
		<?= $this->buttons->submit() ?>
		<?php if (Users::ROLE_ADMIN == $site->role && count(Auth::user()->sites()) > 1): ?>
			<?= $this->buttons->delete(s('Delete site'), '/sites/remove/' . $site->id, '#delete-confirm')  ?>
		<?php endif ?>
	</div>
	<div class="clear"></div>
</div>

<?php echo $this->form->close() ?>

<?php if (Users::ROLE_ADMIN == $site->role && count(Auth::user()->sites()) > 1): ?>
	<div id="delete-confirm" class="confirm">
		<div class="wrapper">
			<p>
				<?php echo s('Do you really want to delete this site?') ?>
				<br />
				<?php echo s('This will also delete all items and categories') ?>
			</p>
			<?php echo $this->html->link(s('Yes, delete'), '/sites/remove/' . $site->id, array(
				'class' => 'ui-button ajax-request go-back highlight'
			)) ?>
			<?php echo $this->html->link(s('No, I don\'t'), '#', array('class' => 'ui-button')) ?>
		</div>
	</div>
<?php endif ?>
