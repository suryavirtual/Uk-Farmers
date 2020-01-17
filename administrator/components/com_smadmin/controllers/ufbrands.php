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
class SmAdminControllerUfbrands extends JControllerAdmin
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
	
	public function getModel($name = 'Ufbrand', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	public function delete(){
		
	}
	
	public function publish(){
		$db = JFactory::getDbo();
		$ids = JRequest::getVar('cid', array(), '', 'array');
		$task = $this->getTask();
		
		if (count( $ids )){ 
			JArrayHelper::toInteger($ids);
			$cids = implode( ',', $ids );
			
			if($task=='publish'){
				$query = "UPDATE #__uf_brands SET published = '1' WHERE id IN ( ".$cids." )";
			}
			if($task=='unpublish'){
				$query = "UPDATE #__uf_brands SET published = '0' WHERE id IN ( ".$cids." )";
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
