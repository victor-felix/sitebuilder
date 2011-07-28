<fieldset>
    <h2><?php echo s('details about your business') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <?php echo $this->form->input('title', array(
                'label' => s('Name of business'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>

        <div class="form-grid-460">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('description', array(
                'label' => s('Description of business'),
                'type' => 'textarea',
                'class' => 'ui-textarea large',
                'maxlenght' => 500
            )) ?>
            <small><?php echo s('Give a brief description baout your business and related activities. Max of 500 chars.') ?></small>
        </div>

        <div class="form-grid-460 first">
            <div class="site-mobile-url">
                <div class="input text">
                    <label for="FormSlug"><?php echo s('url of mobile site') ?></label>
                    <p class="meumobi-url">
                        <span>http://</span>
                        <?php echo $this->form->input('slug', array(
                            'label' => false,
                            'div' => false,
                            'type' => 'text',
                            'class' => 'ui-text' . ($action == 'edit' ? ' disabled' : ''),
                            'disabled' => $action == 'edit'
                        )) ?><span>.meumobi.com</span>
                    </p>
                    <div class="clear"></div>
                </div>
            </div>
            <?php if($action == 'register'): ?>
                <small><?php echo s("Be careful, you couldn't change your url later") ?></small>
            <?php else: ?>
                <small><?php echo s("You can't change the url of your mobile site") ?></small>
            <?php endif ?>

            <div class="site-mobile-custom-domain">
                <div class="input checkbox">
                    <?php echo $this->form->input('custom_domain', array(
                        'label' => false,
                        'div' => false,
                        'type' => 'checkbox',
                        'class' => 'ui-checkbox'
                    )) ?>
                </div>
                <label for="FormCustomDomain" class="checkbox"><?php echo s('use a custom domain name') ?></label>

                <div class="input text">
                    <p class="meumobi-url">
                        <span>http://</span>
                        <?php echo $this->form->input('domain', array(
                            'label' => false,
                            'div' => false,
                            'type' => 'text',
                            'class' => 'ui-text'
                        )) ?>
                    </p>
                    <div class="clear"></div>
                </div>
            </div>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('News feed - RSS') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo s('News feed - RSS') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('feed_title', array(
                'label' => s('Title'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>

        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('feed_url', array(
                'label' => s('url of RSS feed'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('RSS (most commonly expanded as "Really Simple Syndication") is a family of web feed formats used to publish frequently updated works—such as blog entries, news headlines—in a standardized format. You can use it to feed news section of your mobi site') ?></small>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Location') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo s('Location') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('street', array(
                'label' => s('Street'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>

        <div class="form-grid-220 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('number', array(
                'label' => s('Number'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>

        <div class="form-grid-220">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('complement', array(
                'label' => s('Complement'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>

        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('zone', array(
                'label' => s('District'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>

        <div class="form-grid-220 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('country_id', array(
                'label' => s('Country'),
                'type' => 'select',
                'empty' => array(''),
                'options' => $countries,
                'class' => 'ui-select'
            )) ?>
        </div>

        <div class="form-grid-220">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('state_id', array(
                'label' => s('State'),
                'type' => 'select',
                'class' => 'ui-select',
                'options' => $states,
                'empty' => array('')
            )) ?>
        </div>

        <div class="form-grid-220 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('city', array(
                'label' => s('City'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>

        <div class="form-grid-220">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('zip', array(
                'label' => s('zip'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
        </div>

        <div class="form-grid-220 first">
            <?php echo $this->form->input('timezone', array(
                'label' => s('Timezone'),
                'type' => 'select',
                'class' => 'ui-select',
                'options' => $site->timezones()
            )) ?>
        </div>

        <div class="form-grid-220">
            <?php echo $this->form->input('date_format', array(
                'label' => s('Date format'),
                'type' => 'select',
                'class' => 'ui-select',
                'options' => $site->dateFormats()
            )) ?>
        </div>

    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Contact') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo s('Contact') ?></h2>
    <div class="field-group">

        <div class="form-grid-220 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('phone', array(
                'label' => s('Phone'),
                'type' => 'text',
                'class' => 'ui-text'
            )) ?>
            <small><?php echo s('Ex.: (00) 0000-0000') ?></small>
        </div>

        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('email', array(
                'label' => s('Mail'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Open hours') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo s('Open hours') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('timetable', array(
                'label' => s('Open hours'),
                'type' => 'textarea',
                'class' => 'ui-textarea large'
            )) ?>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Your links on web') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo s('Your links on web') ?></h2>
    <div class="field-group">
        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('facebook', array(
                'label' => s('Facebook Page'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('Ex: http://www.facebook.com/username/') ?></small>
        </div>

        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('twitter', array(
                'label' => s('Twitter Page'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('Ex: http://www.twitter.com/username/') ?></small>
        </div>

        <div class="form-grid-460 first">
            <span class="optional"><?php echo s('Optional') ?></span>
            <?php echo $this->form->input('website', array(
                'label' => s('Url of your current website'),
                'type' => 'text',
                'class' => 'ui-text large'
            )) ?>
            <small><?php echo s('Ex: http://www.yourwebsite.com/') ?></small>
        </div>
    </div>
</fieldset>

<a href="#" class="fieldset-expand"><?php echo s('Photos of Business') ?> <span><?php echo s('Optional') ?></span></a>
<fieldset style="display:none">
    <h2><?php echo s('Photos of Business') ?></h2>
    <div class="field-group">
        <div class="first picture-upload-container" data-url="/images/add.htm">
			<input type="hidden" name="foreign_key" value="" />
			<input type="hidden" name="model" value="SitePhotos" />
			<a class="close"></a>
			<div class="default"><?php echo s('add photo'); ?></div>
			<div class="wait"><?php echo s('uploading photo...'); ?></div>
            <?php echo $this->form->input('photo[]', array(
                'label' => s(''),
                'type' => 'file',
                'class' => 'ui-text large picture-upload'
            )) ?>
        </div>
        <a href="#" class="duplicate-previous">more</a>

        <?php if($site->id && $images = $site->photos()): ?>
            <?php foreach($images as $image): ?>
            <?php echo $this->html->link(s('Delete image'), '/images/delete/' . $image->id) ?>
            <?php echo $this->html->image($image->link('80x80')) ?>
            <?php endforeach ?>
        <?php endif ?>
    </div>
</fieldset>
<?php $this->html->script('shared/async_upload', false); ?>