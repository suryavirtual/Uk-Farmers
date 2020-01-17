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
 * Supplier/Member Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 * @since       0.0.9
 */
class SmAdminControllerUfbrand extends JControllerForm
{
	/**
	* Implement to allowAdd or not
	*
	* Not used at this time (but you can look at how other components use it....)
	* Overwrites: JControllerForm::allowAdd
	*
	* @param array $data
	* @return bool
	*/
	protected function allowAdd($data = array())
	{
		return parent::allowAdd($data);
	}
	/**
	* Implement to allow edit or not
	* Overwrites: JControllerForm::allowEdit
	*
	* @param array $data
	* @param string $key
	* @return bool
	*/
	protected function allowEdit($data = array(), $key = 'id')
	{
		$id = isset( $data[ $key ] ) ? $data[ $key ] : 0;
		if( !empty( $id ) )
		{
			return JFactory::getUser()->authorise( "core.edit", "com_smadmin.message." . $id );
		}
	}
	
	public function save(){
		
		$db = JFactory::getDbo();
		$task = $_REQUEST['task'];
		$id=$_REQUEST['id'];
		$brandDtl = $_REQUEST['jform']['brandDtl'];
		$pImages = $_REQUEST['jform']['pImages'];
		$pGuid = $_REQUEST['jform']['pGuid'];
		$catalogues = $_REQUEST['jform']['catalogues'];
		$published = $_REQUEST['jform']['published'];
		$ordering = $_REQUEST['jform']['ordering'];
		$uid = $_REQUEST['uid'];
		$addedby = $_REQUEST['addedBy'];
		
		if(!empty($id)){
			$query1 = "update #__uf_brands set brandDtl='$brandDtl', pImages='$pImages', pGuid='$pGuid', catalogues='$catalogues', published='$published', modifiedon=now(), addedBy='$addedBy', ordering='$ordering' ";
		} else {
			$query1 = "insert into #__uf_brands (id, brandDtl, pImages, pGuid, catalogues, published, addedon, modifiedon, addedBy, ordering) VALUES 
			('', '$brandDtl', '$pImages', '$pGuid', '$catalogues', '$published', now(), '', '$addedby', '$ordering')";
			
		}
		$db->setQuery( $query1 );
		$db->query();
		
		//$this->setRedirect("index.php?option=com_smadmin&view=ufbrands", 'Added Successfully');
		if($task=='save'){
			//$this->setRedirect("index.php?option=com_smadmin&view=ufbrands", 'Added Successfully');
		
		$this->setRedirect("index.php?option=com_smadmin&view=ufbrands", 'Added Successfully');
		}  
		if ($task=='apply'){
			$link = $this->setRedirect($_SERVER['HTTP_REFERER'],'Added Successfully');
		}
	}
}
