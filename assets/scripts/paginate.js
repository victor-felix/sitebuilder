(function($) {
	$.Paginate = function(params) {
		this.settings = {
			controls_wrapp: "#pagination",
			paginate_items_wrapp: ".paginate-items"
		};
		
		this.settings = $.extend(this.settings, params);
		
		this.refreshData = function(data) {
			var result = $(data).filter(this.settings.paginate_items_wrapp);
			var controls = $(data).filter(this.settings.controls_wrapp);
			
        	$(this.settings.paginate_items_wrapp).slideUp('slow', function(){
        		$(this).html(result.html()).slideDown('slow');
        	});
        	
        	$(this.settings.controls_wrapp).hide()
    		.html(controls.html())
    		.show();
        	this.init();
		}
		
		this.init = function() {
			var paginate = this;
			$(this.settings.controls_wrapp+ ' a').click(function(){
				$.ajax({
	                url: $(this).attr('href'),
	                type: 'GET',
	                success: function(dataHTML){
	                	paginate.refreshData(dataHTML);
	                }
	            });
				return false;
			});
		}
		
		this.init();
	}
})(jQuery);