<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('portfoliolanguage');

/**
 * Supports an custom SQL select list
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldPortfoliolanguage extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'portfoliolanguage';
	
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
		$translate = $this->element['translate'] ? (string) $this->element['translate'] : false;
		//$query = (string) $this->element['query'];
		
		
		
		$lang_sef=JRequest::getInt('lang');
		
		
		// Get the database object.
		$db = JFactory::getDbo();	
				
		// Set the query and get the result list.
		$db->setQuery(		
		$db->getQuery(true)
			->select('a.*')
			->from('#__languages As a')
			->where('published=1')
		);		
		$items = $db->loadObjectlist();

		// Build the field options.
		if (!empty($items))
		{
			foreach ($items as $item)
			{
				if ($translate == true)
				{
					$options[] = JHtml::_('select.option', $item->sef, JText::_($item->lang_code));
				}
				else
				{
					$options[] = JHtml::_('select.option', $item->sef, $item->lang_code);
				}
			}
		}

		// Merge any additional options in the XML definition.
		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}





