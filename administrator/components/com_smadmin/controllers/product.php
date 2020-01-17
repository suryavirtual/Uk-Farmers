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
class SmAdminControllerProduct extends JControllerForm
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
	
	public function save(){
		$database = JFactory::getDbo();
		
		$task = $_REQUEST['task'];
		$pid = $_REQUEST['pId'];
		$user_id=$_REQUEST['uId'];
		$cmp_id=$_REQUEST['jform']['cmpId'];
		$addedBy=$_REQUEST['createdBy'];
		$product_name=$_REQUEST['jform']['pName'];
		$product_sku=$_REQUEST['jform']['pSKU'];
		$product_desc=addslashes($_REQUEST['pDesc']);
		$docDesc = addslashes($_REQUEST['docDesc']);
		
		$date = strtotime(date('H:i:s'));
		
		if($task=='save'){
			$link=JURI::base().'index.php?option=com_smadmin&view=products';
		} else if ($task=='apply'){
			$link = JURI::getInstance($_SERVER['HTTP_REFERER']);
		}
		if(empty($pid)){ 
			if(!empty($_FILES['product_image']['name']['0'])){
				$path = substr(JPATH_BASE,0,-13);
				$target_path = $path."/media/com_mtree/images/products/";
			
				for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
					$validextensions = array("jpeg", "jpg", "png", "gif");  //Extensions which are allowed
					$ext = explode('.', basename($_FILES['product_image']['name'][$i]));//explode file name from dot(.)
					$file_extension = end($ext); //store extensions in the variable
					
					if (in_array($file_extension, $validextensions)) {
						$file_name[] = $date."_".$_FILES['product_image']['name'][$i];
						move_uploaded_file($_FILES['product_image']['tmp_name'][$i], $target_path.$date."_".$_FILES['product_image']['name'][$i]);
					} 
				}
				$imageName = implode(",",$file_name);
			}
			if(!empty($imageName)){
				$fImage = $imageName;
			} else {
				$fImage = $imageName;
			}
			
			if(!empty($_FILES['pDocs']['name'])){
				$path_docs = substr(JPATH_BASE,0,-13);
				$target_path_docs = $path_docs."/media/com_mtree/images/products/docs/";
				$docs_name = $date."_".$_FILES['pDocs']['name'];
				move_uploaded_file($_FILES['pDocs']['tmp_name'], $target_path_docs.$docs_name);
			}
			if(!empty($docs_name)){
				$fDoc = $docs_name;
			} else {
				$fDoc = $docs_name;
			}
			
			
			$queryInsert = "INSERT INTO `#__mt_links_products` (`pId`, `uId`, `cmpId`, `catId`, `pName`, `pSKU`, `pImage`, `pDesc`, `pDocs`, `docDesc`, `status`, `createdBy`, `createdOn`, `modifiedBy`, `modifiedOn`, `ordering`) VALUES
				('', '$user_id', '$cmp_id', '0', '$product_name', '$product_sku', '$fImage', '$product_desc', '$fDoc', '$docDesc', '1', '$addedBy', now(), '', '','0')";
			$database->setQuery($queryInsert);
			$database->query();
			
			$success = "Product Added successfully!";
			$this->setRedirect($link, $success);
			
		} else { 
			$path = substr(JPATH_BASE,0,-13);
			$target_path = $path."/media/com_mtree/images/products/";
			
			$querySelect = "select * from #__mt_links_products where pId='$pid'";
			$database->setQuery($querySelect);
			$productsDtl = $database->loadObject();
			
			$updateQuery = "update #__mt_links_products set uId= '$user_id', cmpId='$cmp_id', pName='$product_name', pSKU='$product_sku', pDesc='$product_desc', docDesc='$docDesc', modifiedBy='$addedBy', modifiedOn=now() where pId='$pid'";
			$database->setQuery($updateQuery);
			$database->query();
			
			if(!empty($_FILES['product_image']['name']['0'])){
				$image = explode(",",$productsDtl->pImage);
				
				foreach($image as $images){
					if(file_exists($target_path.$images)){
						unlink($target_path.$images);
					}
				}
				
				for ($i = 0; $i < count($_FILES['product_image']['name']); $i++) {
					$validextensions = array("jpeg", "jpg", "png", "gif");  //Extensions which are allowed
					$ext = explode('.', basename($_FILES['product_image']['name'][$i]));//explode file name from dot(.)
					$file_extension = end($ext); //store extensions in the variable
					
					if (in_array($file_extension, $validextensions)) {
						$file_name[] = $date."_".$_FILES['product_image']['name'][$i];
						move_uploaded_file($_FILES['product_image']['tmp_name'][$i], $target_path.$date."_".$_FILES['product_image']['name'][$i]);
					} 
				}
				$imageName = implode(",",$file_name);
				
				if(!empty($imageName)){
					$updateQuery = "update #__mt_links_products set `pImage`='$imageName' where pId='$pid'";
					$database->setQuery($updateQuery);
					$database->query();
				}
			}
			
			if(!empty($_FILES['pDocs']['name'])){
				$doc = $productsDtl->pDocs;
				
				if(file_exists($target_path."/docs/".$doc)){
					unlink($target_path."/docs/".$doc);
				}
				
				$path_docs = substr(JPATH_BASE,0,-13);
				$target_path_docs = $path_docs."/media/com_mtree/images/products/docs/";
				$docs_name = $date."_".$_FILES['pDocs']['name'];
				if(move_uploaded_file($_FILES['pDocs']['tmp_name'], $target_path_docs.$date."_".$_FILES['pDocs']['name'])){
					$updateQuery = "update #__mt_links_products set pDocs='$docs_name'  where pId='$pid'";
					$database->setQuery($updateQuery);
					$database->query();
				}
			}
			
			$success = "Product Updated successfully!";
			$this->setRedirect($link, $success);
		}
	}
}
