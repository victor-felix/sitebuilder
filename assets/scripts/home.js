$(function() {
  $('p.login a:first-of-type').click(function(e){
      $('#login-window').show();
      $('#FormEmail').focus();
      e.preventDefault();
  });

  $('#login-window a.cancel').click(function(e){
      $('#login-window').hide();
      e.preventDefault();
  });

  $(document).keyup(function(e) {
    if(e.keyCode == 27) {
      $('#login-window').hide();
    }
  });
  
	// Using default configuration
	$(".hero-unit .slider").carouFredSel({
		items		: 1,
		scroll		: {
			fx			: "crossfade"
		},
		pagination  : ".hero-unit .pagination"
	});
	
	$(".quotes .slider").carouFredSel({
		items		: 1,
		scroll		: {
			fx			: "crossfade"
		},
		pagination  : ".quotes .pagination"
	});
});
