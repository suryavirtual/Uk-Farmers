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
class SmAdminModelFmembers extends JModelList
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
				'id',
				'description',
				'published'
			);
		}

		parent::__construct($config);
		
		// parent::__construct(); 
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
			 /*$query = "select sf.id as supplierFileId, sf.description, sf.type, sf.expiry, ftm.*, mt.link_name, u.name as supplierName, dt.doc_name, 
			sfn.fname from #__supplier_files as sf, #__files_to_members as ftm, #__mt_links as mt, #__users as u, #__document_type as dt, 
			#__supplier_file_name as sfn";*/
			$query = "select sf.id as supplierFileId, sf.description,ftm.id as fileviewid,ftm.memberId,ftm.approve,ftm.deleteRequest, mt.link_name, u.name as supplierName, 
			sf.filename as fname from #__supplier_files as sf, #__files_to_members as ftm, #__mt_links as mt, #__users as u";
			
			$search = $this->getState('filter.search');
			
			$query .= " Where";

			//$query .= " sf.id=ftm.fileId and ftm.memberId=mt.link_id and sf.userid=u.id and sf.type=dt.id and ftm.fileNameId=sfn.id";
			$query .= " sf.id=ftm.fileId and ftm.memberId=mt.link_id and sf.userid=u.id";
			
			if (!empty($search)) {
				$query .= " and sf.description LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and ftm.approve = ' . (int) $published;
			}
			//else
			//{
				//$query .= ' and ftm.approve IN (0, 1)';
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
		$query->select('sf.id as supplierFileId, sf.description, sf.type, sf.expiry, ftm.*, mt.link_name, u.name as supplierName, dt.doc_name, sfn.fname')
			  ->from('#__supplier_files as sf, #__files_to_members as ftm, #__mt_links as mt, #__users as u, #__document_type as dt, #__supplier_file_name as sfn' );

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('sf.description LIKE ' . $like);
		}
		
		$query->where('sf.id=ftm.fileId and ftm.memberId=mt.link_id and sf.userid=u.id and sf.type=dt.id and ftm.fileNameId=sfn.id');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('ftm.approve = ' . (int) $published);
		}
		//else
		//{
			//$query->where('(ftm.approve IN (0, 1))');
		//}

		// Add the list ordering clause.
		$orderCol	= $this->state->get('list.ordering', 'ftm.id');
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
