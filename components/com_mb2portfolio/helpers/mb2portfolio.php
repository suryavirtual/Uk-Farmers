<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;

abstract class Mb2portfolioHelper
{
	
	
	
	
	
	
	/**
	 * 
	 * Method to get portfolio styles
	 * 
	 */
	public static function before_head($item, $params, $attribs = array())
	{
				
		
		
		// Basic variable
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$doc = JFactory::getDocument();
		$app = JFactory::getApplication();
		$tmpl = $app->gettemplate();
		$gparams = JComponentHelper::getParams('com_mb2portfolio');
		$inline_style = '';		
		$output = '';
				
		
		
		// Add font-awesome icons
		if ($gparams->get('fa', 1) == 1){
			$doc->addStyleSheet(JURI::base(true) . '/media/com_mb2portfolio/css/font-awesome/css/font-awesome.min.css');
		}		
		
		
		
		
		// Check if in template css directory is mb2portfolio.css file
		if(is_file(JPATH_SITE . '/templates/' . $tmpl . '/css/mb2portfolio.css'))
		{
			$doc->addStyleSheet(JURI::base(true) . '/templates/' . $tmpl . '/css/mb2portfolio.css');
		}
		else
		{			
			$doc->addStyleSheet(JURI::base(true) . '/media/com_mb2portfolio/css/mb2portfolio.css');
		}
		
		
		
		
		
		// Get java script frameworks
		JHtml::_('jquery.framework');	
		JHtml::_('bootstrap.framework');
		
		
		
		
		
		// Load ligtbox (nivo lightbox) script and style
		// Check if template not load lightbox styles and scripts
		if (!isset($doc->_scripts[JURI::base(true) . '/templates/' . $app->getTemplate() . '/js/nivo-lightbox.min.js']) && $params->get('lightbox', 1) == 1)
		{
			$doc->addStyleSheet(JURI::base(true) . '/media/com_mb2portfolio/css/nivo-lightbox/nivo-lightbox.css');
			$doc->addStyleSheet(JURI::base(true) . '/media/com_mb2portfolio/css/nivo-lightbox/themes/default/default.css');
			$doc->addScript(JURI::base(true) . '/media/com_mb2portfolio/js/nivo-lightbox.min.js');
		}
		
		
		
			
		// Load isotope script
		if ($gparams->get('filter', 'none') == 'isotope')
		{			
						
			// If is module check also if module filter is enabled
			if ($attribs['mode'] == 'module')
			{
				if ($params->get('show_filter', 0) == 1)
				{					
					$doc->addScript(JURI::base(true) . '/media/com_mb2portfolio/js/jquery.isotope.min.js');						
				}
			}
			else
			{				
				$doc->addScript(JURI::base(true) . '/media/com_mb2portfolio/js/jquery.isotope.min.js');
			}			
		}
		
		
		
		
		// Carousel script will load in 2 cases:
		// 1. On module mode when carousel is true
		// 2. On project mode when carousel is true and related projects are enabled
		$carousel_script =  (
			(($attribs['mode'] == 'module' && $params->get('carousel_on', 0) == 1) || 
			($attribs['mode'] == 'project' && $gparams->get('related_projects', 1) == 1)) &&
			$attribs['carousel']			
		);
		
		
		
		
		if ($carousel_script)
		{			
			
			
			// Check if carousel script is not loaded by mb2 content module
			if (!isset($doc->_scripts[JURI::base(true) . '/modules/mod_mb2content/js/jquery.carouFredSel-packed.js']))
			{
				$doc->addScript(JURI::base(true) . '/media/com_mb2portfolio/js/jquery.carouFredSel-packed.js');
			}	
			
			// Load touch swipe plugin if is enabled
			if($gparams->get('carousel_swipe', 1) == 1)
			{
				
				// Check if touchSwipe script is not loaded by mb2 content module
				if (!isset($doc->_scripts[JURI::base(true) . '/modules/mod_mb2content/js/jquery.touchSwipe.min.js']))
				{
					$doc->addScript(JURI::base(true) . '/media/com_mb2portfolio/js/jquery.touchSwipe.min.js');
				}
				
			}
					
		}
		
				
		
		
			
		
		
		
		// Check if in template js directory is mb2portfolio.js file
		if(is_file(JPATH_SITE . '/templates/' . $tmpl . '/js/mb2portfolio.js'))
		{
			$doc->addScript(JURI::base(true) . '/templates/' . $tmpl . '/js/mb2portfolio.js');
		}
		else
		{			
			$doc->addScript(JURI::base(true) . '/media/com_mb2portfolio/js/mb2portfolio.js');
		}
		
				
		
		
		// Get inline styles		
		$inline_style .= Mb2portfolioHelper::layout($item, $params, $attribs);
		$inline_style .= Mb2portfolioHelper::inline_style($params, $attribs);
		
		
		
		
		// Get inline styles
		if ($inline_style !='')
		{			
			$doc->addStyleDeclaration($inline_style);
		}		
		
		
				
		
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to get inline styles
	 * 
	 */
	public static function inline_style($params, $attribs = array())
	{
				
		
		$output = '';
		
		
		// Add prefix to css selectros if mode is module
		$attribs['mode'] == 'module' ? $pref = '.mb2-portfolio-module-' . $attribs['mod_id'] . ' ' : $pref = ''; 
		
		
		// Get custom color styles
		if($params->get('custom_color1', '') !='')
		{
		
			// Color
			$output .= $pref . '.mb2-portfolio-social-shares li a';
			$output .= '{color:' . $params->get('custom_color1', '') . ';}';		
	
	
			// Background color
			$output .= $pref . '.mb2-portfolio-social-shares li a:hover,';
			$output .= $pref . '.mb2-portfolio-social-shares li a:focus,';		
			$output .= $pref . '.mb2-portfolio-nav ul li a:hover,';
			$output .= $pref . '.mb2-portfolio-nav ul li a:focus,';			
			$output .= $pref . '.mb2-portfolio-carousel-nav .prev,';
			$output .= $pref . '.mb2-portfolio-carousel-nav .next,';
			$output .= $pref . '.mb2-portfolio-carousel-nav .pager a,';			
			$output .= $pref . '.mb2-portfolio-mark a,';			
			$output .= $pref . '.mb2-portfolio-img-caption';
			$output .= '{background-color:' . $params->get('custom_color1', '') . ';}';	
						
			
			// Border color
			$output .= $pref . '.mb2-portfolio-social-shares li a,';
			$output .= $pref . '.mb2-portfolio-nav ul li a:hover,';
			$output .= $pref . '.mb2-portfolio-nav ul li a:focus';
			$output .= '{border-color:' . $params->get('custom_color1', '') . ';}';
			
			
		}
		
		
		
		// Get custom css style
		if ($params->get('custom_css', '') !='')
		{			
			$output .= $params->get('custom_css', '');
		}
		
			
		
		
		
		return $output;			
		
		
				
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to get portfolio layout
	 * 
	 */
	public static function layout($item, $params, $attribs = array())
	{
				
		
		// Basi vriables
		$output = '';
		$app = JFactory::getApplication();
		$option = $app->input->getCmd('option', '');
		$view = $app->input->getCmd('view', '');
		
		// Get columns param
		$attribs['mode'] == 'project' ? $cols = $params->get('related_cols', 4) : $cols = $params->get('cols', 4);
		$attribs['mode'] == 'project' ? $item_media_width = $params->get('related_media_width', 50) : $item_media_width = $params->get('projects_media_width', 50);
		$attribs['mode'] == 'project' ? $item_layout = $params->get('related_item_layout', 'media-desc-below') : $item_layout = $params->get('projects_item_layout', 'media-desc-below');
		
		
		
		
		// Check if is module
		$attribs['mod_id'] !='' ? $portfolio_container = '.mb2-portfolio-module-' . $attribs['mod_id'] : $portfolio_container = '.mb2-portfolio-container';
		
		
		
		
		
		
		//----------------carousel styles
		$c_item_width = (100/$cols);
		$porfolio_container_c = $portfolio_container . '.is-carousel';
		$porfolio_related_container_c = $portfolio_container . ' .mb2-portfolio-related-items.is-carousel';
		
		// Calculate margin left and right for carousel items in pixels
		if($params->get('item_margin_lr', 1) == 0){
			$c_item_margin_lr = 0;	
		}
		elseif($params->get('item_margin_lr', 1) > 0 && $params->get('item_margin_lr', 1) < 1.1){
			$c_item_margin_lr = 10;		
		}
		elseif($params->get('item_margin_lr', 1) > 1.1 && $params->get('item_margin_lr', 1) < 1.9){
			$c_item_margin_lr = 15;
		}
		elseif($params->get('item_margin_lr', 1) > 1.9){
			$c_item_margin_lr = 23;
		}
		
		
		$output .= $porfolio_container_c .'{margin-left:-' . $c_item_margin_lr . 'px;}';
		
		$output .= $porfolio_container_c .' .mb2-portfolio-col';
		$output .= '{width:' . $c_item_width . '%;margin-left:0;margin-right:0;}';
		
		$output .= $porfolio_container_c .' .mb2-portfolio-item-inner';
		$output .= '{margin-left:' . $c_item_margin_lr . 'px;margin-right:' . $c_item_margin_lr . 'px;}';
		
		//$output .= $porfolio_container_c .' .mb2-portfolio-carousel-nav';
		//$output .= '{right:'. $c_item_margin_lr .'px;}';
		
		//$output .= $porfolio_container_c .' .mb2-portfolio-carousel-nav .pager';
		//$output .= '{right:'. ($c_item_margin_lr + 42) .'px;}';	
		//---------------------------
		
		
			
		
		
		// Portfolio container style
		$container_width = (100 + (2*$params->get('item_margin_lr', 1)));
		$container_margin_left = -$params->get('item_margin_lr', 1);	
		
		$output .= $portfolio_container .'{width:' . $container_width . '%;margin-left:' . $container_margin_left . '%;}';		
		
				
		
		
		// Portfolio item core style
		$item_width = ((100/$cols)-((2*$params->get('item_margin_lr', 1))+0.08));
		
		$output .= $portfolio_container .' .mb2-portfolio-col';
		$output .= '{width:' . $item_width . '%;margin-left:' . $params->get('item_margin_lr', 1) . '%;margin-right:' . $params->get('item_margin_lr', 1) . '%;margin-bottom:' . $params->get('item_margin_b', 30) . 'px;}';
			
		
		
		
		
		// Style for portfolio item elements (media and description)		
		$item_layout == 'desc-media' ? $item_parts_float = 'right' : $item_parts_float = 'left';
		
			
		if($item_layout == 'media-desc' || $item_layout == 'desc-media'){		
			$item_media_width = $item_media_width;		
			$item_desc_width = 100 - $item_media_width;			
		}
		else{		
			$item_media_width = 100;
			$item_desc_width = 100;		
		}
		
		
		
		$output .= $portfolio_container .' .mb2-portfolio-media';
		$output .= '{width:' . $item_media_width . '%;float:' . $item_parts_float . ';}';
		
		$output .= $portfolio_container .' .mb2-portfolio-details';
		$output .= '{width:' . $item_desc_width . '%;float:' . $item_parts_float . ';}';
		
		
		
		
		
		
		//style for other elements
		$output .= $portfolio_container .' .mb2-portfolio-filter-nav,';	
		$output .= $portfolio_container .' .pagination,';
		$output .= $portfolio_container .' .mb2-portfolio-nav';	
		$output .= '{margin-left:'. $params->get('item_margin_lr', 1) .'%!important;margin-right:'. $params->get('item_margin_lr', 1) .'%!important;}';
		
		
		
		
		//------------------------------------------------
		
		
		
		
		// Layout for single project page
		
		if($attribs['mode'] == 'project' && $option == 'com_mb2portfolio' && $view == 'project'){
				
		
			$item->layout == 'desc-media' ? $item_parts_float = 'right' : $item_parts_float = 'left';	
			
			//calculate width of media and details containers
			if($item->layout == 'media-desc' || $item->layout == 'desc-media'){					
				$cal_single_media_width = ($item->media_width-((2*$params->get('item_margin_lr', 1))+0.05));		
				$cal_single_desc_width = ((100 - $item->media_width)-((2*$params->get('item_margin_lr', 1))+0.05));			
			}
			else{			
				$cal_single_media_width = (100-(2*$params->get('item_margin_lr', 1)));
				$cal_single_desc_width = (100-(2*$params->get('item_margin_lr', 1)));			
			}
			
			
			//style for media and description
			$output .= $portfolio_container .' .mb2-portfolio-single-item-media,';	
			$output .= $portfolio_container .' .mb2-portfolio-single-item-deatils';
			$output .= '{margin-left:'. $params->get('item_margin_lr', 1) .'%;margin-right:'. $params->get('item_margin_lr', 1) .'%;}';		
			
			//style for media
			$output .= $portfolio_container .' .mb2-portfolio-single-item-media';	
			$output .= '{width:' . $cal_single_media_width . '%;float:' . $item_parts_float . ';}';
			
			//style for description
			$output .= $portfolio_container .' .mb2-portfolio-single-item-deatils';
			$output .= '{width:' . $cal_single_desc_width . '%;float:' . $item_parts_float . ';}';
			
			
			
			//style for other elements
			$output .= $portfolio_container .' .mb2-portfolio-related-items-heading';
			$output .= '{margin-left:'. $params->get('item_margin_lr', 1) .'%;margin-right:'. $params->get('item_margin_lr', 1) .'%;}';
			
			
			
			//related carousel layout-----------------
			//$output .= $porfolio_related_container_c .'{margin-left:-' . $c_item_margin_lr . 'px;}';
		
			$output .= $porfolio_related_container_c .' .mb2-portfolio-col';
			$output .= '{width:' . $c_item_width . '%;margin-left:0;margin-right:0;}';
			
			$output .= $porfolio_related_container_c .' .mb2-portfolio-item-inner';
			$output .= '{margin-left:' . $c_item_margin_lr . 'px;margin-right:' . $c_item_margin_lr . 'px;}';
			
			//$output .= $porfolio_related_container_c .' .mb2-portfolio-carousel-nav';
			//$output .= '{right:'. $c_item_margin_lr .'px;}';
			
			//$output .= $porfolio_related_container_c .' .mb2-portfolio-carousel-nav .pager';
			//$output .= '{right:'. ($c_item_margin_lr + 42) .'px;}';	
			//---------------------------
			
			
			
		
		}	
		
		
		
		
		
		
		return $output;			
		
		
				
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to check if items are display within carousel
	 * 
	 */
	public static function items_carousel($items, $params, $attribs = array())
	{
				
		
		// Basic variables
		$i=0;
		$attribs['mode'] == 'project-related' ? $caron = ($params->get('related_projects', 1) && $params->get('related_carousel', 0)) : $caron = $params->get('carousel_on', 0);
		$attribs['mode'] == 'project-related' ? $cols = $params->get('related_cols', 4) : $cols = $params->get('cols', 4);
		
		foreach ($items as $item)
		{			
			if ($item && $item->access_filter && $item->lang_filter)
			{
				
				// If mode is project-related check also which item is related
				if ($attribs['mode'] == 'project-related')
				{					
					if ($item->related)
					{
						$i++;						
					}				
				}
				else
				{					
					$i++;
				}
							
			}			
		}		
		
		
		$carousel = ($caron == 1 && $i>$cols);
		
		
		if($carousel)
		{
			return true;			
		}
		else		
		{			
			return false;	
		}
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to get carousel class
	 * 
	 */
	public static function carousel_cls($items, $params, $attribs)
	{
				
			
		$carousel = Mb2portfolioHelper::items_carousel($items, $params, $attribs);
				
		return $carousel ? $attribs['pos'] : $attribs['neg'];		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to get carousel data attribute
	 * 
	 */
	public static function carousel_data($items, $params, $attribs)
	{
				
		
		// Basic variables	
		$carousel = Mb2portfolioHelper::items_carousel($items, $params, $attribs);
		$gparams = JComponentHelper::getParams('com_mb2portfolio');
		$output = '';	
		
		
		// Chck columns param
		$attribs['mode'] == 'project-related' ? $cols = $gparams->get('related_cols', 4) : $cols = $params->get('cols', 4);
		
		
		
		if ($carousel)
		{			
			$data_itemmax = ' data-itemmax="' . $cols . '"';
			$data_duration = ' data-duration="' . $gparams->get('carousel_pause_time', 7000) . '"';
			$data_scroll = ' data-scroll="' . $gparams->get('carousel_scroll', 1) . '"';
			$data_touch = ' data-touch="' . $gparams->get('carousel_swipe', 1) . '"';
			$data_play = ' data-play="' . $gparams->get('carousel_auto', 1) . '"';
			$data_id = ' data-id="' . $attribs['uniqid'] . '"';
			$output .= $data_itemmax . $data_duration . $data_scroll . $data_touch . $data_play . $data_id;			
		}
		
		
		return $output;
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to get carousel navigation
	 * 
	 */
	public static function carousel_nav($items, $params, $attribs)
	{
				
		
		// Basic variables	
		$carousel = Mb2portfolioHelper::items_carousel($items, $params, $attribs);
		$gparams = JComponentHelper::getParams('com_mb2portfolio');
		$output = '';	
		
		
		
		if ($carousel)
		{
			
			
			// Carousel direct nav
			if ($gparams->get('carousel_direct_nav', 1) == 1)
			{
				$prev = 'prev: \'#mb2-portfolio-prev-' . $attribs['uniqid'] . '\',';
				$next = 'next: \'#mb2-portfolio-next-' . $attribs['uniqid'] . '\',';				
			}
			else
			{
				$prev = '';
				$next = '';
			}	
			
			
			
			// Carousel control nav
			$gparams->get('carousel_control_nav', 0) == 1 ? $pager = 'pagination: \'#mb2-portfolio-pager-' . $attribs['uniqid'] . '\',' : $pager = '';
			
			
			
			// Create carousel navigation
			if ($gparams->get('carousel_direct_nav', 1) == 1 || $gparams->get('carousel_control_nav', 0) == 1)
			{
				
				
				$output .= '<div class="mb2-portfolio-carousel-nav"><div class="mb2-portfolio-carousel-nav-inner">';
				
				
				
				if ($gparams->get('carousel_control_nav', 0) == 1)
				{
					
					$output .= '<div id="mb2-portfolio-pager-' . $attribs['uniqid'] . '" class="pager"></div><!-- end .mb2-portfolio-pager -->';
				}
				
				
				
				
				
				if ($gparams->get('carousel_direct_nav', 1) == 1)
				{
					$output .= '<a id="mb2-portfolio-prev-' . $attribs['uniqid'] . '" class="prev" href="#"><i class="mb2portfolio-fa mb2portfolio-fa-angle-double-left"></i></i></a>';
					$output .= '<a id="mb2-portfolio-next-' . $attribs['uniqid'] . '" class="next" href="#"><i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i></a>';					
				}
				
				
				
				
				
				$output .= '</div><!-- end .mb2-portfolio-carousel-nav --></div><!-- end .mb2-portfolio-carousel-nav-inner -->';
				
				
			}		
			
			
		}
		
		
		return $output;
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	


	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * 
	 * Method to reate class for portfolio item
	 * 
	 */
	public static function item_cls($item, $params, $attribs = array())
	{
		
		
		$output = '';
		
		
		// Get skill alias
		
		$output .= $item->skill1_alias;
		$item->skill2_alias ? $output .= ' ' . $item->skill2_alias : '';
		$item->skill3_alias ? $output .= ' ' . $item->skill3_alias : '';
		$item->skill4_alias ? $output .= ' ' . $item->skill4_alias : '';
		$item->skill5_alias ? $output .= ' ' . $item->skill5_alias : '';
		
		
		
		// Get items layout		
		
		if ($attribs['mode'] == 'project-related')
		{
			$output .= ' ' . $params->get('related_item_layout', 'media-desc-below');
		}
		else
		{			
			$output .= ' ' . $params->get('projects_item_layout', 'media-desc-below');
		}	
		
		
		
		return $output;
		
		
		
	}
	
	

}