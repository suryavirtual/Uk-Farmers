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
 * Supplier/Member Admin View
 *
 * @since  0.0.1
 */
class SmAdminViewProduct extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $script;
	protected $canDo;

	/**
	 * Display the Supplier/Member Admin view
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  void
	 */
	public function display($tpl = null)
	{
		// Get the Data
		$this->form = $this->get('Form');
		$this->item = $this->get('Item');
		$this->script = $this->get('Script');

		// What Access Permissions does this user have? What can (s)he do?
		$this->canDo = SmAdminHelper::getActions($this->item->pId);

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));

			return false;
		}

		// Set the toolbar
		$this->addToolBar();

		// Display the template
		parent::display($tpl);

		// Set the document
		$this->setDocument();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @return  void
	 *
	 * @since   1.6
	 */
	protected function addToolBar()
	{
		$input = JFactory::getApplication()->input;

		// Hide Joomla Administrator Main menu
		$input->set('hidemainmenu', true);

		$isNew = ($this->item->pId == 0);
		
		JToolBarHelper::title($isNew ? JText::_('COM_SMADMIN_MANAGER_PRODUCT_NEW')
		                             : JText::_('COM_SMADMIN_MANAGER_PRODUCT_EDIT'), 'product');
		// Build the actions for new and existing records.
		if ($isNew)
		{
			// For new records, check the create permission.
			if ($this->canDo->get('core.create')) 
			{
				JToolBarHelper::apply('product.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('product.save', 'JTOOLBAR_SAVE');
				//JToolBarHelper::custom('product.save2new', 'save-new.png', 'save-new_f2.png',
				                       //'JTOOLBAR_SAVE_AND_NEW', false);
			}
			JToolBarHelper::cancel('product.cancel', 'JTOOLBAR_CANCEL');
		}
		else
		{
			if ($this->canDo->get('core.edit'))
			{
				// We can save the new record
				JToolBarHelper::apply('product.apply', 'JTOOLBAR_APPLY');
				JToolBarHelper::save('product.save', 'JTOOLBAR_SAVE');
 
				// We can save this record, but check the create permission to see
				// if we can return to make a new one.
				if ($this->canDo->get('core.create')) 
				{
					//JToolBarHelper::custom('product.save2new', 'save-new.png', 'save-new_f2.png',
					                       //'JTOOLBAR_SAVE_AND_NEW', false);
				}
			}
			if ($this->canDo->get('core.create')) 
			{
				//JToolBarHelper::custom('product.save2copy', 'save-copy.png', 'save-copy_f2.png',
				                       //'JTOOLBAR_SAVE_AS_COPY', false);
			}
			JToolBarHelper::cancel('product.cancel', 'JTOOLBAR_CLOSE');
		}
	}
	/**
	 * Method to set up the document properties
	 *
	 * @return void
	 */
	protected function setDocument() 
	{
		$isNew = ($this->item->pId == 0);
		$document = JFactory::getDocument();
		$document->setTitle($isNew ? JText::_('COM_SMADMIN_PRODUCT_CREATING')
		                           : JText::_('COM_SMADMIN_PRODUCT_EDITING'));
		$document->addScript(JURI::root() . $this->script);
		$document->addScript(JURI::root() . "/administrator/components/com_smadmin"
		                                  . "/views/product/submitbutton.js");
		JText::script('COM_SMADMIN_PRODUCT_ERROR_UNACCEPTABLE');
	}
}
