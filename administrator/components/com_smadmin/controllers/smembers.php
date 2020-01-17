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
 * @since  0.0.1
 */
class SmAdminControllerSmembers extends JControllerAdmin
{
	

	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
	
	}
	
	public function getModel($name = 'SmAdmin', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	public function saveuser()
	{
		$company_id=$_REQUEST['company'];
		$user_id=implode(',',$_REQUEST['users']);
		$doc_id=implode(',',$_REQUEST['acess']);
        $db = JFactory::getDbo();
        $query="insert into #__supplier_access(id,company_id,user_id,doc_id) values('','$company_id','$user_id','$doc_id') ";
		$db->setQuery($query);
        $db->execute();

        $referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Data Saved  successfully!";
		$this->setRedirect($referer, $success);	
	}
	public function getusers()
	{
		

		$id = $_REQUEST['company_id'];
		
        setcookie('company',$id);
       
		$db = JFactory::getDbo();
		$Query = "SELECT u.id,u.username,u.name,u.email,u.doc_name,um.group_id,mt.link_name FROM #__users as u left join #__user_usergroup_map as um on(um.user_id=u.id)
		left join #__mt_links as mt on(u.comp_list=mt.link_id) WHERE u.comp_list=".$id;

		$db->setQuery($Query);
     	$results['users'] = $db->loadAssocList(); 
     	$results['doc']=$this->getdocuments();
     	echo json_encode($results);
		die;
	}
	public function getallusers()
	{
		
       
		$db = JFactory::getDbo();
		

		$Query1= "select link_id,link_name from #__mt_links";
		$db->setQuery($Query1);
		$link_id=$db->loadAssocList();
		//print_r($link_id);
		//die;
		foreach ($link_id as $link) 
		{
			
		$db1 = JFactory::getDbo();
	 $Query2= "SELECT u.id,u.username,u.name,u.email,u.doc_name,um.group_id,u.comp_list FROM #__users as u 
		left join #__user_usergroup_map as um on(um.user_id=u.id) 
		  where u.comp_list='".$link['link_id']."'";
		  $db1->setQuery($Query2);
		  
		 
		 // $result[]['company_name']=$link['link_name'];
		  $results[$link['link_name']]=$db1->loadAssocList();

		}
	
		 
     	
     	$results['doc']=$this->getdocuments();
     	echo json_encode($results);
		die;
	}
	public function getdocuments()
	{
		$db = JFactory::getDbo();
		$Query = "SELECT id,doc_name FROM #__document_type";
		$db->setQuery($Query);
     	return $document = $db->loadAssocList(); 

	}
	public function updateuser()
	{
		
		//print_r($_REQUEST);
		$supplier_id=$_REQUEST['supplier'];
		$company_id=$_REQUEST['company'];
		$data=$_REQUEST['document'];

		foreach ($data as $key => $value)
		{
			if(!empty($key))
			{
				 $user_id= $key;
	
		$db = JFactory::getDbo();
        $query="update #__users set doc_name='".implode(',',$value)."' where id='".$user_id."' ";
		$db->setQuery($query);
        $db->execute();
	
				

			}
			
		}
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Data Updated  successfully!";
		$this->setRedirect($referer, $success);

		//die("testing");

	}
	public function getcompany()
	{
		$id = $_REQUEST['supplier_id'];
		
        //setcookie('supplier',$id);
       
		$db = JFactory::getDbo();

		$Query = "select l.link_id,l.link_name,cf.value from jos_mt_links l left join jos_mt_cfvalues cf on(l.link_id=cf.link_id)
		  where cf.value='member' AND l.link_approved='1' order by l.link_name";
		$db->setQuery($Query);
     	$results = $db->loadAssocList(); 
     	echo json_encode($results);
		die;

	}
	


}
