$(function() {
	var reloadPreview = function(theme, skin) {
		var frame = $('#theme-frame');
		var url = frame.data('url');
		if (theme && skin) {
			url += "?theme=" + theme + "&skin=" + skin;
		}
		frame.attr('src', url);
	}
	
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
		reloadPreview(theme, skin);
	});
	
	//handle skin selection
	$('.skin-picker li').click(function(e) {
		e.preventDefault();
		var self = $(this);
		var theme_picker = self.parent('ul');
		var skin = self.data('skin');
		var theme = theme_picker.data('theme');
		
		//select skin
		theme_picker.children('li.selected').removeClass('selected');
		self.addClass('selected');
		$('#skin').val(skin);
		
		//select theme
		$('.theme-picker > ul > li.selected').removeClass('selected');
		theme_picker.addClass('selected');
		$('#theme').val(theme);
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
});