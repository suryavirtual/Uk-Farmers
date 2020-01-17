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
class SmAdminControllerEmail extends JControllerForm
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
	
	public function save()
	{

		$database = JFactory::getDbo();
		$task = $_REQUEST['task'];
		$emailId = $_REQUEST['id'];
		$cmp_id=$_REQUEST['supplierCmp'];
		$subject=$_REQUEST['jform']['subject'];
		$document_type=implode(",",$_REQUEST['document_type']);
		$emailBody=addslashes($_REQUEST['body']);
		$memberId = $_POST['member_id'];
		$querySelect = "select mt.*, u.id as supplierId, u.email as supplierEmail from #__mt_links as mt, jos_users as u where mt.link_id='$cmp_id' and mt.user_id=u.id";
		$database->setQuery($querySelect);
		$cmpnyDtl = $database->loadObject();
		$user_id=$cmpnyDtl->supplierId;
		$user_email=$cmpnyDtl->supplierEmail;
		$supplierCmpId=$cmpnyDtl->link_id;
		
		if($task=='save'){
			$link=JURI::base().'index.php?option=com_smadmin&view=emails';
		} else if ($task=='apply'){
			$link = JURI::getInstance($_SERVER['HTTP_REFERER']);
		}

		if(count($_FILES['supplier_files']['name'] > 0))
		{
			$allowed =  array('pdf','xls','csv','doc','docx','ppt','xlsx','png','jpg','gif');
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
			
			if($error=='1')
			{
				$this->setRedirect($referer, JFactory::getApplication()->enqueueMessage('Uploaded file format is not allowed', 'error'));
			}
			else
			{
				for($m=0; $m<count($_FILES['supplier_files']['name']); $m++)
				{
					
					$fileName[] = $_FILES['supplier_files']['name'][$m];
					move_uploaded_file($_FILES['supplier_files']['tmp_name'][$m], JPATH_SITE."/uf_data/upload/email/".$_FILES['supplier_files']['name'][$m]);
				} 
			  $fname = implode("|",$fileName);

			}
		}
		if(!empty($emailId)){

			$query = "update #__email_member set supplierid='$user_id', supplierCmpId='$supplierCmpId', document_type='$document_type', body='$emailBody', subject='$subject', email='$user_email', updatedon=now() where id='$emailId'";
		}
		else {			
			$query = "INSERT INTO #__email_member (`id`, `supplierid`, `supplierCmpId`, `memberid`, `document_type`, `body`, `subject`, `email`, `email_sent`,file, `addedon`, `updatedon`, `status`) 
				VALUES (NULL, '$user_id', '$cmp_id', '', '$document_type', '$emailBody', '$subject', '$user_email', '0','$fname', now(), '', '0')";
                    
		}
				
		$database->setQuery($query);
		$database->query();
		$last_insert_id=$database->insertid();
		/* ends from here */
		
		if(!empty($emailId)){
			$success = "Email Updated successfully!";
		} else {
                       $this->notifyadmin($last_insert_id);
			$success = "Email Added successfully!";
		}
		
		$this->setRedirect($link."&id=".$last_insert_id."#tabs-6", $success);
	}
 
 public function notifyadmin($emId)
     {
          $db = JFactory::getDbo();
         $emaildetails= "select em.*,mt.link_name from jos_email_member as em,jos_mt_links as mt where em.id ='$emId' and em.supplierCmpId= mt.link_id ";
                                $db->setQuery($emaildetails);
                            $emailDtl = $db->loadObject();

                        $fileId=$emailDtl->id;

                    $supplier_id=$emailDtl->supplierid;

                    $supplier_comp_id=$emailDtl->supplierCmpId;

                        $sEmail = $emailDtl->email;

                        $sName=$emailDtl->link_name;

                        $eSubject =$emailDtl->subject;

                        $eBody = $emailDtl->body;

                        $document_type = $emailDtl->document_type;

                        $attachment=$emailDtl->file;
                        $filepath=$_SERVER['DOCUMENT_ROOT']."/uf_data/upload/email/".$attachment;
                        $attchments= $filepath;
                $mailer1 = JFactory::getMailer();
                $body1 = "Hi Admin,<br><br> A New Email sent by the ".$sName.", Please check the admin panel.<br><br><a href='".JURI::root()."/administrator/index.php?option=com_smadmin&view=emails' target='_blank'>click here</a> to check the new email<br><br>Thanks<br>UNF Team";
              // $to1="matt.hooker@adaptris.com";
                 //  $to1="suryakant@virtualemployee.com";
                  $to1 = "admin@united-farmers.org.uk";
                   $from1 = array($sEmail, $sName);
                $subject1 = "Email Recived by ".$sName;
                $mailer1->setSender($from1);
                $mailer1->addRecipient($to1);
                $mailer1->setSubject($subject1);
                $mailer1->setBody($body1);
                $mailer1->addAttachment($attchments);
                $mailer1->isHTML(true);
                $userParams = JComponentHelper::getParams('com_smadmin')->get('send_mail');
                if($userParams == "1")
                {
                $mailer1->send();
                }


        }
      
}
