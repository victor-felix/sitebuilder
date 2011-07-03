<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '/categories', array( 'class' => 'ui-button large back pop-scene' )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle = s('Add Category') ?></h1>
        <?php echo $this->element('common/breadcrumbs', array(
            'category' => $parent
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<?php echo $this->element('categories/form', array(
    'action' => '/categories/add',
    'category' => $category,
    'parent' => $parent,
    'site' => $site
)) ?>


