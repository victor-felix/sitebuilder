<fieldset>
    <h2><?php echo __('informações gerais') ?></h2>
    <div class="field-group">
        <?php echo $this->form->input('parent_id', array(
            'type' => 'hidden',
            'value' => $parent->id
        )) ?>
        
        <?php foreach($type->fields as $id => $field): ?>
            <div class="form-grid-460 first">
                <?php echo $this->form->input($id, array(
                    'label' => __($field['title']),
                    'type' => BusinessItemsTypes::$inputTypes[$field['field_type']],
                    'class' => 'large ui-' . BusinessItemsTypes::$inputTypes[$field['field_type']]
                )) ?>
            </div>
        <?php endforeach ?>

        <div class="form-grid-460 first">
            <?php echo $this->form->input('image', array(
                'label' => __('Imagem'),
                'type' => 'file',
                'class' => 'large ui-text'
            )) ?>
        </div>
    </div>
</fieldset>
