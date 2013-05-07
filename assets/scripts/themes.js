$(function() {
	var reloadPreview = function(theme, skin) {
		var frame = $('#theme-frame');
		var url = frame.data('url') + '?';

		if (theme && skin) {
			url += "theme=" + theme + "&skin=" + skin;
		}
		url += '&' + (new Date()).getTime();
		$('.theme-preview .wrapp .load').fadeIn(300, function() {
			frame.attr('src', url);
		});
	};

	var updateColorFields = function(skin, custom) {
		if (!custom) {
			$('#parent_id').val(skin);
		}
		$('.colors-wrap #color-picker-' + skin + ' .color').each(function() {
			var color = $(this);
			$('#' + color.data('color')).val(color.data('value'));
		});
	};

	var confirmRemoveSkin = function() {
		$('.confirm').fadeIn();
	}

	var removeSkin = function(href) {
		$.get(href, function() {
			var custom = $('.skin-picker li.custom');
			custom.removeClass('custom');
			$('input#parent_id').val(custom.data('skin'));
			$('.confirm').fadeOut();
		});
	}

	//frame loaded
	$('#theme-frame').load(function() {
		$('.theme-preview .wrapp .load').fadeOut(500);
	});

	//handle theme selection
	$('.theme-picker > ul > li').click(function(e) {
		e.preventDefault();
		var self = $(this);
		var theme = self.data('theme');
		var skin_picker = self.children('.skin-picker');

		//select theme
		$('.theme-picker > ul > li.selected').removeClass('selected');
		self.addClass('selected');
		$('#theme').val(theme);

		//change skin value to default skin
		var skin = skin_picker.find('li.selected').data('skin');
		$('#skin').val(skin);

		if ($(e.target).is('.customize-link a')) {
			if ($('.skin-picker li.custom').length) {
				if (skin_picker.find('li.selected.custom').length) {
					window.location.href = $(e.target).data('link') + skin_picker.find('li.selected').data('custom');
				} else {
					confirmRemoveSkin();
				}
			} else {
				window.location.href = $(e.target).data('link') + skin_picker.find('li.selected').data('skin');
			}
			return;
		}
		
		reloadPreview(theme, skin);
	});

	//handle skin selection
	$('.skin-picker li').click(function(e) {
		e.stopPropagation();
		var self = $(this);
		if ($('.skin-picker li.custom').length
			&& !self.is('.custom')) {
			return confirmRemoveSkin();
		}
		var parentTheme = self.parents('li:first');
		var skin = self.data('skin');
		var theme = parentTheme.data('theme');
		//select skin
		parentTheme.find('li.selected').removeClass('selected');
		self.addClass('selected');
		$('#skin').val(skin);

		//select theme
		parentTheme.parent().children('li.selected').removeClass('selected');
		parentTheme.addClass('selected');
		$('#theme').val(theme);

		//show color picker
		if (!self.is('.custom')) {
			$('.colors-wrap .color-picker').hide();
			$('.colors-wrap #color-picker-' + skin).show();
			updateColorFields(skin);
		}

		reloadPreview(theme, skin);
	});

	//handle live preview show
	$('div.live-preview .show-action').click(function(e) {
		e.preventDefault();
		var self = $(this);
		var preview = $('div.live-preview .live-wrapp');

		self.hide();
		preview.animate({width: 'toggle'},500);
		reloadPreview();
	});

	//handle live preview close
	$('div.live-preview .live-wrapp .close').click(function(e) {
		e.preventDefault();
		var self = $(this);
		var show = $('div.live-preview .show-action');
		var preview = $('div.live-preview .live-wrapp');
		preview.animate({width: 'toggle'},500);
		show.show();
	});

	//autoload theme
	if ($('#theme-frame').data('autoload')) {
		var theme_picker = $('.theme-picker > ul > li.selected');
		var theme = theme_picker.data('theme');
		var skin = theme_picker.children('.skin-picker').find('li.selected').data('skin');
		reloadPreview(theme, skin);
	}

	//remove overflow hidden from content
	$('.theme-preview').parents('#content').css('overflow', 'visible');

	//remove live preview on theme customization page
	if ($('.themes div.theme-preview, .dashboard div.theme-preview').length > 0) {
		$('div.live-preview').remove();
	}

	$('.color-picker .color').each(function() {
		var colorElement = $(this);
		colorElement.ColorPicker({
			color: colorElement.data('value'),
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb, element) {
				colorElement.css('backgroundColor', '#' + hex);
				colorElement.data('value', '#' + hex);
			},
			onSubmit: function(hsb, hex, rgb, el) {
				colorElement.css('backgroundColor', '#' + hex);
				colorElement.data('value', '#' + hex);
				$(el).ColorPickerHide();
			}
		});
	});
	
	$('#form-custom-theme').submit(function() {
		var skinElement = $('.skin-picker li.selected');
		updateColorFields(skinElement.data('skin'), skinElement.is('.custom'));
	});

	$('#confirm-remove-skin .ui-button.ajax-request').click(function(e) {
		e.preventDefault();
		e.stopPropagation();
		removeSkin(this.href);
	});
});
