<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $root->title ?></h1>
    </div>
    <div class="clear"></div>
</div>

<div id="categories-list">
<div class="grid-4 first">
    <div class="tip">
        <h4>Dica</h4>
        <p>Utilize o gerenciador ao lado para gerenciar o seu cardápio. Você pode criar categorias (entradas, pratos, etc.), subcategorias (massas, saladas, carnes), ou qualquer estruturação que se adapte ao seu negócio. Em seguida, adicione pratos ou produtos às respectivas categorias usando o botão ‘Gerenciar produtos’ em cada categoria.</p>
    </div>
</div>

<div class="grid-8">
    <ul class="categories-list">
        <?php foreach($categories as $i=>$category): ?>
        
        <?php
        $level = 0;
        if($i > 0)
            $level = ($category->parent_id == $categories[0]->id) ? 1 : 2;
        ?>
        <li class="level-<?php echo $level ?>">
            <?php if($category->parent_id != 0) echo $this->html->link($this->html->image('categories/add-subcat.png'), '#ADD', array('class' => 'ui-button ui-button-add highlight')) ?>
            <span class="title" title="<?php echo __('clique para editar') ?>"><?php echo $category->title; ?></span>
            <div class="controls">
                <?php echo $this->html->link(__('adicionar produto'), '/business_items/add', array('class' => 'ui-button highlight')) ?>
                <?php echo $this->html->link(__('gerenciar produtos'), '/business_items/', array('class' => 'ui-button ')) ?>
                <?php if($category->parent_id != 0) echo $this->html->link($this->html->image('categories/delete.gif'), '#DELETE', array('class' => 'ui-button delete icon')) ?>
            </div>
            <div class="delete-confirm">
                <div class="wrapper">
                    <p>Deseja realmente apagar <strong><?php echo $category->title; ?></strong>? <small>Todos os produtos e subcategorias associados serão apagados.</small></p>
                    <?php echo $this->html->link('Sim, apagar', '/categories/delete/'.$category->id, array(
                        'class' => 'ui-button delete highlight'
                    )); ?>
                    <?php echo $this->html->link('Não, voltar', '#', array(
                        'class' => 'ui-button'
                    )); ?>
                </div>
            </div>
        </li>
        <?php endforeach ?>

        <!-- add subcategory -->
        <li class="level-2-form" style="display: none">
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
        
        <!-- add category -->
        <li class="level-1-form" style="display: none">
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
        
    </ul>

    <?php echo $this->html->link(__('Adicionar Categoria'), '/categories/add', array(
        'class' => 'ui-button large',
        'style' => 'margin-bottom: 40px'
    )) ?>
</div>

<div class="clear"></div>
</div>