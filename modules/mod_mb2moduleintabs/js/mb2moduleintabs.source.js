/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/



/**
*	@name							Accordion
*	@descripton						This Jquery plugin makes creating accordions pain free
*	@version						1.4
*	@requires						Jquery 1.2.6+
*
*	@author							Jan Jarfalk
*	@author-email					jan.jarfalk@unwrongest.com
*	@author-website					http://www.unwrongest.com
*
*	@licens							MIT License - http://www.opensource.org/licenses/mit-license.php
*/

(function($){
     jQuery.fn.extend({
         accordion: function() {       
            return this.each(function() {
            	
            	var $ul						= $(this),
					elementDataKey			= 'accordiated',
					activeClassName			= 'active',
					activationEffect 		= 'slideToggle',
					panelSelector			= 'div',
					activationEffectSpeed 	= 100,
					itemSelector			= 'div.mb2moduleintabs-panel-group';
            	
				if($ul.data(elementDataKey))
					return false;
													
				$.each($ul.find('div.mb2moduleintabs-panel-group>div'), function(){
					$(this).data(elementDataKey, true);
					$(this).hide();
				});
				
				$.each($ul.find('.mb2moduleintabs-panel-heading'), function(){
					$(this).click(function(e){
						activate(this, activationEffect);
						return void(0);
					});
					
					$(this).bind('activate-node', function(){
						$ul.find( panelSelector ).not($(this).parents()).not($(this).siblings()).slideUp( activationEffectSpeed );
						activate(this,'slideDown');
					});
				});
				
				var active = $ul.find('div.iscurrent .mb2moduleintabs-panel-heading')[0];

				if(active){
					activate(active, false);
				}
				
				function activate(el,effect){
					
					$(el).parent( itemSelector ).siblings().removeClass(activeClassName).children( panelSelector ).slideUp( activationEffectSpeed );
					
					$(el).siblings( panelSelector )[(effect || activationEffect)](((effect == "show")?activationEffectSpeed:activationEffectSpeed),function(){
						
						if($(el).siblings( panelSelector ).is(':visible')){
							$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
						} else {
							$(el).parent( itemSelector ).removeClass(activeClassName);
						}
						
						if(effect == 'show'){
							$(el).parents( itemSelector ).not($ul.parents()).addClass(activeClassName);
						}
					
						$(el).parents().show();
					
					});
					
				}
				
            });
        }
    }); 
})(jQuery);




// Tabs plugin

(function($){
     jQuery.fn.extend({
         mb2tabs: function() {       
            return this.each(function() {

				// For each set of tabs, we want to keep track of
				// which tab is active and it's associated content
				var $active, $content, $links = $(this).find('a');
			
				// If the location.hash matches one of the links, use that as the active tab.
				// If no match is found, use the first link as the initial active tab.
				$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
				$active.addClass('active');
			
				$content = $($active[0].hash);
			
				// Hide the remaining content
				$links.not($active).each(function () {
					$(this.hash).hide();
				});
			
				// Bind the click event handler
				$(this).on('click', 'a', function(e){
					// Make the old tab inactive.
					$active.removeClass('active');
					$content.hide();
			
					// Update the variables with the new link and content
					$active = $(this);
					$content = $(this.hash);
			
					// Make the tab active.
					$active.addClass('active');
					$content.fadeIn(100);
			
					// Prevent the anchor's default click action
					e.preventDefault();
				});	

			});
        }
    }); 
})(jQuery);






jQuery(document).ready(function($){
	
	// Panels layout 
	$('.mb2moduleintabs-accordion').each(function(){			
		$(this).accordion();		
	});	
	
	
	
	
	
	// Tabs layout 
	$('.mb2moduleintabs-tabs .mb2moduleintabs-tabs-list').each(function(){			
		
		$(this).mb2tabs();
			
	});		
	
});