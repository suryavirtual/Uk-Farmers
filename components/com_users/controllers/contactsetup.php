<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';
/**
 * Reset controller class for Users.
 *
 * @since  1.6
 */
class UsersControllerContactsetup extends UsersController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function getEmailDtls(){
		$database = JFactory::getDbo();
		$url = $_POST['url'];
		$user_id=$_POST['user_id'];
		$user_email=$_POST['user_email'];
		$member_id=implode(",",$_POST['members']);
		
		if(!empty($docType)){
			$doc_type=implode(",",$_POST['docType']);
		}
		
		if(empty($member_id)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please select at least 1 Member Comapny', 'error'));
		}else{
			$query = "select * from #__users as u where block='0' and comp_list in (".$member_id.") order by name";
			$database->setQuery( $query );
			$getDtls = $database->loadObjectList();
		}
	}
	
	public function deletedocs(){
		$database = JFactory::getDbo();
		$fdType = '';
		$link = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$user_id=$_REQUEST['userid'];
		$doc_id = $_REQUEST['docid'];
		
		$query = "select * from #__users where id='$user_id'";
		$database->setQuery( $query );
		$getDtls = $database->loadObject();
		
		$doctype = explode(",",$getDtls->doc_name);
				
		foreach($doctype as $doctypes){
			if($doctypes == $doc_id){
				$fdType[] = '';
			} else {
				$fdType[] = $doctypes;
			}
		}
		
		$ftype = implode(",",$fdType);
		$queryUpdate = "update #__users set doc_name='$ftype' where id='$user_id'";
		$database->setQuery($queryUpdate);
		if($database->query()){
			$response = array("success" => '1');
		} else {
			$response = array("success" => '0');
		}
		echo json_encode($response);
		die;

	}
	public function getusers()
	{
		$id = $_REQUEST['company_id'];
        setcookie('company',$id);
		$db = JFactory::getDbo();
		$Query = "SELECT id,username,email,doc_name FROM #__users WHERE comp_list=".$id;
		$db->setQuery($Query);
     	$results['users'] = $db->loadAssocList(); 
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

	}
}