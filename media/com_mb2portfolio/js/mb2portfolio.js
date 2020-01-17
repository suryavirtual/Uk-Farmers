/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/


	
(function($){$(window).load(function(){	
	
		
	
	
	/*------------------------------------------------*/
	/*	Carousel (related projects and portfolio module)
	/*------------------------------------------------*/
	$('.mb2-portfolio-carousel').each(function(){		
		
		var $carouselobj = $(this);
		var maxItems = $carouselobj.data('itemmax');
		var scrollItems = $carouselobj.data('scroll');
		var pauseTime = $carouselobj.data('duration');
		var touch = $carouselobj.data('touch');
		var is_play = $carouselobj.data('play');
		var id = $carouselobj.data('id');
		
		
		if(touch == 1){
			var is_touch = true
			var is_mouse = true
			//var is_play = false
		}
		else{
			var is_touch = false
			var is_mouse = false
			//var is_play = true
		}
		
		
		$(this).carouFredSel({
			responsive:true,
			auto:{play:is_play,timeoutDuration:pauseTime},
			scroll:scrollItems,
			prev:'#mb2-portfolio-prev-' + id,
			next:'#mb2-portfolio-next-' + id,
			pagination: '#mb2-portfolio-pager-'+id,
			items:{width:400,height:'auto',visible:{min:1,max:maxItems}},
			swipe: {
	        	onTouch: is_touch,
	        	onMouse: is_mouse
	    	}		
		});
		
		
		
			
		
	});
	
	
	
	
	//image hover effect
	$('div.mb2-portfolio-img.h-effect').hover(function(){		
		$(this).children('.mb2-portfolio-mark').css('opacity', 0);
		$(this).children('.mb2-portfolio-mark').stop().fadeTo(250, 1);
	},
	function(){
		$(this).children('.mb2-portfolio-mark').stop().fadeTo(250, 0);		
	});
	
	
	
	
	
	
	
	
	
	
	
	
	/*------------------------------------------------*/
	/*	Portfolio Isotope filter
	/*------------------------------------------------*/
	$('.mb2-portfolio-container.filter-isotope .mb2-portfolio-projects').each(function(){

		var $isotope_container = $(this);
		
		
		var is_mode = $isotope_container.data('mode');
		
		//isotope layout
		$isotope_container.isotope({
			itemSelector:'.mb2-portfolio-item',
			layoutMode: is_mode, 
			animationEngine:'jquery', 
			animationOptions:{
				duration:250
			}
			
		});		
		
		
		//isotope filter		
		$('.mb2-portfolio-container.filter-isotope .mb2-portfolio-filter-nav-list a').click(function(){
			
			var isotope_selector = $(this).attr('data-filter');			
			
			$isotope_container.isotope({ 
				filter: isotope_selector 
			});
			
			return false;
		});
		
		
	
	
	});
	
	
	
	
	
	
	
	
	
	
	
	
	
	//bootstrap carousel init	
	$('.mb2portfolio-bs-carousel').carousel();	
	
	
	
	
	
	
	
	
	//pretty photo init 
	//$('.mb2-portfolio-pp-link[data-rel^=\'prettyPhoto\']').prettyPhoto({
	//	deeplinking:false,
		//social_tools:false
	//});
	
	
	







})})(jQuery);//end







jQuery(document).ready(function($){
	
	
	
	
		
	//tooltip init
	$('.mb2-portfolio-tooltip').tooltip();
	
	
	
	
	
	
	$('.mb2-portfolio-link').each(function(){
	
		var $nivoLightbox = $(this);
	
		
		/*
		fade
		fadeScale
		slideLeft
		slideRight
		slideUp
		slideDown
		fall
		*/
		
		
		//nivo lightbox init
		$nivoLightbox.nivoLightbox({
			//effect: 'fall'	
		});
	
	
	});
	
	
	
	
	
	/*------------------------------------------------*/
	/*	Portfolio fade filter
	/*------------------------------------------------*/					
	$('.mb2-portfolio-container.filter-fade .mb2-portfolio-filter-nav-list a').click(function(){		
		
		var portfolio_fade_cont = '.mb2-portfolio-container.filter-fade';		
		
		$(this).css('outline','none');			
		$(portfolio_fade_cont + ' .mb2-portfolio-filter-nav-list .current').removeClass('current');			
		$(this).parent().addClass('current');var filterVal = $(this).attr('data-filter');
		
		if(filterVal == '*'){$(portfolio_fade_cont + ' .mb2-portfolio-item').each(function(){
			$(this).animate({opacity: 1}, 300);});} else {
				$(portfolio_fade_cont + ' .mb2-portfolio-item').each(function(){
					if(!$(this).hasClass(filterVal)){
						$(this).animate({opacity: 0.2}, 300);} 
					else {
						$(this).animate({opacity: 1}, 300);
					}
				});
			}
			
			
			
			return false;
	});
	
	
	
	
	
	
	
	
	
	
	
	
});