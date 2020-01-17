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
class SmAdminControllerTmembers extends JControllerAdmin
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

			$query = "UPDATE #__structured_terms_to_members SET approved = '".$value. "' WHERE id IN ( ".$cids." )";
			$db->setQuery( $query );

			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		if($value=='1'){
			$getDtl = "select * from #__structured_terms_to_members where id = '$cids'";
			$db->setQuery( $getDtl );
			$res = $db->loadObject();
			$viewEmail = $res->viewEmail;
			$delReq = $res->deleteRequest;
			$sentEmail = $res->email_sent;

			$message = "Term is published successfuly.";
		} else {
			$message = "Term is unpublished successfuly.";
		}

		$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view');
		$this->setRedirect($redirectTo, $message);
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

			$query = "delete from #__structured_terms_to_members WHERE id IN ( ".$cids." )";
			$db->setQuery( $query );

			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}

		$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view');
		$this->setRedirect($redirectTo);
	}

	public function notify(){
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$values = array('notify' => '1', 'unnotify' => '0');
		$task = $this->getTask();
		$value = JArrayHelper::getValue($values, $task, '', '');

		if($value=='1'){
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );

			$getDtl = "select * from #__structured_terms_to_members where id = '$cids'";
			$db->setQuery( $getDtl );
			$res = $db->loadObject();
			$viewEmail = $res->viewEmail;
			$delReq = $res->deleteRequest;
			$sentEmail = $res->email_sent;

			if(($delReq=='0') && ($sentEmail=='0')){
				$selQuery = "SELECT u.name as supplierName, u.email as senderEmail, mt.user_id, mu.name as memberName, mu.email as memberEmail, st.termName
					from #__users as u, #__structured_terms_to_members as stm, #__mt_links as mt, #__users as mu, jos_structured_terms as st
					WHERE stm.id IN ( ".$cids." ) and stm.user_id=u.id and stm.member_id=mt.link_id and mt.user_id=mu.id and stm.terms_id=st.termId";
				$db->setQuery($selQuery);
				$userDtl = $db->loadObject();

				$sName = $userDtl->supplierName;
				$sEmail = $userDtl->senderEmail;
				$mName = $userDtl->memberName;
				$mEmail = $userDtl->memberEmail;
				$tName = $userDtl->termName;
				$this->sendMail($sName, $sEmail, $mName, $mEmail, $tName);

				$updateEmail = "UPDATE #__structured_terms_to_members SET email_sent = '1' WHERE id IN ( ".$cids." )";
				$db->setQuery($updateEmail);

				if (!$db->query()) {
					$this->setError($this->_db->getErrorMsg());
					return false;

				}

				$message = "Mail successfully sent to member.";
			} else {
				$message = "Mail already sent to member.";
			}

		}

		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Mail sent  successfully!";
		$this->setRedirect($referer, $success);
	}
	/* new function added for notification mail */

	public function notification()
	{
		 $id = $_REQUEST['term_id'];

		$db = JFactory::getDbo();
		//$Query = "SELECT u.id,u.name  FROM #__users as u left join #__user_usergroup_map as um on(u.id=um.user_id) WHERE um.group_id='13' and  u.comp_list=".$id;

     $Query="select st.supplierUserId,st.termName,u.name,u.email,mt.link_id from #__structured_terms as st
     left join #__mt_links as mt on(st.supplierUserId=mt.link_id)
			left join #__users as u on(mt.link_id=u.comp_list) where st.termId=".$id;
		$db->setQuery($Query);
		 $results=$db->loadObject();

		       $sName = 'matt hooker';
				$sEmail = 'matt.hooker@adaptris.com';
				$mName = $results->name;
				$mEmail = $results->email;
				$tName = $results->termName;
				$this->notificationmail($sName, $sEmail, $mName, $mEmail, $tName);

				/*notification mail date update on database */
				$query2 = "update #__structured_terms SET lastnotification =now() WHERE termId=".$id;

		        $db->setQuery($query2);
		        $db->query();
		die;
	}
	public function notificationmail($sName, $sEmail, $mName, $mEmail, $tName)
	{
		$body = "Hi ".$mName.",<br><br> your Terms have been updated , Please check the new Terms.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="matt.hooker@reedbusiness.com";
		$from = array($sEmail, $sName);
		$subject = "Term updated by ".$sName;

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

	public function sendMail($sName, $sEmail, $mName, $mEmail, $tName){
		$body = "Hi ".$mName.",<br><br> New Terms sent by the ".$sName.", Please check these Terms.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="matt.hooker@adaptris.com";
		$from = array($sEmail, $sName);
		$subject = "Term Send by ".$sName;

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
		$body1 = "Hi Admin,<br><br> A New set of Terms sent by the ".$sName.", Please check the admin panel.<br><br><a href='".JURI::root()."/administrator/index.php?option=com_smadmin&view=terms' target='_blank'>click here</a> to check the new email<br><br><br>Thanks<br>UNF Team";
		$to1="matt.hooker@reedbusiness.com";
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
