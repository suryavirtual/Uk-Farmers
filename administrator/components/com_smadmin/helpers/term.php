<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Supplier/Member Admin component helper.
 *
 * @param   string  $submenu  The name of the active view.
 *
 * @return  void
 *
 * @since   1.6
 */

abstract class SmAdminHelper
{
	/**
	 * Configure the Linkbar.
	 */
	public static function addSubmenu($submenu) 
	{
		JSubMenuHelper::addEntry(
			JText::_('COM_SMADMIN_SUBMENU_MESSAGES'),
			'index.php?option=com_smadmin&view=term',
			$submenu == 'messages'
		);

		/*JSubMenuHelper::addEntry(
			JText::_('COM_SMADMIN_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&view=categories&extension=com_smadmin',
			$submenu == 'categories'
		);*/

		// set some global property
		$document = JFactory::getDocument();
		$document->addStyleDeclaration('.icon-48-smadmin ' .
		                               '{background-image: url(../media/com_smadmin/images/tux-48x48.png);}');
		if ($submenu == 'categories') 
		{
			$document->setTitle(JText::_('COM_SMADMIN_ADMINISTRATION_CATEGORIES'));
		}
	}

	/**
	 * Get the actions
	 */
	public static function getActions($messageId = 0)
	{
		$result	= new JObject;

		if (empty($messageId)) {
			$assetName = 'com_smadmin';
		}
		else {
			$assetName = 'com_smadmin.message.'.(int) $messageId;
		}

		$actions = JAccess::getActions('com_smadmin', 'component');

		foreach ($actions as $action) {
			$result->set($action->name, JFactory::getUser()->authorise($action->name, $assetName));
		}

		return $result;
	}
}


