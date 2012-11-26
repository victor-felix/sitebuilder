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
	reloadPreview();
});