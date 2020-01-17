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
class SmAdminControllerFile extends JControllerForm
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
		$fileId = $_REQUEST['id'];
		$cmp_id=$_REQUEST['jform']['cmpId'];
		$description=$_REQUEST['jform']['description'];
		$docType=$_REQUEST['jform']['type'];
		$expiry=$_REQUEST['jform']['expiry'];
		$supplier_files = $_REQUEST['jform']['filename'];
	
		if($_REQUEST['jform']['sentNotification']){
			$sentNotification = '1';
		} else {
			$sentNotification = '0';
		}
				
		$querySelect = "select * from #__mt_links where link_id='$cmp_id'";
		$database->setQuery($querySelect);
		$cmpnyDtl = $database->loadObject();
		$user_id=$cmp_id;
		
		/* fetching user id code ends here */
		
		if($task=='save'){
			$link=JURI::base().'index.php?option=com_smadmin&view=files';
		} else if ($task=='apply'){
			$link = JURI::getInstance($_SERVER['HTTP_REFERER']);
		}
		
		if(!empty($fileId))
		{
		    $qapprove = "select * from #__supplier_files where id='$fileId'";
		    $database->setQuery($qapprove);
		    $alldetails = $database->loadObject();
		    $approved=$alldetails->approved;
		    $added_by=$alldetails->added_by;
		    
			$qdel = "update #__supplier_file_name set fname='$supplier_files' where fid='$fileId'";

			$database->setQuery($qdel);
			$database->query();
			
			$qUpdate = "update #__supplier_files set userid='$user_id',comp_id='$cmp_id',filename='$supplier_files', description='$description', type='$docType', uploaded=now(), expiry='$expiry', approved='$approved', sentNotification='$sentNotification',added_by='$added_by' where id='$fileId'";
			
			$database->setQuery($qUpdate);
			$database->query();
			/* new query for checking checkbox */
			$querySelect = "select sentNotification from #__supplier_files where id='$fileId'";
		    $database->setQuery($querySelect);
		    $notificattionDtl = $database->loadObject();
		    $sentNotifications=$notificattionDtl->sentNotification;
                  /* code commented for mail functionality */
                    // $this->checkmemberpermission($fileId,$docType,$cmp_id);
			/* query ends here */
			
		} 
		else
		 {
			$insertFile = "insert into #__supplier_files (id, userid,comp_id,filename, description, type,uploaded, expiry, approved, viewed, ordering, sentNotification, lastnotification,added_by) VALUES ('', '$user_id','$cmp_id', '', '$description', '$docType', now(), '$expiry', '0', '0', '', '$sentNotification', '','1')";
			$database->setQuery($insertFile);
			$database->query();
			$last_insert_id=$database->insertid();
                        /* code commented for mail */
		//$this->notificationmailadmin($last_insert_id,$user_id);
		       
			
			$q1 = "select * from #__supplier_files order by id desc limit 0,1";
			$database->setQuery( $q1 );
			$result=$database->loadObject();
			$lastfId = $result->id;
				
			if(!empty($supplier_files))
			{
					
					$qInsert = "insert into #__supplier_file_name (id, fid, fname) VALUES ('', '$lastfId', '$supplier_files')";
					$database->setQuery( $qInsert );
					$database->query();
				$qfUpdate = "update #__supplier_files set filename='$supplier_files' where id='$lastfId'";
				$database->setQuery($qfUpdate);
				$database->query();
			}
		}
		
		if(!empty($fileId))
		{
			$success = "File Updated successfully!";
			$this->setRedirect($link, $success);
		} else {

		
			$this->setRedirect($link."&id=".$last_insert_id."#tabs-6",'File Added successfully!');
		}
		
		
	}
	/* new function for sending mail to admin once file added */
	public function notificationmailadmin($last_insert_id,$user_id)
	{
		$db = JFactory::getDbo();
		$Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$user_id;
	
		$db->setQuery($Query);
		 $results=$db->loadObject();
		 $mName = $results->name;
		 $mEmail = $results->email;

	
    	$body = "Hi Admin,<br><br> New file has been added by ".$mName." , Please check this file.<br><br>Thanks<br>UNF Team";
		$to = "ve.akanshajaiswal@gmail.com";
		//$to="matt.hooker@adaptris.com";
		//$to= $mEmail;
		$from = array($mEmail, $mName);
		$subject = "File added by ".$mName;
		
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
	

	}

	/*new function for identify users for mail  */
	public function checkmemberpermission($fileId,$docType,$cmp_id)
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
		    	$querypermission="SELECT docIds FROM #__member_access WHERE supplierId = '".$cmp_id."' AND userId = '".$member_userid."'";
		    	$database->setQuery($querypermission);
		    	$recive_permission=$database->loadColumn();
		    	$final_permission=explode(',', $recive_permission['0']);
		    	if(in_array($document_type,$final_permission))
		    	{
		/*merge code start from here */
		$Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$member_userid;

                $database->setQuery($Query);
                 $results=$database->loadObject();

    /* supplier query for fetching supplier details */
                $supQuery = "SELECT mt.link_name,u.email from #__mt_links as mt
                left join #__users as u on(u.id=mt.user_id) where  link_id='$cmp_id'";
                $database->setQuery($supQuery);
                $supQueryresults=$database->loadObject();

                                 $sName = $supQueryresults->link_name;
                               // $sEmail ="ve.akanshajaiswal@gmail.com";
                                $sEmail = $supQueryresults->email;
                                $mName = $results->name;
                                $mEmail = $results->email;
                                //$mEmail="surya@yopmail.com";

                                $this->notificationmail($sName, $sEmail, $mName, $mEmail);

                $query2 = "update #__supplier_files SET lastnotification =now() WHERE id=".$fileId;
                $database->setQuery($query2);
                $database->query();
							
		    	}
		    	
		    	
		    	
		    }

		    


	}




	/*function ends here */

	/* new function added for notification mail */

	public function notificationmail($sName, $sEmail, $mName, $mEmail)
	{
		$body = "Hi ".$mName.",<br><br> your file has been updated , Please check this file.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
               $to="ve.akanshajaiswal@gmail.com";
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
                 $userParams = JComponentHelper::getParams('com_smadmin')->get('send_mail');
		if($userParams == "1")
		{
		$mailer->send();	
		}
		//$mailer->send();

	}
	/* new funtion ended here */
	
}
