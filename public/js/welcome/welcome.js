$(window).on('load' , function(){
	$("body, html").animate({
		scrollTop: 0
	} /* speed */ );
})

$("a[href^='#']").click(function(e) {
	e.preventDefault();

	var position = $($(this).attr("href")).offset().top;

	$("body, html").animate({
		scrollTop: position
	} /* speed */ );
});
