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
class SmAdminControllerTerms extends JControllerAdmin
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
	
	public function getModel($name = 'Term', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
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
		if (count( $ids ))
		{ 
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
		/* new code for fetching company id */
		$querySelect = "select * from #__structured_terms where termId='$cids'";
		$db->setQuery($querySelect);
		$cmpnyDtl = $db->loadObject();
		$comp_id=$cmpnyDtl->supplierUserId;
                $checknotify=$cmpnyDtl->sentNotification;

	   if(($value == "1") && ($checknotify == "1") )
		{
                /* code commented for email notify */
		  //$this->checkmemberpermission($cids,$comp_id);
		  //$this->notifyadmin($cids,$comp_id);
                  //$this->notifysupplier($cids,$comp_id);

		}


			/*code ends here */
			$query = "UPDATE #__structured_terms SET status = '".$value. "' WHERE termId IN ( ".$cids." )";
			$db->setQuery( $query );
			$db->query();

				/*unpublish code start from here */
		if($value == "0")
		{
              $query1 = "UPDATE #__structured_terms SET unpublished = '1' WHERE termId IN ( ".$cids." )";
			  $db->setQuery( $query1 );
			  $db->query();

			  $query2="select unpublishedcounter from #__structured_terms WHERE termId IN ( ".$cids." ) ";
			  $db->setQuery( $query2 );
			  $db->query();
			  $rows = $db->loadObject();
	          $unpublishedcounter = $rows->unpublishedcounter;
	          $counter=$unpublishedcounter+1;
	          $query3 = "UPDATE #__structured_terms SET unpublishedcounter = '".$counter."' WHERE termId IN ( ".$cids." )";
			  $db->setQuery( $query3 );
			  $db->query();
	         
          
		}
		else
		{
			$query4 = "UPDATE #__structured_terms SET unpublished = '0' WHERE termId IN ( ".$cids." )";
			  $db->setQuery( $query4 );
			  $db->query();

		}
		/*unpublish code ends here*/
 
			
			
			if (!$db->query()) 
			{
				$this->setError($this->_db->getErrorMsg());
				return false;
			}

		
		}
		 		
		$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view'); 
		$this->setRedirect($redirectTo); 
	}
	
	public function delete(){
		$database = JFactory::getDbo();
		$totalid=count($_REQUEST['cid']);
                if($totalid>1)
		{
		$ids = implode(",",$_REQUEST['cid']);
		$querySelect = "select termsFile from #__structured_terms where termId in ($ids)";
		
		$database->setQuery($querySelect);
		$termsDtl = $database->loadObjectList();
		}
		else
		{
              // $ids=$_REQUEST['cid'];
               $ids=$_REQUEST['cid']['0'];
               $querySelect = "select termsFile from #__structured_terms where termId='$ids'";
		
		$database->setQuery($querySelect);
		$termsDtl = $database->loadObjectList();
		}

		/* new code for moving file to archive folder */
		$querySelect = "select termsFile from #__structured_terms where termId in ($ids)";
		
		$database->setQuery($querySelect);
		$termsDtl = $database->loadObjectList();
		foreach($termsDtl as $termsDtls)
		{
		   $oldPath=$_SERVER['DOCUMENT_ROOT']."/".$termsDtls->termsFile;
		   $newPath = $_SERVER['DOCUMENT_ROOT']."/uf_data/archive/";
			$filename= end(explode('/', $termsDtls->termsFile));
			if(file_exists($oldPath))
			{
				
				rename($oldPath,$newPath."/".$filename);
			}
		}
		/* code ends here*/

     if($totalid>1)
		{
			
	  $querySelect ="DELETE jos_structured_terms , jos_structured_terms_to_members
      FROM jos_structured_terms 
      left JOIN jos_structured_terms_to_members ON jos_structured_terms.termId = jos_structured_terms_to_members.terms_id 
      WHERE jos_structured_terms.termId in ($ids)";
        $database->setQuery($querySelect);
		$database->query();

		}
		else
		{

	   $querySelect ="DELETE jos_structured_terms , jos_structured_terms_to_members
      FROM jos_structured_terms 
      left JOIN jos_structured_terms_to_members ON jos_structured_terms.termId = jos_structured_terms_to_members.terms_id 
      WHERE jos_structured_terms.termId = '$ids'";
        $database->setQuery($querySelect);
		$database->query();

		}
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Terms deleted successfully!";
		$this->setRedirect($referer, $success);	
	}
    
    public function deleteone()
    {
    	
    	$database = JFactory::getDbo();
    	$ids=$_REQUEST['cid'];

    	$querySelect ="DELETE jos_structured_terms , jos_structured_terms_to_members
      FROM jos_structured_terms 
      left JOIN jos_structured_terms_to_members ON jos_structured_terms.termId = jos_structured_terms_to_members.terms_id 
      WHERE jos_structured_terms.termId = '$ids'";
        $database->setQuery($querySelect);
		$database->query();
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Terms deleted successfully!";
		$this->setRedirect($referer, $success);	

    }
	/* function added for deleteview*/
	public function deleteview()
	{
		$database = JFactory::getDbo();
		 $ids=$_REQUEST['cid'];
		 $compid=$_REQUEST['memberid'];
		 /*new query for fetching new term id */
		$querySelectid = "select terms_id from #__structured_terms_to_members where id ='$ids'";
		$database->setQuery($querySelectid);
		$termsDtlsid = $database->loadObject();
	    $newtermid=$termsDtlsid->terms_id;
		
		/* query ends here */
		
		$queryDel = "delete from #__supplier_term_viewed where tid='$newtermid' and comp_id='$compid'";
		$database->setQuery($queryDel);
		$database->query();
		$query2 = "update #__structured_terms_to_members SET viewTerm ='1' WHERE member_id='$compid' and id='$ids'";
		$database->setQuery($query2);
		$database->query();
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Files deleted successfully!";
		$this->setRedirect($referer, $success);

	}
	
	public function publish()
	{
		

		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$task = $this->getTask();
		
		if (count( $ids )){ 
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
			
			if($task=='publish'){
				$query = "UPDATE #__structured_terms SET status = '1' WHERE termId IN ( ".$cids." )";
			}
			if($task=='unpublish'){
				$query = "UPDATE #__structured_terms SET status = '0' WHERE termId IN ( ".$cids." )";
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
	public function sendtermstomember()
   {
   	$database = JFactory::getDbo();
	$termId = $_POST['share'];
	$memberId = $_POST['member_id'];
	$user_id=$_POST['supplier'];
	$url=$_POST['url'];
	if(empty($termId) && empty($memberId)){
		$link = $url;
		$this->setRedirect($_SERVER["HTTP_REFERER"], JFactory::getApplication()->enqueueMessage('Please select Terms and Members', 'error'));
	} else {
		foreach($termId as $termIds){
                $querySelectterms = "select * from #__structured_terms where termId='$termIds'";
				$database->setQuery($querySelectterms );
				$resterm = $database->loadObject();
                $chknotify=$resterm->sentNotification;
                $chkpublish=$resterm->status;
			foreach($memberId as $memberIds){
				$querySelect = "select * from #__structured_terms_to_members where terms_id='$termIds' and member_id='$memberIds' and user_id='$user_id'";
				$database->setQuery($querySelect);
				$res = $database->loadObject();
				
				if(!empty($res)){
					$getTermId = $res->terms_id;
					$getMemberId = $res->member_id;
					
					$getTMDtl = "select st.termName, mt.link_name 
						from #__structured_terms_to_members as stm, #__structured_terms as st, #__mt_links as mt 
						where stm.terms_id='$getTermId' and stm.terms_id=st.termId and stm.member_id='$getMemberId' and stm.member_id=mt.link_id";
					$database->setQuery($getTMDtl);
					$resTMDtl = $database->loadObject();
					$trmNameDtl = $resTMDtl->termName;
					$cmpNameDtl = $resTMDtl->link_name;
					
					$message[] = "'$trmNameDtl' is already send to Member '$cmpNameDtl'";
				} else {
					 $query = "insert into #__structured_terms_to_members(id, terms_id, user_id, member_id, updated_on, approved, email_sent, viewTerm, deleteRequest) values 
				('', '$termIds', '$user_id', '$memberIds', now(), '1', '0', '0', '0')";
				
					$database->setQuery($query);
					$database->query();
					/* new code start from  here for terms mail */
                      if(($chknotify == "1") && ($chkpublish == "1") )
                     {
                     /* code commented for email notify */
                     //$this->checkmemberpermission($termIds,$user_id);
                     $query = "UPDATE #__structured_terms SET lastnotification = now() WHERE termId='$termIds'";
                     $database->setQuery( $query );
                     $database->query();
                    }
		        /* new code ends here */
				}
			}
		}
		$fmsg = implode(",<br>",$message);
		
		$link = $url;
		$success = "Term successfully Added!!!";
		
		if(!empty($fmsg)){
			$this->setRedirect($_SERVER["HTTP_REFERER"], JFactory::getApplication()->enqueueMessage($fmsg, 'error'));
		} else {
			
			$this->setRedirect($_SERVER["HTTP_REFERER"], $success);
		}
	}
   }
    /* mail function for sending terms */

   	public function checkmemberpermission($termId,$cmp_id)
	{
		    $database = JFactory::getDbo();
            
            $querySelect1 ="select tm.*,u.name,u.id as user_id,ug.group_id from #__structured_terms_to_members as tm
             left join #__users as u on (tm.member_id=u.comp_list)
             left join #__user_usergroup_map as ug on(ug.user_id=u.id)
             where tm.terms_id='$termId'  and u.block='0' ";
		    $database->setQuery($querySelect1);
		    $notificattionDtl = $database->loadObjectList();

		    foreach ($notificattionDtl as $values) 
		    { 
		    	$member_userid=$values->user_id;
		    	$querypermission="SELECT docIds FROM #__member_access WHERE supplierId = '".$cmp_id."' AND userId = '".$member_userid."'";
		    	$database->setQuery($querypermission);
		    	$recive_permission=$database->loadColumn();
		    	$final_permission=explode(',', $recive_permission['0']);
		    	if(in_array(4,$final_permission))
		    	{
		    		
		    		//$this->notification($member_userid,$fileId,$cmp_id);
                            /*merge code start from here */
                 $Query = "SELECT u.email,u.name  FROM #__users as u  where id =".$member_userid;
                $database->setQuery($Query);
                $results=$database->loadObject();

                /* supplier query for fetching supplier details */
                $supQuery = "SELECT mt.link_name,u.email from #__mt_links as mt
                left join #__users as u on(u.id=mt.user_id) where  link_id='$cmp_id'";
                $database->setQuery($supQuery);
                $supQueryresults=$database->loadObject();


                /*code ends here */

                       $sName = $supQueryresults->link_name;
                                //$sEmail = 'matt.hooker@adaptris.com';
                                $sEmail = $supQueryresults->email;
                                $mName = $results->name;
                                $mEmail = $results->email;
                                //$tName = $results->termName;
                                $this->notificationmail($sName, $sEmail, $mName, $mEmail);
								
	/* merge code ends here */	
		    	}
		    	
		    }

		   
	}
	/* new function for sending mail to the member for just published files */
 public function notificationmember($comp_id,$cids)
 {
 	//echo "companyid".$comp_id."termids".$cids;
 	$database = JFactory::getDbo();

            
            $querySelect1 ="SELECT mt.link_name,u.email,jt.termName,jt.validTo  FROM jos_structured_terms as jt
                           left join jos_mt_links as mt on(mt.link_id=jt.supplierUserId)
                            left join jos_users as u on(u.id=mt.user_id)
                             where jt.termId='$cids' ";
		    $database->setQuery($querySelect1);
		    $notificattionDtl = $database->loadObject();
		    echo "<pre>";
		    print_r($notificattionDtl);
 	die("stop here for testingsssssssssssssssss");

 }


	/* new function ends here */
	public function notifyadmin($termId,$cmp_id)
	{
		$db = JFactory::getDbo();
		//echo "termid".$termId."compid".$cmp_id."<br>";
	  $querysupp="select link_name from jos_mt_links where link_id='$cmp_id'";
	  $db->setQuery($querysupp);
	  $supp_results=$db->loadObject();
	  $supp_names=$supp_results->link_name;
        $Query ="select tm.*,jt.termName,mt.link_name as Suppliername,u.email,mt1.link_name as Membername,mt1.user_id as memberuserid  from #__structured_terms_to_members as tm
        left join jos_structured_terms as jt on(jt.termId=tm.terms_id)
        inner join jos_mt_links as mt on(mt.link_id=jt.supplierUserId)
        inner join jos_mt_links as mt1 on(mt1.link_id=tm.member_id)
        left join jos_users as u on(u.id=mt1.user_id) where jt.termId='$termId'";
		$db->setQuery($Query);
		$results=$db->loadObjectList();
		$html= "<table width='100%' border='1'>
		        <tr><th>Supplier</th><th>Terms Name</th><th>Member Email Address</th><th>Member</th></tr>";
		foreach($results as $value)
		{
		         $supplier_names=$value->Suppliername;
			     $term_name=$value->termName;
                 $member_email=$value->email;
                 $member_name=$value->Membername; 
                 $html.="<tr><td>".$supplier_names."</td>";
                 $html.="<td>".$term_name."</td>";
                 $html.="<td>".$member_email."</td>";
                 $html.="<td>".$member_name."</td></tr>";

		}
		$html.= "</table>";
		$body = $html;
		$body = "Dear Admin,<br><br> ".$supp_names." have published their Terms. Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view.<br><br>".$html."<br>Thanks<br>UNF Team";
		//$to = "enquiries@united-farmers.org.uk";
		$sEmail = $supplier_names;
		$to="ve.akanshajaiswal@gmail.com";
	       //$to="admin@united-farmers.org.uk";
		$from = array($sEmail, $sName);
		$subject = "Notificiation mail for pulbished terms";
		
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
        
       /*new function for notify the supplier */
		public function notifysupplier($termId,$cmp_id)
		{
		       $db = JFactory::getDbo();
                //echo "termid".$termId."compid".$cmp_id."<br>";
          $querysupp="select link_name from jos_mt_links where link_id='$cmp_id'";
          $db->setQuery($querysupp);
          $supp_results=$db->loadObject();
          $supp_names=$supp_results->link_name;
          $Query ="select tm.*,jt.termName,mt.link_name as Suppliername,u.email,mt1.link_name as Membername,mt1.user_id as memberuserid  from #__structured_terms_to_members as tm
          left join jos_structured_terms as jt on(jt.termId=tm.terms_id)
          inner join jos_mt_links as mt on(mt.link_id=jt.supplierUserId)
          inner join jos_mt_links as mt1 on(mt1.link_id=tm.member_id)
          left join jos_users as u on(u.id=mt1.user_id) where jt.termId='$termId'";
                $db->setQuery($Query);
                $results=$db->loadObjectList();
                $html= "<table width='100%' border='1'>
                        <tr><th>Supplier</th><th>Terms Name</th><th>Member Email Address</th><th>Member</th></tr>";
                foreach($results as $value)
                {
                         $supplier_names=$value->Suppliername;
                             $term_name=$value->termName;
                 $member_email=$value->email;
                 $member_name=$value->Membername;
                 $html.="<tr><td>".$supplier_names."</td>";
                 $html.="<td>".$term_name."</td>";
                 $html.="<td>".$member_email."</td>";
                 $html.="<td>".$member_name."</td></tr>";

                }
                $html.= "</table>";
                $body = $html;
                //$body = "Dear Admin,<br><br> ".$supp_names." have published their Terms. Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view.<br><br>".$html."<br>Thanks<br>UNF Team";
               $body = "Dear ".$supp_names."<br><br>We have just uploaded the agreed ".$supp_names." UF Terms to our website. Please find attached copy for your records.<br><br>".$html."<br>Thanks<br>UNF Team";
                //$to = "enquiries@united-farmers.org.uk";
                $sEmail = $supplier_names;
                $to="ve.akanshajaiswal@gmail.com";
               //$to="admin@united-farmers.org.uk";
                $from = array($sEmail, $sName);
                //$subject = "Notificiation mail for pulbished terms";
                $subject = " ".$supp_names." UF Terms Uploaded to the Website";

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

	public function notificationmail($sName, $sEmail, $mName, $mEmail)
	{
		$body = "Dear ".$mName.",<br><br> ".$sName." have published their Terms. Please visit <a href='http://www.unitedfarmers.co.uk/'>www.unitedfarmers.co.uk</a> to view.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
		
	     //$to="matt.hooker@adaptris.com";
                //$to = "suryakant@virtualemployee.com";
		$to="ve.akanshajaiswal@gmail.com";
		$from = array($sEmail, $sName);
		$subject = "New Structured Terms Uploaded to UF Interactive";
		
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
	/* new function for child table in terms */
	public function getchildtables()
	{
		 $id = $_REQUEST['term_id'];
		$model =& $this->getModel('terms');
		$filesDtl = $model->getTermsDetails($id);
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
		              	

		             
		    $html.= "<tr>
		              	<td>".$value->link_name."</td>";
		              	$member = $model->getMemberDetails($value->member_id, $id);
                                
						 $totalusers=count($member);
						 if($totalusers==0)
						{
							$share = "Not Viewed";
						} else {
							$share = "<a class='inline' href='#inline_member5".$k."'>Users</a>";
						} 
            $html.=   "<td>".$share."</td>";
                        $checkdelete=$model->getdelerecord($value->member_id,$id);
		              	$deletterm=$checkdelete->viewTerm;
                     if($deletterm =="0")
                     {
                    $delete ="<a href='index.php?option=com_smadmin&task=terms.deleteview&cid=".$value->id."&memberid=".$value->member_id."'>Delete<a>";
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
							<div id='inline_member5".$k."'>
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



   /* mail function ends here */ 

}
