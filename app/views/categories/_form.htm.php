<?php echo $this->form->create($action, array(
    'class' => 'form-edit',
    'object' => $category
)) ?>

<fieldset>
    <h2><?php echo s('category') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <?php echo $this->form->input('title', array(
                'label' => s('Name of category'),
                'class' => 'ui-text large'
            )) ?>
        </div>

        <div class="form-grid-460 populate-fields">
            <label><?php echo s('Type of category') ?></label>
            <?php echo $this->form->input('populate', array(
                'type' => 'radio',
                'options' => array(
                    'manual' => s('Manual'),
                    'auto' => s('Auto')
                )
            )) ?>
            <small><?php echo s('Manual Categories allow to manage manually any type of content') ?></small>
            <small><?php echo s('Auto Categories allow to import automatically content from RSS feed') ?></small>
        </div>

        <?php if($site->hasManyTypes()): ?>
            <div class="form-grid-460 first populate-based manual">
                <?php echo $this->form->input('type', array(
                    'label' => s('Type'),
                    'type' => 'select',
                    'class' => 'ui-select large',
                    'options' => Segments::listItemTypesFor($site->segment)
                )) ?>
                <small><?php echo s("The type of content defined which content could be inserted on category, it couldn't be updated after creation") ?></small>
            </div>
        <?php endif ?>

        <?php
            $classname = '';
            if($category->id) $classname .= !$category->hasFeed() ? 'hidden' : '';
            else $classname .= 'populate-based';
            $feed = $category->id ? $category->feed_url : '';
        ?>
        <div class="form-grid-460 first auto <?php echo $classname ?>">
            <?php echo $this->form->input('feed', array(
                'label' => s('Feed Url'),
                'class' => 'ui-text large',
                'value' => $feed
            )) ?>
        </div>

        <div class="form-grid-460 first">
            <?php echo $this->form->input('visibility', array(
                'type' => 'checkbox',
                'label' => s('Visibility'),
                'value' => 1
            )) ?>
            <label for="FormVisibility" class="checkbox"><?php echo s('This category is visible for any user') ?></label>
        </div>

        <?php if($parent): ?>
            <?php echo $this->form->input('parent_id', array(
                'type' => 'hidden',
                'value' => $parent->id
            )) ?>
        <?php endif ?>
    </div>
</fieldset>

<fieldset class="actions">
    <?php echo $this->form->submit(s('Save'), array(
        'class' => 'ui-button red larger'
    )) ?>
    <?php if($category->id && $category->parent_id > 0): ?>
        <?php echo $this->html->link($this->html->image('shared/categories/delete.gif') . s('Delete category'), '/categories/delete/' . $category->id, array(
            'class' => 'ui-button delete'
        )) ?>
    <?php endif ?>
</fieldset>

<?php echo $this->form->close() ?>
