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
    public function __construct($config = array()) {
		
		
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(				
				'access', 'a.access', 'access_level'
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

        // List state information
        $limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'));		
       	$this->setState('list.limit', $limit);

        $limitstart = $app->input->getInt('limitstart', 0);
        $this->setState('list.start', $limitstart);
        
        
		//if(empty($ordering)){
		//	$ordering = 'a.ordering';
		//}
		
		$orderCol   = 'a.ordering';
        $this->setState('list.ordering', $orderCol);
        
        $listOrder  = 'DESC';
        $this->setState('list.direction', $listOrder);
        
        // List state information
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
		
		//get params
		$params = JComponentHelper::getParams('com_mb2portfolio');						
		$limit = $params->get('projects_limit', 12);
		$order = $params->get('projects_order', 'DESC');
		$order_by = $params->get('projects_order_by', 'id');
	
		
		
		// Create a new query object.
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        // Select the required fields from the table.
        $query->select(
                $this->getState(
                        'list.select', 'a.*', 'v.access'
                )
        );
		
		
		$id=JRequest::getInt('id');
        
        $query->from('`#__mb2portfolio` AS a');
		$query->order('a.'.$order_by.' '.$order.'');		
			
		
		
		//get language filter
		$this->setState('filter.language', JLanguageMultilang::isEnabled());	
		$lang = JFactory::getLanguage();		
		if($this->getState('filter.language')){			
					
			if($id != 0){
				$query->where('a.state=1 AND (a.language="'.$lang->getTag().'" OR a.language="*") AND (skill_1='.$id.' OR skill_2='.$id.' OR skill_3='.$id.' OR skill_4='.$id.' OR skill_5='.$id.')');	
			}	
			
			else{
				$query->where('a.state=1 AND (a.language="'.$lang->getTag().'" OR a.language="*")');	
			}
			
						
		}
		else{		
			
			if($id != 0){
				$query->where('a.state=1 AND (a.skill_1='.$id.' OR a.skill_2='.$id.' OR a.skill_3='.$id.' OR a.skill_4='.$id.' OR a.skill_5='.$id.')');	
			}
			else{
				$query->where('a.state=1');
			}	
		}		
		
		       
		
		// Join over the foreign key 'skill_1'
		$query->select('c.access AS skill1_access');
		$query->join('LEFT', '#__mb2portfolio_skills AS c ON c.id = a.skill_1');
		// Join over the foreign key 'skill_2'
		$query->select('d.access AS skill2_access');
		$query->join('LEFT', '#__mb2portfolio_skills AS d ON d.id = a.skill_2');
		// Join over the foreign key 'skill_3'
		$query->select('e.access AS skill3_access');
		$query->join('LEFT', '#__mb2portfolio_skills AS e ON e.id = a.skill_3');
		// Join over the foreign key 'skill_4'
		$query->select('f.access AS skill1_access');
		$query->join('LEFT', '#__mb2portfolio_skills AS f ON f.id = a.skill_4');
		// Join over the foreign key 'skill_5'
		$query->select('g.access AS skill1_access');
		$query->join('LEFT', '#__mb2portfolio_skills AS g ON g.id = a.skill_5');
				
				
		
		
		
		
		
		// Filter by user access		
		$access = $this->getState('filter.access');			
		if($access){
			$query
			->where('v.access IN(' . implode(',', $access_level) . ')')
			->where('c.access IN(' . implode(',', $access_level) . ')')
			->where('d.access IN(' . implode(',', $access_level) . ')')
			->where('e.access IN(' . implode(',', $access_level) . ')')
			->where('f.access IN(' . implode(',', $access_level) . ')')
			->where('g.access IN(' . implode(',', $access_level) . ')'); 			
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
        
			
		
					
		// List state information       		
       	$this->setState('list.limit', $limit);		
				
       	$limitstart = $app->input->getInt('limitstart', 0);
       	$this->setState('list.start', $limitstart);
		
		       
        
        return $query;
		
		
		
		
		
		
		
    }
	
	
	
	
	
	
	
	
	
	
	
	
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//get project skills access array
	public static function get_project_skills_access($s_id1,$s_id2,$s_id3,$s_id4,$s_id5){
		
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$i=0;
		
		
		$query .='SELECT access FROM #__mb2portfolio_skills WHERE state=1 AND (id='.$s_id1.' OR id='.$s_id2.' OR id='.$s_id3.' OR id='.$s_id4.' OR id='.$s_id5.')';	
		
		
		$db->setQuery($query);
		//$db->query();
			
		
		$rows = $db->loadObjectList();
		
					
	
				
		foreach($rows as $row){	
				
			if($row->access && !in_array($row->access,$access_level)){				
				return false;
			}
			else{
				return true;	
			}
									
		}
		
		
		
	}
	
	

	
	
	
	
	
	
	
	
	
	//get project skills array
	public static function get_project_skills($s_id1,$s_id2,$s_id3,$s_id4,$s_id5){
		
		
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$access_level = $user->getAuthorisedViewLevels();
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$i=0;
		
		
		$query .='SELECT * FROM #__mb2portfolio_skills WHERE state=1 AND access IN('.implode(',',$access_level).') AND (id='.$s_id1.' OR id='.$s_id2.' OR id='.$s_id3.' OR id='.$s_id4.' OR id='.$s_id5.')';	
		
		
		$db->setQuery($query);
		//$db->query();
			
		
		$rows = $db->loadObjectList();
		
		
		return $rows;
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
		
	
	//get skills list
	public static function get_skills_list($skills=0,$s_id1,$s_id2,$s_id3,$s_id4,$s_id5,$link=0){
		
		
		$app = JFactory::getApplication();
		$menus = $app->getMenu('site');
		$active = $menus->getActive();
		$i=0;	
		$output = '';
			
		
		$rows = Mb2portfolioModelProjects::get_project_skills($s_id1,$s_id2,$s_id3,$s_id4,$s_id5);
		
		
		$count = count($rows);
		
		
		
		if($skills == 1){
			
			//$output .= '<div class="mb2-portfolio-skills"><ul class="mb2-portfolio-skill-list">';
			$output .= '<ul class="mb2-portfolio-skill-list">';
			
			
			foreach($rows as $row){
								
				
				$i++;	
				if($count > $i){
					
					if($link == 1){				
						$output .= '<li><a href="'.JRoute::_(Mb2portfolioHelperRoute::getProjectsRoute($row->id,Mb2portfolioModelProjects::menu_item_id(0,$row->id))).'">'.$row->title.'</a>,</li> ';
						}
					else{
						$output .= '<li>'.$row->title.',</li> ';				
					}		
				}
				else{
					if($link == 1){				
						$output .= '<li><a href="'.JRoute::_(Mb2portfolioHelperRoute::getProjectsRoute($row->id,Mb2portfolioModelProjects::menu_item_id(0,$row->id))).'">'.$row->title.'</a></li>';
						}
					else{
						$output .= '<li>'.$row->title.'</li>';				
					}
				}	
				
				
			}//end foreach	
			
			
			//$output .= '</ul></div><!-- end .portfolio-item-skills -->';
			$output .= '</ul>';			
			
		}	
		
		
		echo $output;		
		
	}	
	
	
	
	
	
	
	
		
	

}