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
 * Supplier/Member Admin List Model
 *
 * @since  0.0.1
 */
class SmAdminModelSmembers extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		

		parent::__construct($config);
	}

	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	public function getcompany()
	{
		 $db = JFactory::getDbo();
		 /*$sql="select l.link_id,l.link_name,cf.value from jos_mt_links l left join jos_mt_cfvalues cf on(l.link_id=cf.link_id)
		  where cf.value='member' AND l.link_approved='1' order by l.link_name";*/
		  $sql="select l.link_id,l.link_name,cf.value from jos_mt_links l right join jos_mt_cfvalues cf on(cf.link_id=l.link_id)
		  where cf.value='member' AND l.link_approved='1' and l.link_id not in('242','240','238','241','242','233') order by l.link_name";

        $db->setQuery($sql);
        $company = $db->loadObjectList();
        //print_r($company);
        return $company; 

	}
	public function getdocument()
	{
		$db = JFactory::getDbo();
		 $sql="select * from jos_document_type";

        $db->setQuery($sql);
        $document = $db->loadObjectList();
        //print_r($company);
        return $document;

	}
	public function getsupplier()
	{
		 $db = JFactory::getDbo();
		 $sql="select l.link_id,l.link_name,cf.value from jos_mt_links l left join jos_mt_cfvalues cf on(l.link_id=cf.link_id)
		  where cf.value='Supplier' AND l.link_approved='1' order by l.link_name";

        $db->setQuery($sql);
        $supplier = $db->loadObjectList();
        //print_r($company);
        return  $supplier;

	}
	
}
