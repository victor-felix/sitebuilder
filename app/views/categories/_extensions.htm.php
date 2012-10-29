<!-- extensions -->
<?php if ($category->id): ?>
<fieldset>
    <div class="grid-4 first">
        <div class="tip">
            <h2><?php echo s('Extensions') ?></h2>
            <p><?php echo s('yout can enable extensions to add custom functionality to this category. the availability of extensions change according to the content type of the category') ?></p>
        </div>
    </div>
    <div class="grid-8">    
    	<ul class="composed-list">
    	<?php foreach (app\models\Extensions::available($category->type, $category->id) as $extension): ?>
    		<?php 
	        	 if($extension->_id){
                 	$href = sprintf('/extensions/edit/%s', $extension->_id);
	        	 } else {
                 	$href = sprintf('/extensions/add/%s/%s', $extension->specification('type'), $category->id);
	        	 }
             ?>	
	        <li class="level-0">
	        	<a href="<?php echo $href ?>" class="push-scene" >   
	            <p class="title">
	            		
		            <span class="accessory-label" >
		            	<span><?php echo s('%s', $extension->enabled ? 'enabled' : 'disabled' ) ?></span>
		            	<span class="arrow" >&#62;</span>
		            </span>
	            	<?php echo s($extension->specification('title')) ?>
	            	<br/>
	            	<span class="description"><?php echo s($extension->specification('description')) ?></span>
		            
	            </p>
	            </a>
	        </li>
	    <?php endforeach; ?>
	    </ul>
    </div>
</fieldset>
<?php endif;?>
<!-- extensions -->
