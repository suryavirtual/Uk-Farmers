<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// No direct access
defined('_JEXEC') or die;

/**
 * Mb2portfolio helper.
 */
class Mb2portfolioHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($vName = '')
	{
		
		
		
						
		JHtmlSidebar::addEntry(
			JText::_('COM_MB2PORTFOLIO_PROJECTS'),
			'index.php?option=com_mb2portfolio&view=projects',
			$vName == 'projects'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MB2PORTFOLIO_SKILLS'),
			'index.php?option=com_mb2portfolio&view=skills',
			$vName == 'skills'
		);
		JHtmlSidebar::addEntry(
			JText::_('COM_MB2PORTFOLIO_EXTRA_FIELDS'),
			'index.php?option=com_mb2portfolio&view=extrafields',
			$vName == 'extrafields'
		);	
			
		
		
		

	}
	
	
	
	
	
	

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @return	JObject
	 * @since	1.6
	 */
	public static function getActions()
	{
		$user	= JFactory::getUser();
		$result	= new JObject;

		$assetName = 'com_mb2portfolio';

		$actions = array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete'
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}
}
