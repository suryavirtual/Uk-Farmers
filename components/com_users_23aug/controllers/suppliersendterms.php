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
require_once JPATH_COMPONENT . '/tcpdf/config/tcpdf_config.php';
require_once JPATH_COMPONENT . '/tcpdf/tcpdf.php';

/**
 * Reset controller class for Users.
 *
 * @since  1.6
 */
class UsersControllerSuppliersendterms extends UsersController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function gettermssaveasnew(){
		$database = JFactory::getDbo();
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		
		$user_id=$_POST['user_id'];
		$url=$_POST['url'];
		$terms_id = $_POST['terms_id'];
		$cmpId = $_POST['cmpId'];
		$termName=$_POST['termName'];
		
		$company_address=addslashes($_POST['company_address']);
		$company_phone=$_POST['company_phone'];
		$company_fax=$_POST['company_fax'];
		$company_email=$_POST['company_email'];
		
		$contact_name=$_POST['contact_name'];
		$contact_position=$_POST['contact_position'];
		$contact_mobile=$_POST['contact_mobile'];
		$contact_phone=$_POST['contact_phone'];
		$contact_fax=$_POST['contact_fax'];
		$contact_email=$_POST['contact_email'];
		
		$date=date_create($_POST['effective_from']);
        $effective_forms= date_format($date,"Y-m-d");
		$effective_from=$effective_forms;
		$date1=date_create($_POST['valid_to']);
        $valid_tos= date_format($date1,"Y-m-d");
		$valid_to=$valid_tos;
		
		$terms_details_editor=$_POST['terms_details_editor'];
		$trmDtl = addslashes($terms_details_editor); 
		
		if(empty($termName)){
			$link = $url;
			$this->setRedirect($link, JFactory::getApplication()->enqueueMessage('Please enter Term Name', 'error'));
		} else {
			
			$dir = JPATH_BASE."/upload/terms/"; //full path like C:/xampp/htdocs/file/file/
			$date = strtotime(date('H:i:s'));
			$TermTitle = JFilterOutput::stringURLSafe($termName);
			$filename="UNF_".$date."_".$_FILES['supplier_files']['name']['0'];
			
			if(count($_FILES['supplier_files']['name']) > 0){
				$allowed =  array('pdf');
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
					move_uploaded_file($_FILES['supplier_files']['tmp_name']['0'], JPATH_BASE."/upload/terms/".$filename);
					if(!empty($terms_id)){
						$qSelect = "select * from #__structured_terms where termId='$terms_id'";
						$database->setQuery( $qSelect );
						$getDtl = $database->loadObject();
						$filename = $getDtl->termsFile;
						if(!empty($filename)){
							unlink($dir.$filename);
						}
						
					   $query = "update #__structured_terms set termName='$termName', supplierUserId='$cmpId', cmpId='$user_id', company_address='$company_address', 
						company_phone='$company_phone', company_fax='$company_fax', company_email='$company_email', effectiveFrom='$effective_from',
						validTo='$valid_to', paymentTerms='', termsDetails='$trmDtl', termsFile='$filename', 
						contactPosition='$contact_position', contactName='$contact_name', mobile='$contact_mobile', contactPhone='$contact_phone', 
						contactFax='$contact_fax', contactEmail='$contact_email' where termId='$terms_id' ";
						
					}
					else {
						
						
						 $query = "INSERT INTO #__structured_terms (`termId`, `termName`, `supplierUserId`, `cmpId`, `company_address`, `company_phone`, 
							`company_fax`, `company_email`, `effectiveFrom`, `validTo`, `paymentTerms`, `termsDetails`, `termsFile`, 
							`contactPosition`, `contactName`, `mobile`, `contactPhone`, `contactFax`, `contactEmail`, `status`, `ordering`) 
							VALUES (NULL, '$termName', '$cmpId','$user_id', '$company_address', '$company_phone', '$company_fax', '$company_email', 
							'$effective_from', '$valid_to', '', '$trmDtl', '$filename', '$contact_position', '$contact_name', 
							'$contact_mobile', '$contact_phone', '$contact_fax', '$contact_email', '0', '1')";
						
							
					}
					$database->setQuery($query);
					$database->query();
					$link = $url;
					if(!empty($terms_id)){
						$success = "Term Updated successfully";
					} else {
						$success = "Term Added successfully";
					}
					$this->setRedirect($link, $success);
				}
			}
			
			
		
		}
	}
	
	public function getterms_onchange_existingterms(){
		$return=array();
		$database = JFactory::getDbo();
        $existing_terms=$_POST['existing_terms'];
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$link = $referer;
		$user_id=$_POST['user_id'];
		$query = "SELECT * FROM #__structured_terms WHERE cmpId=$user_id AND termId=$existing_terms";
		$database->setQuery( $query );
		$rows_existing_terms = $database->loadObject();
		$return['termName']=$rows_existing_terms->termName;
		$return['cmpId']=$rows_existing_terms->cmpId;
		$return['address']=$rows_existing_terms->company_address;
		$return['company_phone']=$rows_existing_terms->company_phone;
		$return['company_fax']=$rows_existing_terms->company_fax;
		$return['company_email']=$rows_existing_terms->company_email;
		$return['contact_name']=$rows_existing_terms->contactName;
		$return['contact_position']=$rows_existing_terms->contactPosition;
		$return['contact_mobile']=$rows_existing_terms->mobile;
		$return['contact_phone']=$rows_existing_terms->contactPhone;
		$return['contact_fax']=$rows_existing_terms->contactFax;
		$return['contact_email']=$rows_existing_terms->contactEmail;
                 $date=date_create($rows_existing_terms->effectiveFrom);
                 $effective_form= date_format($date,"d-m-Y");
		$return['effective_from']=$effective_form;
                 $date1=date_create($rows_existing_terms->validTo);
                 $valid_form= date_format($date1,"d-m-Y");
		$return['valid_to']=$valid_form;
		$return['terms_details']=$rows_existing_terms->termsDetails;
		echo json_encode($return);
		die;
	}
	
	public function markviewedterms(){
		$user_id=$_REQUEST['user_id'];
		$com_id=$_REQUEST['comp_id'];
		$viewed=1;
		$value=$_REQUEST['termid'];
		$database = JFactory::getDBO();
		//$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
        $query1="select * from #__supplier_term_viewed where user_id='$user_id' and tid='$value' and comp_id='$com_id'";
        $database->setQuery($query1);
        $rows_existing = $database->loadObject();
        if(empty($rows_existing))
     {
     	$query = "insert into #__supplier_term_viewed values('','$user_id','$value','$com_id','$viewed')";
		$database->setQuery( $query );
		$database->query();
     }
		
		echo "sucess";
		exit;
	}
	
	public function unplublishTerms(){
		$database = JFactory::getDBO();
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		
		$tid = $_REQUEST['id'];
		
		$query = "update #__structured_terms_to_members set deleteRequest='1' where id='$tid'";
		$database->setQuery( $query );
		$database->query();
		$this->setRedirect($referer, "Term deleted successfully");
	}
	
	
public function sendtermstomember()
{
	
	$database = JFactory::getDbo();
	$termId = $_POST['share'];
	$memberId = $_POST['Box2'];
	$user_id=$_POST['user_id'];
	$url=$_POST['url'];

	if(!empty($user_id))
	{
	$queryCmp = "SELECT * from jos_mt_links where user_id='".$user_id."' and link_published='1'";
	$database->setQuery($queryCmp);
	$rows = $database->loadObject();
	$cmpId = $rows->link_id;
  }
	
	if(empty($termId && $memberId)){
		$link = $url;
		$this->setRedirect($link, JFactory::getApplication()->enqueueMessage('Please select Terms and Members', 'error'));
	} else {
		foreach($termId as $termIds){
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
					echo $query = "insert into #__structured_terms_to_members(id, terms_id, user_id, member_id, updated_on, approved, email_sent, viewEmail, deleteRequest) values 
				('', '$termIds', '$cmpId', '$memberIds', now(), '0', '0', '0', '0')";
				
					$database->setQuery($query);
					$database->query();
				}
			}
		}
		$fmsg = implode(",<br>",$message);
		
		$link = $url;
		$success = "Term successfully send to admin for approval ";
		
		if(!empty($fmsg)){
			$this->setRedirect($url, JFactory::getApplication()->enqueueMessage($fmsg, 'error'));
		} else {
			$this->setRedirect($link, $success);
		}
	}
}
	

}


