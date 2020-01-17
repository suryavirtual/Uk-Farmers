<?php

/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/
defined('_JEXEC') or die;

jimport('joomla.application.component.modellist');

/**
 * Methods supporting a list of Mb2portfolio records.
 */
class Mb2portfolioModelSkills extends JModelList {

    /**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()) {
        parent::__construct($config);
    }

    /**
     * Method to auto-populate the model state.
     *
     * Note. Calling getState in this method will result in recursion.
     *
     * @since	1.6
     */
    protected function populateState($ordering = null, $direction = null) {
        
        // Initialise variables.
        $app = JFactory::getApplication();
		$params = $app->getParams();
		$this->setState('params', $params);

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));
        $this->setState('list.limit', $limit);

        $limitstart = JFactory::getApplication()->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        
		if(empty($ordering)) {
			$ordering = 'a.ordering';
		}
        
		
		
		// Process show_noauth parameter
		if (!$params->get('show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}
		
		
		
		// Filter language
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
		
		
		
		
		
        // List state information.
        parent::populateState($ordering, $direction);
    }
	
	
	
	
	
	

    /**
     * Build an SQL query to load the list data.
     *
     * @return	JDatabaseQuery
     * @since	1.6
     */
    protected function getListQuery() {
        
		// Initialise variables.
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$groups = implode(',', $access_level);
		
		//get params
		$params = JComponentHelper::getParams('com_mb2portfolio');
		$order = $params->get('skills_order', 'DESC');
		$order_by = $params->get('skills_order_by', 'id');
		
		
		
		// Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
        
        $query->from('`#__mb2portfolio_skills` AS a');
		$query->order('a.'.$order_by.' '.$order.'');
		
		
		
		// Create language filter
		$this->setState('filter.language', JLanguageMultilang::isEnabled());	
		$lang = JFactory::getLanguage();		
		if($this->getState('filter.language')){			
			$query->where('a.language="'.$lang->getTag().'" OR a.language="*"');		
			
		}
		
		
		
		
		
		
		$query->where('a.state=1');
        

    	// Join over the users for the checked out user.
    	$query->select('uc.name AS editor');
    	$query->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');
    	
		
		
		
		// Join over the mb2 portfolio for the checked out project count in skill.
    	$query->select('count.id AS project_count');
    	$query->join('LEFT', '#__mb2portfolio AS count ON count.id=a.id');
		
		
	
	
	
		// Join over the created by field 'created_by'
		$query->select('created_by.name AS created_by');
		$query->join('LEFT', '#__users AS created_by ON created_by.id = a.created_by');



		// Filter by access level
		if ($access = $this->getState('filter.access'))
		{		
			$query->where('a.access IN (' . $groups . ')');
						
		}





		// Filter by search in title
		$search = $this->getState('filter.search');
		if (!empty($search)) {
			if (stripos($search, 'id:') === 0) {
				$query->where('a.id = '.(int) substr($search, 3));
			} else {
				$search = $db->Quote('%'.$db->escape($search, true).'%');
                
			}
		}
        
        
        
        return $query;
    }
	
	
	
	
	
	
	
	
	
	/**
	 * Method to get a list of skills
	 *
	 * Overriden to inject convert the attribs field into a JParameter object.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 */
	 public function getItems() {
        
				
		// Get core variables
		$items = parent::getItems();
		$user = JFactory::getUser();
		$userId = $user->get('id');
		$guest = $user->get('guest');
		$groups = $user->getAuthorisedViewLevels();
		$input = JFactory::getApplication()->input;
		$lang_tag = JFactory::getLanguage()->getTag();
        
		
		
		
		foreach($items as &$item){
	
			
			
			
			// Acces view			
			
			if ($this->getState('filter.access'))
			{
				$item->access_filter = false;
			}
			else
			{
						
				$item->access_filter = in_array($item->access,$groups);				
			}
			
			
			
			
			
			
			// Language filter		
			
			if(($item->language == $lang_tag || $item->language == '*'))
			{
				
				$item->lang_filter = true;
				
			}
			else
			{
				
				$item->lang_filter = false;
				
			}
				
		} // End foreach
		
		
		
        return $items;
    }
	
	
	
	
	

}
