<?php
/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/



defined('_JEXEC') or die;


abstract class modMb2moduleintabsHelper
{
	
	
	
	
	
	
	/*
	 *
	 * Method to get module items list
	 *
	 */
	public static function get_items(&$params, $attribs)
	{
								
		$items = modMb2moduleintabsHelper::get_module_arrs($params, $attribs);		
		return $items;		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	/*
	 *
	 * Method to get module item to show
	 *
	 */
	public static function get_mod_item(&$params, $attribs)
	{
		
				
		// Check if user select dome article and show it
		if ($attribs['artid'])
		{
			return modMb2moduleintabsHelper::get_article($params, $attribs);
		}
		
		// If not check if user select some module and showit
		elseif ($attribs['modid'])
		{
			return modMb2moduleintabsHelper::get_module($params, $attribs);
		}
		
		
	}
	
	
	
	
	
	
	
	
	
	/*
	 *
	 * Method to get article
	 *
	 */
	public static function get_article(&$params, $attribs)
	{
		
		
		// Basic variable
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));	
		$output='';		
				
		
		// Get article object from content component table
		$article = JTable::getInstance('content');
		$article->load($attribs['artid']);
		$db = JFactory::getDbo();
		
		
		// Load content component route class
		require_once JPATH_SITE . '/components/com_content/helpers/route.php';
				
		
		// Article will show if:
		// 1. Artcile id exists
		// 2. Article state is set to 'published'
		// 3. User have premission to article
		if ($attribs['artid'] && $article->get('state') && in_array($article->get('access'), $authorised))
		{		
		
			$output .= '<' . $attribs['arth'] . ' class="mb2moduleintabs-article-title">' . $article->get('title') . '</' . $attribs['arth'] . '>';				
			$output .= '<div class="mb2moduleintabs-article-introtext">' . JHtml::_('content.prepare', $article->get('introtext')) . '</div>';
					
			
			// Get article category alias (it's required for article link route)
			$query = 'SELECT alias FROM #__categories WHERE id=' . $article->get('catid');		
			$db->setQuery($query);		
			$catalias = $db->loadObject()->alias;	
				
			
			// Create article link				
			$itemslug = $article->get('id') . ':' . $article->get('alias');
			$catslug = $article->get('catid') . ':' . $catalias;			
			$itemlink = JRoute::_(ContentHelperRoute::getArticleRoute($itemslug, $catslug));		
			
			
			// Check if user use an alternative readmore text
			$artattribs = json_decode($article->get('attribs'));		
			$rmoretext = $artattribs->alternative_readmore ? $artattribs->alternative_readmore : JText::_('MOD_MB2MODULEINTABS_READMORE');
			
			
			$output .= $article->get('fulltext') ? 
			'<p class="mb2moduleintabs-redmore"><a href="' . $itemlink . '" class="btn btn-default">' . $rmoretext . '</a></p>' : 
			'';
			
		}
		else
		{
			$output .= JText::_('MOD_MB2MODULEINTABS_ERROR');	
		}
		
		return $output;
				
		
	}
	
	
	
	
	
	
		
	
	
	
	
	
	/*
	 *
	 * Method to get tabs list params
	 *
	 */
	public static function get_module_arrs(&$params, $attribs)
	{
			
		$modulesarr = array(		
			array($params->get('mb2_modid1', ''), $params->get('mod_itemtitle1', ''), $params->get('mod_artid1', ''), 'itema'),
			array($params->get('mb2_modid2', ''), $params->get('mod_itemtitle2', ''), $params->get('mod_artid2', ''), 'itemb'),
			array($params->get('mb2_modid3', ''), $params->get('mod_itemtitle3', ''), $params->get('mod_artid3', ''), 'itemc'),
			array($params->get('mb2_modid4', ''), $params->get('mod_itemtitle4', ''), $params->get('mod_artid4', ''), 'itemd'),
			array($params->get('mb2_modid5', ''), $params->get('mod_itemtitle5', ''), $params->get('mod_artid5', ''), 'iteme'),
			array($params->get('mb2_modid6', ''), $params->get('mod_itemtitle6', ''), $params->get('mod_artid6', ''), 'itemf'),
			array($params->get('mb2_modid7', ''), $params->get('mod_itemtitle7', ''), $params->get('mod_artid7', ''), 'itemg'),
			array($params->get('mb2_modid8', ''), $params->get('mod_itemtitle8', ''), $params->get('mod_artid8', ''), 'itemh'),
			array($params->get('mb2_modid9', ''), $params->get('mod_itemtitle9', ''), $params->get('mod_artid9', ''), 'itemi'),
			array($params->get('mb2_modid10', ''), $params->get('mod_itemtitle10', ''), $params->get('mod_artid10', ''), 'itemj')			
		);	
		
		return $modulesarr;	
		
	}
	
	
	
	
	
	
	
	
	/*
	 *
	 * Method to get module object
	 *
	 */
	public static function get_module_list(&$params, $attribs)
	{
				
		// Basic variables
		$db = JFactory::getDBO();		
		
		$query = 'SELECT * FROM #__modules WHERE published=1 AND id!=' . $attribs['thismodid'];	
		$db->setQuery($query);		
		
		$rows = $db->loadObjectList('id');		
		
		return $rows;		
		
	}
	
	
	
	
	
	
	
	
	
	/*
	 *
	 * Method to render module
	 *
	 */
	public static function get_module(&$params, $attribs)
	{
		
		// Core variables
		jimport('joomla.application.module.helper');
		$output = '';
		$modid = $attribs['modid'];
		$mlist = modMb2moduleintabsHelper::get_module_list($modid, $attribs);		
		
		if (isset($mlist[$modid]) && $modid != $attribs['thismodid'])
		{	
			$style = $params->get('mb2_use_modchrome', 1) ? $params->get('mb2_modchrome', 'xhtml') : ''; 		
			$module = JModuleHelper::getModule($mlist[$modid]->module, $mlist[$modid]->title);			
			$output .= JModuleHelper::renderModule($module, array('style'=>$style));		
		}
		else
		{
			$output .= JText::_('MOD_MB2MODULEINTABS_ERROR');			
		}
			
			
			
		return $output;			
		
	} 
	
	
	
	
	
	
	
	
	
	
	/*
	 *
	 * Method to get module scripts and styles
	 *
	 */
	public static function before_head(&$params, $attribs)
	{
				
		// Joomla core variables
		$doc = JFactory::getDocument();
					
		
		// Get module style
		$doc->addStylesheet(JURI::base(true) . '/modules/mod_mb2moduleintabs/css/mb2moduleintabs.css');
				
		
		// Get module script
		// Get jquery framework
		if (version_compare(JVERSION, '3.0', '>'))
		{
			JHtml::_('jquery.framework', false);	
		}
		elseif ($params->get('mb2_jquery_on', 1))
		{
			$doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/' . $params->get('mb2_jquery_v', '1.11.1') . '/jquery.min.js');	
		}
		
		$doc->addScript(JURI::base(true) . '/modules/mod_mb2moduleintabs/js/mb2moduleintabs.js');
		
		
		
		// Get inline styles ------------ 
		$inlcss = '';
		
		
		
		// Minimum height if tab abenl (it's require for left and right tab position because of absolute position of tabs list)
		if ($params->get('mb2_tabs_min_height', ''))
		{
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs-content';			
			$inlcss .= '{';
			$inlcss .= 'min-height:' . $params->get('mb2_tabs_min_height', '') . 'px;';
			$inlcss .= '}';			
		}
		
		
		// Set width of tabs list (only for left and right sidebar layout)
		if ($params->get('mb2_tabs_list_width', ''))
		{
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs.tabs-left .mb2moduleintabs-tabs-list,';	
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs.tabs-right .mb2moduleintabs-tabs-list';			
			$inlcss .= '{';
			$inlcss .= 'width:' . $params->get('mb2_tabs_list_width', '') . 'px;';
			$inlcss .= '}';
			
			// Add the same margin left for tabs content
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs.tabs-left .mb2moduleintabs-tabs-content';				
			$inlcss .= '{';
			$inlcss .= 'margin-left:' . $params->get('mb2_tabs_list_width', '') . 'px;';
			$inlcss .= '}';
			
			// Add the same margin right for tabs content
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs.tabs-right .mb2moduleintabs-tabs-content';				
			$inlcss .= '{';
			$inlcss .= 'margin-right:' . $params->get('mb2_tabs_list_width', '') . 'px;';
			$inlcss .= '}';
				
		}
		
		/*
		
		
		
		.mb2moduleintabs-tabs.tabs-left .mb2moduleintabs-tabs-list
		*/
		
		
		
		// Normal color and bg color
		if ($params->get('mb2_normal_color', '') || $params->get('mb2_normal_bg_color', ''))
		{
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-panel-group .mb2moduleintabs-panel-heading,';
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs-list li a';			
			$inlcss .= '{';
			$inlcss .= $params->get('mb2_normal_bg_color', '') ? 'background-color:' . $params->get('mb2_normal_bg_color', '') . ';' : '';
			$inlcss .= $params->get('mb2_normal_color', '') ? 'color:' . $params->get('mb2_normal_color', '') . ';' : '';
			$inlcss .= '}';
			
			
			
						
		}
		
		
		// Active color and bg color
		if ($params->get('mb2_active_color', '') || $params->get('mb2_active_bg_color', ''))
		{
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-panel-group.active .mb2moduleintabs-panel-heading,';
			$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs-list li a.active';
			$inlcss .= '{';
			$inlcss .= $params->get('mb2_active_bg_color', '') ? 'background-color:' . $params->get('mb2_active_bg_color', '') . ';' : '';
			$inlcss .= $params->get('mb2_active_color', '') ? 'color:' . $params->get('mb2_active_color', '') . ';' : '';
			$inlcss .= '}';	
			
			
			// Tabs list border bottom color
			if ($params->get('mb2_active_bg_color', ''))
			{
				$inlcss .= '.mb2moduleintabs_' . $attribs['modid'] . ' .mb2moduleintabs-tabs .mb2moduleintabs-tabs-list';
				$inlcss .= '{';
				$inlcss .= 'border-color:' . $params->get('mb2_active_bg_color', '') . ';';
				$inlcss .= '}';
			}
					
		}
		
		
		// Custom css
		$inlcss .= $params->get('mb2_custom_css', '') ? $params->get('mb2_custom_css', '') : '';
		
		
		// Output inline style
		$inlcss ? $doc->addstyleDeclaration($inlcss) : '';
		
		
		
			
	}
	
	
	 
	
	
	
	
}