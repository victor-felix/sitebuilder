<h1><?php echo __('Itens de Negócio') ?></h1>
<h2><?php echo $this->html->link(__('Adicionar Novo'), '/business_items/add') ?></h2>

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
</table>