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
class SmAdminModelFiles extends JModelList
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
   
   var $_data1 = null;
    var $_total1 = null;
    var $_pagination1 = null;
    var $limit1 = null;
    var $limitstart1 = null;
    var $orders=null;

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
		$application = JFactory::getApplication() ; 
	         $config = JFactory::getConfig() ; 
	         $limitstart = JRequest::getInt( 'limitstart', 0 );
	          $limit = $application->getUserStateFromRequest( 'global.list.limit', 'limit', $application->getCfg('list_limit'), 'int' );
	        $this->limitstart = $limitstart;
	        $this->limit = $limit;
	    $this->setState('limitstart', $limitstart); 
	    $this->setState('limit', $limit);
	}
	/* new code added for publish data */
	function _loadData1() 
	{ 
		if (empty($this->_data1) && empty($this->_total1)) 
		{ 
			$query = "select sf.*,mt.link_name from #__supplier_files as sf,#__mt_links as mt";
			
			$search = $this->getState('filter.search');
			
			$query .= " Where"; 

			$query .= " 1=1 and mt.link_id=sf.comp_id and sf.approved='1'";
                        $query .= " and sf.expiry >= date_sub(now(), interval 3 month)";
			
			if (!empty($search)) {
				$query .= " and sf.description LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and sf.approved  = ' . (int) $published;
			}
			$query.='order by mt.link_name,sf.uploaded desc';
			$this->_db->setQuery($query); 
			$this->_data1 = $this->_db->loadObjectList(); 
			$this->_total1 = count( $this->_data1 ) ; 

		} 

		return $this->_data1 ; 
	}


	function _loadData() 
	{ 
		if (empty($this->_data) && empty($this->_total)) 
		{ 
			$query = "select sf.*,mt.link_name from #__supplier_files as sf,#__mt_links as mt";
			
			$search = $this->getState('filter.search');
			
			$query .= " Where";

			$query .= " 1=1 and mt.link_id=sf.comp_id and sf.approved='0'";
                        $query .= " and sf.expiry >= date_sub(now(), interval 3 month)";
			
			if (!empty($search)) {
				$query .= " and sf.description LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				//$query .= ' and sf.approved  = ' . (int) $published;
			}
			
			$query.='order by sf.uploaded DESC';
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
		$query->select('sf.*, u.name as sendBy')
			  ->from('#__supplier_files as sf, #__users as u' );

		// Filter: like / search
		$search = $this->getState('filter.search');

		if (!empty($search))
		{
			$like = $db->quote('%' . $search . '%');
			$query->where('sf.description LIKE ' . $like);
		}
		
		$query->where('1=1 and sf.userid=u.id');

		// Filter by published state
		$published = $this->getState('filter.published');

		if (is_numeric($published))
		{
			$query->where('sf.approved = ' . (int) $published);
		}

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
	
	public function getFileDetails($id){
		$database = JFactory::getDbo();
		 $query = "select sf.id as supplierFileId, sf.description,ftm.id as fileviewid,ftm.memberId,ftm.sentEmail,ftm.approve,ftm.deleteRequest, mt.link_name, sf.filename as fname from #__supplier_files as sf, #__files_to_members as ftm, #__mt_links as mt  Where sf.id=ftm.fileId and ftm.memberId=mt.link_id and sf.id='$id' order by mt.link_name"; 
		
		$database->setQuery($query);
		$filesDtl = $database->loadObjectList();
		
		return $filesDtl;
	}
	
	public function getEmailDtail($fileName){
		$database = JFactory::getDbo();
		
		$query="select DISTINCT u.email from #__users as u,#__supplier_files as sf where u.id=sf.userid and sf.filename='$fileName'";
		$database->setQuery($query);
		$emailDtl = $database->loadObjectList();
		
		return $emailDtl;
	}
	public function getEmailDtails($id){
		$database = JFactory::getDbo();
		$query="select  u.email from #__users as u,#__supplier_files as sf where u.id=sf.userid and sf.id='$id'";
		$database->setQuery($query);
		$emailDt2 = $database->loadObject();
		
		return $emailDt2;
	}
	
	public function getMemberDetails($compId, $fviewedId){
		$database = JFactory::getDbo();
		
		$query = "select sf.user_id,u.name,u.email from #__supplier_file_viewed as sf left join #__users as u on(u.id=sf.user_id) where sf.comp_id='$compId' and sf.fid='$fviewedId'";
		$database->setQuery( $query );
		$member = $database->loadObjectList();
		
		return $member;
	}
	public function getsharememberdetails($fileid)
	{
		
		$database = JFactory::getDbo();
		$query="select fm.fileId,mt.link_name,mt.link_id from #__files_to_members as fm, #__mt_links as mt  where  fm.fileId='$fileid' and  fm.memberId=mt.link_id and mt.link_published='1' group by mt.link_name";
		$database->setQuery($query);
		$memberdetails = $database->loadObjectList();
		
		return $memberdetails;
	}
	public function getdelerecord($member_id,$termid)
	{
		$database = JFactory::getDbo();
		$query="select viewFile from #__files_to_members  where  fileId='$termid' and memberId='$member_id' ";
		$database->setQuery($query);
		$deleteDtl = $database->loadObject();
		
		return $deleteDtl;

	}
}
