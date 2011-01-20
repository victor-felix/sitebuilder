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
        <?php foreach($categories[0] as $root): ?>
        <li class="level-0">
            <span class="title" title="<?php echo __('clique para editar') ?>">
                <?php echo $root->title ?>
            </span>
            <div class="controls">
                <?php echo $this->html->link(__('adicionar produto'), '/manage', array('class' => 'ui-button highlight')) ?>
                <?php echo $this->html->link(__('gerenciar produtos'), '/manage', array('class' => 'ui-button manage')) ?>
            </div>
        </li>

            <?php foreach($categories[$root->id] as $category): ?>
            <li class="level-1">
                <?php echo $this->html->link($this->html->image('categories/add-subcat.png'), '/manage', array('class' => 'ui-button ui-button-add highlight')) ?>
                <span class="title" title="<?php echo __('clique para editar') ?>">
                    <?php echo $category->title ?>
                </span>
                <div class="controls">
                    <?php echo $this->html->link(__('adicionar produto'), '#', array('class' => 'ui-button highlight')) ?>
                    <?php echo $this->html->link(__('gerenciar produtos'), '#', array('class' => 'ui-button ')) ?>
                    <?php echo $this->html->imagelink('categories/delete.gif', '#', array(), array(
                        'class' => 'ui-button delete icon'
                    )) ?>
                </div>
                <div class="delete-confirm">
                    <div class="wrapper">
                        <p>Deseja realmente apagar <strong>Sobremesas</strong>? <small>Todos os produtos e subcategorias associados serão apagados.</small></p>
                        <?php echo $this->html->link('Sim, apagar', '/categories/delete/' . $category->id, array(
                            'class' => 'ui-button delete highlight'
                        )) ?>
                        <?php echo $this->html->link('Não, voltar', '#', array(
                            'class' => 'ui-button'
                        )) ?>
                    </div>
                </div>
            </li>

                <?php foreach($categories[$category->id] as $subcategory): ?>
                <li class="level-2">
                    <span class="title" title="<?php echo __('clique para editar') ?>">
                        <?php echo $subcategory->title ?>
                    </span>
                    <div class="controls">
                        <?php echo $this->html->link(__('adicionar produto'), '/manage', array('class' => 'ui-button highlight')) ?>
                        <?php echo $this->html->link(__('gerenciar produtos'), '/manage', array('class' => 'ui-button ')) ?>
                        <?php echo $this->html->link($this->html->image('categories/delete.gif'), '/manage', array('class' => 'ui-button delete icon')) ?>
                    </div>
                    <div class="delete-confirm">
                        <div class="wrapper">
                            <p>Deseja realmente apagar <strong>Sobremesas</strong>? <small>Todos os produtos e subcategorias associados serão apagados.</small></p>
                            <?php echo $this->html->link('Sim, apagar', '/categories/delete/1', array(
                                'class' => 'ui-button delete highlight'
                            )) ?>
                            <?php echo $this->html->link('Não, voltar', '#', array(
                                'class' => 'ui-button'
                            )) ?>
                        </div>
                    </div>
                </li>
                <?php endforeach ?>

            <?php endforeach ?>

        <?php endforeach ?>

        
        <!-- add subcategory -->
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
        
        <!-- add category -->
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
        
    </ul>

    <?php echo $this->html->link(__('Adicionar Categoria'), '/categories/add', array(
        'class' => 'ui-button large',
        'style' => 'margin-bottom: 40px'
    )) ?>
</div>

<div class="clear"></div>
</div>