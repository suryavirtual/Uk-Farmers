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
class UsersControllerSuppliersendfiles extends UsersController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
            public function getsupplierdetailsfinal(){
		
		$filetype="";
		$folderlocation="";

		$database = JFactory::getDBO();
		$user = JFactory::getUser();
		$usr_id = $user->id;
		$company_id=$_POST["comp_id"];
		$description =addslashes($_POST["file_description"]);
		$type = $_POST["sendfile1type"];
		$queryCmp = "select folder_name from #__folder where link_id='$company_id'";
	        $database->setQuery($queryCmp);
	        $rows = $database->loadObject();
	        $cmpnames = $rows ->folder_name;
	   /* if (preg_match('/\s/',$cmpnames) )
			   {
			   	$cmpnames = str_replace(' ','_', $cmpnames);
			   }
			   else
			   {
			   	$cmpnames = $cmpnames;
			   }
			   */

		/* code ends here */
		
		 $usernames=$user->name;
		switch ($type) {
			case 1:
				$filetype="marketing";
				break;
				case 2:
				$filetype="offers";
				break;
				case 3:
				$filetype="pricelists";
				break;
				case 4:
				$filetype="terms";
				break;
		
			
		}
		
		$folderlocation="/uf_data/suppliers/".$cmpnames ."/".$filetype."/";
		$expiry1 = date_create($_POST["expiry"]);
		$expiry= date_format($expiry1,"Y-m-d");
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
				
		if(count($_FILES['supplier_files']['name'] > 0)){
			$allowed =  array('pdf','xls','csv','doc','docx','ppt','xlsx');
			for($l=0; $l<count($_FILES['supplier_files']['name']); $l++){
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

				for($m=0; $m<count($_FILES['supplier_files']['name']); $m++)
				{
					$filename=$_FILES['supplier_files']['name'][$m];
                                         $lastDot = strrpos($filename, ".");
                                         $filenames = str_replace(".", "", substr($filename, 0, $lastDot)) . substr($filename, $lastDot);
					$fileName[] = $filenames;
					move_uploaded_file($_FILES['supplier_files']['tmp_name'][$m], JPATH_BASE.$folderlocation.$filenames);
				} 

				$fname = implode("|",$fileName);
				$fname="uf_data/suppliers/".$cmpnames."/".$filetype."/".$fname;
				$query = "INSERT INTO #__supplier_files (id, userid,comp_id, filename, description, type, uploaded, expiry, approved) VALUES 
					('', '$usr_id','$company_id','$fname', '$description', '$type', now(), '$expiry', '0')";

				$database->setQuery( $query );
				$database->query();
				$last_insert_id=$database->insertid();
                               /*code commented for email notify */
			       // $this->notificationmailadmin($last_insert_id,$usr_id);
				
				$q1 = "select * from #__supplier_files order by id desc limit 0,1";
				$database->setQuery( $q1 );
				$result=$database->loadObject();
				$fid = $result->id;
				for($n=0; $n<count($fileName); $n++){
					$fname = $fileName[$n];
					$qf = "insert into #__supplier_file_name (id, fid, fname) values ('', '$fid', '$fname')";
					$database->setQuery( $qf );
					$database->query();
				}
				
				$this->setRedirect($referer."#tabs-2", "File Uploaded Successfully.");
			}
		}
	}

	public function notificationmailadmin($last_insert_id,$user_id)
	{
		$db = JFactory::getDbo();
		$Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$user_id;
	
		$db->setQuery($Query);
		 $results=$db->loadObject();
		 $mName = $results->name;
		 $mEmail = $results->email;

		$body = "Hi Admin,<br><br> New file has been added , Please check this file.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
                $to = "enquiries@united-farmers.org.uk";
		//$to="matt.hooker@adaptris.com";
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
	
/*new function added for sending mail notification for all member user */
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
                               /*merge code start from here */
                       
                               						   /* supplier query for fetching supplier details */
                $supQuery = "SELECT mt.link_name,u.email from #__mt_links as mt
                left join #__users as u on(u.id=mt.user_id) where  link_id='$cmp_id'";
                $database->setQuery($supQuery);
                $supQueryresults=$database->loadObject();


		       $sName = $supQueryresults->link_name;
                        $sEmail = $supQueryresults->email;
           
                           $Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$member_userid;
                           $database->setQuery($Query);
                           $results=$database->loadObject();
                                $mName = $results->name;
                                $mEmail = $results->email;
                                $this->notificationmail($sName, $sEmail, $mName, $mEmail);
                $query2 = "update #__supplier_files SET lastnotification =now() WHERE id=".$fileId;
                $database->setQuery($query2);
                $database->query();
						   /* merge code ends here*/	
		    	}

		    	
		    	
		    	
		    }
		    

		  


	}

	public function notificationmail($sName, $sEmail, $mName, $mEmail, $tName)
	{
		$body = "Hi ".$mName.",<br><br> your file has been updated , Please check this file.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		//$to="pratishthasingh@virtualemployee.com";
		$to="matt.hooker@adaptris.com";
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
	

	}

	/* new function code ended here */

	public function deleteRequestFiles(){
		$database = JFactory::getDBO();
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$fid = $_REQUEST['id'];
				
		 $query = "update #__files_to_members set deleteRequest='1' where id='$fid'";
		$database->setQuery( $query );
		$database->query();
		$this->setRedirect($referer, "File deleted successfully.");
	}							
				
       public function markviewedfiles()
	{	
		$user_id=$_REQUEST['user_id'];
		$com_id=$_REQUEST['comp_id'];
		$file_id=$_REQUEST['fileid'];
		$viewed=1;
		$database = JFactory::getDBO();
		//$query = "update #__files_to_members set viewEmail='1' where id in ($fid)";
	    $query1="select * from #__supplier_file_viewed where user_id='$user_id' and fid='$file_id' and comp_id='$com_id'";
        $database->setQuery($query1);
        $rows_existing = $database->loadObject();
        if(empty($rows_existing))
     {
		$query = "insert into #__supplier_file_viewed values('','$user_id','$file_id','$com_id','$viewed')";
		$database->setQuery( $query );
		$database->query();
		echo "hi";
		die();
	}
	echo "sucess";
		exit;
			
	}
	    


					
     public function unsharee()
    {
	$database = JFactory::getDBO();
	$rid=trim($_POST['rid']);
	$mid=join(',',trim($_POST['rid']));
	if($rid){
		$query = "DELETE FROM member_files WHERE fileid=".$rid." AND memberid IN(".$mid.")";
		$database->setQuery( $query );
		if (!$result=$database->query()) {
			echo $database->stderr();
			return false;
		}
		exit;
	}else{
		echo "error";
	}
    }
	/* new function for checking not sent member files */
	public function checksentmembers()
	{


        	$database = JFactory::getDBO();
	    $query1="select sf.*,mt.link_name,u.email from jos_supplier_files as sf left join jos_mt_links as mt on(mt.link_id=sf.comp_id)
	    left join jos_users as u on(u.id=mt.user_id) where sf.approved='0' and sf.id NOT IN(SELECT fileId FROM jos_files_to_members )order by mt.link_name";
           $database->setQuery($query1);
           $rows_existing = $database->loadObjectList();
      

      
        
        $html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
		        <tr><th>Supplier</th><th>File Name</th><th>Member Email Address</th></tr>";
		foreach($rows_existing as $value)
		{
			 $today = strtotime(date("Y-m-d"));
			 $upload_date = strtotime($value->uploaded);
			//die("stop here for testing time");
			if ($upload_date < $today) 
			   {

		         $supplier_names=$value->link_name;
			     $file_name=$value->description;
                 $member_email=$value->email;
                 $member_name=$value->Membername; 
                 $html.="<tr><td>".$supplier_names."</td>";
                 $html.="<td>".$file_name."</td>";
                 $html.="<td>".$member_email."</td></tr>";
                }
                 

		}
		$html.= "</table>";
		$body = $html;
		$body = "Dear Admin,<br><br> suppliers has added  their Files but not sent to the member. Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view.<br><br>".$html."<br>Thanks<br>UNF Team";
		//$to = "enquiries@united-farmers.org.uk";
		$sEmail = "enquiries@united-farmers.org.uk";
                $sName = "admin";
		$to="ve.akanshajaiswal@gmail.com";
		//$to="suryakant@virtualemployee.com";
		//$to="matt.hooker@adaptris.com";
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
                

        }
         /* new function for send monthly notification */
       /* new code commented for mail notify */
       /*   public function monthlytermexpiry()
        {  
            $database = JFactory::getDBO();
            $query1="select tr.*,mt.link_name,u.email from #__structured_terms as tr left join #__mt_links as mt on(mt.link_id=tr.supplierUserId)
            left join #__users as u on(u.id=mt.user_id) where tr.status='1' and tr.validTo between CURDATE() and date_add(CURDATE(), interval 1 month) order by mt.link_name";
            $database->setQuery($query1);
            $rows_existing = $database->loadObjectList();
            $this->monthlytermexpirymember($rows_existing);
            $html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
                        <tr><th>Supplier</th><th>Term Name</th><th>Expiry Date</th></tr>";
                foreach($rows_existing as $value)
                {

                 $term_id=$value->termId;
                 $supplier_names=$value->link_name;
                 $supplier_email=$value->email;
                 $term_name=$value->termName;
                 $expiry=$value->validTo;	
                 $this->monthlytermexpirysupplier($supplier_names,$supplier_email,$term_name,$expiry);
                 $html.="<tr><td>".$supplier_names."</td>";
                 $html.="<td>".$term_name."</td>";
                 $html.="<td>".$expiry."</td></tr>";
                


                }
                $html.= "</table>";
                $body = $html;
                $body = "Dear Admin,<br><br> supplier terms that are going to expire at the end of this month. Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view.<br><br>".$html."<br>Thanks<br>UNF Team";
                
                //$to = "enquiries@united-farmers.org.uk";
                $sEmail = $supplier_email;
                $sName = "Admin";
                $to="ve.akanshajaiswal@gmail.com";
                //$to="suryakant@virtualemployee.com";
                //$to="matt.hooker@adaptris.com";
                $from = array($sEmail, $sName);
                $subject = "Monthly Suppliers Terms Expiry Notification";
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

        } */        
        
       /*new function for sending mails to the supplier */
         public function monthlytermexpirysupplier($supplier_names,$supplier_email,$term_name,$expiry)
        {
       
        	$html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
                        <tr><th>Supplier</th><th>Term Name</th><th>Expiry Date</th></tr>";
            $html.="<tr><td>".$supplier_names."</td>";
            $html.="<td>".$term_name."</td>";
            $html.="<td>".$expiry."</td></tr>";
            $html.= "</table>";
             $body = "Dear ".$supplier_names.",<br><br> Your United Farmers Terms are expiring shortly.  Please contact <a href='http://www.unitedfarmers.co.uk/'>United Farmers</a> with a view to update.<br><br>".$html."<br>Thanks<br>UNF Team";
                $sEmail = $supplier_email;
                $sName= "UNF ADMIN";
               // $to=$supplier_email;
                $to="ve.akanshajaiswal@gmail.com";
               
                $from = array($sEmail, $sName);
                $subject = "Monthly Supplier Terms Expiry Notification";
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
               
        	//die("for testing purpose new function");
        	

        }
      /*new function for sending mails to the member */
         public function monthlytermexpirymember($rows_existing)
        { 
               $database = JFactory::getDBO();
               foreach($rows_existing as $value)
                {
				
                 $term_id=$value->termId;
                 $supplier_names=$value->link_name;
                 $supplier_email=$value->email;
                 $supplier_id=$value->supplierUserId;
                 $term_name=$value->termName;
                 $expiry=$value->validTo;
                 /* new code for sending mails to member */
                 $query2="select tm.member_id,mt.link_name from #__structured_terms_to_members as tm left join jos_mt_links as mt on(mt.link_id=tm.member_id)
                 where tm.terms_id=".$term_id." ";
                 $database->setQuery($query2);
                 $rows_existing_member = $database->loadObjectList();
                 /*new code for fetching user mail */
                 foreach($rows_existing_member as $value)
                 {
                 	$member_id = $value->member_id;
                 	$member_name=$value->link_name;
                 	$query3="select u.id,u.name,u.email from #__users as u where FIND_IN_SET(".$member_id.",u.comp_list) ";
                    $database->setQuery($query3);
                    $rows_existing_member_users = $database->loadObjectList();
                    
                  foreach($rows_existing_member_users as $value)
                    {
                    	$user_id=$value->id;
                    	$user_name=$value->name;
                    	$user_email=$value->email;
                    	$query4="select * from #__member_access where userId=".$user_id." and memberId=".$member_id." and supplierId=".$supplier_id." ";
                        $database->setQuery($query4);
                        $rows_existing_member_users_permission = $database->loadObject();
                        $groupArr = array();
                        $groupArr=explode(",", $rows_existing_member_users_permission->docIds);

                        $html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
                        <tr><th>Supplier</th><th>Term Name</th><th>Expiry Date</th></tr>";
                        if(in_array('4',$groupArr))
                        {
                        $sentmail['member'][]=$member_name;
                        $sentmail['member_email'][]=$user_email;
                        $sentmail['supplier'][]=$supplier_names;
                        $sentmail['term_file'][]=$term_name;
                        $sentmail['expiry'][]=$expiry; 

                        /*new code for sending mail for members*/
                        
                       $html.="<tr><td>".$supplier_names."</td>";
                       $html.="<td>".$term_name."</td>";
                       $html.="<td>".$expiry."</td></tr>";
                       $html.= "</table>";
                      $body = "Dear ".$member_name.",<br><br> The following terms are close to expiry<br><br>".$html."<br>Thanks<br>UNF Team";
                      $sEmail = $supplier_email;
                      $sName=$supplier_names;
                      //$to=$user_email;
                      $to="ve.akanshajaiswal@gmail.com";
                      $from = array($sEmail, $sName);
                      $subject = "Monthly Member Alert Notification";
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

                        /*code ends here */
			
                   }



                    } 

                 }


           
             }
             

           $html= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
                   <tr><th>Member</th><th>Member Email</th><th>Supplier</th><th>Terms File</th><th>Expiry Date</th></tr>";   
             for($i=0;$i<count($sentmail['member']);$i++)
           {
        	 $html.="<tr><td>".$sentmail['member'][$i]."</td>";
             $html.="<td>".$sentmail['member_email'][$i]."</td>";
             $html.="<td>".$sentmail['supplier'][$i]."</td>";
             $html.="<td>".$sentmail['term_file'][$i]."</td>";
             $html.="<td>".$sentmail['expiry'][$i]."</td></tr>";
        	
           }
        $html.= "</table>";
         $body = "Dear Admin,<br><br> Your United Farmers Terms are expiring shortly.  Please contact <a href='http://www.unitedfarmers.co.uk/'>United Farmers</a> with a view to update.<br><br>".$html."<br>Thanks<br>UNF Team";

          //$to = "enquiries@united-farmers.org.uk";
                $sEmail = "enquiries@united-farmers.org.uk";
                $sName=  "UNF Admin";
                $to="ve.akanshajaiswal@gmail.com";
                //$to="suryakant@virtualemployee.com";
                //$to="matt.hooker@adaptris.com";
                $from = array($sEmail, $sName);
                $subject = "Monthly Members Terms Expiry Notification";
				# Invoke JMail Class
                $mailer1 = JFactory::getMailer();
                # Set sender array so that my name will show up neatly in your inbox
                $mailer1->setSender($from);
                # Add a recipient -- this can be a single address (string) or an array of addresses
                $mailer1->addRecipient($to);
                $mailer1->setSubject($subject);
                $mailer1->setBody($body);
                # If you would like to send as HTML, include this line; otherwise, leave it out
                $mailer1->isHTML(true);
                # Send once you have set all of your options
                $userParams = JComponentHelper::getParams('com_smadmin')->get('send_mail');
                if($userParams == "1")
                {
                $mailer1->send();
                }

        


       }
 
     
           public function sendfilestomember()
           {

		$database = JFactory::getDBO();
		$file = $_POST['share'];
		$member = $_POST['Box2'];
		$user_id = $_REQUEST['users_id'];
		$comp_id = $_REQUEST['comp_id'];
		
		$getFileId = implode(",",$file);
		 if(empty($file) && empty($member))
		{
		
		$this->setRedirect($_SERVER["HTTP_REFERER"]."#tabs-2", JFactory::getApplication()->enqueueMessage('Please select Files and Members', 'error'));
	    }
	    else
	    {
	    
		//$q1 = "select * from #__supplier_file_name where fid in (".$getFileId.")";
		$q1="select fn.*,sf.*,u.comp_list from #__supplier_file_name as fn 
		left join #__supplier_files as sf on(sf.id=fn.fid) 
		left join #__users as u on(sf.userid=u.id)  where fn.fid in (".$getFileId.")";
		$database->setQuery( $q1 );
		$getFile = $database->loadObjectList();
		
		for($l=0; $l<count($member); $l++){
			for($m=0; $m<count($getFile); $m++){
				$fid = $getFile[$m]->fid;
				$docType=$getFile[$m]->type;
				$fNameId = $getFile[$m]->id;
				$mid = $member[$l];
				$sup_id=$getFile[$m]->comp_list;
				
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
					$query = "insert into #__files_to_members (id, fileId, fileNameId, user_id,comp_id, memberId, approve, viewFile, deleteRequest, sentEmail, publishDate) values ('', '$fid', '$fNameId', '$user_id','$comp_id', '$mid', '0', '0', '0', '0', '')";
					$database->setQuery( $query );
					$result=$database->query();

					/*new code for sending mail to the all member user */

                    $this->checkmemberpermission($fid,$docType,$mid,$sup_id);
					/* code ends here */
				}
			}
		}
		$getFmsg = array_unique($message);
		$fmsg = implode(",<br>",$getFmsg);
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		if(!empty($fmsg)){
			$this->setRedirect($referer."#tabs-2", JFactory::getApplication()->enqueueMessage($fmsg, 'error'));
		} else {
			$this->setRedirect($referer."#tabs-2", "File sent successfully to UF Admin for approval.");
		}
	}
	}		


            





}
