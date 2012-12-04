<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = s('News') ?></h1>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->form->create('/sites/news/' . $site->id, array(
    'id' => 'form-general-site-info',
    'class' => 'form-edit',
    'object' => $site,
    //'method' => 'file'
)) ?>

<fieldset>
	<h2><?php echo s('News feed - RSS') ?></h2>
	<div class="field-group">
		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('feed_title', array(
				'label' => s('Title'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
		</div>

		<div class="form-grid-460 first">
			<span class="optional"><?php echo s('Optional') ?></span>
			<?php echo $this->form->input('feed_url', array(
				'label' => s('url of RSS feed'),
				'type' => 'text',
				'class' => 'ui-text large'
			)) ?>
			<small><?php echo s('RSS (most commonly expanded as "Really Simple Syndication") is a family of web feed formats used to publish frequently updated works—such as blog entries, news headlines—in a standardized format. You can use it to feed news section of your mobi site') ?></small>
		</div>
	</div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Save'), array(
        'class' => 'ui-button red larger'
    )) ?>
</fieldset>

<?php echo $this->form->close() ?>