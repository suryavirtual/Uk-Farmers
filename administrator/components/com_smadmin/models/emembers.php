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
class SmAdminModelEmembers extends JModelList
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
			$query = "select sem.*, em.subject, em.addedon,em.file, mt.link_name, u.name as supplierName from #__send_email_member as sem, #__email_member as em, #__mt_links as mt, #__users as u";
			
			$search = $this->getState('filter.search');
			
			$query .= " Where";

			$query .= " sem.emId=em.id and sem.memberId=mt.link_id and em.supplierid=u.id";
			
			if (!empty($search)) {
				$query .= " and em.subject LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and sem.approve = ' . (int) $published;
			}
			//else
			//{
				//$query .= ' and sem.approv IN (0, 1)';
			//}
			$this->_db->setQuery($query); 

			$this->_data = $this->_db->loadObjectList(); 
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
		$query->select('sem.*, em.subject, em.addedon, mt.link_name, u.name as supplierName')
			  ->from('#__send_email_member as sem, #__email_member as em, #__mt_links as mt, #__users as u' );

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('subject LIKE ' . $like);
		}
		
		$query->where('sem.emId=em.id and sem.memberId=mt.link_id and em.supplierid=u.id');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('sem.approve = ' . (int) $published);
		}
		//elseif ($published === '')
		//{
		//	$query->where('(sem.approve IN (0, 1))');
		//}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'sem.id');
		$orderDirn 	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		if($this->limitstart == 0)
		{
		
			$query1 = $query;
		} else {
			$query1 = $query . ' limit ' . $this->limit . ' offset ' . $this->limitstart;	
		}
		
		return $query1; 
	}
}
