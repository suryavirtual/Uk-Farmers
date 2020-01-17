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
                $user = JFactory::getUser();
                $username=$user->name;
		/* file upload code start from here  */
	/*	if(count($_FILES['supplier_files']['name'] > 0))
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

					move_uploaded_file($_FILES['supplier_files']['tmp_name'][$m], JPATH_BASE."/uf_data/upload/email/".$_FILES['supplier_files']['name'][$m]);
				} 
			 $fname = implode("|",$fileName);

			}
		 } */


		/*ends here */
		
		if(empty($subject)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please enter subject Name', 'error'));
		} else if(empty($member_id)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please select members', 'error'));
		} else if(empty($doc_type)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage('Please select document Type', 'error'));
		} else {
			$query = "INSERT INTO #__email_member (`id`, `supplierid`, `supplierCmpId`, `memberid`, `document_type`, `body`, `subject`, `email`, `email_sent`,file,
				`addedon`, `updatedon`, `status`, `ordering`) VALUES ('', '$user_id', '$cmp_list', '$member_id', '$doc_type', '$msg_body', '$subject', '$user_email', '0','', now(), '', '0', '1');";
			$database->setQuery($query);
			$database->query();
			$queryMember = "select * from #__email_member order by id desc limit 0,1";
			$database->setQuery($queryMember);
			$getDtl = $database->loadObject();
			$emId = $getDtl->id;
                       //  $this->notifyadmin($emId);
			
			for($l=0; $l<count($_POST['member_id']); $l++){
				$memId = $_POST['member_id'][$l];
				$querySM = "INSERT INTO #__send_email_member (`id`, `emId`, `memberId`, `approve`, `approveDate`) VALUES ('', '$emId', '$memId', '0', '');";
				$database->setQuery($querySM);
				$database->query();
                         /*new code for sending details for admin email */
				$querymembers= "select mt.link_name,u.email from #__mt_links as mt left join #__users as u on(mt.user_id=u.id) where mt.link_id=".$memId." ";
				$database->setQuery($querymembers);
                                $rows_members = $database->loadObject();
                                $sentmail['member'][]=$rows_members->link_name;
                                $sentmail['member_email'][]=$rows_members->email;
			}
                         $html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
                   <tr><th>Member</th><th>Member Email</th></tr>";   
        for($i=0;$i<count($sentmail['member']);$i++)
        {
        	$html.="<tr><td>".$sentmail['member'][$i]."</td>";
                $html.="<td>".$sentmail['member_email'][$i]."</td></tr>";
            
        	
        }
         $html.= "</table>";
          $body = "Dear Admin,<br><br>An email from (".$username.", ".$user_email.") the following members is awaiting approval .<br><br>".$html."<br>Thanks<br>UNF Team";
      

          //$to = "enquiries@united-farmers.org.uk";
                 $sEmail = $user_email;
                 $sName=  "UNF Admin";
                $to= "ve.akanshajaiswal@gmail.com";
                //$to="suryakant@virtualemployee.com";
                //$to="matt.hooker@adaptris.com";
                 $from = array($sEmail, $sName);
                 $subject = "Supplier/Member email awaiting approval";
               
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
                $userParams = JComponentHelper::getParams('com_smadmin')->get('send_mail');
                if($userParams == "1")
                {
                $mailer->send();
                }
			
			
			$this->setRedirect($url, 'Email send successfully to admin for approval');
		
		}
	}
        public function notifyadmin($emId)
	{
		$db = JFactory::getDbo();

	 $emaildetails= "select em.*,mt.link_name from #___email_member as em,#___mt_links as mt where em.id ='$emId' and em.supplierCmpId=mt.link_id ";
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
		#now sending another mail to the admin for checking the email
		$mailer1 = JFactory::getMailer();
		$body1 = "Hi Admin,<br><br> A New Email sent by the ".$sName.", Please check the admin panel.<br><br><a href='".JURI::root()."/administrator/index.php?option=com_smadmin&view=emails' target='_blank'>click here</a> to check the new email<br><br>Thanks<br>UNF Team";
		//$to1="matt.hooker@adaptris.com";
                $to1="ve.akanshajaiswal@gmail.com";
               // $to1 = "admin@united-farmers.org.uk";
		$from1 = array($sEmail, $sName);
		$subject1 = "Email Recived by ".$sName;
		$mailer1->setSender($from1);
		$mailer1->addRecipient($to1);
		$mailer1->setSubject($subject1);
		$mailer1->setBody($body1);
		$mailer1->isHTML(true);
                $userParams = JComponentHelper::getParams('com_smadmin')->get('send_mail');
		if($userParams == "1")
		{
		$mailer1->send();	
		}
	     

	}
}
