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

        <?php if($item->id && $image = $item->image()): ?>
            <?php echo $this->html->link('Apagar Imagem', '/images/delete/' . $image->id) ?>
            <?php echo $this->html->image($image->link('80x80')) ?>
        <?php endif ?>
    </div>
</fieldset>
