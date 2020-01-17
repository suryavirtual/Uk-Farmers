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
class UsersControllerSupplierproducts extends UsersController
{
	/**
	 * Method to request a username reminder.
	 *
	 * @return  boolean
	 *
	 * @since   1.6
	 */
	public function addproducts(){
		$database = JFactory::getDbo();
		$user_id=$_POST['uID'];
		$cmp_id=$_POST['cmpId'];
		$addedBy=$_POST['addedBy'];
		$url=$_POST['url'];
		$product_name=$_POST['product_name'];
		$product_sku=$_POST['product_sku'];
		$docDesc=$_POST['docDesc'];
		$product_desc=addslashes($_POST['product_desc']);
		
		if(empty($product_name && $product_sku)){
			$link = $url;
			$this->setRedirect($link, JFactory::getApplication()->enqueueMessage('Please enter product name & SKU', 'error'));
		} else {	
			$queryInsert = "INSERT INTO `#__mt_links_products` (`pId`, `uId`, `cmpId`, `catId`, `pName`, `pSKU`, `pImage`, `pDesc`, `pDocs`, `docDesc`, `status`, `createdBy`, `createdOn`, `modifiedBy`, `modifiedOn`, `ordering') VALUES
			('', '$user_id', '$cmp_id', '0', '$product_name', '$product_sku', '', '$product_desc', '', '$docDesc', '0', '$addedBy', now(), '', '','0')";
			$database->setQuery($queryInsert);
			$database->query();
			
			$querySelect = "select * from #__mt_links_products order by pId desc limit 1";
			$database->setQuery($querySelect);
			$productsDtl = $database->loadObject();
			$insrtPid = $productsDtl->pId;
			
			$target_path = JPATH_BASE."/media/com_mtree/images/products/";
			for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
				$validextensions = array("jpeg", "jpg", "png", "gif");  //Extensions which are allowed
				$ext = explode('.', basename($_FILES['product_image']['name'][$i]));//explode file name from dot(.)
				$file_extension = end($ext); //store extensions in the variable
				
				$date = strtotime(date('H:i:s'));
				
				if (in_array($file_extension, $validextensions)) {
					$file_name[] = $date."_".$_FILES['product_image']['name'][$i];
					move_uploaded_file($_FILES['product_image']['tmp_name'][$i], $target_path.$date."_".$_FILES['product_image']['name'][$i]);
				} else {
					$link = $url;
					$this->setRedirect($link, JFactory::getApplication()->enqueueMessage('Please upload only jpg,png or gif files', 'error'));
				}
			}
			$imageName = implode(",",$file_name);
			
			if(!empty($imageName)){
				$updateQuery = "update #__mt_links_products set `pImage`='$imageName' where pId='$insrtPid'";
				$database->setQuery($updateQuery);
				$database->query();
			}
			
			$target_path_docs = JPATH_BASE."/media/com_mtree/images/products/docs";
			$docs_name = $date."_".$_FILES['product_docs']['name'];
			if(move_uploaded_file($_FILES['product_docs']['tmp_name'], $target_path_docs.$date."_".$_FILES['product_docs']['name'])){
				$updateQuery = "update #__mt_links_products set pDocs='$docs_name' where pId='$insrtPid'";
				$database->setQuery($updateQuery);
				$database->query();
			}
			$link = $url;
			$success = "Product Added successfully";
			$this->setRedirect($link, $success);
		}
	}
	
	public function editproducts(){
		$database = JFactory::getDbo();
		$user_id=$_POST['uID'];
		$cmp_id=$_POST['cmpId'];
		$modifyBy=$_POST['addedBy'];
		$pid=$_POST['pid'];
		$url=$_POST['url'];
		$product_name=$_POST['product_name'];
		$product_sku=$_POST['product_sku'];
		$docDesc=$_POST['docDesc'];
		$product_desc=addslashes($_POST['product_desc']);
		
		if(empty($product_name && $product_sku)){
			$link = $url;
			$this->setRedirect($link, JFactory::getApplication()->enqueueMessage('Please enter product name & SKU', 'error'));
		} else {
			$target_path = JPATH_BASE."/media/com_mtree/images/products/";
			
			$querySelect = "select * from #__mt_links_products where pId='$pid'";
			$database->setQuery($querySelect);
			$productsDtl = $database->loadObject();
			
			$updateQuery = "update #__mt_links_products set pName='$product_name', pSKU='$product_sku', pDesc='$product_desc', docDesc='$docDesc', modifiedBy='$modifyBy', modifiedOn=now() where pId='$pid'";
			$database->setQuery($updateQuery);
			$database->query();
			
			if(!empty($_FILES['product_image']['name']['0'])){
				$image = explode(",",$productsDtl->pImage);
				foreach($image as $images){
					unlink($target_path.$images);
				}
				for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
					$validextensions = array("jpeg", "jpg", "png", "gif");  //Extensions which are allowed
					$ext = explode('.', basename($_FILES['product_image']['name'][$i]));//explode file name from dot(.)
					$file_extension = end($ext); //store extensions in the variable
					
					$date = strtotime(date('H:i:s'));
					
					if (in_array($file_extension, $validextensions)) {
						$file_name[] = $date."_".$_FILES['product_image']['name'][$i];
						move_uploaded_file($_FILES['product_image']['tmp_name'][$i], $target_path.$date."_".$_FILES['product_image']['name'][$i]);
					} else {
						$link = $url;
						$this->setRedirect($link, JFactory::getApplication()->enqueueMessage('Please upload only jpg,png or gif files', 'error'));
					}
				}
				$imageName = implode(",",$file_name);
				
				if(!empty($imageName)){
					$updateQuery = "update #__mt_links_products set `pImage`='$imageName' where pId='$pid'";
					$database->setQuery($updateQuery);
					$database->query();
				}
			}
			
			if(!empty($_FILES['product_docs']['name'])){
				$doc = $productsDtl->pDocs;
				unlink($target_path."docs/".$doc);
				$target_path_docs = JPATH_BASE."/media/com_mtree/images/products/docs/";
				$docs_name = $date."_".$_FILES['product_docs']['name'];
				if(move_uploaded_file($_FILES['product_docs']['tmp_name'], $target_path_docs.$date."_".$_FILES['product_docs']['name'])){
					$updateQuery = "update #__mt_links_products set pDocs='$docs_name' where pId='$pid'";
					$database->setQuery($updateQuery);
					$database->query();
				}
			}
			
			$link = $url;
			$success = "Product Saved successfully";
			$this->setRedirect($link, $success);
		}
	}
	
	public function deleteproducts(){
		$database = JFactory::getDbo();
		$pid=$_REQUEST['pid'];
		
		$querySelect = "select * from #__mt_links_products where pId='$pid'";
		$database->setQuery($querySelect);
		$productsDtl = $database->loadObject();
		
		$image = $productsDtl->pImage;
		$pDocs = $productsDtl->pDocs;
		
		$target_path = JPATH_BASE."/media/com_mtree/images/products/";
		$target_path_doc = JPATH_BASE."/media/com_mtree/images/products/docs/";
		
		if(!empty($image)){
			$img = explode(",",$image);
					
			foreach($img as $imgs){
				unlink($target_path.$imgs);
			}
		}
		
		if(!empty($pDocs)){
			unlink($target_path_doc.$pDocs);
		}
		
		$queryDelete = "delete from #__mt_links_products where pId='$pid'";
		$database->setQuery($queryDelete);
		$database->query();
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$link = $referer;
		$success = "Product deleted successfully";
		$this->setRedirect($link, $success);	
	}
}


