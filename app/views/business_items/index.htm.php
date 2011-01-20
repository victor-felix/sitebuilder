<div class="page-heading">
    <div class="grid-4 first"><?php echo $this->html->link(__('‹ voltar'), '#BACK', array(
        'class' => 'ui-button large back'
    )) ?>
    </div>
    <div class="grid-8">
        <h1><?php echo __('Massas') ?></h1>
        <p class="breadcrumb">Cardápio / Pratos</p>
        
        <?php echo $this->html->link(__('adicionar produto'), '/business_items/add', array(
            'class' => 'ui-button highlight large add-business-item'
        )) ?>
    </div>
    <div class="clear"></div>
</div>

<ul class="businessitems-list">
    <li>
        <?php echo $this->html->link($this->html->image('http://www.magnifique.ca/wp-content/uploads/2010/11/food-photo.jpg'), '/business_items/edit/', array(
            'class' => 'photo'
        )) ?>
        <div class="info">
            <?php echo $this->html->link('Aliquam erat volupat', '/business_items/edit/'); ?>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam suscipit dolor sed odio tempor lobortis.</p>
        </div>
    </li>
    <li>
        <?php echo $this->html->link($this->html->image('http://www.magnifique.ca/wp-content/uploads/2010/11/food-photo.jpg'), '/business_items/edit/', array(
            'class' => 'photo'
        )) ?>
        <div class="info">
            <?php echo $this->html->link('Aliquam erat volupat', '/business_items/edit/'); ?>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam suscipit dolor sed odio tempor lobortis. Mauris laoreet ante quis eros cursus tempus.</p>
        </div>
    </li>
    <li>
        <?php echo $this->html->link($this->html->image('http://www.magnifique.ca/wp-content/uploads/2010/11/food-photo.jpg'), '/business_items/edit/', array(
            'class' => 'photo'
        )) ?>
        <div class="info">
            <?php echo $this->html->link('Aliquam erat volupat', '/business_items/edit/'); ?>
            <p>Etiam suscipit dolor sed odio tempor lobortis. Mauris laoreet ante quis eros cursus tempus. Aenean justo purus, vulputate egestas egestas et, pretium at arcu. Aliquam erat volutpat. Fusce vitae pulvinar justo. Nam ac tellus libero, at pretium erat.</p>
        </div>
    </li>
    <li>
        <?php echo $this->html->link($this->html->image('http://www.magnifique.ca/wp-content/uploads/2010/11/food-photo.jpg'), '/business_items/edit/', array(
            'class' => 'photo'
        )) ?>
        <div class="info">
            <?php echo $this->html->link('Aliquam erat volupat', '/business_items/edit/'); ?>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam suscipit dolor sed odio tempor lobortis. Mauris laoreet ante quis eros cursus tempus. Aenean justo purus, vulputate egestas egestas et, pretium at arcu. Aliquam erat volutpat.</p>
        </div>
    </li>
</ul>

<div class="fieldset-actions">
    <div class="grid-4 first">
        <?php echo $this->html->link(__('‹ voltar'), '#BACK', array(
            'class' => 'ui-button large back'
        )) ?>
    </div>
    <div class="grid-8">
        <?php echo $this->html->link(__('adicionar produto'), '/business_items/add', array(
            'class' => 'ui-button highlight large'
        )) ?>
    </div>
    <div class="clear"></div>

</div>
<!--
<table>
    <tr>
        <th><?php echo __('Título') ?></th>
        <th colspan="2"><?php echo __('Ações') ?></th>
    </tr>
    <?php foreach($business_items as $bi): ?>
        <tr>
            <td><?php echo $bi->values()->title ?></td>
            <td><?php echo $this->html->link(__('Editar'), '/business_items/edit/' . $bi->id) ?></td>
            <td><?php echo $this->html->link(__('Apagar'), '/business_items/delete/' . $bi->id) ?></td>
        </tr>
    <?php endforeach ?>
</table>-->