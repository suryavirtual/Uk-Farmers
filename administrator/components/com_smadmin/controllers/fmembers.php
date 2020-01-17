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
class SmAdminControllerFmembers extends JControllerAdmin
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
		$this->registerTask('unapproved', 'approved');
	}
	
	public function getModel($name = 'SmAdmin', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	function approved()
	{
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$values = array('approved' => '1', 'unapproved' => '0');
		$task = $this->getTask();

		$value = JArrayHelper::getValue($values, $task, '', '');
		
		
		
		if (count( $ids )){ 
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
			$date = date("Y-m-d"); 
			
			$query = "UPDATE #__files_to_members SET sentEmail = '".$value. "', publishDate='".$date."' WHERE id IN ( ".$cids." )";
			$db->setQuery( $query );
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		
		if($value=='1'){
			$getDtl = "select * from #__files_to_members where id = '$cids'";
			$db->setQuery( $getDtl );
			$res = $db->loadObject();
			//$viewEmail = $res->viewEmail;
			$delReq = $res->deleteRequest;
			$sentEmail = $res->sentEmail;
			
			if( ($delReq=='0') && ($sentEmail=='0')){
				 $selQuery = "SELECT ftm.*, mt.link_name, sf.description, sf.type, sf.expiry, dt.doc_name, u.name as supplierName, u.email as supplierEmail, um.name as memberName, um.email as memberEmail, sfn.fname 
					from #__files_to_members as ftm, #__mt_links as mt, #__supplier_files as sf, #__document_type as dt, #__users as u, #__users as um, #__supplier_file_name as sfn 
					WHERE ftm.id IN ( ".$cids." ) and ftm.memberId=mt.link_id and ftm.fileId=sf.id and sf.type=dt.id and sf.userid=u.id and mt.user_id=um.id and ftm.fileNameId=sfn.id";
				$db->setQuery($selQuery);
				$userDtl = $db->loadObject();
				
				$sName = $userDtl->supplierName;
				$sEmail = $userDtl->supplierEmail;
				$mName = $userDtl->memberName;
				$mEmail = $userDtl->memberEmail;
				$fileDescription = $userDtl->description;
				$doc_name = $userDtl->doc_name;
				$expiry = $userDtl->expiry;
				$fname = $userDtl->fname;
				$this->sendMail($sName, $sEmail, $mName, $mEmail, $fileDescription, $doc_name, $expiry, $fname );
				
				
			} else {
				//$message = "File is published successfuly & Mail is already sent to member.";
			}
		}
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Mail sent  successfully!";
		$this->setRedirect($referer, $success);
	}
	
	public function undelreq(){
		
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$values = array('delreq' => '1', 'undelreq' => '0');
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, '', '');
		
		if (count( $ids )){
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
			
			$selQuery = "select * from #__files_to_members where id='$cids'";
			$db->setQuery( $selQuery );
			$resDtl = $db->loadObject();
			$attFileId = $resDtl->fileNameId;
			
			$queryDelFile = "delete from #__supplier_file_name WHERE id='$attFileId'";
			$db->setQuery( $queryDelFile );
			$db->query();
						
			$queryDel = "delete from #__files_to_members WHERE id IN ( ".$cids." )";
			$db->setQuery( $queryDel );
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
	
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Mail sent  successfully!";
		$this->setRedirect($referer, $success);
	
	}
	/* new function added for notification mail */

	public function notification()
	{
		 $id = $_REQUEST['user_id'];
		 $fileid = $_REQUEST['file_id'];
       
		$db = JFactory::getDbo();
		$Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$id;
	
		$db->setQuery($Query);
		 $results=$db->loadObject();


		       $sName = 'matt hooker';
				$sEmail = 'matt.hooker@adaptris.com';
				$mName = $results->name;
				$mEmail = $results->email;
				 
				$this->notificationmail($sName, $sEmail, $mName, $mEmail);
	
		$db->setQuery($Query1);
		$query2 = "update #__supplier_files SET lastnotification =now() WHERE id=".$fileid;
		
		        $db->setQuery($query2);
		        $db->query();
		// print_r($results);
     	//echo json_encode($results);
		die;
	}
	public function notificationmail($sName, $sEmail, $mName, $mEmail, $tName)
	{
		$body = "Hi ".$mName.",<br><br> your file has been updated , Please check this file.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="pratishthasingh@virtualemployee.com";
		$from = array($sEmail, $sName);
		$subject = "File updated by ".$sName;
		
		# Invoke JMail Class
		$mailer = JFactory::getMailer();

		# Set sender array so that my name will show up neatly in your inbox
		$mailer->setSender($from);

		# Add a recipient -- this can be a single address (string) or an array of addresses
		$mailer->addRecipient($to);

		$mailer->setSubject($subject);
		$mailer->setBody($body);

		# If you would like to send as HTML, include this line; otherwise, leave it out
		$mailer->isHTML(true);

		# Send once you have set all of your options
		$mailer->send();

	}
	/* new funtion ended here */
	
	public function sendMail($sName, $sEmail, $mName, $mEmail, $fileDescription, $doc_name, $expiry, $fname){
		$body = "Hi ".$mName.",<br><br> A New File of type ".$doc_name." sent by the ".$sName.", having expiry date:".$expiry." Please check this File.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="matt.hooker@adaptris.com";
		$from = array($sEmail, $sName);
		$subject = "Term Sent by ".$sName;
		
		# Invoke JMail Class
		$mailer = JFactory::getMailer();

		# Set sender array so that my name will show up neatly in your inbox
		$mailer->setSender($from);

		# Add a recipient -- this can be a single address (string) or an array of addresses
		$mailer->addRecipient($to);

		$mailer->setSubject($subject);
		$mailer->setBody($body);

		# If you would like to send as HTML, include this line; otherwise, leave it out
		$mailer->isHTML(true);

		# Send once you have set all of your options
		$mailer->send();
				#now sending another mail to the admin for checking the email
		$mailer1 = JFactory::getMailer();
		$body1 = "Hi Admin,<br><br> A New File sent by the ".$sName.", Please check the admin panel.<br><br><a href='".JURI::root()."/administrator/index.php?option=com_smadmin&view=files' target='_blank'>click here</a> to check the new email<br><br><br>Thanks<br>UNF Team";
		$to1="pratishthasingh@virtualemployee.com";
		$from1 = array($sEmail, $sName);
		$subject1 = "Email Recived by ".$sName;
		$mailer1->setSender($from1);
		$mailer1->addRecipient($to1);
		$mailer1->setSubject($subject1);
		$mailer1->setBody($body1);
		$mailer1->isHTML(true);
		$mailer1->send();
	}
}
