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
class SmAdminControllerEmails extends JControllerAdmin
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
	
	public function getModel($name = 'Email', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
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
		
			$query = "UPDATE #__email_member SET status = '".$value. "' WHERE id IN ( ".$cids." )";
			$db->setQuery( $query );
			
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		
		if($value=='1'){
			 /*echo "query@ ".$selQuery = "select sem.*, em.supplierid,em.subject, em.body,em.file,em.document_type, mt.link_name,u.comp_list as supplier_id, u.name as supplierName, u.email as supplierEmail, um.name as memberName, um.email as memberEmail 
				from jos_send_email_member as sem, jos_email_member as em, jos_mt_links as mt, jos_users as u, jos_users as um 
				WHERE sem.emId IN ( ".$cids." ) and sem.emId=em.id and sem.memberId=mt.link_id and em.supplierid=u.id and mt.user_id=um.id"; */
				$emaildetails= "select em.*,mt.link_name from jos_email_member as em,jos_mt_links as mt where em.id IN(".$cids.") and em.supplierCmpId=mt.link_id ";
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

				$selQuery = "select  sem.memberId 
				from jos_send_email_member as sem
				WHERE sem.emId IN ( ".$cids." ) ";
				
			$db->setQuery($selQuery);
			$userDtl = $db->loadObjectList();
			
			foreach ($userDtl as $values) 
		    {

		    $cmp_id=$values->memberId; 
			$this->checkmemberpermission($fileId,$document_type,$cmp_id,$supplier_comp_id,$sName,$sEmail,$eSubject,$attchments);


		    }
		    $this->notifyadmin($sName,$sEmail);
				
		}

		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Mail sent  successfully!";
		$this->setRedirect($referer, $success);	
	}

    public function checkmemberpermission($fileId,$docType,$cmp_id,$supplier_id,$sName,$sEmail,$eSubject,$attchments)
	{
		
		 $database = JFactory::getDbo();
         $querySelect1 ="select u.name,u.id as user_id,u.email,ug.group_id from #__users as u
		  left join #__user_usergroup_map as ug on(ug.user_id=u.id) where u.comp_list IN (".$cmp_id.") and   u.block='0'  ";
		    $database->setQuery($querySelect1);
		    $notificattionDtl = $database->loadObjectList();
		    $document_type=intval($docType);
            
		    foreach ($notificattionDtl as $values) 
		    { 
		    	 $member_userid=$values->user_id;
		    	 $mName=$values->name;
		    	 $mEmail=$values->email;
		    	
		    	$querypermission="SELECT docIds FROM #__member_access WHERE memberId = '".$cmp_id."' AND supplierId = '".$supplier_id."' AND userId = '".$member_userid."'";
		    	$database->setQuery($querypermission);
		    	$recive_permission=$database->loadColumn();
		    	$final_permission=explode(',', $recive_permission['0']);
		    	
		    	
		    	if(in_array($document_type,$final_permission))
		    	{
		    		
		    		$this->sendMail($sName, $sEmail, $mName, $mEmail, $eSubject,$attchments);

		    			
		    	}
		    	
		    }   
		 
	}
	public function notifyadmin($sName,$sEmail)
	{
		#now sending another mail to the admin for checking the email
		$mailer1 = JFactory::getMailer();
		$body1 = "Hi Admin,<br><br> A New Email sent by the ".$sName.", Please check the admin panel.<br><br><a href='".JURI::root()."/administrator/index.php?option=com_smadmin&view=emails' target='_blank'>click here</a> to check the new email<br><br>Thanks<br>UNF Team";
		//$to1="matt.hooker@adaptris.com";
		//$to1 = "admin@united-farmers.org.uk";
                $to1="suryakant@virtualemployee.com";
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
		//$mailer1->send();	

	}

	
	public function sendMail($sName, $sEmail, $mName, $mEmail, $eSubject,$attchments){
		$body = "Hi ".$mName.",<br><br> A New Email sent by the ".$sName.", Please check this Email.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="matt.hooker@adaptris.com";
		//$to="pratishthasingh@virtualemployee.com";
		//$to = "suryakant@virtualemployee.com";
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
		
	}
	
	public function delete(){
		$database = JFactory::getDbo();
		$ids = implode(",",$_REQUEST['cid']);
		
		$queryDel = "delete from #__email_member where id in ($ids)";
		$database->setQuery($queryDel);
		
		if($database->query()){
			$queryDel = "delete from #__send_email_member where emId in ($ids)";
			$database->setQuery($queryDel);
			$database->query();
		}
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Email deleted successfully!";
		$this->setRedirect($referer, $success);	
	}
	
	public function saveorder()
	{
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$orders = JRequest::getVar('order', array(), '', 'array');
				
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		JLog::add('SmAdminControllerEmails::saveorder() is deprecated. Function will be removed in 4.0', JLog::WARNING, 'deprecated');

		// Get the arrays from the Request
		$order = $this->input->post->get('order', null, 'array');
		$originalOrder = explode(',', $this->input->getString('original_order_values'));

		// Make sure something has changed
		if (!($order === $originalOrder))
		{
			parent::saveorder();
		}
		else
		{
			// Nothing to reorder
			$this->setRedirect(JRoute::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));

			return true;
		}
	}
	/*new function added for sharing emails to the member */

	public function sendemails()
	{
		
		$database = JFactory::getDBO();
		$file = $_POST['share'];
		$member = $_POST['member_id'];
		$supplier_id=$_POST['supplier'];
		/* adding user id for supplier */
		$querySelect = "select user_id from #__mt_links where link_id='$supplier_id'";
		$database->setQuery($querySelect);
		$cmpnyDtl = $database->loadObject();
		$user_id=$cmpnyDtl->user_id;
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$getFileId = implode(",",$file);
        if(empty($file)  || empty($member))
        {
		
		$this->setRedirect($_SERVER["HTTP_REFERER"]."#tabs-6", JFactory::getApplication()->enqueueMessage('Please select emails and Members ', 'error'));
	   }
	   else
	   {
	   	$q1 = "select em.* from #__email_member as em where em.id in (".$getFileId.")";
		$database->setQuery( $q1 );
		$getFile = $database->loadObjectList();
		for($l=0; $l<count($member); $l++){

			for($m=0; $m<count($getFile); $m++)
			{

				$fid = $getFile[$m]->id;
				$docType=$getFile[$m]->document_type;
				$mid = $member[$l];
				$querySelect = "select * from #__send_email_member where emId='$fid' and memberId='$mid' ";
				$database->setQuery($querySelect);
				$res = $database->loadObject();

				if(!empty($res)){
					$getFileId = $res->emId;
					$getFileNameId = $res->fileNameId;
					$getMemberId = $res->memberId;
					
					 $getFLDtl = "select ems.*,em.*,mt.link_name from #__send_email_member as ems,#__email_member as em,#__mt_links as mt where ems.emId='$getFileId' and ems.memberid='$getMemberId' and mt.link_id='$getMemberId' and em.id=ems.emId";
					$database->setQuery( $getFLDtl );
					$resFLDtl = $database->loadObject();
					$fileNameDtl = $resFLDtl->subject;
				    $cmpNameDtl = $resFLDtl->link_name;
					$msgs=" '$fileNameDtl' is already send to Member '$cmpNameDtl' ";
					$this->setRedirect($referer."#tabs-6",$msgs);

				} else {

				
					 $query = "INSERT INTO #__send_email_member (`id`, `emId`, `memberId`, `approve`, `approveDate`) VALUES ('', '$fid ', '$mid', '0', '')";
				
					$database->setQuery($query);
					$database->query();
					$this->setRedirect($referer."#tabs-6", "Email send successfully ");

				
					
				}

			
			}
		}

	   } 


		
	}
	//new function for fetching child table data
	public function getchildtables()
	{
		 $id = $_REQUEST['email_id'];
		$model =& $this->getModel('emails');
		$filesDtl = $model->getMemberDetails($id);
		if(!empty($filesDtl))
		{
		$html= "<td colspan='9'>
					
		              <table width='100%' class='table-bordered'>
		              <tr>";
		              $html.="<th style='background-color: #ccc;'>Sr No</th>";
		              $html.="<th style='background-color: #ccc;'>Sent To</th>";
		              //$html.="<th style='background-color: #ccc;'>Notify Email</th>";
		              //$html.="<th style='background-color: #ccc;'>Delete from View</th></tr>";
		              
		              $k=1;
		              foreach ($filesDtl as $value)
		              {
		              	

		             
		    $html.= "<tr>
		             <td>".$k."</td>";
		      
            $html.=   "<td>".$value->link_name."</td>";

					//$html.=	"<td><center>center</center></td>";
		            //$html.=	"<td>test</td>";


		              	$html.="</tr>"; 

		                $k++; }
		              $html.="</table>
		               </td>";

		           
		            }
		echo $html;

		die;
	}
}

