<fieldset>
    <h2><?php echo __('informações gerais') ?></h2>
    <div class="field-group">
        <?php foreach($item->fields() as $field): ?>
            <div class="form-grid-460 first">
                <?php echo $this->items->input($field) ?>
            </div>
        <?php endforeach ?>

        <div class="form-grid-460 first">
            <?php echo $this->form->input('image', array(
                'label' => __('Imagem'),
                'type' => 'file',
                'class' => 'large ui-text'
            )) ?>
        </div>

        <?php
            // juliogreff says:
            // to delete image, use something like this:
            // if($item->id && $image = $item->image()):
            //     echo $this->html->link('/images/delete/' . $image->id)
            // endif
            // to get the image's path, use $image->link('80x80')
            // the default size is 80x80, try to use that.
            // if it's really necessary to use another size, change it in the
            // config file or just tell me and I can change it for you
        ?>
    </div>
</fieldset>
