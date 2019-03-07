jQuery(function($){
	"use strict";

var IMI = window.IMI || {};
/* ==================================================
	Scroll Functions
================================================== */
	IMI.scrollToTop = function(){
			var windowWidth = $(window).width(),
			didScroll = false;
	
		var $arrow = $('#back-to-top');
	
		$arrow.on('click',function(e) {
			$('body,html').animate({ scrollTop: "0" }, 750, 'easeOutExpo' );
			e.preventDefault();
		});
	
		$(window).scroll(function() {
			didScroll = true;
		});
	
		setInterval(function() {
			if( didScroll ) {
				didScroll = false;
	
				if( $(window).scrollTop() > 200 ) {
					$arrow.css("right",10);
				} else {
					$arrow.css("right","-40px");
				}
				
			}
		}, 250);
	};

/* ==================================================
   Magnific Popup
================================================== */
	IMI.Magnific = function() {
		jQuery('.format-gallery').each(function(){
			$(this).magnificPopup({
				delegate: 'a.popup-image', // child items selector, by clicking on it popup will open
				type: 'image',
				gallery:{enabled:true}
				// other options
			});
		});
		jQuery('.magnific-image').magnificPopup({ 
			type: 'image',
			gallery:{enabled:false}
			// other options
		});
		jQuery('.magnific-video').magnificPopup({ 
			type: 'iframe',
			gallery:{enabled:false}
			// other options
		});
	};
/* ==================================================
   Animated Counters
================================================== */
	IMI.Counters = function() {
		$('.counters').each(function () {
			$(".timer .count").appear(function() {
			var counter = $(this).html();
			$(this).countTo({
				from: 0,
				to: counter,
				speed: 2000,
				refreshInterval: 60
				});
			});
		});
	};
/* ==================================================
   SuperFish menu
================================================== */
	IMI.SuperFish = function() {
		$('.sf-menu').superfish({
			  delay: 200,
			  animation: {opacity:'show', height:'show'},
			  speed: 'fast',
			  cssArrows: false,
			  disableHI: true
		});
		$(window).resize(function(){
			if($(window).width() >= 992){
				$('.sf-menu').show();
			}
		});
	};
/* ==================================================
   Header Functions
================================================== */
	IMI.StickyHeader = function() {
		if($('body').width() > 992 ){
			$(".site-header").sticky();
		}
	};

 });
 
 