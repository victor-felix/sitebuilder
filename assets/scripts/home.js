$(function() {
  $(document).keyup(function(e) {
    if(e.keyCode == 27) {
      $('#login-window').hide();
    }
  });
  
	// Using default configuration
	$(".hero-unit .slider").carouFredSel({
		items : 1,
		scroll : {
			fx : "fade",
			duration : 1500,
			pauseOnHover: true
		},
		pagination : ".hero-unit .pagination"
	});
	
	$(".quotes .slider").carouFredSel({
		items : 1,
		scroll : {
			fx : "crossfade"
		},
		pagination : ".quotes .pagination"
	});
});
