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
    openWith: '		 '
  },
  markupSet: [{
    name: 'Bold',
    key: 'B',
    className: 'bold',
    openWith: '[b]',
    closeWith: '[/b]'
  }, {
    name: 'Italic',
    key: 'I',
    className: 'italic',
    openWith: '[i]',
    closeWith: '[/i]'
  }, {
    name: 'Link',
    key: 'L',
    className: 'link',
    openWith: '[url=[![Link:!:http://]!]]',
    closeWith: '[/url]',
    placeHolder: 'Your text to link...'
  }]
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
  easeInCubic: function(x, t, b, c, d) {
    return c * (t /= d) * t * t + b;
  },
  easeOutCubic: function(x, t, b, c, d) {
    return c * ((t = t / d - 1) * t * t + 1) + b;
  },
  easeInOutCubic: function(x, t, b, c, d) {
    if ((t /= d / 2) < 1) {
      return c / 2 * t * t * t + b;
    }
    return c / 2 * ((t -= 2) * t * t + 2) + b;
  }
});

(function($) {
  var content = $('#content'),
    slider = $('#slide-container'),
    slideSize = parseInt(content.css('width').replace('px', ''), 10);

  // function to handle actual slide states
  // responsable for setting wrapper setting
  // and remove old sections when not needed anymore
  var resetSlide = function(remove) {
    var sections = $('.slide-elem'),
      numSections = sections.size(),
      margin = -1 * parseInt(slider.css('marginLeft').replace('px', ''), 10),
      numVisible = (margin / slideSize) + 1;
    // remove old sections as needed
    if (remove && numVisible < numSections) {
      while (numVisible < numSections) {
        $('.slide-elem').last().remove();
        sections = $('.slide-elem');
        numSections = sections.size();
      }
    }
    // only set the size after sections were removed
    slider.css('width', slideSize * numSections + 'px');

    $('.populate-fields input:checked').trigger('click');
  };

  // Function to add slide 'listItens' for
  // don't break history slide, so do it when
  // in slide category go slide items/add
  var addSliderItens = function(urlRequest) {
    if (urlRequest.indexOf("/items/add/") != -1) {
      if (!($('.slide-elem[rel*="index"]').is("*"))) {
        var urlRequestItens = urlRequest.replace("add", "index");
        $.ajax({
          url: urlRequestItens,
          type: 'GET',
          success: function(dataIndex) {
            $(slider).width($(slider).width() + slideSize);
            $(slider).css('marginLeft', (parseInt(slider.css('marginLeft'), 10) - slideSize) + 'px');
            $('.slide-elem:last').before('<div class="slide-elem" rel="' + urlRequestItens + '">' + dataIndex + '</div>');
          }
        });
      }
    }
  }

  // Functions that handle the animation
  // Any link with the push-scene class will load in a new slide scene
  // Any link with the pop-scene class will have it's href ignored and goes back one step on the navigation
  function pushScene() {
    slider.undelegate('.push-scene', 'click', pushScene);
    var urlRequest = $(this).attr('href');
    $.get(urlRequest, function(data) {
      slider.append('<div class="slide-elem" rel="' + urlRequest + '">' + data + '</div>');
      resetSlide();
      slider.animate({
        marginLeft: (parseInt(slider.css('marginLeft'), 10) - slideSize) + 'px'
      }, {
        duration: 800,
        easing: 'easeInOutCubic',
        complete: function() {
          if ($('.markitup').length) {
            $('.markitup').markItUp(mySettings);
          }
          configureMultiselect();
          addSliderItens(urlRequest);
          slider.delegate('.push-scene', 'click', pushScene);
        }
      });
    });
  }
  slider.delegate('.push-scene', 'click', function(e) {
    e.preventDefault();
  });
  slider.delegate('.push-scene', 'click', pushScene);

  function popScene() {
    slider.undelegate('.pop-scene', 'click', popScene);
    slider.animate({
      marginLeft: (parseInt(slider.css('marginLeft'), 10) + slideSize) + 'px'
    }, {
      duration: 800,
      easing: 'easeInOutCubic',
      complete: function() {
        slider.delegate('.pop-scene', 'click', popScene);
        resetSlide(true);
      }
    });
  }
  slider.delegate('.pop-scene', 'click', function(e) {
    e.preventDefault();
  });
  slider.delegate('.pop-scene', 'click', popScene);


  // ajax error/success helper
  var globalCallback = function(data, status) {
    if (data && typeof data.refresh != 'undefined') {
      $.ajax({
        url: data.refresh,
        type: 'GET',
        success: function(dataHTML) {
          var target = $('.slide-elem[rel="' + data.refresh + '"]');
          target.html(dataHTML);
        }
      });
    }
    if (data && typeof data.go_back != 'undefined' && data.go_back) {
      $($('.slide-elem:last .ui-button.back')[0]).click();
    }
    var message = false;
    if (data && typeof data.success != 'undefined') {
      message = $('<a id="success-feedback" href="#">' + data.success + '</a>').hide();
    }
    if (data && data.error) {
      message = $('<a id="error-feedback" href="#">' + data.error + '</a>').hide();
    } else if (status != 200) {
      message = $('<a id="error-feedback" href="#">Erro ao deletar, tente novamente</a>').hide();
    }
    if (message) {
      message.prependTo('#content').slideDown('fast');
      message.delay(5000).slideUp('fast').delay(1000, function() {
        $(this).remove();
      });
    }
  };

  var dataWithCode = function(func) {
    return function(data, status, xhr) {
      // if it's an error event, the xhr is the first param
      if (data.constructor == XMLHttpRequest) {
        xhr = data;
        data = '';
      }
      // jQuery status is not what we want, replace it
      status = parseInt(xhr.status, 10);
      try {
        console.log('returned status ' + status);
      } catch (e) {}
      globalCallback(data, status, xhr);
      //func(data,status,xhr);
    };
  };

  // Forms inside the slider wrapper will be serialized and posted.
  // All forms will trigger the pop-scene on success, and in case of error
  // will rewrite the current scene with the HTML returned from the app
  // *** juliogreff says: added :not(.skip-slide) to prevent the event's prevention
  // *** and submit the form normally. This will be kept until we're able to send
  // *** images in an asynchronous manner
  slider.delegate('form:not(.skip-slide)', 'submit', function(e) {
    e.preventDefault();
    $(this).find('button[type=submit]').attr('disabled', 'disabled');
    var url = this.action;
    var handler = dataWithCode(function(data, status) {
      if (typeof data == 'string' && data.indexOf('error') != -1) {
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

  slider.delegate('.edit-in-place', 'click', function(e) {
    var t = $(e.target);
    if (!t.is('span')) {
      return;
    }
    inPlace = t;
    inPlaceValue = $.trim(inPlace.text());
    var input = $('<input type="text"/>').val(inPlaceValue);
    inPlace.html(input);
    input.get(0).select();
  });

  var resetEdit = function() {
    if (inPlace) {
      inPlace.html(inPlaceValue);
    }
    inPlace = false;
  };

  var configureMultiselect = function () {
    $('select[multiple][data-allow-add]').select2({tags:true});//enable add dinamyc options
    $('select[multiple]:not([data-allow-add])').select2();
  };

  content.delegate('.edit-in-place input', 'blur', resetEdit);

  content.delegate('.edit-in-place input', 'keypress', function(e) {
    if (e.keyCode == 13) {
      // ENTER key submits
      var handler = dataWithCode(function(data, status) {
        if (status == 200) {
          inPlaceValue = data.title;
        }
        resetEdit.call(this);
      });
      var url = inPlace.attr('data-saveurl');
      $.ajax({
        url: url,
        data: {
          title: this.value
        },
        type: 'POST',
        success: handler,
        error: handler
      });
    }
  });

  // Handles the delete confirmation dialog buttons.
  // When clicked cancel, closes the dialog. When clicked OK, makes the
  // request and triggers ajax:success event
  content.delegate('.confirm .ui-button', 'click', function(e) {
    var self = $(this);
    if (self.hasClass('ajax-request')) {
      if (!slider.length) {
        return;
      }
      var handler = dataWithCode(function(data, status) {});
      $.ajax({
        url: this.href,
        type: 'GET',
        success: handler,
        error: handler
      });
    }
    self.parent().parent().fadeOut('fast');
    e.preventDefault();
  });

  // Handles the item's deletion in items/edit
  content.delegate('.ui-button.has-confirm', 'click', function(e) {
    var confirmSelector = '';
    if (confirmSelector = $(this).data('confirm')) {
      e.preventDefault();
      $(confirmSelector).fadeIn('fast');
    }
  });

  content.delegate('.confirm .ui-button.ajax-request.go-back', 'ajax:success', function(e, data) {
    $('.confirm').fadeOut('fast');
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

  $('.js-duplicate-previous').click(function(e) {
    e.preventDefault();
    $(this).prev().clone().insertBefore(this);
  });

  // expand any target element
  content.delegate('.js-expand-target', 'click', function(e) {
    var targetSelector = $(this).data('target');
    var target = $(targetSelector).slideToggle();
    e.preventDefault();
  });

  // switch button
  content.delegate('.ui-switch a', 'click', function(e) {
    var switchButton = $(this).parents('.ui-switch:first');
    var isEnabled = switchButton.hasClass('enabled');
    if (isEnabled) {
      switchButton.removeClass('enabled');
    } else {
      switchButton.addClass('enabled');
    }

    if (switchButton.data('target')) {
      $(switchButton.data('target')).val(Number(!isEnabled));
    }

    e.preventDefault();
  });

  //change availabe extension by selected type
  content.delegate('select.item-types', 'change', function(e) {
    var type = $(this).val();

    $('.js-extension-list:visible').slideUp('fast', function() {
      $('.js-extension-list.' + type).slideDown('fast');
    });
    e.preventDefault();
  });

  //handle extension save on new category
  content.delegate('.js-extension-list a', 'click', function(e) {
    var obj = $(this);
    var formData = obj.parents('form:first').serialize();
    var newHref = obj.attr('href') + '?' + formData;
    obj.attr('href', newHref);
    //alert(formData);
    //e.preventDefault();
  });

  $.Paginate = function(params) {
    this.settings = {
      controls_wrapp: "#pagination",
      paginate_items_wrapp: ".paginate-items"
    };

    this.settings = $.extend(this.settings, params);

    this.refreshData = function(data) {
      var result = $(data).filter(this.settings.paginate_items_wrapp);
      var controls = $(data).filter(this.settings.controls_wrapp);

      $(this.settings.paginate_items_wrapp).slideUp('slow', function() {
        $(this).html(result.html()).slideDown('slow');
      });

      $(this.settings.controls_wrapp).hide()
        .html(controls.html())
        .show();
    }

    this.init = function() {
      var paginate = this;
      slider.delegate(this.settings.controls_wrapp + ' a', 'click', function() {
        $.ajax({
          url: $(this).attr('href'),
          type: 'GET',
          success: function(dataHTML) {
            paginate.refreshData(dataHTML);
          }
        });
        return false;
      });
    }

    this.init();
  }
  $.Paginate();

  //text dinamic fontsize plugin
  $.fn.textfill = function(options) {
    return this.each(function() {
      var self = $(this);
      var fontSize = self.data('max-font-size') ? self.data('max-font-size') : 30;
      var ourText = self.children(':first');
      var maxHeight = parseInt(self.css('max-height'));
      var maxWidth = parseInt(self.css('max-width'));
      var textHeight;
      var textWidth;
      do {
        ourText.css('font-size', fontSize);
        textHeight = ourText.height();
        textWidth = ourText.width();
        fontSize = fontSize - 1;
      } while ((textHeight > maxHeight || textWidth > maxWidth) && fontSize > 3);
    });
  };
  $('p.dynamic-text').textfill();

  //handle cunstom domain
  var change_custom_domain = function(e) {
    var self = $(this);
    if (self.val()) {
      $('.form-edit .current-custom-domain').html(self.val());
    }
  };

  content.delegate('.form-edit .domains .ui-text', 'focusin', change_custom_domain);
  content.delegate('.form-edit .domains .ui-text', 'keyup', change_custom_domain);

  //restrict to alphanumeric
  try {
    $('.domains .ui-text').alphanumeric({
      allow: ".-_ "
    });
    $('.domains .js-duplicate-previous').click(function() {
      $('.domains .ui-text').alphanumeric({
        allow: ".-_ "
      });
    });
  } catch (e) {}

  configureMultiselect();
})(jQuery);

$(function() {
  // create slug for domain name from site title
  var updateSlug = function() {
    if (!$('#FormSlug').attr('disabled')) {
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
    var objThis = this;
    $($('fieldset[style*="display: block"],fieldset[style=""]')).each(function(i, element) {
      if ($(objThis).html() != $(element).prev().html()) {
        $(element).slideToggle();
        $(element).prev().slideToggle();
      }
    });
    $(this).slideToggle();
    $(this).next('fieldset').slideToggle();
    e.preventDefault();
  });

  $('#form-edit-site-info > fieldset').click(function(e) {
    if ($(this).prev().is('a.fieldset-expand')) {
      if ($(e.target).hasClass('duplicate-previous') || $(e.target).parents('.picture-upload-container:first').length > 0 || e.originalEvent && $(e.originalEvent.target).hasClass('picture-upload-container')) {
        console.log('upload click');
      } else {
        $(this).prev().click();
      }
    }
  });

  $('#form-edit-site-info > fieldset .field-group').click(function(e) {
    if ($(this).children('.picture-upload-container').length < 1) {
      e.stopPropagation();
    }
  });

  // flash messages
  $('#success-feedback, #error-feedback').click(function(e) {
    e.preventDefault();
    $(this).slideUp('fast');
  });
  $('#feedback').click(function(e) {
    $(this).slideUp('fast');
  });
  $('#success-feedback, #error-feedback').delay(5000).slideUp('fast').delay(1000, function() {
    $(this).remove();
  });

  if ($('.markitup').length) {
    $('.markitup').markItUp(mySettings);
  }
  
  $('body').click(function(e) {
    $("#navbar .open").removeClass("open");
  })

  $("#navbar")
    .delegate(".business-name", 'click', function(e) {
      e.stopPropagation();
      $(this).closest(".sites").toggleClass("open");
      $("#navbar .user.open").removeClass("open")
    })
    .delegate(".user p", 'click', function(e) {
      e.stopPropagation();
      $(this).closest(".user").toggleClass("open");
      $("#navbar .sites.open").removeClass("open")
    });

  //Implement bussiness description counter
  $('#businessDescription').keyup(function() {
    var remainVal = $(this).attr('maxlength') - $(this).val().length;
    $('#businessCounter').html(remainVal);
    if (remainVal < 10) {
      $('#businessCounter').parent().addClass('red');
    } else {
      $('#businessCounter').parent().removeClass('red');
    }
  });

  $('#form-edit-site-info #businessDescription').keyup();

});

$(window).load(function() {
  if (!Modernizr.input.placeholder) {
    $('[placeholder]').focus(function() {
      var input = $(this);
      if (input.val() == input.attr('placeholder')) {
        input.val('');
        input.removeClass('placeholder');
      }
    }).blur(function() {
      var input = $(this);
      if (input.val() == '' || input.val() == input.attr('placeholder')) {
        input.addClass('placeholder');
        input.val(input.attr('placeholder'));
      }
    }).blur();
    $('[placeholder]').parents('form').submit(function() {
      $(this).find('[placeholder]').each(function() {
        var input = $(this);
        if (input.val() == input.attr('placeholder')) {
          input.val('');
        }
      })
    });
  }

  $('a.popup-link').click(function() {
    $('.popup-wrapper').fadeIn('fast');
  });

  $('.popup-wrapper a.close').click(function() {
    $('.popup-wrapper').fadeOut('fast');
  });
  var removeField = function(e) {
    var self = $(this);
    var href = self.attr('href') != '#' ? self.attr('href') : false;
    if (href) {
      return true;
    } else {
      self.parent().remove();
    }
    e.preventDefault();
  };

  $('#domains #add-new').click(function(e) {
    e.preventDefault();
    var field = $(this).prev().clone();
    field.find('input').removeAttr('disabled').val('');
    field.find('a').click(removeField);
    field.show().insertBefore(this);
  });
  $('#domains .domain a').click(removeField);

  dataTableLang = {
    "sEmptyTable": "Nenhum registro encontrado",
    "sInfo": "Mostrando de _START_ até _END_ de _TOTAL_ registros",
    "sInfoEmpty": "Mostrando 0 até 0 de 0 registros",
    "sInfoFiltered": "(Filtrados de _MAX_ registros)",
    "sInfoPostFix": "",
    "sInfoThousands": ".",
    "sLengthMenu": "_MENU_ resultados por página",
    "sLoadingRecords": "Carregando...",
    "sProcessing": "Processando...",
    "sZeroRecords": "Nenhum registro encontrado",
    "sSearch": "Pesquisar",
    "oPaginate": {
      "sNext": "Próximo",
      "sPrevious": "Anterior",
      "sFirst": "Primeiro",
      "sLast": "Último"
    },
    "oAria": {
      "sSortAscending": ": Ordenar colunas de forma ascendente",
      "sSortDescending": ": Ordenar colunas de forma descendente"
    }
  };

  if ($('#visitors-list').length) {
    //enable datatable list
    var visitorTable = $('#visitors-list').DataTable({
      language: dataTableLang
    });
  }

  if (window.visitorGraphData) {
    Object.keys(window.visitorGraphData).forEach(function(key) {
      var options = { element: key, data: window.visitorGraphData[key] };
      //TODO remove this conditional, this isn't the best place to be
      if (key == 'subscribed-graph')
        options.formatter = function (x) { return x + '%'};
      Morris.Donut(options);
    });
  }
});
