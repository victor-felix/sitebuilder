<?php $this->layout = 'register' ?>
<?php $this->showTitle = false ?>
<?php $this->pageTitle = s('Create your Mobi') ?>

<div class="registration-finished">
    <?php echo $this->html->image('shared/register/finished.png', array(
        'alt' => s('Settings completed successfully')
    )); ?>
    <h2><?php echo s('Congratulations! your site <strong>mobi</strong> is ready at %s', $this->html->link($site->link())) ?></h2>
    <div class="next-steps">
        <h3><?php echo s('Next steps') ?></h3>
        <p><?php echo s('Your mobi site is live, you can continue to improve it.') ?></p>
        <ul>
            <li><?php echo $this->html->link(s('Add categories to your business'), '/categories') ?></li>
            <li><?php echo $this->html->link(s('Set more details about your business'), '/settings') ?></li>
        </ul>
        <div class="clear"></div>
    </div>
</div>
