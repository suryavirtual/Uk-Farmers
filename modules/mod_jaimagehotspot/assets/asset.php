<?php
/**
 * ------------------------------------------------------------------------
 * JA Image Hotspot Module for Joomla 2.5 & 3.4
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die('Restricted access');

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$lang = jFactory::getLanguage();
$basepath = JURI::root(true).'/modules/' . $module->module . '/assets/';
//$description =$params->get('description');
jimport('joomla.filesystem.file');


//Load library jquery
require_once(dirname(__FILE__).'/behavior.php');
JHTML::_('JABehavior.jquery');

if(!defined('T3')){
    //Load popover and dropdown library
    $doc->addScript($basepath.'js/bootstrap-tooltip.js?v=1');
    $doc->addScript($basepath.'js/bootstrap-popover.js?v=1');
}
if (!defined('_MODE_JAIMAGESHOSTSPOT_')) {
    define('_MODE_JAIMAGESHOSTSPOT_', 1);
    $doc->addScript($basepath.'js/modernizr.custom.63321.js');

    if($lang->isRTL() == 1){
        $doc->addScript($basepath.'js/jquery.dropdown.rtl.js');
    }else{
        $doc->addScript($basepath.'js/jquery.dropdown.js');
    }
    $doc->addStyleSheet($basepath.'css/style.css?v=1');
    if(version_compare(JVERSION, '3.0', 'lt')) {
        $doc->addStyleSheet($basepath.'css/style_nonbs.css');
    }
    if($lang->isRTL() == 1){
        $doc->addStyleSheet($basepath.'css/style.rtl.css');
    }
    //load override css
    $templatepath = 'templates/'.$app->getTemplate().'/css/'.$module->module.'.css';
    if(file_exists(JPATH_SITE . '/' . $templatepath)) {
        $doc->addStyleSheet(JURI::root(true).'/'.$templatepath);
    }
}

$displaytooltips = $params->get('displaytooltips',1);

$data = json_encode($description);
//escape special characters
$data = preg_replace("/(\\\\(n|r|t)|')/", '\\\\$1', $data);

if($displaytooltips == 1){
	$script = " ;(function($){
		$(document).ready(function(){
		var desc = $.parseJSON('".$data."');
        function getTransitionDuration (el, with_delay){
                var style=window.getComputedStyle(el),
                    duration = style.webkitTransitionDuration,
                    delay = style.webkitTransitionDelay;

				if(!duration) return 1;
                // fix miliseconds vs seconds
                duration = (duration.indexOf('ms')>-1) ? parseFloat(duration) : parseFloat(duration)*1000;
                delay = (delay.indexOf('ms')>-1) ? parseFloat(delay) : parseFloat(delay)*1000;
                if(with_delay) return (duration + delay);
                else return duration;
            }
			for(var i=0; i<desc.length; i++){
				if(desc[i]['imgid'] == $('#ja-imagesmap".$module->id." a.point'+i).attr('id').replace('ja-marker-','')){
					$('#ja-imagesmap".$module->id." a.point'+i).popover({
						template: '<div class=\"popover\"><div class=\"arrow\"></div><h3 class=\"popover-title\"></h3><div class=\"popover-content\"></div><div class=\"x-close\" title=\"Close\" onclick=\"javascript: return jQuery(this).parents(\'div.popover\').hide();\"><span>Close</span></div></div>'
					})
				}
			}
			
	
            var timeout = 0;

            $('#ja-imagesmap".$module->id." a.point').each(function(){
                timeout += getTransitionDuration(document.getElementById($(this).attr('id')));
            });
			if(timeout == 0 || timeout == $('#ja-imagesmap".$module->id." a.point').length) timeout = $('#ja-imagesmap".$module->id." a.point').length + 1;
            setTimeout(function() {
                $('#ja-imagesmap".$module->id." a.point').each(function(){
                    $(this).popover('show').unbind('click').unbind('hover');
                });
            }, timeout);

            clearTimeout(timeout);

            $('#ja-imagesmap".$module->id." #cd-dropdown').jadropdown( {
                gutter : 0,
                stack : false
            });

			$('#ja-imagesmap".$module->id." .cd-dropdown ul li').click(function(){
				var target = $(this).attr('data-value');
				$('#ja-imagesmap".$module->id." #'+target).popover('show');
			});
		});
	 })(jQuery);";
}else{
    $hidedelay = (int) $params->get('hidedelay', 2000);
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $script = " ;(function($){
		$(document).ready(function(){
			var hoverTimeout;
			$('#ja-imagesmap".$module->id." #cd-dropdown').jadropdown( {
                gutter : 0,
                stack : false
            });";
    if (preg_match('/android/i', $user_agent)) {
        $script .= "$('#ja-imagesmap".$module->id." a.point').each(function(){
			$(this).click(function(){
				current_click = $(this).prop('id');
				$('#ja-imagesmap".$module->id." a.point').each(function(){ 
					if($(this).prop('id') != current_click){
						$(this).popover('destroy');
						$(this).show();
					}					
				});
				$(this).popover('show'); 
			});
		});";

    }else{
        $script .= "$('#ja-imagesmap".$module->id." a.point').each(function(){
			$(this).hover(function(){
				var desc = $.parseJSON('".$data."');
				for(var i=0; i<desc.length; i++){
				if(desc[i]['imgid'] == $('#ja-imagesmap".$module->id." a.point'+i).attr('id').replace('ja-marker-','')){
					$('#ja-imagesmap".$module->id." a.point'+i).popover({
						template: '<div class=\"popover\"><div class=\"arrow\"></div><h3 class=\"popover-title\"></h3><div class=\"popover-content\"></div></div>'
					})
				}
			}
				clearTimeout(hoverTimeout);
				current_hover = $(this).prop('id');
				$('#ja-imagesmap".$module->id." a.point').each(function(){ 
					if($(this).prop('id') != current_hover){
						$(this).popover('destroy');
						$(this).show(); 
					}
				});
				$(this).popover('show');
			},function(){
				(function(t){
					hoverTimeout = setTimeout(function() {
						$(t).popover('destroy');
						$(t).show();
					}, ".$hidedelay.");
				})(this);
			});
		});";
    }


    $script .="$('#ja-imagesmap".$module->id." .cd-dropdown ul li').click(function(){
				var target = $(this).attr('data-value');
				$('#ja-imagesmap".$module->id." a.point').each(function(){
					if($(this).prop('id') != target){
						$(this).popover('destroy');
						$(this).show();
					}
				});
				
				var desc = $.parseJSON('".$data."');
				for(var i=0; i<desc.length; i++){
				if(desc[i]['imgid'] == $('#ja-imagesmap".$module->id." a.point'+i).attr('id').replace('ja-marker-','')){
					$('#ja-imagesmap".$module->id." a.point'+i).popover({
						template: '<div class=\"popover\"><div class=\"arrow\"></div><h3 class=\"popover-title\"></h3><div class=\"popover-content\"></div></div>'
					})
				}
			}
				
				$('#ja-imagesmap".$module->id." #'+target).popover('show');
			});
		});
	 })(jQuery);";
}


$doc->addScriptDeclaration($script);
//$doc->addScript($basepath.'js/script.js');