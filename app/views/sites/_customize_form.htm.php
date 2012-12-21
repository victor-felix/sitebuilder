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
