<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_mb2portfolio
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;

/**
 * Content Component Route Helper
 *
 * @static
 * @package     Joomla.Site
 * @subpackage  com_mb2portfolio
 * @since       1.5
 */

 

 

jimport( 'joomla.html.parameter' );
jimport( 'joomla.application.module.helper' );


abstract class Mb2portfolioHelperRoute {
	
	
	
	
	protected static $lookup = array();

	protected static $lang_lookup = array();
	
	
	
	
	public static function getProjectRoute($id, $skills, $language = 0){		
					
		
		
		$needles = array(
			'project'  => array((int) $id),
			'projects'  => array(0,$skills[0]) // Return itemid for projects page by skill 1
											   // Add 0 befre array to get menuitem id of portfolio projects page (not by skill)
		);
		
		
		
		
		// Create the link
		$link = 'index.php?option=com_mb2portfolio&amp;view=project&amp;id=' . $id;		
		
		
		// Add skills to url
		$link .= '&amp;skill_1=' . $skills[0];			
		$link .= '&amp;skill_2=' . $skills[1];	
		$link .= '&amp;skill_3=' . $skills[2];
		$link .= '&amp;skill_4=' . $skills[3];
		$link .= '&amp;skill_5=' . $skills[4];
		
			
			
		
		if ($language && $language != "*" && JLanguageMultilang::isEnabled())
		{
			self::buildLanguageLookup();

			if (isset(self::$lang_lookup[$language]))
			{
				$link .= '&amp;lang=' . self::$lang_lookup[$language];
				$needles['language'] = $language;
			}
		}
		
		

		if ($item = self::_findItem($needles))
		{
			$link .= '&amp;Itemid=' . $item;
		}
		
		
		
		
		
						
		return $link;	
	}
	
	
	
	
	
	
	
	
	public static function getProjectsRoute($skillid, $language = 0){		
		
		
					
		$needles = array(
			'projects'  => array((int) $skillid, 0) // Add 0 to return to portfolio project page
		);
		
		//Create the link
		
		$link = 'index.php?option=com_mb2portfolio&amp;view=projects&amp;id=' . $skillid;		
					
		
		
		
		if ($item = self::_findItem($needles))
		{
			$link .= '&Itemid=' . $item;
		}
		
		
		
		return $link;	
		
		
		
		
	}
	
	
	
	
	
	
	
	protected static function _findItem($needles = null)
	{
		$app		= JFactory::getApplication();
		$menus		= $app->getMenu('site');
		$language	= isset($needles['language']) ? $needles['language'] : '*';

		// Prepare the reverse lookup array.
		if (!isset(self::$lookup[$language]))
		{
			self::$lookup[$language] = array();

			$component	= JComponentHelper::getComponent('com_mb2portfolio');

			$attributes = array('component_id');
			$values = array($component->id);

			if ($language != '*')
			{
				$attributes[] = 'language';
				$values[] = array($needles['language'], '*');
			}

			$items = $menus->getItems($attributes, $values);

			foreach ($items as $item)
			{
				if (isset($item->query) && isset($item->query['view']))
				{
					$view = $item->query['view'];
					if (!isset(self::$lookup[$language][$view]))
					{
						self::$lookup[$language][$view] = array();
					}
					
					
					if(!isset($item->query['id']))
					{
						self::$lookup[$language][$view] = $item->id;
					}
					
					if (isset($item->query['id'])) {

						// here it will become a bit tricky
						// language != * can override existing entries
						// language == * cannot override existing entries
						if (!isset(self::$lookup[$language][$view][$item->query['id']]) || $item->language != '*')
						{
							self::$lookup[$language][$view][$item->query['id']] = $item->id;
							
							
							
							
						}
					}
				}
			}
		}

		if ($needles)
		{
			
			
			foreach ($needles as $view => $ids)
			{
				
				if (isset(self::$lookup[$language][$view]))
				{
					foreach ($ids as $id)
					{
						if (isset(self::$lookup[$language][$view][(int) $id]))
						{
							return self::$lookup[$language][$view][(int) $id];
						}
					}
				}
			}
		}

		// Check if the active menuitem matches the requested language
		$active = $menus->getActive();
		if ($active && $active->component == 'com_mb2portfolio' && ($language == '*' || in_array($active->language, array('*', $language)) || !JLanguageMultilang::isEnabled()))
		{
			return $active->id;
		}

		// If not found, return language specific home link
		$default = $menus->getDefault($language);
		return !empty($default->id) ? $default->id : null;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	

	

	
}





