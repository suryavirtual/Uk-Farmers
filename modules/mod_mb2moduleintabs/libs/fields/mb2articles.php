<?php
/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('list');

/**
 * Supports an custom SQL select list
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldMb2articles extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'Mb2articles';

	

	/**
	 * Method to get the custom field options.
	 * Use the query attribute to supply a query to generate the list.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		$options = array();

		// Initialize some field attributes.
		//$key   = $this->keyField;
		//$value = $this->valueField;

		// Get the database object.
		$db = JFactory::getDbo();

		// Set the query and get the result list.
		$query = 'SELECT id, title FROM #__content';
		
		$db->setQuery($query);
		$items = $db->loadObjectlist();

		// Build the field options.
		if (!empty($items))
		{
			$options[] = JHtml::_('select.option', 0, JText::_('JNONE') );
			
			foreach ($items as $item)
			{
				
				$options[] = JHtml::_('select.option', $item->id, $item->title . ' (Id: ' . $item->id . ')' );
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}