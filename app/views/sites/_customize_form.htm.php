<fieldset style="position: relative;">
	<?php echo $this->element('sites/themes_list', array(
		'themes' => $themes,
		'site' => $site
	)) ?>
	<?php echo $this->element('sites/theme_preview', array(
		'site' => $site,
		'autoload' => true
	)) ?>
</fieldset>
<div id="confirm" class="comfirm">
	<div class="wrapper">
		<p>
			<?php echo s('Really want to delete the <strong>%s</strong> category?', e($category->title)) ?>
			<br />
			<?php echo s('This will also delete all items and subcategories') ?>
		</p>
		<?php echo $this->html->link(s('Yes, delete'), '/categories/delete/' . $category->id, array(
			'class' => 'ui-button ajax-request go-back highlight'
		)) ?>
		<?php echo $this->html->link(s('No, I don\'t'), '#', array( 'class' => 'ui-button' )) ?>
	</div>
</div>