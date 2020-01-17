<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );





class PlgSearchMb2portfolio extends JPlugin
{
	
	
	
	
	
		/**
         * Constructor
         *
         * @access      protected
         * @param       object  $subject The object to observe
         * @param       array   $config  An array that holds the plugin configuration
         * @since       1.6
         */
        public function __construct(& $subject, $config)
        {
                parent::__construct($subject, $config);
                $this->loadLanguage();
        }
		
		
		
		
		
		
		
		
		function onContentSearchAreas()
        {
                static $areas = array(
                        'mb2portfolio' => 'Mb2portfolio'
                );
                return $areas;
        }
		
		
		
		
		 /**
	 * Search content (weblinks).
	 *
	 * The SQL must return the following fields that are used in a common display
	 * routine: href, title, section, created, text, browsernav
	 *
	 * @param   string  $text      Target search string.
	 * @param   string  $phrase    Matching option (possible values: exact|any|all).  Default is "any".
	 * @param   string  $ordering  Ordering option (possible values: newest|oldest|popular|alpha|category).  Default is "newest".
	 * @param   mixed   $areas     An array if the search it to be restricted to areas or null to search all areas.
	 *
	 * @return  array  Search results.
	 *
	 * @since   1.6
	 */
	public function onContentSearch($text, $phrase = '', $ordering = '', $areas = null)
	{
		
		
		//require component files
		require_once JPATH_SITE . '/components/com_mb2portfolio/models/projects.php';	
		require_once JPATH_SITE . '/components/com_mb2portfolio/helpers/route.php';
				
		$projects_model = new Mb2portfolioModelProjects();
		
		$db = JFactory::getDbo();
		$app = JFactory::getApplication();
		$user = JFactory::getUser();
		$groups = implode(',', $user->getAuthorisedViewLevels());	
		

		$searchText = $text;

		if (is_array($areas))
		{
			if (!array_intersect($areas, array_keys($this->onContentSearchAreas())))
			{
				return array();
			}
		}

		//$sContent = $this->params->get('search_content', 1);
		//$sArchived = $this->params->get('search_archived', 1);
		$limit = $this->params->def('search_limit', 50);
		$state = array();
		//if ($sContent)
		//{
			$state[] = 1;
		//}
		//if ($sArchived)
		//{
		//	$state[] = 2;
		//}

		if (empty($state))
		{
			return array();
		}

		$text = trim($text);
		if ($text == '')
		{
			return array();
		}
		$searchMb2portfolio = JText::_('PLG_SEARCH_MB2PORTFOLIO');

		switch ($phrase)
		{
			case 'exact':
				$text = $db->quote('%' . $db->escape($text, true) . '%', false);
				$wheres2 = array();
				$wheres2[] = 'a.intro_text LIKE ' . $text;
				$wheres2[] = 'a.full_text LIKE ' . $text;
				$wheres2[] = 'a.title LIKE ' . $text;
				$where = '(' . implode(') OR (', $wheres2) . ')';
				break;

			case 'all':
			case 'any':
			default:
				$words = explode(' ', $text);
				$wheres = array();
				foreach ($words as $word)
				{
					$word = $db->quote('%' . $db->escape($word, true) . '%', false);
					$wheres2 = array();
					$wheres2[] = 'a.intro_text LIKE ' . $word;
					$wheres2[] = 'a.full_text LIKE ' . $word;
					$wheres2[] = 'a.title LIKE ' . $word;
					$wheres[] = implode(' OR ', $wheres2);
				}
				$where = '(' . implode(($phrase == 'all' ? ') AND (' : ') OR ('), $wheres) . ')';
				break;
		}

		switch ($ordering)
		{
			case 'oldest':
				$order = 'a.created ASC';
				break;

			case 'popular':
				$order = 'a.hits DESC';
				break;

			case 'alpha':
				$order = 'a.title ASC';
				break;

			case 'category':
				$order = 'c.title ASC, a.title ASC';
				break;

			case 'newest':
			default:
				$order = 'a.created DESC';
		}

		$query = $db->getQuery(true);

		// SQLSRV changes.
		$case_when = ' CASE WHEN ';
		$case_when .= $query->charLength('a.alias', '!=', '0');
		$case_when .= ' THEN ';
		$a_id = $query->castAsChar('a.id');
		$case_when .= $query->concatenate(array($a_id, 'a.alias'), ':');
		$case_when .= ' ELSE ';
		$case_when .= $a_id . ' END as id';

		$case_when1 = ' CASE WHEN ';
		$case_when1 .= $query->charLength('c.alias', '!=', '0');
		$case_when1 .= ' THEN ';
		$c_id = $query->castAsChar('c.id');
		$case_when1 .= $query->concatenate(array($c_id, 'c.alias'), ':');
		$case_when1 .= ' ELSE ';
		$case_when1 .= $c_id . ' END as skill_1';

		$query->select('a.title AS title, a.created AS created, a.intro_text, a.full_text,' . $case_when . "," . $case_when1)
		->select($query->concatenate(array('a.intro_text', 'a.full_text')) . ' AS text')
		->select($query->concatenate(array($db->quote($searchMb2portfolio), 'c.title'), " / ") . ' AS section')
		->select('\'0\' AS browsernav')
		->from('#__mb2portfolio AS a')
		->join('INNER', '#__mb2portfolio_skills as c ON c.id = a.skill_1')
		->where('(' . $where . ') AND a.state IN (' . implode(',', $state) . ') AND c.state = 1 AND c.access IN (' . $groups . ')')
		->group('a.id, a.title, a.created, a.intro_text, a.full_text, c.id')
		->order($order);

		// Filter by language.
		if ($app->isSite() && JLanguageMultilang::isEnabled())
		{
			$tag = JFactory::getLanguage()->getTag();
			$query->where('a.language in (' . $db->quote($tag) . ',' . $db->quote('*') . ')')
				->where('c.language in (' . $db->quote($tag) . ',' . $db->quote('*') . ')');
		}

		$db->setQuery($query, 0, $limit);
		$rows = $db->loadObjectList();
		
		
		
		
		
		
		

		$return = array();
		if ($rows)
		{
			foreach ($rows as $key => $row)
			{			
				
				$row_id = explode(':', $row->id);			
				$menu_id = $projects_model->menu_item_id($row_id[0],0);
							
				$rows[$key]->href = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($row_id[0],$menu_id));			
				
			}

			foreach ($rows as $project)
			{
				if (SearchHelper::checkNoHTML($project, $searchText, array('text', 'title')))
				{
					$return[] = $project;
				}
			}
		}

		return $return;
	}
}