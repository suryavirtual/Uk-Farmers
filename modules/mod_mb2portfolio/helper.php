<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/


defined('_JEXEC') or die('Restricted access');

// Get component helper class
if (!class_exists('Mb2portfolioHelper'))
{
	require_once JPATH_SITE . '/components/com_mb2portfolio/helpers/mb2portfolio.php';
}

if (!class_exists('Mb2portfolioHelperRoute'))
{
	require_once JPATH_SITE . '/components/com_mb2portfolio/helpers/route.php';
}

// Get component model
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_mb2portfolio/models', 'Mb2portfolioModel');




/**
 * Helper for mod_articles_latest
 *
 * @package     Joomla.Site
 * @subpackage  mod_articles_latest
 * @since       1.6.0
 */
abstract class ModMb2portfolioHelper
{
	/**
	 * Retrieve a list of article
	 *
	 * @param   JRegistry  &$params  module parameters
	 *
	 * @return  mixed
	 */
	public static function getList(&$params)
	{
		
		
		
		// Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Projects', 'Mb2portfolioModel', array('ignore_request' => true));
		
		
		// Set application parameters in model
		$app = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);
		
		
		// Set module params
		$mod = JModuleHelper::getModule('mod_mb2portfolio');
		$mParams = new JRegistry();
		$mParams->loadString($mod->params);
		
		
		// Published filter
		$model->setState('list.start', 0);
		$model->setState('list.limit', $mParams->get('project_limit', 3));
		$model->setState('filter.published', 1);
		
		
		// Skill (category) filter		
		$model->setState('filter.skillid', $mParams->get('project_skill', ''));		
				
		
		// Access filter
		//$access = !JComponentHelper::getParams('com_mb2portfolio')->get('show_noauth');
		//$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		//$model->setState('filter.access', $access);
		
				
		// Filter by language
		//$model->setState('filter.language', $app->getLanguageFilter());
		
		
		// Order
		$model->setState('list.ordering', $mParams->get('order_by', 'created'));
		$model->setState('list.direction', $mParams->get('order', 'DESC'));		
		
		
		$items = $model->getItems();

		foreach ($items as &$item)
		{	
			
			
			// Generate item link
			$item->slug = $item->id . ':' . $item->alias;		
			
			$skillsarr = array($item->skill_1, $item->skill_2, $item->skill_3, $item->skill_4, $item->skill_5);								
			$link = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($item->slug, $skillsarr, $language = 0));
			
			$item->link = $link;
			
		}

		return $items;
		
		
		
	}
	
	
	
	
	
}