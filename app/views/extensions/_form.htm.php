<fieldset>
    <h2><?php echo s('settings') ?></h2>
    <div class="field-group">
        <?php foreach($extension->fields() as $field): ?>
            <div class="form-grid-460 first">
                <?php echo $this->items->input($field) ?>
            </div>
        <?php endforeach ?>
    </div>
</fieldset>
