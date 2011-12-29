<?php if(!$this->controller->isXhr()): ?>
    <div id="slide-container">
    <div class="slide-elem" rel="/categories">
<?php endif ?>

<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = e($root->title) ?></h1>
    </div>
    <div class="clear"></div>
</div>

<div id="categories-list">
<div class="grid-4 first">
    <div class="tip">
        <h4><?php echo s('Tip') ?></h4>
        <p><?php echo s('Use panel on right of screen to manage your items. You can create categories and subcategories to organize your items') ?></p>
    </div>
</div>

<div class="grid-8">
    <ul class="categories-list">
        <?php echo $this->element('categories/item', array(
            'level' => 0,
            'category' => $categories[0][0],
            'categories' => $categories
        )) ?>

        <!-- add subcategory -->
        <!--
        <li class="level-2-form">
            <?php echo $this->form->create('/categories/add') ?>
            <?php echo $this->form->input('title', array(
                'type' => 'text',
                'div' => false,
                'label' => false,
                'class' => 'ui-text'
            )) ?>
            <?php echo $this->form->submit('salvar', array(
                'class' => 'ui-button highlight'
            )) ?>
            <?php echo $this->html->link('cancelar', '#', array(
                'class' => 'ui-button small'
            )) ?>
            <?php echo $this->form->close() ?>
        </li>
        -->
        
        <!-- add category -->
        <!--
        <li class="level-1-form">
            <?php echo $this->form->create('/categories/add') ?>
            <?php echo $this->form->input('title', array(
                'type' => 'text',
                'div' => false,
                'label' => false,
                'class' => 'ui-text'
            )) ?>
            <?php echo $this->form->submit('salvar', array(
                'class' => 'ui-button highlight'
            )) ?>
            <?php echo $this->html->link('cancelar', '#', array(
                'class' => 'ui-button small'
            )) ?>
            <?php echo $this->form->close() ?>
        </li>
        -->
        
    </ul>

    <?php echo $this->html->link(s('Add Category'), '/categories/add/' . $root->id, array(
        'class' => 'ui-button large add push-scene',
        'style' => 'margin-bottom: 40px'
    )) ?>
</div>

<div class="clear"></div>

</div><!-- /categories-list -->

<?php if(!$this->controller->isXhr()): ?>
    </div><!-- /slide-elem -->
    </div><!-- /slide-container -->
<?php endif ?>
 