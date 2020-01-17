<?php
/**
 * ARRA User Export Import component for Joomla! 1.6
 * @version 1.6.0
 * @author ARRA (joomlarra@gmail.com)
 * @link http://www.joomlarra.com
 * @Copyright (C) 2010 - 2011 joomlarra.com. All Rights Reserved.
 * @license GNU General Public License version 2, see LICENSE.txt or http://www.gnu.org/licenses/gpl-2.0.html
 * PHP code files are distributed under the GPL license. All icons, images, and JavaScript code are NOT GPL (unless specified), and are released under the joomlarra Proprietary License, http://www.joomlarra.com/licenses.html 
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

function statistics(){
	$database = JFActory::getDbo();
	
	$usertype = JFactory::getApplication()->input->get("usertype", "", "raw");
	
	if($usertype != "" && strlen($usertype)>1){
		$usertype = substr($usertype, 0, -1);
		$usertype = str_replace("|", ",", $usertype);
	}
	
	$block = JFactory::getApplication()->input->get("block", "", "raw");
	$visited = JFactory::getApplication()->input->get("visited", "", "raw");
	$start_date = JFactory::getApplication()->input->get("start_date", "", "raw");
	$end_date = JFactory::getApplication()->input->get("end_date", "", "raw");
	$start_register_date = JFactory::getApplication()->input->get("start_register_date", "", "raw");
	$end_register_date = JFactory::getApplication()->input->get("end_register_date", "", "raw");
	$send_email = JFactory::getApplication()->input->get("send_email", "", "raw");
	$activated = JFactory::getApplication()->input->get("activated", "", "raw");
	
	$where = " u.id=ugm.user_id ";
	
	if($usertype != ""){
		$where .= " and ugm.group_id in(".$usertype.")";
	}
	else{
		$where .= " and ugm.group_id in(-1)";
	}
	
	if($block != "" && $block != "-1"){
		$where .= " and u.block=".$block;
	}
	
	if($send_email != "" && $send_email != "-1"){
		$where .= " and u.sendEmail=".$send_email;
	}
	
	if($activated != "" && $activated != "-1"){
		if($activated == "0"){
			$where .= " and u.activation = ''";
		}
		else{
			$where .= " and u.activation <> ''";
		}
	}
	
	if($visited != "" && $visited != "-1"){
		if($visited == "0"){
			$where .= " and u.lastvisitDate <> '0000-00-00 00:00:00'";
		}
		else{
			$where .= " and u.lastvisitDate = '0000-00-00 00:00:00'";
		}				
	}
	else{
		if($start_date == "" && $end_date == ""){
		}
		else{
			if($start_date != "" && $end_date == ""){
				$where .= " and u.lastvisitDate >= '".$start_date."'";
			}
			elseif($start_date == "" && $end_date != ""){
				$where .= " and u.lastvisitDate <= '".$end_date."'";
			}
			elseif($start_date != "" && $end_date != ""){
				$where .= " and u.lastvisitDate >= '".$start_date."' and u.lastvisitDate <= '".$end_date."'";
			}
		}
	}
	
	if($start_register_date == "" && $end_register_date == ""){
	}
	else{
		if($start_register_date != "" && $end_register_date == ""){
			$where .= " and u.registerDate >= '".$start_register_date."'";
		}
		elseif($start_register_date == "" && $end_register_date != ""){
			$where .= " and u.registerDate <= '".$end_register_date."'";
		}
		elseif($start_register_date != "" && $end_register_date != ""){
			$where .= " and u.registerDate >= '".$start_register_date."' and u.registerDate <= '".$end_register_date."'";
		}
	}
	
	$sql = "select u.id from #__users u, #__user_usergroup_map ugm where ".$where." group by u.id";
	$database->setQuery($sql);
	$database->query();
	$result = $database->loadAssocList();
	
	echo count($result);
	exit;
}

statistics();

?>	