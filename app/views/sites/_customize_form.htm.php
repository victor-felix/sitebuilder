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
    <h2><?php echo s('themas') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <small style="margin: 0 0 15px 0"><?php echo s('You can customize the appearence of your mobi site to fit the ergonomy to fit your business colors. Select a theme below and apply one of provided skins') ?></small>
            
            <div class="theme-picker">
                <h3><?php echo s('Select a thema <em>(more themas very soon)</em>') ?></h3>
                <ul>
                    <?php foreach($themes as $slug => $theme): ?>
                        <li>
                            <a href="<?php echo '#' . $slug ?>">
                                <span class="thumbs">
                                <?php foreach ($theme->thumbnails as $thumbnail): ?>
                                    <?php echo $this->html->image('http://meu-template-engine.int-meumobi.com/' . $thumbnail) ?>
                                <?php endforeach ?>
                                </span>
                                <span><?php echo $slug ?></span>
                            </a>
                            <span class="arrow left"></span>
                            <span class="arrow right"></span>
                        </li>
                    <?php endforeach ?>
                </ul>
                <div class="clear"></div>
            </div>

            <?php $keys = array_keys(get_object_vars($themes)) ?>
            <?php echo $this->form->input('theme', array(
                'type' => 'hidden',
                'value' => $site->theme ? $site->theme : $keys[0]
            )) ?>

            <div class="skin-picker">
                <h3><?php echo s('Personalize the thema') ?></h3>
                <ul>
                </ul>
                <div class="clear"></div>
            </div>
            <?php $k = array_keys(get_object_vars($themes->{$keys[0]}->colors)) ?>
            <?php echo $this->form->input('skin', array(
                'type' => 'hidden',
                'value' => $site->skin
            )) ?>
        </div>
    </div>
</fieldset>
<?php $this->html->script('shared/theme_carroussel', false); ?>