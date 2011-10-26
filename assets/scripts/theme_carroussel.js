$(".theme-picker").delegate('.arrow', 'click', function(e){
	e.preventDefault();
	e.stopPropagation();
	var target = $(this),
		thumbs  = $(this).closest('li').find(".thumbs");
	if (target.is('.right')) {
		thumbs.find('img:eq(0)').appendTo(thumbs)
	}else {
		thumbs.find('img:last').prependTo(thumbs)
	}
})