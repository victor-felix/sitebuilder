<fieldset>
	<h2><?= s('common settings') ?></h2>
	<div class="field-group">

		<?php foreach($item->fields(Auth::user()->site()) as $field)://TODO remove Auth call ?>
			<div class="form-grid-460 first">
				<?= $this->items->input($field) ?>
			</div>
		<?php endforeach ?>

		<?php if($images = $item->images()): ?>
			<?php foreach($images as $i => $image): $class = $i % 3 ? '' : 'first' ?>
				<div class="<?= $class ?> picture-upload-container done" style="background-image: url(<?= $image->link('139x139') ?>)">
					<?= $this->html->link('', '/images/delete/' . $image->id, [
						'class' => 'close'
					]) ?>
					<?= $this->form->input('image['.$image->id.'][title]', [
						'label' => false,
						'class' => 'ui-text large',
						'placeholder' => s('edit subtitle'),
						'value' => $image->title
					]) ?>
				</div>
			<?php endforeach ?>
		<?php endif ?>

		<?php $class = (isset($i) ? $i + 1 : 0) % 3 ? '' : 'first' ?>
		<div class="<?= $class ?> picture-upload-container" data-url="/images/add.htm">
			<input type="hidden" name="image[foreign_key]" value="<?= $item->_id ?>" />
			<input type="hidden" name="image[model]" value="Items" />
			<a class="close"></a>
			<iframe src="about:blank" id="iframe_<?= time() ?>"></iframe>
			<div class="default">
				<div class="icon_upload"></div>
				<?= s('add photo') ?>
			</div>
			<div class="wait"><?= s('uploading photo...') ?></div>
			<?= $this->form->input('image[photo]', [
				'label' => false,
				'type' => 'file',
				'class' => 'ui-text large picture-upload'
			]) ?>
			<?= $this->form->input('image[ID][title]', [
				'label' => false,
				'class' => 'ui-text large',
				'placeholder' => s('edit subtitle'),
			]) ?>
		</div>

		<a href="#" class="duplicate-previous">more</a>

	</div>
</fieldset>
<fieldset>
	<h2><?= s('PDF Files') ?></h2>
	<div class="field-group">
		<?php if($item->medias): ?>
			<?php 
				foreach($item->medias as $key => $media ):
					if ($media['type'] == 'application/pdf'):
			?>
				<div class="form-grid-460 first item-media">
					<?= $this->form->input("medias[$key][type]", [
						'type' => 'hidden',
						'data-keep-value' => true,
						'value' => $media['type'],
					]) ?>
					<?= $this->form->input("medias[$key][length]", [
						'type' => 'hidden',
						'value' => $media['length'],
					]) ?>
					<?= $this->form->input("medias[$key][title]", [
						'class' => 'ui-text large',
						'label' => s('Title'),
						'value' => $media['title'],
					]) ?>
					<?= $this->form->input("medias[$key][url]", [
						'label' => false,
						'class' => 'ui-text large',
						'label' => s('Url'),
						'value' => $media['url'],
					]) ?>
					<?= $this->html->link(s('Remove file'), null, ['data-remove' => true, 'class' => 'right']); ?>
				</div>
			<?php
					endif;
				endforeach;
			?>
		<?php endif ?>
		<div class="form-grid-460 first item-media hidden">
			<?= $this->form->input("medias[0][type]", [
				'type' => 'hidden',
				'data-keep-value' => true,
				'value' => 'application/pdf',
				'disabled' => true,
			]) ?>
			<?= $this->form->input("medias[0][title]", [
				'class' => 'ui-text large',
				'label' => s('Title'),
				'disabled' => true,
			]) ?>
			<?= $this->form->input("medias[0][url]", [
				'label' => false,
				'class' => 'ui-text large',
				'label' => s('Url'),
				'disabled' => true,
			]) ?>
			<?= $this->html->link(s('Remove file'), null, ['data-remove' => true, 'class' => 'right']); ?>
		</div>
	<?= $this->html->link(s('Add file'), null, ['data-add-new-property' => ".item-media", 'class' => 'ui-button']); ?>
	</div>
</fieldset>
