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

$.extend($.easing, {
    easeInCubic: function (x, t, b, c, d) {
        return c*(t/=d)*t*t + b;
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c*((t=t/d-1)*t*t + 1) + b;
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
        return c/2*((t-=2)*t*t + 2) + b;
    }
});

(function($){
    var content = $('#content'),
        slider = $('#slide-container'),
        slideSize = parseInt(content.css('width').replace('px',''),10);
    
    var resetSlide = function(remove) {
        var sections = $('.slide-elem'),
            numSections = sections.size(),
            margin = -1*parseInt(slider.css('marginLeft').replace('px',''),10),
            numVisible = (margin/slideSize) +1;
        
        if(remove && numVisible < numSections) {
            while (numVisible < numSections) {
                $('.slide-elem').last().remove();
                sections = $('.slide-elem');
                numSections = sections.size();
            }
        }
        slider.css('width',slideSize*numSections+'px');
    };
    
    slider.delegate('.push-scene', 'click', function(e){
        e.preventDefault();
        $.get(this.href, function(data){
            slider.append('<div class="slide-elem">'+data+'</div>')
            resetSlide();
            slider.animate(
                {marginLeft:(parseInt(slider.css('marginLeft'),10)-slideSize)+'px'},
                {duration:800,easing:'easeInOutCubic'}
            );
        });
    });
    
    slider.delegate('.pop-scene', 'click', function(e){
        e.preventDefault();
        slider.animate(
            {marginLeft:(parseInt(slider.css('marginLeft'),10)+slideSize)+'px'},
            {duration:800,easing:'easeInOutCubic',complete:function(){resetSlide(true);}}
        );
    });
    
    slider.delegate('form', 'submit', function(e){
        e.preventDefault();
        var url = this.action;
        var handler = function(data,stat,xhr) {
            var status,
                respData='';
            if(typeof data == 'string') {
                status = xhr.status;
                respData = data;
            } else {
                status = data.status;
            }
            console.log(url+' returned status ' + status);
            if(parseInt(status,10) == 200) {
                if(data.indexOf('error')!=-1) {
                    $('.slide-elem:last').html(data);
                } else {
                    $('.slide-elem:last .ui-button.back').click();
                }
            } 
        };
        $.ajax({
           url: url,
           data: $(this).serialize(),
           type: 'POST',
           success: handler,
           error: handler
        });
    });
    
})(jQuery);

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
		$(this).parent().parent().find(".delete-confirm").show();
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
	
	$('#success-feedback, #error-feedback').click(function(e){
		$(this).slideUp("fast");
		e.preventDefault();
	});
	
	setTimeout(function(){
		$('#success-feedback, #error-feedback').slideUp();
	}, 2000);
});