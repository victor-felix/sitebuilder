<?php if($category): ?>
    <p class="breadcrumb">
        <?php foreach($category->breadcrumbs() as $c): ?>
            <?php echo e($c->title) ?> /
        <?php endforeach ?>
    </p>
<?php endif ?>