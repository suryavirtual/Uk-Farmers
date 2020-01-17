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
class UsersControllerSupplierdetails extends UsersController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function getsupplierdetailsfinal(){
		$db = JFactory::getDbo();
		$Itemid = JRequest::getVar('Itemid');
		
		$supplier_about_us=addslashes($_POST['supplier_about_us']);
		$supplier_company_name=addslashes($_POST['supplier_company_name']);
		$supplieraddress=addslashes($_POST['supplieraddress']);
		$supplier_phone_number=$_POST['supplier_phone_number'];
		$supplier_fax_number=$_POST['supplier_fax_number'];
		$supplier_email=$_POST['supplier_email'];
		$user_id=$_POST['user_id'];
		$form_url = $_POST['form_url'];
		
		if($_FILES["supplier_company_logo"]["name"]!=""){
			$file_name=$user_id.'_'.$_FILES["supplier_company_logo"]["name"];
		} else {
			$file_name="";
		}
		
		$target_dir_mt = JPATH_BASE."/media/com_mtree/images/listings/";
		$target_file_mt_m = $target_dir_mt .'m/'. basename($user_id.'_'.$_FILES["supplier_company_logo"]["name"]);
		$target_file_mt_o = $target_dir_mt .'o/'. basename($user_id.'_'.$_FILES["supplier_company_logo"]["name"]);
		$target_file_mt_s = $target_dir_mt .'s/'. basename($user_id.'_'.$_FILES["supplier_company_logo"]["name"]);
		
		$modifiedDate = date("Y-m-d H:i:s");
		$ailiasComp=JFilterOutput::stringURLSafe($supplier_company_name);
		
		$db->setQuery('SELECT mt.*, mtimg.filename FROM #__mt_links as mt, #__mt_images as mtimg 
			WHERE mt.user_id ='.(int)$user_id.' and mt.link_published=1 and mt.link_id=mtimg.link_id');
		$results = $db->loadObject();
		$available_suppliers = count($results);
		
		if($available_suppliers>0){
			$image_name=$results->filename;
			if (file_exists($target_file_mt_m)) {
				unlink($target_file_mt_m);
			}
			if (file_exists($target_file_mt_o)) {
				unlink($target_file_mt_o);
			}
			if (file_exists($target_file_mt_s)) {
				unlink($target_file_mt_s);
			}
			if( $file_name == ""){
				$file_name=$image_name;
			}
			
			$linkId = $results->link_id;
			
			$update_query_links = "UPDATE #__mt_links SET `link_name` ='$supplier_company_name', `link_desc` = '$supplier_about_us',`link_modified`='$modifiedDate',`address` ='$supplieraddress',`telephone` ='$supplier_phone_number',`fax` ='$supplier_fax_number',`email` ='$supplier_email'  WHERE user_id=$user_id";
			$db->setQuery($update_query_links);
			$db->query();
			
			$update_query_img = "UPDATE #__mt_images SET `filename` ='$file_name' WHERE link_id=$linkId";
			$db->setQuery($update_query_img);
			$db->query();
			
			move_uploaded_file($_FILES["supplier_company_logo"]["tmp_name"], $target_file_mt_m);
			copy($target_file_mt_m, $target_file_mt_o);
			copy($target_file_mt_m, $target_file_mt_s);
			
			$link = $form_url;
			$success = "Your Details Updated SuccessFully";
			$this->setRedirect($link, $success);	
		}
	}
	
	public function sendEmail(){
		$db = JFactory::getDbo();
		$msg = $_REQUEST['supplier_message'];
		$sName = $_REQUEST['user_name'];
		$sEmail = $_REQUEST['user_email'];
		
		$db->setQuery("select * from #__users where username='admin'");
		$result = $db->loadObjectList();
		
		$eSubject = "Request to changes in the My Details";
		
		foreach($result as $results){
			$adminName = $results->name;
			$adminEmail = $results->email;
			
			$this->sendMail($sName, $sEmail, $adminName, $adminEmail, $eSubject, $msg);
		}
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Changes Request send successfully!!!";
		$this->setRedirect($referer, $success);
	}
	
	public function sendMail($sName, $sEmail, $adminName, $adminEmail, $eSubject, $msg){
		$body = "Hi ".$adminName.",<br><br> A New Email sended by the ".$sName.", to make changes in the My details section.<br>".$msg."<br><br>Thanks<br>UNF Team";
		$to = $mEmail;
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

		# If you would like to send as HTML, include this line; otherwise, leave it out
		$mailer->isHTML(true);

		# Send once you have set all of your options
		$mailer->send();
	}
}
