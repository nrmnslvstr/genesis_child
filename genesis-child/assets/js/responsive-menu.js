jQuery(function( $ ){
	//* --- Add Responsive Menu Button
	$("header .title-area").after('<div class="responsive-menu-container"><button class="responsive-menu-button"><i class="fa fa-bars"></i></button></div>');
	$(".responsive-menu-button").click(function(){
		//$("header .header-widget-area").toggleClass('active');
		$("header .header-widget-area").slideToggle(150,function(){
			
		});
	});
	
	//* --- AddtoAny Prepend "SHARE" Text
	$(".a2a_floating_style.a2a_default_style").prepend('<span style="float: left; color: #000; margin-left: 10px;">SHARE: </span>');

	//* --- Add jQuery.sticky into Sticky Widget
	$(".sticky-widget").sticky({ topSpacing: 125 });
     

	
	$(window).on("resize", function(event){
		
		
		if($(window).width() > 990) {
			$("header .header-widget-area").show();
		}
	});
	
	//* --- Append Dropdown Icon into items with Children
	var $selector = $('.sub-menu.active');
	if ( $selector.length > 0 ) {
		// Do something with $selector
		$(".menu-item-has-children > a span").append('<i class="fa fa-angle-double-up sub-menu-toggle"></i>');
	} else {
		$(".menu-item-has-children > a span").append('<i class="fa fa-angle-double-down sub-menu-toggle"></i>');
	}

	//* --- Add "slow" animation into Menu Toggling
	$(".menu-item-has-children").click(function(){
		//$(this).children(".sub-menu").toggleClass('active');
		$(this).toggleClass("sub-menu-active");
		$(this).children(".sub-menu").slideToggle(150,function(){
			
		});
	});
	
	//* --- Add some Style into Fixed header for Mobile Devices - For Admin Purpose
	if($(window).width() < 600) {
		$(window).scroll(function () {
			if ($(window).scrollTop() > 46){
			   $('body.admin-bar .site-header').css("top","0");
			} else {
				$('body.admin-bar .site-header').css("top","46px");
			}
		});
	}
	
});