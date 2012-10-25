<!-- extensions -->
<?php if ($category->id): ?>
<div>
    <div class="grid-4 first">
        <div class="tip" style="border:none">
            <h4><?php echo s('Extensions') ?></h4>
            <p><?php echo s('yout can enable extensions to add custom functionality to this category. the availability of extensions change according to the content type of the category') ?></p>
        </div>
    </div>
    <div class="grid-8">    
    	<ul class="categories-list" style="border:none">
    	<?php foreach (app\models\Extensions::available($category->type, $category->id) as $extension): ?>
	        <li class="level-0" style="border-top:none; border-bottom: 1px solid #DDD;">
	            <span class="title"><?php echo s($extension->specification('title')) ?></span>
	            <div class="controls">
	                 <?php if($extension->_id):?>
	                 
	                 	<?php echo $this->html->link(
		                		s('%s', $extension->enabled ? 'disable' : 'enable'), 
		                		sprintf('/extensions/enable/%s/', $extension->_id), 
		                		array('class' => 'ui-button manage left-join')
		                		) ?>
	                 
	                 	<?php echo $this->html->link(s('Edit'), sprintf('/extensions/edit/%s', $extension->_id), array(
		                    'class' => 'ui-button manage push-scene'
		                )) ?>
	                 <?php else: ?>
	                 	<?php echo $this->html->link(
		                		s('Enable'), 
		                		sprintf('/extensions/add/%s/%s', $extension->specification('type'), $category->id), 
		                		array('class' => 'ui-button manage push-scene')
		                		) ?>
	                <?php endif;?>	   
	                             
	            </div>
	            <div class="children-count" style="width: auto;"><?php echo s('%s', $extension->enabled ? 'enabled' : 'disabled' ) ?></div>
	        </li>
	    <?php endforeach; ?>
	    </ul>
    </div>
</div>
<?php endif;?>
<!-- extensions -->
