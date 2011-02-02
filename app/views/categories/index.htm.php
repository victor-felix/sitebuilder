<?php
if(!$this->controller->isXhr()) {
?>
<div id="slide-container">
<div class="slide-elem" rel="/categories">
<?php
}
?>

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
        <h4><?php echo __('Dica') ?></h4>
        <p><?php echo __('Utilize o gerenciador ao lado para gerenciar o seu cardápio. Você pode criar categorias (entradas, pratos, etc.), subcategorias (massas, saladas, carnes), ou qualquer estruturação que se adapte ao seu negócio. Em seguida, adicione pratos ou produtos às respectivas categorias usando o botão ‘Gerenciar produtos’ em cada categoria.') ?></p>
    </div>
</div>

<div class="grid-8">
    <ul class="categories-list">
        <?php foreach($categories[0] as $root): ?>
        <li class="level-0" data-catid="<?php echo $root->id ?>">
            <span class="title edit-in-place" data-saveurl="/categories/edit/<?php echo $root->id ?>" title="<?php echo __('clique para editar') ?>">
                <?php echo e($root->title) ?>
            </span>
            <div class="controls">
                <?php echo $this->html->link(__('adicionar produto'), '/business_items/add/' . $root->id, array('class' => 'ui-button highlight push-scene')) ?>
                <?php echo $this->html->link(__('gerenciar produtos'), '/business_items/index/' . $root->id, array('class' => 'ui-button manage push-scene')) ?>
            </div>
            <div class="children-count"><?php echo $root->childrenCount() ?></div>
        </li>

            <?php if(array_key_exists($root->id, $categories)) foreach($categories[$root->id] as $category): ?>
            <li class="level-1" data-catid="<?php echo $category->id ?>">
                <?php echo $this->html->link($this->html->image('categories/add-subcat.png'), '/categories/add/' . $category->id, array('class' => 'ui-button ui-button-add highlight push-scene ui-button-add')) ?>
                <span class="title edit-in-place" data-saveurl="/categories/edit/<?php echo $category->id ?>" title="<?php echo __('clique para editar') ?>">
                    <?php echo e($category->title) ?>
                </span>
                <div class="controls">
                    <?php echo $this->html->link(__('adicionar produto'), '/business_items/add/' . $category->id, array('class' => 'ui-button highlight push-scene')) ?>
                    <?php echo $this->html->link(__('gerenciar produtos'), '/business_items/index/' . $category->id, array('class' => 'ui-button manage push-scene')) ?>
                    <?php echo $this->html->imagelink('categories/delete.gif', '#', array(), array(
                        'class' => 'ui-button delete icon'
                    )) ?>
                </div>
                <div class="children-count"><?php echo $category->childrenCount() ?></div>
                <div class="delete-confirm">
                    <div class="wrapper">
                        <p><?php echo __('Deseja realmente apagar <strong>%s</strong>?', e($category->title)) ?> <small><?php echo __('Todos os produtos e subcategorias associados serão apagados.') ?></small></p>
                        <?php echo $this->html->link(__('Sim, apagar'), '/categories/delete/' . $category->id, array(
                            'class' => 'ui-button delete highlight'
                        )) ?>
                        <?php echo $this->html->link(__('Não, voltar'), '#', array( 'class' => 'ui-button' )) ?>
                    </div>
                </div>
            </li>

                <?php if(array_key_exists($category->id, $categories)) foreach($categories[$category->id] as $subcategory): ?>
                <li class="level-2" data-catid="<?php echo $subcategory->id ?>">
                    <span class="title edit-in-place" data-saveurl="/categories/edit/<?php echo $subcategory->id ?>" title="<?php echo __('clique para editar') ?>">
                        <?php echo e($subcategory->title) ?>
                    </span>
                    <div class="controls">
                        <?php echo $this->html->link(__('adicionar produto'), '/business_items/add/' . $subcategory->id, array('class' => 'ui-button highlight push-scene')) ?>
                        <?php echo $this->html->link(__('gerenciar produtos'), '/business_items/index/' . $subcategory->id, array('class' => 'ui-button manage push-scene')) ?>
                        <?php echo $this->html->link($this->html->image('categories/delete.gif'), '#', array('class' => 'ui-button delete icon')) ?>
                    </div>
                    <div class="children-count"><?php echo $subcategory->childrenCount() ?></div>
                    <div class="delete-confirm">
                        <div class="wrapper">
                            <p><?php echo __('Deseja realmente apagar <strong>%s</strong>?', e($subcategory->title)) ?> <small><?php echo __('Todos os produtos e subcategorias associados serão apagados.') ?></small></p>
                            <?php echo $this->html->link('Sim, apagar', '/categories/delete/' . $subcategory->id, array(
                                'class' => 'ui-button delete highlight'
                            )) ?>
                            <?php echo $this->html->link('Não, voltar', '#', array( 'class' => 'ui-button' )) ?>
                        </div>
                    </div>
                </li>
                <?php endforeach ?>

            <?php endforeach ?>

        <?php endforeach ?>


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

    <?php echo $this->html->link(__('Adicionar Categoria'), '/categories/add/' . $root->id, array(
        'class' => 'ui-button large add push-scene',
        'style' => 'margin-bottom: 40px'
    )) ?>
</div>

<div class="clear"></div>

</div><!-- /categories-list -->

<?php
if(!$this->controller->isXhr()) {
?>
</div><!-- /slide-elem -->
</div><!-- /slide-container -->
<?php
}
?>
