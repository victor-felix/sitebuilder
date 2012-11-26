<!--fieldset>
    <h2><?php echo s('Logo') ?></h2>
    <div class="field-group">
        <?php if($site->logo()): ?>
            <?php echo $this->html->image($site->logo()->link('200x200'), array(
                'class' => 'logo'
            )) ?>
            <?php echo $this->html->link(s('delete logo'), '/images/delete/' . $site->logo()->id) ?>
        <?php endif ?>
        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('logo', array(
                'label' => s('Logo'),
                'type' => 'file',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('To improve appearence of logo on your mobi site, we recommend to use an image on GIF or PNG with transparent background. Max size 50kb') ?></small>
        </div>
    </div>
</fieldset -->
            
<fieldset>
<?php if ($action == 'edit'): ?>
	<h2><?php echo s('themes') ?></h2>
<?php endif;?>

<?php echo $this->element('sites/themes_list', array(
		'themes' => $themes,
        'site' => $site)) 
?>

</fieldset>