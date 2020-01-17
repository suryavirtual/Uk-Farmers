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
class SmAdminControllerFiles extends JControllerAdmin
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
	
	public function getModel($name = 'File', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
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
			$querySelect = "select * from #__supplier_files where id='$cids'";
		    $db->setQuery($querySelect);
		    $cmpnyDtl = $db->loadObject();
		     $sup_id=$cmpnyDtl->comp_id;
		     $fid=$cmpnyDtl->id;
		     $docType=$cmpnyDtl->type;
	             $checknotify=$cmpnyDtl->sentNotification;	    
		     $query = "UPDATE #__supplier_files SET approved = '".$value. "' WHERE id IN ( ".$cids." )";
			$db->setQuery( $query );
			$db->query();
	        	$querySelectmember = "SELECT memberId FROM jos_files_to_members where fileId='$cids' ";
		    $db->setQuery($querySelectmember);
		    $memDtl = $db->loadObjectList();
                    if(($value == "1") && ($checknotify == "1") )
		    {
		    foreach ($memDtl as $val)
		    {
		    $mid=$val->memberId;
                    /* code commented for email */
		    //$this->checkmemberpermission($fid,$docType,$mid,$sup_id);	
		    }
                   /* code commented for email */
		    //$this->notifyadmin($fid,$sup_id);
                    }
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		} 		
		$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view'); 
		$this->setRedirect($redirectTo); 
	}
  public function deleteview()
	{
		$database = JFactory::getDbo();
		$ids=$_REQUEST['cid'];
		$compid=$_REQUEST['memberid'];
		$queycompcid="select fileId,id from #__files_to_members WHERE id='$ids' and memberId='$compid' ";
		$database->setQuery($queycompcid);
		 $fileids = $database->loadObject();
		 $newfileids=$fileids->fileId;
		 $newids=$fileids->id;
		 $queryDel = "delete from #__supplier_file_viewed where fid='$newfileids' and comp_id='$compid'";
		 
		$database->setQuery($queryDel);
		$database->query();
		$query2 = "update #__files_to_members SET viewFile ='1' WHERE  id='$newids'";
		$database->setQuery($query2);
		$database->query();
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Files deleted successfully!";
		$this->setRedirect($referer, $success);

	}
	
 public function delete()
 {

  $database = JFactory::getDbo();
  $totalid=count($_REQUEST['cid']);
  $path = substr(JPATH_BASE,0,-13);
  $dir = $path."/upload/files/";
  
  if($totalid >1)
  {

   $ids = implode(",",$_REQUEST['cid']);
   $querySelect ="DELETE jos_supplier_files , jos_supplier_file_name,jos_files_to_members,jos_supplier_file_viewed
      FROM jos_supplier_files 
      left JOIN jos_supplier_file_name ON jos_supplier_files.id = jos_supplier_file_name.fid 
      left join jos_files_to_members on jos_supplier_files.id = jos_files_to_members.fileId 
      left join jos_supplier_file_viewed on jos_supplier_file_viewed.fid=jos_files_to_members.id 
      WHERE jos_supplier_files.id in ($ids)";
        $database->setQuery($querySelect);
  $database->query();
  $querySelect = "select * from #__supplier_files where id in ($ids)";
  $database->setQuery($querySelect);
  $filesDtl = $database->loadObjectList();

  }
  else
  {
  
  $id=$_REQUEST['cid']['0']; 
 //  $id=$_REQUEST['cid']; 

   $querySelect ="DELETE jos_supplier_files , jos_supplier_file_name,jos_files_to_members,jos_supplier_file_viewed
      FROM jos_supplier_files 
      left JOIN jos_supplier_file_name ON jos_supplier_files.id = jos_supplier_file_name.fid 
      left join jos_files_to_members on jos_supplier_files.id = jos_files_to_members.fileId 
      left join jos_supplier_file_viewed on jos_supplier_file_viewed.fid=jos_files_to_members.id 
      WHERE jos_supplier_files.id = '$id'";
        $database->setQuery($querySelect);
  $database->query();
  $querySelect = "select * from #__supplier_files where id = '$id'";
  $database->setQuery($querySelect);
  $filesDtl = $database->loadObjectList();
   

  }
  
  
  
  foreach($filesDtl as $filesDtls)
  {
   $fname = explode("|", $filesDtls->filename);
   $cnt = count($fname);
   if(count($fname) > 0){
    for($p=0; $p<count($fname); $p++){
     $getName = $fname[$p];
     if(file_exists($dir.$getName)){
      unlink($dir.$getName);
     }
    }    
   }
  }
  
  
  $referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
  $success = "Files deleted successfully!";
  $this->setRedirect($referer, $success);
 }

	public function deleteone()
    {
      $database = JFactory::getDbo();
      $id=$_REQUEST['cid']; 
	  $querySelect ="DELETE jos_supplier_files , jos_supplier_file_name,jos_files_to_members,jos_supplier_file_viewed
      FROM jos_supplier_files 
      left JOIN jos_supplier_file_name ON jos_supplier_files.id = jos_supplier_file_name.fid 
      left join jos_files_to_members on jos_supplier_files.id = jos_files_to_members.fileId 
      left join jos_supplier_file_viewed on jos_supplier_file_viewed.fid=jos_files_to_members.id 
      WHERE jos_supplier_files.id = '$id'";
      $database->setQuery($querySelect);
	  $database->query();
	  $referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
	  $success = "Files deleted successfully!";
	  $this->setRedirect($referer, $success);

    }
	
	public function publish(){
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$task = $this->getTask();
		
		if (count( $ids )){ 
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
			
			if($task=='publish'){
				$query = "UPDATE #__supplier_files SET approved = '1' WHERE id IN ( ".$cids." )";
			}
			if($task=='unpublish'){
				$query = "UPDATE #__supplier_files SET approved = '0' WHERE id IN ( ".$cids." )";
			}
			$db->setQuery( $query );
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view'); 
		$this->setRedirect($redirectTo); 
	}
	
	public function saveorder()
	{
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$orders = JRequest::getVar('order', array(), '', 'array');
				
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

		JLog::add('SmAdminControllerProducts::saveorder() is deprecated. Function will be removed in 4.0', JLog::WARNING, 'deprecated');

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


	/*new function added for send files to member */
	public function sendfilestomember()
	{
		$database = JFactory::getDBO();
		$file = $_POST['share'];
		$member = $_POST['Box2'];
		$supplier_id=$_POST['supplier_id'];
		$user_id=$_POST['supplier_id'];
		$comp_id=$_POST['supplier_id'];
		$getFileId = implode(",",$file);
		if(empty($file) && empty($member))
		{
		
		$this->setRedirect($_SERVER["HTTP_REFERER"]."#tabs-6", JFactory::getApplication()->enqueueMessage('Please select Files and Members', 'error'));
	    }
	    else
	    {

          $q1 = "select fn.*,sf.*  from #__supplier_file_name as fn 
		left join #__supplier_files as sf on(sf.id=fn.fid) 
		 where fn.fid in (".$getFileId.")";
		$database->setQuery( $q1 );
		$getFile = $database->loadObjectList();

		
		for($l=0; $l<count($member); $l++){
			for($m=0; $m<count($getFile); $m++){
				$fid = $getFile[$m]->fid;
				$docType=$getFile[$m]->type;
				$fNameId = $getFile[$m]->id;
				$mid = $member[$l];
				$noticficationcheck=$getFile[$m]->sentNotification;
				$sup_id=$getFile[$m]->comp_id;
                                $chkpublished=$getFile[$m]->approved;
				
				$querySelect = "select * from #__files_to_members where fileId='$fid' and fileNameId='$fNameId' and memberId='$mid' and user_id='$user_id'";
				$database->setQuery($querySelect);
				$res = $database->loadObject();
				
				if(!empty($res)){
					$getFileId = $res->fileId;
					$getFileNameId = $res->fileNameId;
					$getMemberId = $res->memberId;
					
					$getFLDtl = "select sf.description, mt.link_name
						from #__files_to_members as ftm, #__supplier_files as sf, #__mt_links as mt
						where ftm.fileId='$getFileId' and ftm.fileId=sf.id and ftm.fileNameId='$getFileNameId' and ftm.memberId='$getMemberId' and ftm.memberId=mt.link_id";
					$database->setQuery( $getFLDtl );
					$resFLDtl = $database->loadObject();
					$fileNameDtl = $resFLDtl->description;
					$cmpNameDtl = $resFLDtl->link_name;
					
					$message[] = "'$fileNameDtl' is already send to Member '$cmpNameDtl'";
				} else {
					$query = "insert into #__files_to_members (id, fileId, fileNameId, user_id,comp_id,memberId, approve, viewFile, deleteRequest, sentEmail, publishDate) values ('', '$fid', '$fNameId', '$user_id','$comp_id', '$mid', '0', '0', '0', '0', '')";
					$database->setQuery( $query );
					$result=$database->query();
					/*new code for sending mail to the all member user */
                     if(($noticficationcheck == "1") && ($chkpublished == "1") )
                     {
                     /* code commented for email */
                     // $this->checkmemberpermission($fid,$docType,$mid,$sup_id);	
                     $query = "UPDATE #__supplier_files SET lastnotification = now() WHERE id='$fid'";
                    
                     $database->setQuery( $query );
                     $database->query();
                     }
                    
                    
					/* code ends here */
				}
			}
		}
         
       $getFmsg = array_unique($message);
		$fmsg = implode(",<br>",$getFmsg);
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		if(!empty($fmsg)){
			$this->setRedirect($referer."#tabs-6", JFactory::getApplication()->enqueueMessage($fmsg, 'error'));
		} else {
			$this->setRedirect($referer."#tabs-6", "Files send successfully ");
		}

	    }
		
		
	}
	/* functions ends here */
	
	public function checkmemberpermission($fileId,$docType,$cmp_id,$supplier_id)
	{
		    $database = JFactory::getDbo();
            
            $querySelect1 ="select fm.*,u.name,u.id as user_id,ug.group_id from #__files_to_members as fm
             left join #__users as u on (fm.memberId=u.comp_list)
             left join #__user_usergroup_map as ug on(ug.user_id=u.id)
             where fm.fileId='$fileId'  and u.block='0' ";
		    $database->setQuery($querySelect1);
		    $notificattionDtl = $database->loadObjectList();
		    $document_type=intval($docType);
		    foreach ($notificattionDtl as $values) 
		    { 
		    	$member_userid=$values->user_id;
		    	
		    	$querypermission="SELECT docIds FROM #__member_access WHERE memberId = '".$cmp_id."' AND supplierId = '".$supplier_id."' AND userId = '".$member_userid."'";
		    	$database->setQuery($querypermission);
		    	$recive_permission=$database->loadColumn();
		    	$final_permission=explode(',', $recive_permission['0']);
		    	
		    	if(in_array($document_type,$final_permission))
		    	{
		$Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$member_userid;
		$database->setQuery($Query);
		$results=$database->loadObject();
		/* supplier query for fetching supplier details */
		$supQuery = "SELECT mt.link_name,u.email from #__mt_links as mt
		left join #__users as u on(u.id=mt.user_id) where  link_id='$supplier_id'";
		$database->setQuery($supQuery);
		$supQueryresults=$database->loadObject();

		/*code ends here */


		        $sName = $supQueryresults->link_name;
				$sEmail = $supQueryresults->email;
				$mName = $results->name;
				$mEmail = $results->email;
				 
				$this->notificationmail($sName, $sEmail, $mName, $mEmail);
	
	$query2 = "update #__supplier_files SET lastnotification =now() WHERE id=".$fileId;
		$database->setQuery($query2);
    	$database->query();


	/*merging code ends here */		
		    	}	
		    	
		    }

	}

	/*function ends here */


	/*new function for notification mail for enquiry admin */
public function notifyadmin($fileid,$cmp_id)
	{
		$db = JFactory::getDbo();
		//echo "termid".$termId."compid".$cmp_id."<br>";
	  $querysupp="select link_name from jos_mt_links where link_id='$cmp_id'";
	  $db->setQuery($querysupp);
	  $supp_results=$db->loadObject();
	  $supp_names=$supp_results->link_name;


        $Query ="select fm.*,sf.description,mt.link_name as Suppliername,u.email,mt1.link_name as Membername,mt1.user_id as memberuserid  from #__files_to_members as fm
        left join #__supplier_files as sf on(sf.id=fm.fileId)
        inner join jos_mt_links as mt on(mt.link_id=sf.comp_id)
        inner join jos_mt_links as mt1 on(mt1.link_id=fm.memberId)
        left join jos_users as u on(u.id=mt1.user_id) where fm.fileId='$fileid'";
		$db->setQuery($Query);
		$results=$db->loadObjectList();

		$html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
		        <tr><th>Supplier</th><th>File Name</th><th>Member Email Address</th><th>Member</th></tr>";
		foreach($results as $value)
		{
		         $supplier_names=$value->Suppliername;
			     $file_name=$value->description;
                 $member_email=$value->email;
                 $member_name=$value->Membername; 
                 $html.="<tr><td>".$supplier_names."</td>";
                 $html.="<td>".$file_name."</td>";
                 $html.="<td>".$member_email."</td>";
                 $html.="<td>".$member_name."</td></tr>";

		}
		$html.= "</table>";
		$body = $html;
		$body = "Dear Admin,<br><br> ".$supp_names." have published their Files. Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view.<br><br>".$html."<br>Thanks<br>UNF Team";
		//$to = "enquiries@united-farmers.org.uk";
		$sEmail = $supplier_names;
		//$to="pratishthasingh@virtualemployee.com";
		$to="ve.akanshajaiswal@gmail.com";
		//$to="admin@united-farmers.org.uk";
		$from = array($sEmail, $sName);
		$subject = "Notificiation mail for pulbished Files";
		
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
		//$mailer->send();


	}


	/*new function ends here */
	public function notificationmail($sName, $sEmail, $mName, $mEmail, $tName)
	{
		$body = "Dear ".$mName.",<br><br> Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view recently uploaded file from (".$sName.").<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		$to="ve.akanshajaiswal@gmail.com";
	       // $to=$sEmail;
		$from = array($sEmail, $sName);
		$subject = "Supplier File Published ";
		
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
		//$mailer->send();

	}
	/* new funtion ended here */

/* new function for child table in terms */
	public function getchildtable()
	{
		$id = $_REQUEST['file_id'];
		$model =& $this->getModel('files');
		$filesDtl = $model->getFileDetails($id);
		if(!empty($filesDtl))
		{
		$html= "<td colspan='9'>
					
		              <table width='100%' class='table-bordered'>
		              <tr>
		              <th style='background-color: #ccc;'>Sent To</th>
		              <th style='background-color: #ccc;'>Viewed By</th>";
		              //$html.="<th style='background-color: #ccc;'>Notify Email</th>";
		              $html.="<th style='background-color: #ccc;'>Delete from View</th>
		              </tr>";
		              $k=0;
		              foreach ($filesDtl as $value)
		              {
                          $deletterm="";
		             
		    $html.= "<tr>
		              	<td>".$value->link_name."</td>";
		              	$member = $model->getMemberDetails($value->memberId, $id);
						 $totalusers=count($member);
						 if($totalusers==0)
						{
							$share = "Not Viewed";
						} else {
							$share = "<a class='inline' href='#inline_member2".$k."'>Users</a>";
						} 
            $html.=   "<td>".$share."</td>";
                        $checkdelete=$model->getdelerecord($value->memberId,$id);
		              	$deletterm=$checkdelete->viewFile;
                     if($deletterm =="0")
                     {
                    $delete ="<a href='index.php?option=com_smadmin&task=files.deleteview&cid=".$value->fileviewid."&memberid=".$value->memberId."'>Delete<a>";
                     }
                     else
                     {
                    $delete="Deleted";
                     } 
                     if($value->email_sent == "0"):
                      $notify="<a href='index.php?option=com_smadmin&task=tmembers.notify&cid=". $value->id."'><img src='".JURI::root()."/administrator/templates/ukfarmer/images/admin/disabled.png' alt=''></a>";
                       else:
                         $notify="<img src='".JURI::root()."/administrator/templates/ukfarmer/images/admin/tick.png' alt=''>";
                             endif;


					//$html.=	"<td><center>".$notify."</center></td>";
		            $html.=	"<td>".$delete."</td>";


		              	$html.="</tr>";
		              	    	 if($totalusers > 0) { 
				$html.="<tr>
					<td colspan='5'  style='padding:0px !important; border:0px;'>
						<div style='display:none'>
							<div id='inline_member2".$k."'>
								<table width='100%' border='1'>";

									$html.="<tr><th>S No</th><th>username</th><th>Email</th></tr>";
								    $m=1; 
									foreach($member as $users):
										$html.="<tr>
										<td>".$m."</td>
                             			<td>".$users->name."</td>
                             			<td>".$users->email."</td>
                             			</tr>";
									 $m++; endforeach;
								$html.="</table>
							</div>
						</div>
					</td>
				</tr>";
				 } 

		                $k++; }
		              $html.="</table>
		               </td>";

		           
		            }
		echo $html;

		die;
	}
	/*function ends here */

	/*new code ends here */


}
