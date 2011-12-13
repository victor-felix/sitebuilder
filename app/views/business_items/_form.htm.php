<fieldset>
    <h2><?php echo s('common settings') ?></h2>
    <div class="field-group">
        <?php foreach($item->fields() as $field): ?>
            <div class="form-grid-460 first">
                <?php echo $this->items->input($field) ?>
            </div>
        <?php endforeach ?>

            <?php if($images = $item->images()): ?>
                <?php foreach($images as $i => $image): $class = $i % 3 ? '' : 'first' ?>
                    <div class="<?php echo $class ?> picture-upload-container done" style="background-image: url(<?php echo $image->link('139x139') ?>)">
                        <?php echo $this->html->link('', '/images/delete/' . $image->id, array(
                            'class' => 'close'
                        )) ?>
                        <?php echo $this->form->input('image['.$image->id.'][title]', array(
                            'label' => false,
                            'class' => 'ui-text large',
                            'value' => $image->title
                        )) ?>
                    </div>
                <?php endforeach ?>
            <?php endif ?>

            <?php $class = (isset($i) ? $i + 1 : 0) % 3 ? '' : 'first' ?>
            <div class="<?php echo $class ?> picture-upload-container" data-url="/images/add.htm">
                <input type="hidden" name="image[foreign_key]" value="<?php echo $item->id() ?>" />
                <input type="hidden" name="image[model]" value="Items" />
                <a class="close"></a>
                <iframe src="about:blank" id="iframe_<?php echo time(); ?>"></iframe>
                <div class="default">
			<div class="icon_upload"></div>
			<?php echo s('add photo'); ?>
		</div>
                <div class="wait"><?php echo s('uploading photo...'); ?></div>
                <?php echo $this->form->input('image[photo]', array(
                    'label' => false,
                    'type' => 'file',
                    'class' => 'ui-text large picture-upload'
                )) ?>
                <?php echo $this->form->input('image[ID][title]', array(
                    'label' => false,
                    'class' => 'ui-text large'
                )) ?>
            </div>

            <a href="#" class="duplicate-previous">more</a>
 
    </div>
</fieldset>
