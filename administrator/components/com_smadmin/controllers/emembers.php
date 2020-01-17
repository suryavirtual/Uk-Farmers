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
class SmAdminControllerEmembers extends JControllerAdmin
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
	
	function approved(){
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$values = array('approved' => '1', 'unapproved' => '0');
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, '', '');
		
		if (count( $ids )){ 
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
			$date = date("Y-m-d");
		
			$query = "UPDATE #__send_email_member SET approve = '".$value. "', approveDate = '".$date."' WHERE id IN ( ".$cids." )";
			$db->setQuery( $query );
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		if($value=='1'){
			$selQuery = "select sem.*, em.subject, em.body,em.file,em.document_type, mt.link_name, u.name as supplierName, u.email as supplierEmail, um.name as memberName, um.email as memberEmail 
				from jos_send_email_member as sem, jos_email_member as em, jos_mt_links as mt, jos_users as u, jos_users as um 
				WHERE sem.id IN ( ".$cids." ) and sem.emId=em.id and sem.memberId=mt.link_id and em.supplierid=u.id and mt.user_id=um.id";
			$db->setQuery($selQuery);
			$userDtl = $db->loadObject();						
			$sName = $userDtl->supplierName;
			$sEmail = $userDtl->supplierEmail;
			$mName = $userDtl->memberName;
			$mEmail = $userDtl->memberEmail;
			$eSubject = $userDtl->subject;
			$eBody = $userDtl->body;
			$document_type = $userDtl->document_type;
			 $attachment=$userDtl->file;
			// $filepath=JURI::root()."upload/email/".$attachment;
			 $filepath=$_SERVER['DOCUMENT_ROOT']."/uk_farmers/upload/email/".$attachment;

			 
			  $attchments= $filepath; 
			
			
		
			
			$this->sendMail($sName, $sEmail, $mName, $mEmail, $eSubject,$attchments);
		}
		//$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view'); 
		//$this->setRedirect($redirectTo); 
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Mail sent  successfully!";
		$this->setRedirect($referer, $success);	
	}
	
	public function sendMail($sName, $sEmail, $mName, $mEmail, $eSubject,$attchments){
		$body = "Hi ".$mName.",<br><br> A New Email sent by the ".$sName.", Please check this Email.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="matt.hooker@adaptris.com";
		//$to="pratishthasingh@virtualemployee.com";
		$from = array($sEmail, $sName);
		$subject = "Email Send by ".$sName;
		
		# Invoke JMail Class
		$mailer = JFactory::getMailer();

		# Set sender array so that my name will show up neatly in your inbox
		$mailer->setSender($from);

		# Add a recipient -- this can be a single address (string) or an array of addresses
		$mailer->addRecipient($to);

		

		$mailer->setSubject($subject);
		$mailer->setBody($body);

		#Add attachment with mail
		$mailer->addAttachment($attchments);

		# If you would like to send as HTML, include this line; otherwise, leave it out
		$mailer->isHTML(true);

		# Send once you have set all of your options
		$mailer->send();
		#now sending another mail to the admin for checking the email
		$mailer1 = JFactory::getMailer();
		$body1 = "Hi Admin,<br><br> A New Email sent by the ".$sName.", Please check the admin panel.<br><br><a href='".JURI::root()."/administrator/index.php?option=com_smadmin&view=emails' target='_blank'>click here</a> to check the new email<br><br>Thanks<br>UNF Team";
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
