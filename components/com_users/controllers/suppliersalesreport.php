<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

require_once JPATH_COMPONENT . '/controller.php';

/**
 * Reset controller class for Users.
 *
 * @since  1.6
 */
class UsersControllerSuppliersalesreport extends UsersController
{
/**
 * Method to request a username reminder.
 *
 * @return  boolean
 *
 * @since   1.6
 */
 
 public function getsuppliersaleshistory(){
	$selectedcompany =$_POST['selectedcompany'];
	$usr_id=$_POST['user_id'];
	$database = JFactory::getDbo();
	$query = "SELECT mt.link_name,ph.memberid, sum(ph.current_month_value) as current_month_value, 
	sum(ph.previous_yr_current_month_value) as previous_yr_current_month_value, sum(ph.yr_to_date_value) as yr_to_date_value, 
	sum(ph.previous_yr_to_date_value) as previous_yr_to_date_value, sum(ph.rolling_yr_value) as rolling_yr_value, 
	sum(ph.yoy_ratio_increase) as yoy_ratio_increase
	FROM `jos_purchase_history` as ph, jos_mt_links as mt 
	where ph.memberid=mt.link_id and mt.link_name='$selectedcompany' GROUP by ph.memberid";
	
	$database->setQuery($query);
	$rows = $database->loadObjectList();
	
	echo "<table width=\"100%\" border=\"0\" class=\"fabrikTable\">";
    echo "<tr>";
	echo "<th>Month This Year</th>";
	echo "<th>Month Last Year</th>";
	echo "<th>YTD This Year</th>";
    echo "<th>YTD Last Year</th>";
    echo "<th>Rolling 12 Months</th>";
    echo "<th>YOY Ratio Increase</th>";
    echo "</tr>";
    for( $i=0; $i<count($rows); $i++ ) {
		$row = $rows[$i];
		$link = '';
		if ($i % 2) {
			$class="even";
		} else {
			$class="odd";
		} 
		
		echo "<tr class=\"$class\">";
		echo "<td>".number_format($row->current_month_value, 2)."</td>";
		echo "<td>".number_format($row->previous_yr_current_month_value, 2)."</td>";
		echo "<td>".number_format($row->yr_to_date_value, 2)."</td>";
		echo "<td>".number_format($row->previous_yr_to_date_value, 2)."</td>";
		echo "<td>".number_format($row->rolling_yr_value, 2)."</td>";
		echo "<td>".number_format($row->yoy_ratio_increase,2)." %</td>";
		echo "</tr>";
 
}

echo "</table>";


		die;
	}
}
