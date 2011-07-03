<fieldset>
    <h2><?php echo s('common settings') ?></h2>
    <div class="field-group">
        <?php foreach($item->fields() as $field): ?>
            <div class="form-grid-460 first">
                <?php echo $this->items->input($field) ?>
            </div>
        <?php endforeach ?>

        <div class="form-grid-460 first">
            <?php echo $this->form->input('image', array(
                'label' => s('Image'),
                'type' => 'file',
                'class' => 'large ui-text'
            )) ?>
        </div>

        <?php if($item->id && $image = $item->image()): ?>
            <?php echo $this->html->link(s('Delete image'), '/images/delete/' . $image->id) ?>
            <?php echo $this->html->image($image->link('80x80')) ?>
        <?php endif ?>
    </div>
</fieldset>
