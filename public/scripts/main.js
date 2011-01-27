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
        if ((t/=d/2) < 1) return c/2*t*t*t + b;
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
    };
    
    // Functions that handle the animation
    // Any link with the push-scene class will load in a new slide scene
    // Any link with the pop-scene class will have it's href ignored and goes back one step on the navigation
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
    
    // Forms inside the slider wrapper will be serialized and posted.
    // All forms will trigger the pop-scene on success, and in case of error
    // will rewrite the current scene with the HTML returned from the app
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
    
    // Handles the delete confirmation dialog buttons.
    // When clicked cancel, closes the dialog. When clicked OK, makes the
    // request and triggers ajax:success event
    slider.delegate('.delete-confirm .ui-button', 'click', function(e) {
        e.preventDefault();
        var self = $(this);
        if(self.hasClass('delete')) {
            $.get(this.href, function(data) {
                self.trigger('ajax:success', [data]);
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
    

    // Handles the categories's deletion in categories/index
    slider.delegate('.categories-list .controls .delete', 'click', function(e) {
        e.preventDefault();
        $(this).parent().parent().find('.delete-confirm').fadeIn('fast');
    });
    
    slider.delegate('.categories-list .delete-confirm .ui-button', 'ajax:success', function(e) {
        var li = $(this).closest('li');
        li.nextUntil('.' + li.attr('class')).andSelf().slideUp();
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

    // expand fieldsets in sites/edit
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
    
    // skin picker
    $('.skin-picker a').live('click', function(e) {
        e.preventDefault();
        var self = $(this),
            href = self.attr('href'),
            skin = href.substr(href.indexOf('#') + 1);

        $('.skin-picker li.selected').removeClass('selected');
        self.parent().addClass('selected');
        $('#FormSkin').val(skin);
    });
    if($('#FormSkin').val()) {
        $('.skin-picker a[href*=' + $('#FormSkin').val() + ']').parent().addClass('selected');
    }
    
    // flash messages
    $('#success-feedback, #error-feedback').click(function(e) {
        e.preventDefault();
        $(this).slideUp('fast');
    });
    $('#success-feedback, #error-feedback').delay(5000).slideUp('fast');
});