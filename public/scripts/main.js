var Utils = {
    slug: function(string) {
        var patterns = [
            /À|à|Á|á|å|Ã|â|Ã|ã/g, /È|è|É|é|Ê|ê|ẽ|Ë|ë/g, /Ì|ì|Í|í|Î|î/g,
            /Ò|ò|Ó|ó|Ô|ô|ø|Õ|õ/g, /Ù|ù|Ú|ú|ů|Û|û|Ü|ü/g, /ç|Ç/g, /ñ|Ñ/g,
            /ä|æ|Ä|ä/g, /Ö|ö/g, /ß/g, /[^\w\s]/g, /\s/g, /^-+|-+$/g, /-{2,}/g
        ];
        var replaces = ['a', 'e', 'i', 'o', 'u', 'c', 'n', 'ae', 'oe', 'ss', ' ', '', '', ''];
        
        $.each(patterns, function(i, pattern) {
            string = string.replace(pattern, replaces[i]);
        });
        
        return string.toLowerCase();
    }
};

$(function() {
    // create slug for domain name from site title
    var updateSlug = function() {
        if(!$('#FormDomain').attr('disabled')) {
            var slug = Utils.slug($(this).val());
            $('#FormDomain').val(slug);
        }
    };
    
    $('#form-register-site-info #FormTitle').bind({
        keyup: updateSlug,
        blur: updateSlug
    }).blur();

	$('.fieldset-expand').click(function(e) {
		$(this).slideToggle();
		$(this).next('fieldset').slideToggle();
		e.preventDefault();
	});
	
	// theme picker
	$('.theme-picker a').click(function(e) {
		var self = $(this),
		    href = self.attr('href'),
		    theme = href.substr(href.indexOf('#') + 1),
		    skin_picker = $('.skin-picker ul');

		e.preventDefault();
		$('.theme-picker li.selected').removeClass('selected');
		self.parent().addClass('selected');
		$('#FormTheme').val(theme);
	});
	if($('#FormTheme').val()) {
	    $('.theme-picker a[href*=' + $('#FormTheme').val() + ']').parent().addClass('selected');
	}
	

	$('.skin-picker a').live('click', function(e) {
		var self = $(this),
		    href = self.attr('href'),
		    skin = href.substr(href.indexOf('#') + 1);

    	e.preventDefault();
		$('.skin-picker li.selected').removeClass('selected');
		self.parent().addClass('selected');
		$('#FormSkin').val(skin);
    });
	if($('#FormSkin').val()) {
	    $('.skin-picker a[href*=' + $('#FormSkin').val() + ']').parent().addClass('selected');
	}
	
	/* TO DO */
	$('.categories-list .controls .delete').click(function(e){
		e.preventDefault();
		$(this).parent().next(".delete-confirm").fadeIn("fast");
	})
	
	$('#form-edit-businessitem .delete').click(function(e){
		e.preventDefault();
		$(".delete-confirm").fadeIn("fast");
	})
	
	/* TO DO */
	$('.delete-confirm .ui-button.delete').click(function(e){
		$(this).parent().parent().parent().slideUp();
		// e.preventDefault();
	})
	
	$('.delete-confirm .ui-button:nth-of-type(2)').click(function(e){
		$(this).parent().parent().hide();
		e.preventDefault();
	})
});