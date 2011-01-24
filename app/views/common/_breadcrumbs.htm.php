<p class="breadcrumb">
    <?php foreach($category->breadcrumbs() as $c): ?>
        <?php echo $c->title ?> /
    <?php endforeach ?>
</p>