<h1><?php echo __('Sites') ?></h1>
<h2><?php echo $this->html->link(__('Adicionar Novo'), '/sites/add') ?></h2>

<table>
    <tr>
        <th><?php echo __('Título') ?></th>
        <th><?php echo __('Domínio') ?></th>
        <th colspan="2"><?php echo __('Ações') ?></th>
    </tr>
    <?php foreach($sites as $site): ?>
        <tr>
            <td><?php echo $site->title ?></td>
            <td><?php echo $site->domain ?></td>
            <td><?php echo $this->html->link(__('Editar'), '/sites/edit/' . $site->id) ?></td>
            <td><?php echo $this->html->link(__('Apagar'), '/sites/delete/' . $site->id) ?></td>
        </tr>
    <?php endforeach ?>
</table>