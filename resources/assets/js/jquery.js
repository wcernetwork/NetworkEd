$(function() {

	/*http://www.backslash.gr/demos/jquery-sticky-navigation/*/
	// grab the initial top offset of the navigation 
	var sticky_navigation_offset_top = $('#top_bar').offset().top;
	
	// our function that decides weather the navigation bar should have "fixed" css position or not.
	var sticky_navigation = function(){
		var scroll_top = $(window).scrollTop(); // our current vertical position from the top
		
		// if we've scrolled more than the navigation, change its position to fixed to stick to top, otherwise change it back to relative
		if (scroll_top > sticky_navigation_offset_top - 1) { 
			$('#top_bar').addClass('fixed');
		} else if (!$('#top_bar').hasClass('fixed-scroll-override')) {
			$('#top_bar').removeClass('fixed');
		}   
	};
	
	// run our function on load
	sticky_navigation();

	// and run it again every time you scroll
	$(window).scroll(function() {
		sticky_navigation();
	});

	// $(function () {
 //        $('#datetimepicker').datetimepicker({
 //            language: 'ru',
 //            useCurrent: false
 //        });
 //    });
	
});