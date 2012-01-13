<?php echo $this->form->create($action, array( 
    'class' => 'form-edit skip-slide ',
    'object' => $category,
    'method' => 'file'
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

        <div class="form-grid-460 populate-fields <?php echo($category->id)?'two_column':'three_column'; ?>">
            <label><?php echo s('Type of category') ?></label>
            <?php
                $strCssClass = ($category->id)?'two_column':'three_column'; 
                echo $this->form->input('populate', array(
                'type' => 'radio',
                'options' => getDataPopulateFields($category),
            ));
            ?>
            <small class="<?php echo $strCssClass; ?>"><?php echo s('Manual Categories allow to manage manually any type of content') ?></small>
            <small class="<?php echo $strCssClass; ?>"><?php echo s('Auto Categories allow to import automatically content from RSS feed') ?></small>
            <small class="<?php echo $strCssClass; ?>"><?php if(!$category->id) echo s('Import items from CSV'); ?></small>
        </div>

        <?php if($site->hasManyTypes()): ?>
            <div class="form-grid-460 first populate-based manual import">
                <?php echo $this->form->input('type', array(
                    'label' => s('Type'),
                    'type' => 'select',
                    'class' => 'ui-select large',
                    'options' => Segments::listItemTypesFor($site->segment)
                )) ?>
                <small><?php echo s("The type of content defined which content could be inserted on category, it couldn't be updated after creation") ?></small>
            </div>
        <?php endif ?>

        <div class="form-grid-460 first populate-based import <?php if($category->id) echo 'manual'; ?>">
            <?php echo $this->form->input('import', array(
                'label' => s('CSV File'),
                'type' => 'file',
                'class' => 'ui-select large'
            )) ?>
        </div>

        <?php
            $classname = '';
            if($category->id) $classname .= !$category->hasFeed() ? 'hidden' : '';
            else $classname .= 'populate-based';
            $feed = $category->id ? $category->feed_url : '';
        ?>
        <div class="form-grid-460 first auto populate-based <?php echo $classname ?>">
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
   
	    <?php if(!is_null($category->id)): ?>
            <?php echo $this->html->link(s('Export as CSV'), '/api/' . $site->domain . '/export/' . $category->id) ?>
        <?php endif ?>
	    
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


<?php
	// some function for not confund with code HTML
	function getDataPopulateFields($category){
		return ($category->id)?
			array(
                    'manual' => s('Manual'),
                    'auto' => s('Auto')):
			array(
                    'manual' => s('Manual'),
                    'auto' => s('Auto'),
                    'import' => s('Import'));	
	}
?>