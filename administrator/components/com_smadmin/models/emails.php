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
class SmAdminModelEmails extends JModelList
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
    /* code for another query*/
   var $_data1 = null;
    var $_total1 = null;
    var $_pagination1 = null;
    var $limit1 = null;
    var $limitstart1 = null;
    var $orders=null;


    /* end here */
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
	/* new code added here */
	function _loadData1() 
	{ 
		if (empty($this->_data1) && empty($this->_total1)) 
		{ 
			$query = "select em.*,u.name as sendBy,u.email,mt.link_name from #__email_member as em, #__users as u,#__mt_links as mt";	
			$search = $this->getState('filter.search');
			$query .= " Where";
			$query .= " 1=1 and em.supplierid=u.id and em.status='1' and u.comp_list=mt.link_id ";
			if (!empty($search)) {
				$query .= " and em.subject LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and em.status = ' . (int) $published;
			}
			$query.=" order by mt.link_name";
                   
			$this->_db->setQuery($query); 

			$this->_data1 = $this->_db->loadObjectList(); 
			$this->_total1 = count( $this->_data1 ) ; 

		} 

		return $this->_data1 ; 
	}
	/* new code ends here */
	function _loadData() 
	{ 
		if (empty($this->_data) && empty($this->_total)) 
		{ 
			
			$query = "select em.*,u.name as sendBy,u.email,mt.link_name from #__email_member as em, #__users as u,#__mt_links as mt";
			
			$search = $this->getState('filter.search');
			
			$query .= " Where";
			$query .= " 1=1 and em.supplierid=u.id and em.status='0' and u.comp_list=mt.link_id ";
			if (!empty($search)) {
				$query .= " and em.subject LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and em.status = ' . (int) $published;
			}
		
			$query.=" order by em.addedon desc ";
                      
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
	function getData1() 
	{ 
		$this->_loadData1() ; 
		
		//$limitstart = $this->getState('limitstart');
		//$limit = $this->getState('limit');

		  // return array_slice( $this->_data1, $limitstart, $limit ); 
		return $this->_loadData1(); 
	}

	function getTotal() 
	{ 
		return $this->_total; 
	} 
	function getTotal1() 
	{ 
		return $this->_total1; 
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
	function getPagination1() 
	{ 
		$this->_loadData1() ;

		if (empty($this->_pagination1)) 
		{ 
			jimport('joomla.html.pagination'); 

			$limitstart = $this->getState('limitstart');
			$limit = $this->getState1('limit');
			$total = $this->getTotal1();

			$this->_pagination = new JPagination( $total, $limitstart, $limit ); 
		} 

		return $this->_pagination1; 
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
		$query->select('em.*, u.name as sendBy')
			  ->from('#__email_member as em, #__users as u' );

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('subject LIKE ' . $like);
		}
		
		$query->where('1=1 and em.supplierid=u.id');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('status = ' . (int) $published);
		}
		//elseif ($published === '')
		//{
			//$query->where('(status IN (0, 1))');
		//}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ordering');
		$orderDirn 	= $this->state->get('list.direction', 'asc');

		$query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));
		if($this->limitstart == 0){
		
			$query1 = $query;
		} else {
			$query1 = $query . ' limit ' . $this->limit . ' offset ' . $this->limitstart;	
		}
		
		return $query1;
	}
	public function getMemberDetails($id){
		$database = JFactory::getDbo();
		/*$query = "select stm.*,mt.link_name,u.email
			           from #__send_email_member as stm, #__mt_links as mt,#__users as u
			           Where stm.emId='$id' and stm.memberId=mt.link_id and mt.user_id=u.id order by mt.link_name";*/
			           $query = "select stm.*,mt.link_name
			           from #__send_email_member as stm, #__mt_links as mt
			           Where stm.emId='$id' and stm.memberId=mt.link_id  order by mt.link_name";
		$database->setQuery( $query );
		$member = $database->loadObjectList();
		
		return $member;
	}
}
