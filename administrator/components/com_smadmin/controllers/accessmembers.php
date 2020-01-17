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
 * Supplier/Member Controller
 *
 * @since  0.0.1
 */
class SmAdminControllerAccessmembers extends JControllerAdmin
{
	/**
	 * Proxy for getModel.
	 *
	 * @param   string  $name    The model name. Optional.
	 * @param   string  $prefix  The class prefix. Optional.
	 * @param   array   $config  Configuration array for model. Optional.
	 *
	 * @return  object  The model.
	 *
	 * @since   1.6
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->registerTask('unapproved', 'approved');
	}
	
	public function getModel($name = 'Accessmember', $prefix = 'SmAdminModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}
	
	function saveaccess(){
		$db = JFactory::getDbo();
		
		$value = $_POST['value']; 
			

		foreach($value as $values)
		{
			if(strpos($values['name'], '[') > 0){
				$start = strpos($values['name'], '[');
				$str = substr($values['name'], $start+1);
				$fstr = substr($str,0,-1);
				$frmValue['document'][$fstr][]=$values['value'];
			} else {
				$frmValue[$values['name']][] = $values['value'];
			}
		}
		$suppId = $frmValue['supplier']['0'];
		$memberId = $frmValue['member']['0'];
		$document = $frmValue['document'];
		/*new query added for delete */
			$query1= "update #__member_access set docIds='' where supplierId='$suppId' and memberId='$memberId' ";
             $db->setQuery($query1);
             $db->query();
             
                    if($suppId =="all" && $memberId !="all"){
                        $query1= "update #__member_access set docIds='' where  memberId='$memberId' ";
                        $db->setQuery($query1);
                        $db->query();
                    }
		
		
		
		foreach($document as $key => $value)
		{
		
		    $totalen=strlen($key);
		    $rmv = strpos($key,'][');
			
		   $getId = substr($key,0,$rmv);
		   $supp_Id=substr($key,$rmv+2,$totalen);
			 
			$user_id = $getId;
			$docIds = implode(",", $value);
                                  
                        if($suppId =="all" && $memberId =="all"){
                            $uQuery = "select * from #__users where id='$user_id'";
                            $db->setQuery($uQuery);
                            $resUser = $db->loadObject();
                            $compId = $resUser->comp_list;
                                    
                            $suppId=$supp_Id;
                            $getQuery = "select * from #__member_access where supplierId='$suppId' and memberId='$compId' and userId='$user_id'";
                
                        }elseif($suppId != "all" && $memberId =="all"){
                            if($memberId=='all'){
				$uQuery = "select * from #__users where id='$user_id'";
				$db->setQuery($uQuery);
				$resUser = $db->loadObject();
				$compId = $resUser->comp_list;
                            }
                            if($memberId=='all'){
                                    $getQuery = "select * from #__member_access where supplierId='$suppId' and memberId='$compId' and userId='$user_id'";
                            } else {
                                    $getQuery = "select * from #__member_access where supplierId='$suppId' and memberId='$memberId' and userId='$user_id'";
                            }
                            
                        }elseif($suppId =="all" && $memberId !="all"){
                            
                               
                                $getQuery = "select * from #__member_access where supplierId='$supp_Id' and memberId='$memberId' and userId='$user_id'";
                        }else{
                            if($memberId=='all'){
				$uQuery = "select * from #__users where id='$user_id'";
				$db->setQuery($uQuery);
				$resUser = $db->loadObject();
				$compId = $resUser->comp_list;
                            }
                            if($memberId=='all'){
                                    $getQuery = "select * from #__member_access where supplierId='$suppId' and memberId='$compId' and userId='$user_id'";
                            } else {
                                     $getQuery = "select * from #__member_access where supplierId='$suppId' and memberId='$memberId' and userId='$user_id'";
                            }
                        }          
			
			$db->setQuery($getQuery);
			$res = $db->loadObjectList();
			
			if(count($db->loadObjectList()) >= 1){
				if($memberId=='all')
				{
					$query = "update #__member_access set docIds='$docIds' where supplierId='$suppId' and memberId='$compId' and userId='$user_id' ";
				} else {
                                    if($suppId =="all" && $memberId !="all"){
                                         $query = "update #__member_access set docIds='$docIds' where supplierId='$supp_Id' and memberId='$memberId' and userId='$user_id' ";
                                    }else{
                                        $query = "update #__member_access set docIds='$docIds' where supplierId='$suppId' and memberId='$memberId' and userId='$user_id' ";
                                    }
					 
				}
			} else {
                            
				if($memberId=='all'){
					$query = "insert into #__member_access values ('', '$suppId', '$compId', '$user_id', '$docIds')";
				} else {
                                    if($suppId =="all" && $memberId !="all"){
                                        $query = "insert into #__member_access values ('', '$supp_Id', '$memberId', '$user_id', '$docIds')";
                                    }else{
                                        $query = "insert into #__member_access values ('', '$suppId', '$memberId', '$user_id', '$docIds')";
                                    }
					 
				}
			}
			
									
			$db->setQuery($query);
			$db->query();
			
		}
                echo 'true';
		exit;
	}
}
