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

JFormHelper::loadFieldClass('list');

/**
 * Supplier/Member Admin Form Field class for the Supplier/Member Admin component
 *
 * @since  0.0.1
 */
class JFormFieldUfbrand extends JFormFieldList
{
	/**
	 * The field type.
	 *
	 * @var         string
	 */
	public $type = 'Ufbrand';

	/**
	 * Method to get a list of options for a list input.
	 *
	 * @return  array  An array of JHtml options.
	 */
	protected function getOptions()
	{
		$db    = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__uf_brands');
		$query->where('published="1"');
		$db->setQuery((string) $query);
		$messages = $db->loadObjectList();
		
		$options  = array();

		if ($messages)
		{
			foreach ($messages as $message)
			{
				$options[] = JHtml::_('select.option', $message->id, $message->brandDtl);
			}
		}

		$options = array_merge(parent::getOptions(), $options);

		return $options;
	}
}
