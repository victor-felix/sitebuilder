<fieldset>
    <h2><?php echo s('Logo') ?></h2>
    <div class="field-group">
        <?php if($site->logo()): ?>
            <?php echo $this->html->image($site->logo()->link('200x200'), array(
                'class' => 'logo'
            )) ?>
            <?php echo $this->html->link(s('delete logo'), '/images/delete/' . $site->logo()->id) ?>
        <?php endif ?>
        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('logo', array(
                'label' => s('Logo'),
                'type' => 'file',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('To improve appearence of logo on your mobi site, we recommend to use an image on GIF or PNG with transparent background. Max size 50kb') ?></small>
        </div>
    </div>
</fieldset>

<fieldset>
    <h2><?php echo s('themes') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <small style="margin: 0 0 15px 0"><?php echo s('You can customize the appearence of your mobi site to fit the ergonomy to fit your business colors. Select a theme below and apply one of provided skins') ?></small>
			<?php 
				$currentTheme = $site->theme ? $site->theme : $themes[0]->_id;
				$currentSkin = $site->skin ? $site->skin : key($themes[0]->colors);
			?>
            <div class="theme-picker">
                <h3><?php echo s('Select a theme <em>(more themes very soon)</em>') ?></h3>
                <ul>
                    <?php foreach($themes as $theme): ?>
                        <li class="<?php if($theme->_id == $currentTheme) echo 'selected'?>">
                            <a href="<?php echo '#' . $theme->_id ?>">
                                <span class="thumbs">
                                <?php foreach ($theme->thumbnails as $thumbnail): ?>
                                    <?php echo $this->html->image(Themes::thumbPath($thumbnail)) ?>
                                <?php endforeach ?>
                                </span>
                                <span><?php echo $theme->name ?></span>
                            </a>
                            <span class="arrow left"></span>
                            <span class="arrow right"></span>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>
            <div class="skin-picker">
                <h3><?php echo s('Personalize the theme') ?></h3>
                
                <?php foreach($themes as $theme): ?>
	                <?php
	                	$skins = array_keys((array) $theme->colors);
	                	$currentThemeSkin =  in_array($currentSkin, $skins) && $currentTheme == $theme->_id
	                						 ? $currentSkin 
	                						 : reset($skins);
	                ?>
	                <ul id="<?php echo $theme->_id; ?>-skins" class="skin-list" style="<?php if($theme->_id != $currentTheme) echo 'display: none;'?>">
	                	
	                	<?php foreach($skins as $skin): ?>
						    <li class="<?php if($skin == $currentThemeSkin) echo 'selected'?>">
						        <a href="<?php echo '#' . $skin ?>" style="background-color:#<?php echo $skin ?>"></a>
						    </li>
						<?php endforeach ?>
						
	                </ul>
	                
                <?php endforeach ?>
                
                <div class="clear"></div>
            </div>
            
            <?php echo $this->form->input('theme', array(
                'type' => 'hidden',
                'value' => $currentTheme,
            	'id' => 'theme'
            )) ?>
            
            <?php echo $this->form->input('skin', array(
                'type' => 'hidden',
                'value' => $currentSkin,
            	'id' => 'skin'
            )) ?>
        </div>
    </div>
</fieldset>
<?php $this->html->script('shared/theme_carroussel', false); ?>
