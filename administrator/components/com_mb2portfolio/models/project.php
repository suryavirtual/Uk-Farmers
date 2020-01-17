<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Mb2portfolio model.
 */
 

	class Mb2portfolioModelproject extends JModelAdmin
	{
		/**
		 * @var		string	The prefix to use with controller messages.
		 * @since	1.6
		 */
		protected $text_prefix = '';
	
	
		/**
		 * Returns a reference to the a Table object, always creating it.
		 *
		 * @param	type	The table type to instantiate
		 * @param	string	A prefix for the table class name. Optional.
		 * @param	array	Configuration array for model. Optional.
		 * @return	JTable	A database object
		 * @since	1.6
		 */
		public function getTable($type = 'Project', $prefix = 'Mb2portfolioTable', $config = array())
		{
			return JTable::getInstance($type, $prefix, $config);
		}
	
		/**
		 * Method to get the record form.
		 *
		 * @param	array	$data		An optional array of data for the form to interogate.
		 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
		 * @return	JForm	A JForm object on success, false on failure
		 * @since	1.6
		 */
		public function getForm($data = array(), $loadData = true)
		{
			// Initialise variables.
			$app	= JFactory::getApplication();
	
			// Get the form.
			$form = $this->loadForm('com_mb2portfolio.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
			if (empty($form)) {
				return false;
			}
	
			return $form;
		}
	
		/**
		 * Method to get the data that should be injected in the form.
		 *
		 * @return	mixed	The data for the form.
		 * @since	1.6
		 */
		protected function loadFormData()
		{
			// Check the session for previously entered form data.
			$data = JFactory::getApplication()->getUserState('com_mb2portfolio.edit.project.data', array());
	
			if (empty($data)) {
				$data = $this->getItem();
				
	
				//Support for multiple or not foreign key field: skill_1
				$array = array();
				foreach((array)$data->skill_1 as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data->skill_1 = implode(',',$array);
	
				//Support for multiple or not foreign key field: skill_2
				$array = array();
				foreach((array)$data->skill_2 as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data->skill_2 = implode(',',$array);
	
				//Support for multiple or not foreign key field: skill_3
				$array = array();
				foreach((array)$data->skill_3 as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data->skill_3 = implode(',',$array);
	
				//Support for multiple or not foreign key field: skill_4
				$array = array();
				foreach((array)$data->skill_4 as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data->skill_4 = implode(',',$array);
	
				//Support for multiple or not foreign key field: skill_5
				$array = array();
				foreach((array)$data->skill_5 as $value): 
					if(!is_array($value)):
						$array[] = $value;
					endif;
				endforeach;
				$data->skill_5 = implode(',',$array);
			}
	
			return $data;
		}
		
		
		
		
		
		
		
		
		/**
		 * Method to save the form data.
		 *
		 * @param   array  The form data.
		 *
		 * @return  boolean  True on success.
		 * @since   1.6
		 */
		public function save($data)
		{
			$app = JFactory::getApplication();			
			$registry = new JRegistry;
			
			
			if (isset($data['images']) && is_array($data['images']))
			{	
				$registry = new JRegistry;			
				$registry->loadArray($data['images']);
				$data['images'] = (string) $registry;
			}
			
			
			
			
			if (isset($data['video']) && is_array($data['video']))
			{
				$registry = new JRegistry;
				$registry->loadArray($data['video']);
				$data['video'] = (string) $registry;
			}
			
			
			
			if (isset($data['extra_fields']) && is_array($data['extra_fields']))
			{
				$registry = new JRegistry;
				$registry->loadArray($data['extra_fields']);
				$data['extra_fields'] = (string) $registry;
			}	
			
			
			
			if (isset($data['links']) && is_array($data['links']))
			{
				$registry = new JRegistry;
				$registry->loadArray($data['links']);
				$data['links'] = (string) $registry;
			}
			
			
			
			if (isset($data['metadata']) && is_array($data['metadata']))
			{
				$registry = new JRegistry;
				$registry->loadArray($data['metadata']);
				$data['metadata'] = (string) $registry;
			}
			
			
			
	
			
			if (parent::save($data))
			{
				
	
				return true;
			}
	
			return false;
		}
			
		
		
		
		
		
	
		/**
		 * Method to get a single record.
		 *
		 * @param	integer	The id of the primary key.
		 *
		 * @return	mixed	Object on success, false on failure.
		 * @since	1.6
		 */
		public function getItem($pk = null)
		{
			if ($item = parent::getItem($pk)) {
				
				
				
				// Convert the images field to an array.
				$registry = new JRegistry;				
				$registry->loadString($item->images);
				$item->images = $registry->toArray();
	
				
				// Convert the video field to an array.
				$registry = new JRegistry;
				$registry->loadString($item->video);
				$item->video = $registry->toArray();
				
				
				
				// Convert the extra_fields field to an array.
				$registry = new JRegistry;
				$registry->loadString($item->extra_fields);
				$item->extra_fields = $registry->toArray();
				
				
				
				
				// Convert the links field to an array.
				$registry = new JRegistry;
				$registry->loadString($item->links);
				$item->links = $registry->toArray();
				
				
				
				
				// Convert the metadata field to an array.
				$registry = new JRegistry;
				$registry->loadString($item->metadata);
				$item->metadata = $registry->toArray();
				
				
	
			}
	
			return $item;
		}
		
		
		
	
		
		
		
	
		/**
		 * Prepare and sanitise the table prior to saving.
		 *
		 * @since	1.6
		 */ 
		 
		protected function prepareTable($table)
		{
			jimport('joomla.filter.output');
	
			if (empty($table->id)) {
	
				// Set ordering to the last item if not set
				if (@$table->ordering === '') {
					$db = JFactory::getDbo();
					$db->setQuery('SELECT MAX(ordering) FROM #__mb2portfolio');
					$max = $db->loadResult();
					$table->ordering = $max+1;
				}
	
			}
		}
	
	}	

 
