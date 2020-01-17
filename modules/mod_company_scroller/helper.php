<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_company_scroller
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Helper for mod_company_scroller
 *
 * @package     Joomla.Site
 * @subpackage  mod_company_scroller
 * @since       1.5
 */
class ModCompanyScrollerHelper
{
	/**
	 * Retrieve company scroller items
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return array
	 */
	public static function getList(&$params)
	{
		// Get the PathWay object from the application
		$app     = JFactory::getApplication();
		$lang    = JFactory::getLanguage();
		$menu    = $app->getMenu();
		$company = htmlspecialchars($params->get('company'));
		$db = JFactory::getDbo();
		
		if($company == '1'){
			$cmp = 'Member';
		} else {
			$cmp = 'Supplier';
		}
		
		$db->setQuery("SELECT cf.link_id, cm.filename, cl.link_name FROM `jos_mt_cfvalues` as cf, jos_mt_images as cm, jos_mt_links as cl 
		where cf.value='$cmp' and cm.link_id=cf.link_id and cm.filename !='' and cm.link_id=cl.link_id and cl.link_published='1'");
		$results = $db->loadObjectList();
		
		return $results;
	}
}
