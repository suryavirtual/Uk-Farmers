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
class SmAdminModelTerms extends JModelList
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

	function _loadData1() 
	{ 
		if (empty($this->_data1) && empty($this->_total1)) 
		{ 

			$query="select st.termName,st.termId,st.lastnotification,st.sentNotification,st.termsFile,st.effectiveFrom,st.validTo,st.status,st.unpublished,u.name as sendBy,mt.link_name,stm.* from #__structured_terms as st  
			left join #__structured_terms_to_members as stm on(st.termId=stm.terms_id) 
			left join #__users as u on(st.cmpId=u.id) 
			left join #__mt_links as mt on(st.supplierUserId=mt.link_id) "; 
			
			$search = $this->getState('filter.search');
			
			$query .= " Where";

			$query .= " st.status='1'";

			
			if (!empty($search)) 
			{
				
				$query .= " and st.termName LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and stm.approved = ' . (int) $published;
			}


			//order by term name addded
			if(isset($_REQUEST['order']))
			{
                         $orders=$_REQUEST['order'];
			}

             if($orders)
             {
             	$query.=' group by st.termName order by st.termName';
             	
             }
             else
             {

             	$query.=' group by st.termId  order by mt.link_name,st.termName';
             }
			
			$this->_db->setQuery($query);
			echo "@query".$query;

			$this->_data1 = $this->_db->loadObjectList(); 

			$this->_total1 = count( $this->_data1 ) ; 

		} 

		return $this->_data1 ; 
	}
	/* new code added for manage files */
	function _loadData() 
	{ 
		if (empty($this->_data) && empty($this->_total)) 
		{ 
           $query="select st.termName,st.termId,st.lastnotification,st.sentNotification,st.termsFile,st.effectiveFrom,st.validTo,st.status,st.unpublished,u.name as sendBy,mt.link_name,stm.* from #__structured_terms as st  
			left join #__structured_terms_to_members as stm on(st.termId=stm.terms_id) 
			left join #__users as u on(st.cmpId=u.id) 
			left join #__mt_links as mt on(st.supplierUserId=mt.link_id) ";
			$search = $this->getState('filter.search');
			$query .= " Where";
			$query .= " st.status='0'";					
			if (!empty($search)) 
			{
				
				$query .= " and st.termName LIKE '%" . $search . "%'";
			}
			
			// Filter by published state
			$published = $this->getState('filter.published');

			if (is_numeric($published))
			{
				$query .= ' and stm.approved = ' . (int) $published;
			}

			//$query.=' group by st.termName order by st.termId desc';
                          $query.='group by st.termId order by st.termId desc';
			
			$this->_db->setQuery($query); 

			$this->_data = $this->_db->loadObjectList(); 
			$this->_total = count( $this->_data ) ; 

		} 

		return $this->_data ; 
	}
	/* code ended here */
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
       	public function getTermsDetails($id){
		$database = JFactory::getDbo();
	    $query = "select stm.*,mt.link_name
	    from #__structured_terms_to_members as stm, #__mt_links as mt
	    Where stm.member_id=mt.link_id and stm.terms_id='$id'  order by mt.link_name"; 
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
	
	public function getMemberDetails($compId, $fviewedId){
		$database = JFactory::getDbo();
		$query = "select sf.user_id,u.name,u.email from #__supplier_term_viewed as sf left join #__users as u on(u.id=sf.user_id) where sf.comp_id='$compId' and sf.tid='$fviewedId'";
		$database->setQuery( $query );
		$member = $database->loadObjectList();
		return $member;
	}
	public function getSupplierDetails($termid)
	{
	$database = JFactory::getDbo();
	$query3="select DISTINCT mt.link_name  from #__structured_terms_to_members  as stm,#__mt_links as mt  where mt.link_id=stm.user_id and stm.terms_id='$termid'";
        $database->setQuery($query3);
	$supplierDtl = $database->loadObjectList();
         return $supplierDtl;

	}
	public function getdelerecord($member_id,$termid)
	{
		$database = JFactory::getDbo();
		$query="select viewTerm from #__structured_terms_to_members  where  terms_id='$termid' and member_id='$member_id' ";
		$database->setQuery($query);
		$deleteDtl = $database->loadObject();
		return $deleteDtl;

	}
	public function getsentmemberdDetails($termid)
	{
		$database = JFactory::getDbo();
		$query="select st.*, mt.link_name,u.email from #__structured_terms as st,#__structured_terms_to_members as stm, #__mt_links as mt,#__users as u where u.id=mt.user_id and st.termId='$termid' and st.termId=stm.terms_id and mt.link_id=stm.member_id and stm.approved='1' group by mt.link_name ";
		$database->setQuery($query);
		$memberdetails = $database->loadObjectList();
		return $memberdetails;
	}



	
}
