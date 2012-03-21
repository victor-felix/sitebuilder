<?php if(!$this->controller->isXhr()): ?>
    <div id="slide-container">
    <div class="slide-elem" rel="/categories">
<?php endif ?>
<?php $this->pageTitle = s('Site users') ?>
<div class="page-heading">
    <div class="grid-4 first">&nbsp;</div>
    <div class="grid-8">
        <h1><?php echo $this->pageTitle ?></h1>
    </div>
    <div class="clear"></div>
</div>

<div id="users-list">
    <div class="grid-4 first">
        <div class="tip">
            <h4><?php echo s('Tip') ?></h4>
            <p><?php echo s('Use panel on right of screen to manage your items. You can create categories and subcategories to organize your items') ?></p>
        </div>
    </div>
    <div class="grid-8">
    

    <ul class="categories-list">
        <?php foreach ($users as $user): ?>
        <li data-parentid="0" data-catid="3" class="level-0">
            <span title="click to edit" data-saveurl="/categories/edit/3" class="title edit-in-place"><?php echo $user->fullname() ?></span>
            <div class="controls">
            <!-- 
                <a href="/business_items/add/3" class="ui-button highlight push-scene">add item</a>
                <a href="/business_items/index/3" class="ui-button manage push-scene left-join">manage items</a>--> 
                <a href="/categories/edit/3" class="ui-button manage push-scene">remove</a>
            </div>
            <div class="children-count"><?php echo s('joined at %s',$user->created) ?></div>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php echo $this->html->link(s('Invite new user'), '/users/invite', array(
        'class' => 'ui-button large add push-scene',
        'style' => 'margin-bottom: 40px'
    )) ?>
    </div>
</div><!-- users-list -->

<?php if(!$this->controller->isXhr()): ?>
    </div><!-- /slide-elem -->
    </div><!-- /slide-container -->
<?php endif ?>