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
class UsersControllerSendemails extends UsersController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function sendEmails(){
		
		$database = JFactory::getDbo();
		$url = $_POST['url'];
		$user_id=$_POST['user_id'];
		$cmp_list=$_POST['cmp_list'];
		$subject=$_POST['subject'];
		$user_email=$_POST['user_email'];
		$member_id=implode(",",$_POST['member_id']);
		$doc_type=implode(",",$_POST['doc_type']);
		$msg_body=addslashes($_POST['body']);
		/* file upload code start from here  */
		if(count($_FILES['supplier_files']['name'] > 0))
		{
			$allowed =  array('pdf','xls','csv','doc','docx','ppt','xlsx');
			for($l=0; $l<count($_FILES['supplier_files']['name']); $l++)
			{
				$ext = pathinfo($_FILES['supplier_files']['name'][$l], PATHINFO_EXTENSION);
				if(!in_array($ext,$allowed)){
					$error = '1';
					$l=count($_FILES['supplier_files']['name']);
				} else {
					$error = '0';
				}
			}
			
			if($error=='1'){
				$this->setRedirect($referer, JFactory::getApplication()->enqueueMessage('Uploaded file format is not allowed', 'error'));
			}else{
				for($m=0; $m<count($_FILES['supplier_files']['name']); $m++){
					 $fileName[] = $_FILES['supplier_files']['name'][$m];

					move_uploaded_file($_FILES['supplier_files']['tmp_name'][$m], JPATH_BASE."/upload/email/".$_FILES['supplier_files']['name'][$m]);
				} 
			 $fname = implode("|",$fileName);

			}
		}


		/*ends here */
		
		if(empty($subject)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please enter subject Name', 'error'));
		} else if(empty($member_id)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please select members', 'error'));
		} else if(empty($doc_type)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please select document Type', 'error'));
		} else {
			$query = "INSERT INTO #__email_member (`id`, `supplierid`, `supplierCmpId`, `memberid`, `document_type`, `body`, `subject`, `email`, `email_sent`,file,
				`addedon`, `updatedon`, `status`, `ordering`) VALUES ('', '$user_id', '$cmp_list', '$member_id', '$doc_type', '$msg_body', '$subject', '$user_email', '0','$fname', now(), '', '0', '1');";
			$database->setQuery($query);
			$database->query();
			
			$queryMember = "select * from #__email_member order by id desc limit 0,1";
			$database->setQuery($queryMember);
			$getDtl = $database->loadObject();
			$emId = $getDtl->id;
			
			for($l=0; $l<count($_POST['member_id']); $l++){
				$memId = $_POST['member_id'][$l];
				$querySM = "INSERT INTO #__send_email_member (`id`, `emId`, `memberId`, `approve`, `approveDate`) VALUES ('', '$emId', '$memId', '0', '');";
				$database->setQuery($querySM);
				$database->query();
			}
			
			
			$this->setRedirect($url, 'Email send successfully to admin for approval');
		
		}
	}
}
