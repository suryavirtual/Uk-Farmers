<?php
/**
* @version 1.0
* @package SalesHistory
* @copyright (C) 2008 Matt Hooker
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

global $mosConfig_offset;
global $database;

$mosConfig_offset = 0;
$database = JFactory::getDbo();
$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$user_type = JAccess::getGroupsByUser($usr_id, false);
$cmpId = $user->comp_list;

if($usr_id !=''){
// FULL SUPPLIER PURCHASE HISTORY SECTION
$now = date( 'Y-m-d H:i:s', time() + $mosConfig_offset * 3600 );

// set totals for top of page
$mty_total = 0;
$mly_total = 0;
$ytdty_total = 0;
$ytdly_total = 0;
$rolling_total = 0;

// Retreive parameters
$limit = (int)JRequest::getVar('limit','0');
$limitstart = (int)JRequest::getVar('limitstart','0');
$count = (int)JRequest::getVar('count','10');

$totals_query = "SELECT mt.link_name,ph.memberid, sum(ph.current_month_value) as current_month_value, 
	sum(ph.previous_yr_current_month_value) as previous_yr_current_month_value, sum(ph.yr_to_date_value) as yr_to_date_value, 
	sum(ph.previous_yr_to_date_value) as previous_yr_to_date_value, sum(ph.rolling_yr_value) as rolling_yr_value, 
	sum(ph.yoy_ratio_increase) as yoy_ratio_increase
	FROM `jos_purchase_history` as ph, jos_mt_links as mt 
	where ph.memberid=mt.link_id and mt.link_id='$cmpId' GROUP by ph.memberid  
	ORDER BY mt.link_name ASC";
$database->setQuery( $totals_query);
$rows = $database->loadObjectList();
$total = count($rows);

if(count($rows)){
	for( $i=0; $i<count($rows); $i++ ) {
		$row = $rows[$i];
		
		$mty_total = $mty_total + $row->current_month_value;
		$mly_total = $mly_total + $row->previous_yr_current_month_value;
		$ytdty_total = $ytdty_total + $row->yr_to_date_value;
		$ytdly_total = $ytdly_total + $row->previous_yr_to_date_value;
		$rolling_total = $rolling_total + $row->rolling_yr_value;
	}
}

jimport('joomla.html.pagination');
$pageNav = new JPagination( $total, $limitstart, $limit );
?>

<div id="printableArea">
	<table width="100%" border="0" class="topsaleshistoryheading">
	<tr>
		<th>
			<span class="uf_sales_history">UF Sales History :</span>
			<span class="uf_sales_history_date"><?php echo date('l jS \of F Y'); ?></span>
		</th>
	</tr>
	</table>
	
	<table width="100%" border="0" class="fabrikTable">
	<tr>
		<th>Month This Year</th>
		<th>Month Last Year</th>
		<th>YTD This Year</th>
		<th>YTD Last Year</th>
		<th>Rolling 12 Months</th>
	</tr>
	
	<tr>
		<td align="right"><?php echo number_format($mty_total, 2);?></td>
		<td align="right"><?php echo number_format($mly_total, 2); ?></td>
		<td align="right"><?php echo number_format($ytdty_total, 2); ?></td>
		<td align="right"><?php echo number_format($ytdly_total, 2); ?></td>
		<td align="right"><?php echo number_format($rolling_total, 2); ?></td>
	</tr>
	</table>
	
	<?php 
	if($user_type=='8'){
	if(count($rows)>0) { ?>
	<table width="100%" border="0" class="fabrikTable_company">
	<tr>
		<th><span class="uf_sales_history">Company Name</span></th>
	</tr>
	<tr>
		<td>
			<select name="companyname_sales_history" id="companyname_sales_history">
			<?php foreach($rows as $company_name_final_result){?>
				<option value="<?php echo $company_name_final_result->link_name;?>"><?php echo $company_name_final_result->link_name;?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	</table>
	
	<div id="sales_report_history">
		<table width="100%" border="0" class="fabrikTable">
		<tr>
			<th>Month This Year</th>
			<th>Month Last Year</th>
			<th>YTD This Year</th>
			<th>YTD Last Year</th>
			<th>Rolling 12 Months</th>
			<th>YOY Ratio Increase</th>
		</tr>
		
		<tr>
			<td><?php echo number_format($rows['0']->current_month_value, 2); ?></td>
			<td><?php echo number_format($rows['0']->previous_yr_current_month_value, 2); ?></td>
			<td><?php echo number_format($rows['0']->yr_to_date_value, 2); ?></td>
			<td><?php echo number_format($rows['0']->previous_yr_to_date_value, 2); ?></td>
			<td><?php echo number_format($rows['0']->rolling_yr_value, 2); ?></td>
			<td><?php echo number_format($rows['0']->yoy_ratio_increase,2); ?> %</td>
		</tr>
		</table>
	</div>
</div>
<?php 
}
else
{
	echo "No sales Yet";
} 
}
?>
<input type="hidden" name="current_supplierid" value="<?php echo $usr_id; ?>" id="current_supplierid" />
<input type="button" onclick="printDiv('printableArea')" value="Print" class="btn btn-medium main-bg validate" style="float:right;margin-top: 18px;" />
<style>
div.pagination ul li{
	float:left;
	padding:5px;
}
</style>

	<script type="text/javascript">
<?php echo 'var siteURL="'.JURI::root().'"';?>
//<![CDATA[
jQuery(document).ready(function(){
	jQuery("#companyname_sales_history").change(function () {
		var form_data = new FormData();
		var selectedcompany = jQuery("#companyname_sales_history").val();
		jQuery('#companyname_sales_history > option[value *= "'+selectedcompany+'"] ').attr('selected',true);
		var user_id = jQuery("#current_supplierid").val();
		form_data.append('selectedcompany', selectedcompany);
		form_data.append('user_id',user_id);
		jQuery.ajax({
			url:siteURL+'index.php?option=com_users&task=suppliersalesreport.getsuppliersaleshistory',
			type: 'POST',
			data:form_data,
			success: function (data) {
				jQuery("#sales_report_history").html(data);
			},
			cache: false,
			contentType: false,
			processData: false
		});
		return false;
	}); 
});
function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
	  location.reload();
}

//]]>
</script>
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login!!!', 'error'));
}
