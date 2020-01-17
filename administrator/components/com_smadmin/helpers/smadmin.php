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
         $database = JFactory::getDbo();
	 $q1 = "select sf.*,mt.link_name from #__supplier_files as sf,#__mt_links as mt Where 1=1 and mt.link_id=sf.comp_id and sf.approved='0' and sf.expiry >= date_sub(now(), interval 3 month)";
         $database->setQuery( $q1 );
         $fileDtl = $database->loadObjectList();
         $totalfilecount=count($fileDtl);
         $database->query();
         $query2="select st.* from #__structured_terms as st,#__mt_links as mt Where 1=1 and st.supplierUserId=mt.link_id and  st.status ='0' ";
		 $database->setQuery( $query2 );
		 $database->query();
		 $rows = $database->loadObjectList();
		 $totattermscount=count($rows);
		 $database->query();
		 $query3="select * from #__email_member WHERE status ='0'";
		 $database->setQuery( $query3 );
		 $rowsemails = $database->loadObjectList();
		 $totalemailcount=count($rowsemails);
		 $database->query();
             
		/*JSubMenuHelper::addEntry(
			JText::_('Manage Products'),
			'index.php?option=com_smadmin&view=products',
			$submenu == 'messages'
		);*/

		JSubMenuHelper::addEntry(
			JText::_('Manage Terms ' .$totattermscount ),
			'index.php?option=com_smadmin&view=terms',
			$submenu == 'categories'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('Manage Files ' .$totalfilecount),
			'index.php?option=com_smadmin&view=files',
			$submenu == 'categories'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('Manage Emails ' .$totalemailcount),
			'index.php?option=com_smadmin&view=emails',
			$submenu == 'categories'
		);
		
		/*JSubMenuHelper::addEntry(
			JText::_('Terms To Member'),
			'index.php?option=com_smadmin&view=tmembers',
			$submenu == 'categories'
		);
		*/
		/*JSubMenuHelper::addEntry(
			JText::_('Files To Member'),
			'index.php?option=com_smadmin&view=fmembers',
			$submenu == 'categories'
		);*/
		
		/*JSubMenuHelper::addEntry(
			JText::_('Emails To Member'),
			'index.php?option=com_smadmin&view=emembers',
			$submenu == 'categories'
		);*/
		JSubMenuHelper::addEntry(
			JText::_('Supplier/Member Notifications'),
			'index.php?option=com_smadmin&view=accessmembers',
			$submenu == 'categories'
		);
		
		JSubMenuHelper::addEntry(
			JText::_('Country UF Brands'),
			'index.php?option=com_smadmin&view=ufbrands',
			$submenu == 'ufBrands'
		);
		JSubMenuHelper::addEntry(
			JText::_('Supplier/Member Notifications Import/Export'),
			'index.php?option=com_smadmin&view=import',
			$submenu == 'categories'
		);

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
