<h1><?php echo __('Categorias') ?></h1>
<h2><?php echo $this->html->link(__('Adicionar Novo'), '/categories/add') ?></h2>

<table>
    <tr>
        <th><?php echo __('Título') ?></th>
        <th colspan="2"><?php echo __('Ações') ?></th>
    </tr>
    <?php foreach($categories as $category): ?>
        <tr>
            <td><?php echo $category->title ?></td>
            <td><?php echo $this->html->link(__('Editar'), '/categories/edit/' . $category->id) ?></td>
            <td><?php echo $this->html->link(__('Apagar'), '/categories/delete/' . $category->id) ?></td>
        </tr>
    <?php endforeach ?>
</table>