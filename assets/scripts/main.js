var mySettings = {
    nameSpace: 'xbbcode',
    onShiftEnter: {
        keepDefault: false,
        replaceWith: '[br /]\n'
    },
    onCtrlEnter: {
        keepDefault: false,
        openWith: '\n[p]',
        closeWith: '[/p]\n'
    },
    onTab: {
        keepDefault: false,
        openWith: '    '},
    markupSet: [
        {name: 'Bold', key: 'B', openWith:'[b]', closeWith: '[/b]'},
        {name: 'Italic', key: 'I', openWith: '[i]', closeWith: '[/i]'},
        {name: 'Link', key: 'L', openWith: '[url=[![Link:!:http://]!]]', closeWith: '[/url]', placeHolder: 'Your text to link...'},
        {name: 'Big', openWith: '[big]', closeWith: '[/big]'},
        {name: 'Small', openWith: '[small]', closeWith: '[/small]'}
    ]
};

// Utilitary functions
var Utils = {
    // Transforms non-ascii characters in its non-accented equivalents
    // Inspired on Inflector::slug from Spaghetti* Framework, MIT
    // https://github.com/spaghettiphp/spaghettiphp/blob/dc3ba37cbcd20a3cb4796b2c08e3611ba9f4da34/lib/core/common/Inflector.php#L13
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

// Better animation by using better easing functions
// Copied from jQuery UI, MIT/GPL
// https://github.com/jquery/jquery-ui/blob/7a6dd71f8cf04d19c938f0678c0f2a2586ed65c5/ui/jquery.effects.core.js#L598
$.extend($.easing, {
    easeInCubic: function (x, t, b, c, d) {
        return c*(t/=d)*t*t + b;
    },
    easeOutCubic: function (x, t, b, c, d) {
        return c*((t=t/d-1)*t*t + 1) + b;
    },
    easeInOutCubic: function (x, t, b, c, d) {
        if ((t/=d/2) < 1) {return c/2*t*t*t + b;}
        return c/2*((t-=2)*t*t + 2) + b;
    }
});

(function($){
    var content = $('#content'),
        slider = $('#slide-container'),
        slideSize = parseInt(content.css('width').replace('px',''),10);

    // function to handle actual slide states
    // responsable for setting wrapper setting
    // and remove old sections when not needed anymore
    var resetSlide = function(remove) {
        var sections = $('.slide-elem'),
            numSections = sections.size(),
            margin = -1*parseInt(slider.css('marginLeft').replace('px',''),10),
            numVisible = (margin/slideSize) +1;
        // remove old sections as needed
        if(remove && numVisible < numSections) {
            while (numVisible < numSections) {
                $('.slide-elem').last().remove();
                sections = $('.slide-elem');
                numSections = sections.size();
            }
        }
        // only set the size after sections were removed
        slider.css('width',slideSize*numSections+'px');

        $('.populate-fields input:checked').trigger('click');
    };
    
	// Function to add slide 'listItens' for
	// don't break history slide, so do it when
	// in slide category go slide business_items/add
	var addSliderItens = function(urlRequest){
		if(urlRequest.indexOf("/business_items/add/")!= -1){
			if(!($('.slide-elem[rel*="index"]').is("*"))){
				var urlRequestItens = urlRequest.replace("add", "index");
				$.ajax({
					url: urlRequestItens,
					type: 'GET',
					success: function(dataIndex){
						$(slider).width($(slider).width()+slideSize);
						$(slider).css('marginLeft', (parseInt(slider.css('marginLeft'),10)-slideSize)+'px');
						$('.slide-elem:last').before('<div class="slide-elem" rel="'+urlRequestItens+'">'+dataIndex+'</div>');						
					}
				});	
			}	
		}	    
	}

    // Functions that handle the animation
    // Any link with the push-scene class will load in a new slide scene
    // Any link with the pop-scene class will have it's href ignored and goes back one step on the navigation
    slider.delegate('.push-scene', 'click', function(e){
        e.preventDefault();
        var urlRequest = $(this).attr('href');
        $.get(urlRequest, function(data){
		slider.append('<div class="slide-elem" rel="'+urlRequest+'">'+data+'</div>');
		resetSlide();		
		slider.animate(
			{marginLeft:(parseInt(slider.css('marginLeft'),10)-slideSize)+'px'},
			{duration:800,easing:'easeInOutCubic',complete:function() {
			    if($('.markitup').length) {
			        $('.markitup').markItUp(mySettings);
			    }
			    if($('.chosen').length) {
			        $('.chosen').chosen();
			    }
			    addSliderItens(urlRequest);			
			}}
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
    // ajax error/success helper
    var globalCallback = function(data,status) {
        if(data && typeof data.refresh != 'undefined'){
            $.ajax({
                url: data.refresh,
                type: 'GET',
                success: function(dataHTML){
                    var target = $('.slide-elem[rel='+data.refresh+']');
                  target.html(dataHTML);
                }
            });
        }
        if(data && typeof data.go_back != 'undefined' && data.go_back){
            $('.slide-elem:last .ui-button.back')[0].click();
        }
        var message=false;
        if(data && typeof data.success != 'undefined') {
            message = $('<a id="success-feedback" href="#">'+data.success+'</a>').hide();
        }
        if(data && data.error) {
            message = $('<a id="error-feedback" href="#">'+data.error+'</a>').hide();
        } else if(status != 200) {
            message = $('<a id="error-feedback" href="#">Erro ao deletar, tente novamente</a>').hide();
        }
        if(message) {
            message.prependTo('#content').slideDown('fast');
            message.delay(5000).slideUp('fast').delay(1000,function(){$(this).remove();});
        }
    };

    var dataWithCode = function(func) {
        return function(data,status,xhr) {
            // if it's an error event, the xhr is the first param
            if(data.constructor == XMLHttpRequest) {
                xhr = data;
                data = '';
            }
            // jQuery status is not what we want, replace it
            status = parseInt(xhr.status,10);
            try{console.log('returned status ' + status);}catch(e){}
            globalCallback(data,status,xhr);
            func(data,status,xhr);
        };
    };
    
    // Forms inside the slider wrapper will be serialized and posted.
    // All forms will trigger the pop-scene on success, and in case of error
    // will rewrite the current scene with the HTML returned from the app
    // *** juliogreff says: added :not(.skip-slide) to prevent the event's prevention
    // *** and submit the form normally. This will be kept until we're able to send
    // *** images in an asynchronous manner
    slider.delegate('form:not(.skip-slide)', 'submit', function(e){
        e.preventDefault();
        $(this).find('button[type=submit]').attr('disabled', 'disabled');
        var url = this.action;
        var handler = dataWithCode(function(data,status) {
            if(typeof data == 'string' && data.indexOf('error')!=-1) {
                $('.slide-elem:last').html(data);
            }
        });
        $.ajax({
           url: url,
           data: $(this).serialize(),
           type: 'POST',
           success: handler,
           error: handler
        });
    });

    // Edit in place
    var inPlace,
        inPlaceValue = '';

    slider.delegate('.edit-in-place','click',function(e){
        var t = $(e.target);
        if(!t.is('span')) {
            return;
        }
        inPlace = t;
        inPlaceValue = $.trim(inPlace.text());
        var input = $('<input type="text"/>').val(inPlaceValue);
        inPlace.html(input);
        input.get(0).select();
    });

    var resetEdit = function() {
        if(inPlace) {
            inPlace.html(inPlaceValue);
        }
        inPlace = false;
    };

    content.delegate('.edit-in-place input','blur',resetEdit);

    content.delegate('.edit-in-place input','keypress',function(e){
        if (e.keyCode == 13) {
            // ENTER key submits
            var handler = dataWithCode(function(data,status) {
                if(status == 200) {
                    inPlaceValue = data.title;
                }
                resetEdit.call(this);
            });
            var url = inPlace.attr('data-saveurl');
            $.ajax({
               url: url,
               data: {title:this.value},
               type: 'POST',
               success: handler,
               error: handler
            });
        }
    });

    content.delegate('.populate-fields input','click', function(e){
        var me = $(this).val();
        $('.populate-based:not(.'+me+'):visible').slideUp('slow');
        $('.populate-based.'+me).hide().removeClass('hidden').slideDown('slow');
    });

    // Handles the delete confirmation dialog buttons.
    // When clicked cancel, closes the dialog. When clicked OK, makes the
    // request and triggers ajax:success event
    slider.delegate('.delete-confirm .ui-button', 'click', function(e) {
        e.preventDefault();
        var self = $(this);
        if(self.hasClass('delete')) {
            var handler = dataWithCode(function(data,status) {
            });
            $.ajax({
               url: this.href,
               type: 'GET',
               success: handler,
               error: handler
            });
        }
        else {
            self.parent().parent().fadeOut('fast');
        }
    });

    // Handles the item's deletion in items/edit
    slider.delegate('#form-edit-businessitem .delete', 'click', function(e) {
        e.preventDefault();
        $('#form-edit-businessitem + .delete-confirm').fadeIn('fast');
    });

    slider.delegate('#form-edit-businessitem + .delete-confirm .ui-button', 'ajax:success', function(e, data) {
        $('.slide-elem:last').prev().html(data);
        $('.slide-elem:last .ui-button.back').click();
    });

    var site_edit = $('#form-edit-site-info, #form-register-site-info');
    site_edit.delegate('#FormCountryId', 'change', function() {
        var data = {
            country_id: $(this).val()
        };

        $.get('/states', data, function(response) {
            var states = $("#FormStateId");
            states.children().not(':first-child').remove();
            $.each(response, function(key, value) {
                $('<option>').html(value).attr('value', key).appendTo(states);
            });
        });
    });

    $('.duplicate-previous').live('click', function(e) {
        e.preventDefault();
        $(this).prev().clone().insertBefore(this);
    });
})(jQuery);

$(function() {
    // create slug for domain name from site title
    var updateSlug = function() {
        if(!$('#FormSlug').attr('disabled')) {
            var slug = Utils.slug($(this).val());
            $('#FormSlug').val(slug);
        }
    };

    $('#form-register-site-info #FormTitle').bind({
        keyup: updateSlug,
        blur: updateSlug
    }).blur();

    // expand fieldsets in sites/edit
    $('.fieldset-expand').click(function(e) {
        $(this).slideToggle();
        $(this).next('fieldset').slideToggle();
        e.preventDefault();
    });
    
    $('#form-edit-site-info > fieldset').click(function(){
	$(this).prev().click();    
    });
    
    $('#form-edit-site-info > fieldset .field-group:not(.picture-upload-container)').click(function(e){
	e.stopPropagation();    
    });
	

    if($('.theme-picker').length) {
        $('.theme-picker a').click(function(e) {
            var self = $(this),
                href = self.attr('href'),
                theme = href.substr(href.indexOf('#') + 1),
                skin_picker = $('.skin-picker ul');

            e.preventDefault();

            $('.theme-picker li.selected').removeClass('selected');
            self.parent().addClass('selected');
            $('#FormTheme').val(theme);

            skin_picker.html('');
            $.get('/skins', {theme: theme}, function(response) {
                skin_picker.html(response);

                var current_skin = $('#FormSkin').val();
                var skin_selector = '.skin-picker a[href*=' + $('#FormSkin').val() + ']';

                if(current_skin && $(skin_selector).length) {
                    $(skin_selector).click();
                }
                else {
                    $('.skin-picker a:first').click();
                }
            })
        });

        $('.theme-picker a[href*=' + $('#FormTheme').val() + ']').click();
        $('.skin-picker').delegate('a', 'click', function(e) {
            var self = $(this),
                href = self.attr('href'),
                skin = href.substr(href.indexOf('#') + 1);

            $('.skin-picker li.selected').removeClass('selected');
            self.parent().addClass('selected');
            $('#FormSkin').val(skin);
        });
    }

    if($('#FormSkin').val()) {
        $('.skin-picker a[href*=' + $('#FormSkin').val() + ']').parent().addClass('selected');
    }

    // flash messages
    $('#success-feedback, #error-feedback').click(function(e) {
        e.preventDefault();
        $(this).slideUp('fast');
    });
    $('#success-feedback, #error-feedback').delay(5000).slideUp('fast').delay(1000,function(){$(this).remove();});

    if($('.markitup').length) {
        $('.markitup').markItUp(mySettings);
    }
    if($('.chosen').length) {
        $('.chosen').chosen();
    }
	$('body').click(function(e){
		$("#navbar .open").removeClass("open");
	})
	$("#navbar")
		.delegate(".business-name", 'click', function(e){
			e.stopPropagation();
			$(this).closest(".sites").toggleClass("open");
			$("#navbar .user.open").removeClass("open")
		})
		.delegate(".user p", 'click', function(e){
			e.stopPropagation();
			$(this).closest(".user").toggleClass("open");
			$("#navbar .sites.open").removeClass("open")
		})
});