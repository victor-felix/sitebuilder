<h1>Sites</h1>
<h2><?php echo $this->html->link('Add New', '/sites/add') ?></h2>

<table>
    <tr>
        <th>Title</th>
        <th>Domain</th>
        <th colspan="2">Actions</th>
    </tr>
    <?php foreach($results as $result): ?>
        <tr>
            <td><?php echo $result->title ?></td>
            <td><?php echo $result->domain ?></td>
            <td><?php echo $this->html->link('Edit', '/sites/edit/' . $result->id) ?></td>
            <td><?php echo $this->html->link('Delete', '/sites/delete/' . $result->id) ?></td>
        </tr>
    <?php endforeach ?>
</table>