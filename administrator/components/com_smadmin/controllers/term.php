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

require_once JPATH_COMPONENT . '/tcpdf/config/tcpdf_config.php';
require_once JPATH_COMPONENT . '/tcpdf/tcpdf.php';
/**
 * Supplier/Member Controller
 *
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 * @since       0.0.9
 */
class SmAdminControllerTerm extends JControllerForm
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
	public function getusers()
	{
	         $id = $_REQUEST['supplier_id'];
                $db = JFactory::getDbo();
		$Query = "SELECT u.id,u.name  FROM #__users as u left join #__user_usergroup_map as um on(u.id=um.user_id) WHERE um.group_id='13' and find_in_set('$id',u.comp_list) and u.id NOT IN(select user_id FROM jos_mt_links WHERE link_id='$id') ";
		$db->setQuery($Query);
	        $results=$db->loadAssocList();
        	echo json_encode($results);
		die;
	}
	
	/* ajax function for fetchig email id according to user id */
	public function getemails()
	{
		
		$id = $_REQUEST['users_id'];
		$db = JFactory::getDbo();
		$Query = "SELECT email  FROM #__users WHERE id=".$id;
		$db->setQuery($Query);
		$results=$db->loadObject();
        	echo json_encode($results);
		die;
	}
	/* ajax function ends here */
	/* ajax function for fetching folder name according to link_id */
	public function getfoldername()
	{
		$id = $_REQUEST['link_id'];
		$db = JFactory::getDbo();
		$Query = "select folder_name from #__folder where link_id=".$id;
		$db->setQuery($Query);
		$results=$db->loadObject();
        	echo json_encode($results);
		die;
	}
	/* ajax function ends here */
	
	public function save()
	{
		$database = JFactory::getDbo();
		$task = $_REQUEST['task'];
		$termId = $_REQUEST['termId'];
		$cmp_id=$_REQUEST['jform']['supplierUserId'];
		$contactName=addslashes($_REQUEST['jform']['contactName']);
		$effectiveFrom=$_REQUEST['jform']['effectiveFrom'];
		$termName=$_REQUEST['jform']['termName'];
		$contactEmail=$_REQUEST['jform']['contactEmail'];
		$validTo=$_REQUEST['jform']['validTo'];
		$filename=$_REQUEST['jform']['termsFile'];
		$memberId = $_POST['member_id'];
		/* new checkbox code added here */
		if($_REQUEST['jform']['sentNotification']){
			$sentNotification = '1';
		} else {
			$sentNotification = '0';
		}
		/*end here */
		$querySelect = "select user_id from #__mt_links where link_id='$cmp_id'";
		$database->setQuery($querySelect);
		$cmpnyDtl = $database->loadObject();
		$user_id=$cmpnyDtl->user_id;
		
		if($task=='save'){
			$link=JURI::base().'index.php?option=com_smadmin&view=terms';
		} else if ($task=='apply'){
			$link = JURI::getInstance($_SERVER['HTTP_REFERER']);
		}
		$link1=JURI::base().'index.php?option=com_smadmin&view=terms';
		$path = substr(JPATH_BASE,0,-13);
		$dir = $path."/upload/terms/";
		
		if(!empty($termId))
		{
			$qSelect = "select * from #__structured_terms where termId='$termId'";
			$database->setQuery( $qSelect );
			$getDtl = $database->loadObject();
			//$filename = $getDtl->termsFile;
			if(!empty($filename)){
				if(file_exists($dir.$filename)){
					unlink($dir.$filename);
				}
			}
			
		 $query = "update #__structured_terms set termName='$termName', supplierUserId='$cmp_id', cmpId='$user_id', company_address='$company_address', 
			company_phone='$company_phone', company_fax='$company_fax', company_email='$company_email', effectiveFrom='$effectiveFrom',
			validTo='$validTo', paymentTerms='', termsDetails='$termsDetails', termsFile='$filename', 
			contactPosition='$contactPosition', contactName='$contactName', mobile='$mobile', contactPhone='$contactPhone', 
			contactFax='$contactFax', contactEmail='$contactEmail', sentNotification='$sentNotification' where termId='$termId' ";
			$database->setQuery($query);
		    $database->query();
		    /* new query for checking checkbox */
			$querySelect = "select sentNotification from #__structured_terms where termId='$termId' ";
		    $database->setQuery($querySelect);
		    $notificattionDtl = $database->loadObject();
                    /* code commented for mail */
		    //$this->checkmemberpermission($termId,$cmp_id);

			/* query ends here */
		    $this->setRedirect($link,'Term Updated successfully!');
		}
		else 
		{
			$checkquery="select * from #__structured_terms where termName='$termName' and supplierUserId='$cmp_id' ";
			$database->setQuery($checkquery);
			$checkdetails=$database->loadObject();
			$getval=count($checkdetails);
			if($getval>0)
			{
             
             $this->setRedirect($link1,'This term is alredy added by this supplier');
			}
			else
			{

			$query1 = "INSERT INTO #__structured_terms (`termId`, `termName`, `supplierUserId`, `cmpId`, `company_address`, `company_phone`, 
				`company_fax`, `company_email`, `effectiveFrom`, `validTo`, `paymentTerms`, `termsDetails`, `termsFile`, 
				`contactPosition`, `contactName`, `mobile`, `contactPhone`, `contactFax`, `contactEmail`, `status`,ordering,unpublished,unpublishedcounter,sentNotification) 
				VALUES (NULL, '$termName', '$cmp_id', '$user_id', '$company_address', '$company_phone', '$company_fax', '$company_email', 
				'$effectiveFrom', '$validTo', '', '$termsDetails', '$filename', '$contactPosition', '$contactName', 
				'$mobile', '$contactPhone', '$contactFax', '$contactEmail', '0','0','1','','$sentNotification')";
				$database->setQuery($query1);
		        $database->query();
		        $last_insert_id=$database->insertid();
		        $this->setRedirect($link."&termId=".$last_insert_id."#tabs-4",'Term Added successfully!');
			}
		}
			
		if(!empty($termId)){
			$success = "Term Updated successfully!";
		} else {
			$success = "Term Added successfully!";
		}
	}

	/* new function added for notification mail */
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
          /* merge code start from here*/
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
                               // $tName = $results->termName;
                                $this->notificationmail($sName, $sEmail, $mName, $mEmail);
	/* merge code ends here */	
		    	}	
		    	
		    }

	}


	public function notificationmail($sName, $sEmail, $mName, $mEmail, $tName)
	{
		$body = "Hi ".$mName.",<br><br> your Term has been updated , Please check this Term.<br><br>Thanks<br>UNF Team";
		//$to = $mEmail;
                 $to= "ve.akanshajaiswal@gmail.com";
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
                $userParams = JComponentHelper::getParams('com_smadmin')->get('send_mail');
		if($userParams == "1")
		{
		$mailer->send();	
		}
		//$mailer->send();

	}
	/* new funtion ended here */

	/* ajax function for fetchig user according to supplier id */
	public function getcfvalue()
	{
		
		 $id = $_REQUEST['uf_val'];
       
		$db = JFactory::getDbo();
		$Query = " SELECT * from  #__mt_cfvalues WHERE cf_id='34' and value='$id' ";

		$db->setQuery($Query);
		 $results=$db->loadAssocList();
     	if(!empty($results))
     	{
       echo "found";
     	}
		die;
	}
	
	
	
}
