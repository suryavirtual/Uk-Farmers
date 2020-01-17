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
class SmAdminControllerProducts extends JControllerAdmin
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
	
	public function getModel($name = 'Product', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
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
			
			$query = "UPDATE #__mt_links_products SET status = '".$value. "' WHERE pId IN ( ".$cids." )";
			$db->setQuery( $query );
			
			if (!$db->query()) {
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		} 		
		$redirectTo = 'index.php?option='.JRequest::getVar('option').'&view='.JRequest::getVar('view'); 
		$this->setRedirect($redirectTo); 
	}
	
	public function delete(){
		$database = JFactory::getDbo();
		$ids = implode(",",$_REQUEST['cid']);
		
		$querySelect = "select * from #__mt_links_products where pId in ($ids)";
		$database->setQuery($querySelect);
		$productsDtl = $database->loadObjectList();
		
		foreach($productsDtl as $productsDtls){
			$image = $productsDtls->pImage;
			$pDocs = $productsDtls->pDocs;
			
			$path = substr(JPATH_BASE,0,-13);
			$target_path = $path."/media/com_mtree/images/products/";
			$target_path_doc = $path."/media/com_mtree/images/products/docs/";
			
			if(!empty($image)){
				$img = explode(",",$image);
						
				foreach($img as $imgs){
					unlink($target_path.$imgs);
				}
			}
			
			if(!empty($pDocs)){
				unlink($target_path_doc.$pDocs);
			}
		}
		$queryDel = "delete from #__mt_links_products where pId in ($ids)";
		$database->setQuery($queryDel);
		$database->query();
		
		$referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
		$success = "Product deleted successfully!";
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
				$query = "UPDATE #__mt_links_products SET status = '1' WHERE pId IN ( ".$cids." )";
			}
			if($task=='unpublish'){
				$query = "UPDATE #__mt_links_products SET status = '0' WHERE pId IN ( ".$cids." )";
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
}
