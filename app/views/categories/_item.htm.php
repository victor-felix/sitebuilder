<li class="level-<?php echo $level ?>" data-catid="<?php echo $category->id ?>" data-parentid="<?php echo $category->parent_id ?>">
    <?php if($level == 1): ?>
        <?php echo $this->html->imagelink('categories/add-subcat.png', '/categories/add/' . $category->id, array(), array(
            'class' => 'ui-button ui-button-add highlight push-scene'
        )) ?>
    <?php endif ?>

    <span class="title edit-in-place" data-saveurl="/categories/edit/<?php echo $category->id ?>" title="<?php echo __('clique para editar') ?>">
        <?php echo e($category->title) ?>
    </span>

    <div class="controls">

        <?php if($category->title != 'automatic'): // TODO: cahnge to correct automatic category logic ?>

            <?php if($level >= 1): ?>
                <?php echo $this->html->link(__('adicionar item'), '/business_items/add/' . $category->id, array(
                    'class' => 'ui-button highlight push-scene'
                )) ?>
            <?php endif ?>
        
            <?php echo $this->html->link(__('gerenciar items'), '/business_items/index/' . $category->id, array(
                'class' => 'ui-button manage push-scene left-join'
            )) ?>
        <?php else: ?>
            <em><?php echo __('categoria automática')?></em>
        <?php endif ?>

        <?php echo $this->html->link(__('opções'), '/categories/edit/' . $category->id, array(
            'class' => 'ui-button manage push-scene'
        )) ?>
    </div>
    <div class="children-count"><?php echo $category->childrenCount() ?></div>
    <?php if($category->parent_id > 0): ?>
        <div class="delete-confirm">
            <div class="wrapper">
                <p><?php echo __('Deseja realmente apagar <strong>%s</strong>?', e($category->title)) ?> <small><?php echo __('Todos os produtos e subcategorias associados serão apagados.') ?></small></p>
                <?php echo $this->html->link(__('Sim, apagar'), '/categories/delete/' . $category->id, array(
                    'class' => 'ui-button delete highlight'
                )) ?>
                <?php echo $this->html->link(__('Não, voltar'), '#', array( 'class' => 'ui-button' )) ?>
            </div>
        </div>
    <?php endif ?>
</li>

<?php if(array_key_exists($category->id, $categories)): ?>
    <?php foreach($categories[$category->id] as $subcategory): ?>
        <?php echo $this->element('categories/item', array(
            'level' => $level + 1,
            'category' => $subcategory,
            'categories' => $categories
        )) ?>
    <?php endforeach ?>
<?php endif ?>
