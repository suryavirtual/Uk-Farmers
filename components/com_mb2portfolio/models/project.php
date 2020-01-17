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

jimport('joomla.application.component.modelform');
jimport('joomla.event.dispatcher');

/**
 * Mb2portfolio model.
 */
class Mb2portfolioModelProject extends JModelForm
{
    
    var $_item = null;
    
	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @since	1.6
	 */
	protected function populateState()
	{
		$app = JFactory::getApplication('com_mb2portfolio');

		// Load state from the request userState on edit or from the passed variable on default
        if (JFactory::getApplication()->input->get('layout') == 'edit') {
            $id = JFactory::getApplication()->getUserState('com_mb2portfolio.edit.project.id');
        } else {
            $id = JFactory::getApplication()->input->get('id');
            JFactory::getApplication()->setUserState('com_mb2portfolio.edit.project.id', $id);
        }
		$this->setState('project.id', $id);

		// Load the parameters.
		$params = $app->getParams();
        $params_array = $params->toArray();
        if(isset($params_array['item_id'])){
            $this->setState('project.id', $params_array['item_id']);
        }
		$this->setState('params', $params);

	}
      
	  
	  
	  
	  
	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.6
	 */
	protected function loadFormData()
	{
		$data = $this->getData(); 
        
        return $data;
	}  
	  
	  
	    

	/**
	 * Method to get an ojbect.
	 *
	 * @param	integer	The id of the object to get.
	 *
	 * @return	mixed	Object on success, false on failure.
	 */
	public function getData($id = null)
	{
		
		
		
		if ($this->_item === null)
		{
			$this->_item = false;

			if (empty($id)) {
				$id = $this->getState('project.id');				
			}

			// Get a level row instance.
			$table = $this->getTable();

			// Attempt to load the row.
			if ($table->load($id))
			{
				// Check published state.
				if ($published = $this->getState('filter.published'))
				{
					if ($table->state != $published) {
						return $this->_item;
					}
				}

				// Convert the JTable to a clean JObject.
				$properties = $table->getProperties(1);
				$this->_item = JArrayHelper::toObject($properties, 'JObject');
			} elseif ($error = $table->getError()) {
				$this->setError($error);
			}
		}
		
		
		
		
		if (empty($this->_item)){
			return JError::raiseError(404, JText::_('COM_MB2PORTFOLIO_ERROR_PROJECT_NOT_FOUND'));
		}
		
		
		
		
		return $this->_item;	
		
		
		
		
	}
	
	
	
	
	
	
	
    
	public function getTable($type = 'Project', $prefix = 'Mb2portfolioTable', $config = array())
	{   
        $this->addTablePath(JPATH_COMPONENT_ADMINISTRATOR.'/tables');
        return JTable::getInstance($type, $prefix, $config);
	}  
	
	
	
	
	
	
	/**
	 * Method to title of project skills
	 *
	 *
	 */
	 public function getSkillTitle($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('title')
                ->from('#__mb2portfolio_skills')
                ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }
	
	
	
	
	
	
	
	/**
	 * Method to alias of project skills
	 *
	 *
	 */
	 public function getSkillAlias($id) {
        $db = JFactory::getDbo();
        $query = $db->getQuery(true);
        $query
                ->select('alias')
                ->from('#__mb2portfolio_skills')
                ->where('id = ' . $id);
        $db->setQuery($query);
        return $db->loadObject();
    }
	
	
	
	
	
	
	
	
	
	
		
	
	
	
	//get item navigation
	function item_navigation($direction = 'next'){
		
		$app = JFactory::getApplication();		
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$lang = JFactory::getLanguage();
		$id=JRequest::getInt('id');		
		
		
		$query .='SELECT * ';		
		
		
		
		$query .='FROM #__mb2portfolio WHERE state=1 AND access IN('.implode(',',$access_level).')';	
		
		
		
		
		
		if($app->getLanguageFilter()){
			$query .= ' AND (language="'. $lang->getTag() .'" OR language="*")';
		}
		
		
		if($direction=='prev'){			
			$query .=' AND id<'.$id.' ORDER BY id DESC LIMIT 1';
		}
		else{
			$query .=' AND id>'.$id.' ORDER BY id ASC LIMIT 1';			
		}		
		
		
		$db->setQuery($query);
	
		$row = $db->loadObject();		
		
		if($row)
		{
			return $row;
		}					
		
			
	}
	
	
	
	
	
	
	
	
	//get portfolio back link
	function back_to_portfolio(){
		
		$app = JFactory::getApplication();
		$app->getLanguageFilter();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$lang->getTag();
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		
				
		
		$query .= 'SELECT * FROM #__menu WHERE published=1 AND link="index.php?option=com_mb2portfolio&view=projects&id=0" AND access IN('.implode(',',$access_level).')';	
		
		if($app->getLanguageFilter()){
			$query .= ' AND (language="'. $lang->getTag() .'" OR language="*")';
		}
			
		
		
		$db->setQuery($query);
		
	
		$rows = $db->loadObjectList();
		
		
		
		if($rows){		
			foreach($rows as $row){
				return $row->link.'&amp;Itemid='.$row->id;				
			}			
		}
		else{
			return JURI::base();
		}
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//get extra fields
	function get_extra_field($id=null){	
		
		$app = JFactory::getApplication();
		$app->getLanguageFilter();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$lang->getTag();
		$db = $this->getDbo();
		$query = $db->getQuery(true);	
		
		
		
		
		$query .= 'SELECT * FROM #__mb2portfolio_extra_fields WHERE state=1 AND id='.$id.' AND access IN('.implode(',',$access_level).')';
		
		if($app->getLanguageFilter()){
			$query .= ' AND (language="'. $lang->getTag() .'" OR language="*")';
		}	
		
			
				
		$db->setQuery($query);
		//$db->query();	
		
		$rows = $db->loadObject();		
		
		return $rows;		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to get a list of related projects.
	 *
	 * Overriden to inject convert the attribs field into a JParameter object.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 */	
	function get_related_items($id, $skills = array()){	
				
		$projects_model = JModelLegacy::getInstance('Projects', 'Mb2portfolioModel', array('ignore_request' => true));
		
		$projects_model->setState('filter.published', 1);
		
			
		$items = $projects_model->getItems();	
		
		$i=-1;
		
		
				
				
			foreach ($items as $item )
			{
					
				if ($item->id != $id && (			
				($item->skill_1 == $skills[0] || $item->skill_1 == $skills[1] || $item->skill_1 == $skills[2] || $item->skill_1 == $skills[3] || $item->skill_1 == $skills[4]) || 			
				($item->skill_2 != 0 && ($item->skill_2 == $skills[0] || $item->skill_2 == $skills[1] || $item->skill_2 == $skills[2] || $item->skill_2 == $skills[3] || $item->skill_2 == $skills[4])) ||
				($item->skill_3 != 0 && ($item->skill_3 == $skills[0] || $item->skill_3 == $skills[1] || $item->skill_3 == $skills[2] || $item->skill_3 == $skills[3] || $item->skill_3 == $skills[4])) ||
				($item->skill_4 != 0 && ($item->skill_4 == $skills[0] || $item->skill_4 == $skills[1] || $item->skill_4 == $skills[2] || $item->skill_4 == $skills[3] || $item->skill_4 == $skills[4])) ||
				($item->skill_5 != 0 && ($item->skill_5 == $skills[0] || $item->skill_5 == $skills[1] || $item->skill_5 == $skills[2] || $item->skill_5 == $skills[3] || $item->skill_5 == $skills[4]))
				)) 				
				{
					$item->related = true;						
						
				}
				else
				{
					$item->related = false;
						
				}
					
					
			}
				
		
		
		
		
		// Get related items on single project page
		/*
		
		$related = $globalParams->get('related_projects', 1);
		$skill_1 = $app->input->get('skill_1');
		$skill_2 = $app->input->get('skill_2');
		$skill_3 = $app->input->get('skill_3');
		$skill_4 = $app->input->get('skill_4');
		$skill_5 = $app->input->get('skill_5');
		
		
		if($related == 1 && $view == 'project')
		{
			
			$query->where('a.id!=' . $id );
			$query->where('a.state=1 AND ((a.skill_1=' . $skill_1 . ' OR a.skill_1=' . $skill_2 . ' OR a.skill_1=' . $skill_3 . ' OR a.skill_1=' . $skill_4 . ' OR a.skill_1=' . $skill_5 .') OR (a.skill_2!=0 AND (a.skill_2=' . $skill_1 . ' OR a.skill_2=' . $skill_2 . ' OR a.skill_2=' . $skill_3 . ' OR a.skill_2=' . $skill_4 . ' OR a.skill_2=' . $skill_5 .')) OR (a.skill_3!=0 AND (a.skill_3=' . $skill_1 . ' OR a.skill_3=' . $skill_2 . ' OR a.skill_3=' . $skill_3 . ' OR a.skill_3=' . $skill_4 . ' OR a.skill_3=' . $skill_5 .')) OR (a.skill_4!=0 AND (a.skill_4=' . $skill_1 . ' OR a.skill_4=' . $skill_2 . ' OR a.skill_4=' . $skill_3 . ' OR a.skill_4=' . $skill_4 . ' OR a.skill_4=' . $skill_5 .')) OR (a.skill_5!=0 AND (a.skill_5=' . $skill_1 . ' OR a.skill_5=' . $skill_2 . ' OR a.skill_5=' . $skill_3 . ' OR a.skill_5=' . $skill_4 . ' OR a.skill_5=' . $skill_5 .')))' );
			
		
			
						
			
			
		}
		
		*/
		
		
		
		
		
		
		
		

		return $items;
		
				
	}
	
	
	
	
	
	
	   

    
	/**
	 * Method to check in an item.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkin($id = null)
	{
		// Get the id.
		$id = (!empty($id)) ? $id : (int)$this->getState('project.id');

		if ($id) {
            
			// Initialise the table
			$table = $this->getTable();

			// Attempt to check the row in.
            if (method_exists($table, 'checkin')) {
                if (!$table->checkin($id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}

		return true;
	}

	/**
	 * Method to check out an item for editing.
	 *
	 * @param	integer		The id of the row to check out.
	 * @return	boolean		True on success, false on failure.
	 * @since	1.6
	 */
	public function checkout($id = null)
	{
		// Get the user id.
		$id = (!empty($id)) ? $id : (int)$this->getState('project.id');

		if ($id) {
            
			// Initialise the table
			$table = $this->getTable();

			// Get the current user object.
			$user = JFactory::getUser();

			// Attempt to check the row out.
            if (method_exists($table, 'checkout')) {
                if (!$table->checkout($user->get('id'), $id)) {
                    $this->setError($table->getError());
                    return false;
                }
            }
		}

		return true;
	} 
	
	
	
	
	
	
	/**
	 * Method to get the number of project hits.
     * 
	 * @param 	integer		id of the project
	 *
	 */
	
	public function hit($id = null)
	{
		
		if (empty($id))
		{
			$id = $this->getState('project.id');
		}
		
		$project = $this->getTable('Project', 'Mb2portfolioTable');	
			
		return $project->hit($id);
		
	}
	
	
	
	   
    
	/**
	 * Method to get the profile form.
	 *
	 * The base form is loaded from XML 
     * 
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm	A JForm object on success, false on failure
	 * @since	1.6
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_mb2portfolio.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		return $form;
	}

	

	/**
	 * Method to save the form data.
	 *
	 * @param	array		The form data.
	 * @return	mixed		The user id on success, false on failure.
	 * @since	1.6
	 */
	public function save($data)
	{
		$id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('project.id');
        $state = (!empty($data['state'])) ? 1 : 0;
        $user = JFactory::getUser();

        if($id) {
            //Check the user can edit this item
            $authorised = $user->authorise('core.edit', 'com_mb2portfolio') || $authorised = $user->authorise('core.edit.own', 'com_mb2portfolio');
            if($user->authorise('core.edit.state', 'com_mb2portfolio') !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        } else {
            //Check the user can create new items in this section
            $authorised = $user->authorise('core.create', 'com_mb2portfolio');
            if($user->authorise('core.edit.state', 'com_mb2portfolio') !== true && $state == 1){ //The user cannot edit the state of the item.
                $data['state'] = 0;
            }
        }

        if ($authorised !== true) {
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        
        $table = $this->getTable();
        if ($table->save($data) === true) {
            return $id;
        } else {
            return false;
        }
        
	}
    
     function delete($data)
    {
        $id = (!empty($data['id'])) ? $data['id'] : (int)$this->getState('project.id');
        if(JFactory::getUser()->authorise('core.delete', 'com_mb2portfolio') !== true){
            JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
            return false;
        }
        $table = $this->getTable();
        if ($table->delete($data['id']) === true) {
            return $id;
        } else {
            return false;
        }
        
        return true;
    }
    
}