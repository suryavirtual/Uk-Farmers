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
 * Supplier/Member Admin List Model
 *
 * @since  0.0.1
 */
class SmAdminModelTmembers extends JModelList
{
	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 *
	 * @see     JController
	 * @since   1.6
	 */
	var $_data = null;
    var $_total = null;
    var $_pagination = null;
    var $limit = null;
    var $limitstart = null;
	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'termId',
				'termName',
				'published'
			);
		}

		parent::__construct($config);
		$application = JFactory::getApplication() ; 

	    $config = JFactory::getConfig() ; 

	    $limitstart = JRequest::getInt( 'limitstart', 0 );
	    $limit = $application->getUserStateFromRequest( 'global.list.limit', 'limit', $application->getCfg('list_limit'), 'int' );

	    $this->limitstart = $limitstart;
	    $this->limit = $limit;
	    $this->setState('limitstart', $limitstart); 
	    $this->setState('limit', $limit);
	}

	function _loadData() 
	{ 

		if (empty($this->_data) && empty($this->_total)) 
		{ 
			/*$query = "select st.termId, st.termName, st.SupplierUserId, st.cmpId, stm.*, u.name as sendBy, mt.link_name, cu.id as memId, cu.name as memberName from #__structured_terms as st, #__structured_terms_to_members as stm, #__users as u, #__mt_links as mt, #__users as cu"; */

			$query="select stm.*,st.termName,u.name as sendBy,mt.link_name from  #__structured_terms_to_members as stm 
			left join #__structured_terms as st on(st.termId=stm.terms_id) 
			left join #__users as u on(st.cmpId=u.id) 
			left join #__mt_links as mt on(stm.member_id=mt.link_id) ";
			
			$search = $this->getState('filter.search');
			
			

			//$query .= " st.termId=stm.terms_id and stm.user_id=mt.link_id and stm.member_id=mt.link_id and mt.user_id=cu.id";
			
			if (!empty($search)) 
			{
				$query .= " Where";
				$query .= " st.termName LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and stm.approved = ' . (int) $published;
			}


			
			
			$this->_db->setQuery($query); 

			$this->_data = $this->_db->loadObjectList(); 
			//echo "total rows=".count($this->_data);
			//echo "<pre>";
			//print_r($this->_data);
			$this->_total = count( $this->_data ) ; 

		} 

		return $this->_data ; 
	} 
	
	function getData() 
	{ 
		$this->_loadData() ; 
		
		$limitstart = $this->getState('limitstart');
		$limit = $this->getState('limit');

		   return array_slice( $this->_data, $limitstart, $limit ); 
	}

	function getTotal() 
	{ 
		return $this->_total; 
	} 

	function getPagination() 
	{ 
		$this->_loadData() ;

		if (empty($this->_pagination)) 
		{ 
			jimport('joomla.html.pagination'); 

			$limitstart = $this->getState('limitstart');
			$limit = $this->getState('limit');
			$total = $this->getTotal();

			$this->_pagination = new JPagination( $total, $limitstart, $limit ); 
		} 

		return $this->_pagination; 
	}


	/**
	 * Method to build an SQL query to load the list data.
	 *
	 * @return      string  An SQL query
	 */
	protected function getListQuery()
	{
		
		// Initialize variables.
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		// Create the base select statement.
		$query->select('st.termId, st.termName, st.SupplierUserId, st.cmpId, stm.*, u.name as sendBy, mt.link_name, cu.id as memId, cu.name as memberName')
			  ->from('#__structured_terms as st, #__structured_terms_to_members as stm, #__users as u, #__mt_links as mt, #__users as cu' );

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('termName LIKE ' . $like);
		}
		
		$query->where('st.termId=stm.terms_id and stm.user_id=u.id and stm.member_id=mt.link_id and mt.user_id=cu.id');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('stm.approved = ' . (int) $published);
		}
		//elseif ($published === '')
		//{
		//	$query->where('(stm.approved IN (0, 1))');
		//}
  
		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'stm.id');
		$orderDirn 	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		if($this->limitstart == 0){
		
			$query1 = $query;
		} else {
			$query1 = $query . ' limit ' . $this->limit . ' offset ' . $this->limitstart;	
		}
		
		return $query1;
	}
}
