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
    // load themes for selected segment
    $('#FormSegment').change(function() {
        var self = $(this);
        if(self.val()) {
            $.get('/segments/view/' + self.val(), {}, function(response) {
                var themes = response.themes;
                var theme_select = $('#FormTheme');
                $('option', theme_select).slice(1).remove();

                $.each(themes, function(key, value) {
                    var option = $('<option />').html(value).attr('value', key);
                    option.appendTo(theme_select);
                });
            });
        }
    }).change();
    
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
	
	/* TO DO */
	$('.theme-picker a').click(function(e) {
		var self = $(this),
		    href = self.attr('href'),
		    theme = href.substr(href.indexOf('#') + 1),
		    skin_picker = $('.skin-picker ul');

		e.preventDefault();
		$('.theme-picker li.selected').removeClass('selected');
		    
		self.parent().addClass('selected');
		$('#FormTheme').val(theme);
		$.get('/themes/skins/' + theme, {}, function(response) {
		    skin_picker.empty();
		    $.each(response, function(i) {
		        var img = $('<img />').attr('src', 'http://www-sop.inria.fr/ariana/Projets/P2R/commons/images/blank.gif');
		        var a = $('<a />').attr({
		            href: '#' + i,
		            title: this
		        }).append(img);

		        skin_picker.append($('<li />').append(a));
		    });
		    skin_picker.parent().slideDown();
		});
	});

	$('.skin-picker a').live('click', function(e) {
		e.preventDefault();
		var self = $(this),
		    href = self.attr('href'),
		    skin = href.substr(href.indexOf('#') + 1);
    		e.preventDefault();
    		$('.skin-picker li.selected').removeClass('selected');
    		self.parent().addClass('selected');
    		$('#FormSkin').val(theme);
    });
	
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
		e.preventDefault();
	})
	
	$('.delete-confirm .ui-button:nth-of-type(2)').click(function(e){
		$(this).parent().parent().hide();
		e.preventDefault();
	})
});