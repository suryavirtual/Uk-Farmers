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
// Get params
jimport( 'joomla.application.component.helper' );


	

/**
 * Methods supporting a list of Mb2portfolio records.
 */
class Mb2portfolioModelProjects extends JModelList {

    
	
	
	
	/**
     * Constructor.
     *
     * @param    array    An optional associative array of configuration settings.
     * @see        JController
     * @since    1.6
     */
    public function __construct($config = array()){	
	
		
		
		if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id', 'a.id',
                'title', 'a.title',
                'alias', 'a.alias',
                'state', 'a.state',
                'skill_1', 'a.skill_1',
				'skill_2', 'a.skill_2',
				'skill_3', 'a.skill_3',
				'skill_4', 'a.skill_4',
				'skill_5', 'a.skill_5',
                'intro_text', 'a.intro_text',
                'full_text', 'a.full_text',
                'images', 'a.images',
                'created', 'a.created',
                'created_by', 'a.created_by',
                'created_by_alias', 'a.created_by_alias',
                'modified', 'a.modified',
                'modified_by', 'a.modified_by',
                'access', 'a.access',
                'ordering', 'a.ordering',
                'hits', 'a.hits',
                'metadesc', 'a.metadesc',
                'extra_fields', 'a.extra_fields',
                'links', 'a.links',
                'language', 'a.language',
				'layout', 'a.layout',
				'video', 'a.video',
				'metadata', 'a.metadata',
				'extra_fields', 'a.extra_fields',
				'links', 'a.links'
			);
        }	
		
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
		$globalParams = JComponentHelper::getParams('com_mb2portfolio');
		$skillid = JRequest::getInt('id');
		$this->setState('params', $params);
		
			
			    
		// Ordering
        $this->setState('list.ordering', 'a.ordering');		       
        $this->setState('list.direction', 'DESC'); 
		
		
		
		// Published filter	
		$this->setState('filter.published', 1);
		
		
		
		// Process show_noauth parameter
		if (!$params->get('show_noauth'))
		{
			$this->setState('filter.access', true);
		}
		else
		{
			$this->setState('filter.access', false);
		}
		
		
		
		// Filter by skillid
		if( $skillid)
		{
			$this->setState('filter.skillid', (int) $skillid);			
		}
		
		
		
		// Filter language
		$this->setState('filter.language', JLanguageMultilang::isEnabled());
		
	
		
		// List state information
        parent::populateState($ordering, $direction);
		
		
		
		
		// List state information	     		
       	$this->setState('list.limit', $globalParams->get('projects_limit', 12));		
       	$this->setState('list.start', $app->input->getInt('limitstart', 0));
		
			
		
		
    }
	
	
	
	
	
	
	/**
	 * Method to get a store id based on model configuration state.
	 *
	 * This is necessary because the model is used by the component and
	 * different modules that might need different sets of data or different
	 * ordering requirements.
	 *
	 * @param   string  $id  A prefix for the store id.
	 *
	 * @return  string  A store id.
	 *
	 * @since   1.6
	 */
	protected function getStoreId($id = '')
	{
		// Compile the store id.
		$id .= ':' . serialize($this->getState('filter.published'));
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . serialize($this->getState('filter.skillid'));

		return parent::getStoreId($id);
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
		$lang_tag = JFactory::getLanguage()->getTag();
		$globalParams = JComponentHelper::getParams('com_mb2portfolio');						
		
		
	

		
		// Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*'
                )
        );
		
		
        
        $query->from('`#__mb2portfolio` AS a');
	//$query->order('a.' . $order_by . ' ' . $order );
         $query->order('ordering');			
		
		
			
		// Published filter
				
		$published = $this->getState('filter.published');
				
		if(is_numeric($published))
		{
			$query->where('a.state=' . $published . '');
		}
		
		
		  
		
		// Join over the foreign key 'skill_1'
		$query->select('skill_1.id AS skill1_id, skill_1.title AS skill1_title, skill_1.alias AS skill1_alias, skill_1.access AS skill1_access, skill_1.language AS skill1_language');
		$query->join('LEFT', '#__mb2portfolio_skills AS skill_1 ON skill_1.id = a.skill_1');
		
		
		
		// Join over the foreign key 'skill_2'
		$query->select('skill_2.id AS skill2_id, skill_2.title AS skill2_title, skill_2.alias AS skill2_alias, skill_2.access AS skill2_access, skill_2.language AS skill2_language');
		$query->join('LEFT', '#__mb2portfolio_skills AS skill_2 ON skill_2.id = a.skill_2');
		
		
		
		// Join over the foreign key 'skill_1'
		$query->select('skill_3.id AS skill3_id, skill_3.title AS skill3_title, skill_3.alias AS skill3_alias, skill_3.access AS skill3_access, skill_3.language AS skill3_language');
		$query->join('LEFT', '#__mb2portfolio_skills AS skill_3 ON skill_3.id = a.skill_3');
		
		
		
		
		// Join over the foreign key 'skill_1'
		$query->select('skill_4.id AS skill4_id, skill_4.title AS skill4_title, skill_4.alias AS skill4_alias, skill_4.access AS skill4_access, skill_4.language AS skill4_language');
		$query->join('LEFT', '#__mb2portfolio_skills AS skill_4 ON skill_4.id = a.skill_4');
		
		
		
		
		// Join over the foreign key 'skill_1'
		$query->select('skill_5.id AS skill5_id, skill_5.title AS skill5_title, skill_5.alias AS skill5_alias, skill_5.access AS skill5_access, skill_5.language AS skill5_language');
		$query->join('LEFT', '#__mb2portfolio_skills AS skill_5 ON skill_5.id = a.skill_5');
		
		
		
		
		
		
		
		
		//  Filter by skill	
		
		$skillid = $this->getState('filter.skillid');
		
		if(is_numeric($skillid))
		{
			// Filter by a single skill
			$query->where('a.skill_1=' . (int) $skillid . ' OR (a.skill_2!=0 AND a.skill_2=' . (int) $skillid . ') OR (a.skill_3!=0 AND a.skill_3=' . (int) $skillid . ') OR (a.skill_4!=0 AND a.skill_4=' . (int) $skillid . ') OR (a.skill_5!=0 AND a.skill_5=' . (int) $skillid .')' );	
		}
		
		
				   
			
		
		
		
			
		
		
		// Filter by language
		if ($this->getState('filter.language'))
		{
			$query->where('a.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ')');
			$query->where('skill_1.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ')');
			
			$query->where('CASE WHEN skill_2.language IS NOT NULL THEN skill_2.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') ELSE skill_1.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') END');
			$query->where('CASE WHEN skill_3.language IS NOT NULL THEN skill_3.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') ELSE skill_1.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') END');
			$query->where('CASE WHEN skill_4.language IS NOT NULL THEN skill_4.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') ELSE skill_1.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') END');
			$query->where('CASE WHEN skill_5.language IS NOT NULL THEN skill_5.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') ELSE skill_1.language IN (' . $db->quote($lang_tag) . ',' . $db->quote('*') . ') END');
			
			
		}
		
		
				
		
		
		
		
		// Filter by access level
		if ($access = $this->getState('filter.access'))
		{			
			
			$query
			->where('a.access IN (' . $groups . ')')
			->where('skill_1.access IN (' . $groups . ')')
			
			// Check access to skills wich are not require		
			->where('CASE WHEN skill_2.access IS NOT NULL THEN skill_2.access IN (' . $groups . ') ELSE skill_1.access IN (' . $groups . ') END')
			->where('CASE WHEN skill_3.access IS NOT NULL THEN skill_3.access IN (' . $groups . ') ELSE skill_1.access IN (' . $groups . ') END')
			->where('CASE WHEN skill_4.access IS NOT NULL THEN skill_4.access IN (' . $groups . ') ELSE skill_1.access IN (' . $groups . ') END')
			->where('CASE WHEN skill_5.access IS NOT NULL THEN skill_5.access IN (' . $groups . ') ELSE skill_1.access IN (' . $groups . ') END')
			;
						
		}
		
		
		
		
		
		
		
		   
		
		
		// Add the list ordering clause.
		//$query->order($this->getState('list.ordering', $globalParams->get('projects_order_by', 'id')) . ' ' . $this->getState('list.direction', $globalParams->get('projects_order', 'DESC')));	   
		   
		   
		 
		   
		       
        
        return $query;
		
		
		
		
		
		
		
    }
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to get a list of projects.
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
        
		
		//$query = $this->_getListQuery();
        //$items = $this->_getList($query, $this->getStart(), $this->getState('list.limit'));
		//$items = $this->_getList($query, $this->getState('limitstart'), $this->getState('list.limit'));
		
		foreach($items as &$item){
	
			
			
			
			
			// Acces view			
			
			if ($this->getState('filter.access'))
			{
				$item->access_filter = false;
			}
			else
			{
						
				$item->access_filter = 
					in_array($item->access,$groups) && 
					in_array($item->skill1_access,$groups) && 
					($item->skill2_access ? in_array($item->skill2_access,$groups) : in_array($item->skill1_access,$groups)) && 
					($item->skill3_access ? in_array($item->skill3_access,$groups) : in_array($item->skill1_access,$groups)) && 
					($item->skill4_access ? in_array($item->skill4_access,$groups) : in_array($item->skill1_access,$groups)) && 
					($item->skill5_access ? in_array($item->skill5_access,$groups) : in_array($item->skill1_access,$groups))
				;				
			}		
			
			
			
			
			
			
			
			
			// Language filter		
			
			if( ($item->language == $lang_tag || $item->language == '*') &&
				($item->skill1_language == $lang_tag || $item->skill1_language == '*') &&
				($item->skill2_language ? $item->skill2_language == $lang_tag || $item->skill2_language == '*' : $item->skill1_language == $lang_tag || $item->skill1_language == '*') &&
				($item->skill3_language ? $item->skill3_language == $lang_tag || $item->skill3_language == '*' : $item->skill1_language == $lang_tag || $item->skill1_language == '*') &&
				($item->skill4_language ? $item->skill4_language == $lang_tag || $item->skill4_language == '*' : $item->skill1_language == $lang_tag || $item->skill1_language == '*') &&
				($item->skill5_language ? $item->skill5_language == $lang_tag || $item->skill5_language == '*' : $item->skill1_language == $lang_tag || $item->skill1_language == '*'))
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to get a list of related projects.
	 *
	 * Overriden to inject convert the attribs field into a JParameter object.
	 *
	 * @return  mixed  An array of objects on success, false on failure.
	 *
	 */	
	function get_skills_list(){	
				
		$skills_model = JModelLegacy::getInstance('Skills', 'Mb2portfolioModel', array('ignore_request' => true));
			
		$items = $skills_model->getItems();	

		return $items;
		
				
	}
	
	
	
	
	
	
	
	
	
	
	
	/*
	//load skills to dipslay portfolio filter
	public static function get_skills(){		
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
			
	
		
		$query .='SELECT * FROM #__mb2portfolio_skills WHERE state=1 AND access IN('.implode(',',$access_level).')';
		
		if($app->getLanguageFilter()){
			$query .=' AND (language="'.$lang->getTag().'" OR language="*")';
		}			
					
		$db->setQuery($query);
		//$db->query();			
			
		$row = $db->loadObjectList();	
		
		
		return $row;                                
	}
	
	
	
	
	
	*/
	
	
	
	
	
	
	
	
	//get skills from project
	public static function get_skills_from_project($project_id=0){		
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
			
		
		
		if($project_id !=0){
		
			$query .='SELECT * FROM #__mb2portfolio WHERE id='. $project_id .' AND state=1 AND access IN('. implode(',',$access_level) .')';
			
			if($app->getLanguageFilter()){
				$query .=' AND (language="'. $lang->getTag() .'" OR language="*")';
			}			
						
			$db->setQuery($query);
			//$db->query();			
				
			$row = $db->loadObject();	
			
			
			return $row;   
		
		}
		else{
			
			return false;	
		}
		
		
		                            
	}
	
	
	
	
	
	
	
	
	//check if is menu item to single project
	public static function get_single_project_menu_id($project_id){
		
		
		//$project = $this->get_skills_from_project($project_id);
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);		
				
		
		
		
		$query .='SELECT id FROM #__menu WHERE published=1 AND access IN('.implode(',',$access_level).')';
		
		
		if($app->getLanguageFilter()){
			$query .=' AND (language="'.$lang->getTag().'" OR language="*")';
		}	
			
		
		$query .=' AND link="index.php?option=com_mb2portfolio&view=project&id='.$project_id.'"';
			
		
		
					
					
		$db->setQuery($query);
		//$db->query();			
			
		$row = $db->loadResult();	
		
		
		return $row;		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	//check if is menu item to skill which is attached as one of project skills
	public static function get_projects_menu_id_by_skill($project_id){
		
		
		$project = Mb2portfolioModelProjects::get_skills_from_project($project_id);
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);
		
			
		
		$query .= 'SELECT id FROM #__menu WHERE published=1 AND access IN('.implode(',',$access_level).')';	
		
		
		
		if($app->getLanguageFilter()){
			$query .= ' AND (language="'. $lang->getTag() .'" OR language="*")';
		}
		
		
		
		if($project){			
			
			$query .= ' AND (link="index.php?option=com_mb2portfolio&view=projects&id=' . $project->skill_1. '"';		
			$project->skill_2 !=0 ? $query .=' OR link="index.php?option=com_mb2portfolio&view=projects&id='.$project->skill_2.'"' : '';		
			$project->skill_3 !=0 ? $query .=' OR link="index.php?option=com_mb2portfolio&view=projects&id='.$project->skill_3.'"' : '';
			$project->skill_4 !=0 ? $query .=' OR link="index.php?option=com_mb2portfolio&view=projects&id='.$project->skill_4.'"' : '';
			$project->skill_5 !=0 ? $query .=' OR link="index.php?option=com_mb2portfolio&view=projects&id='.$project->skill_5.'"' : '';
			
			$query .= ')';		
		
		}
					
					
		$db->setQuery($query);
		//$db->query();			
			
		$row = $db->loadResult();	
		
		
		return $row;		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	//check if is menu item to skill
	public static function get_skill_menu_id($skill_id){		
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
        $query = $db->getQuery(true);		
				
		
		
		
		$query .='SELECT id FROM #__menu WHERE published=1 AND access IN('.implode(',',$access_level).')';
		
		
		if($app->getLanguageFilter()){
			$query .= ' AND (language="'. $lang->getTag() .'" OR language="*")';
		}	
		
		
		$query .=' AND link="index.php?option=com_mb2portfolio&view=projects&id='.$skill_id.'"';	
		
					
					
		$db->setQuery($query);
		//$db->query();			
			
		$row = $db->loadResult();	
		
		
		return $row;		
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//get menu itemid
	public static function menu_item_id($project_id=0,$skill_id=0){
		
		
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);	
		$output='';	
		
		
		
		
		
		if($skill_id != 0){
			
			
			$skill_menu_item = Mb2portfolioModelProjects::get_skill_menu_id($skill_id);
			
			if($skill_menu_item){		
				$output .= $skill_menu_item;			
			}
			else{
				$output .= Mb2portfolioModelProjects::portfolio_menu_item_id();
			}
			
			
		}
		else{
			
			
			$single_project_menu_item = Mb2portfolioModelProjects::get_single_project_menu_id($project_id);
			$skill_project_menu_item = Mb2portfolioModelProjects::get_projects_menu_id_by_skill($project_id);	
						
				
			if($single_project_menu_item){
				$output .= $single_project_menu_item;				
			}
			elseif($skill_project_menu_item){
				$output .= $skill_project_menu_item;
			}
			else{
				$output .= Mb2portfolioModelProjects::portfolio_menu_item_id();
			}
			
				
			
		}
		
		
		
		
		
		return $output;	
		
		
			
	}
	
	
	
	
	
	
	
	
	//get portfolio page menu itemid
	public static function portfolio_menu_item_id(){
		
		
		
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');
		$active = $menus->getActive();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$lang = JFactory::getLanguage();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$params = JComponentHelper::getParams('com_mb2portfolio');						
		$portfolio_page = $params->get('portfolio_page', '');
		
		
		
		$query .='SELECT id FROM #__menu WHERE published=1 AND access IN('.implode(',',$access_level).')';
		
	
		if($app->getLanguageFilter()){
			$query .= ' AND (language="'. $lang->getTag() .'" OR language="*")';
		}		
		
		
		$query .=' AND link="index.php?option=com_mb2portfolio&view=projects&id=0"';
		
				
		
		
		$db->setQuery($query);
		//$db->query();
			
		
		$row = $db->loadResult();
		
		
		
		if($portfolio_page !=''){
			
			return $portfolio_page;
			
		}
		elseif(isset($row)){
			
			return $row;
			
		}	
		else{
			
			return $active->id;	
			
		}
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	
	
	
	
	
	
	
		
	

}
