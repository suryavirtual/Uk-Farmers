<?php
/**
* @version 1.0
* @package SalesHistory
* @copyright (C) 2008 Matt Hooker
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );

$database = JFactory::getDbo();
$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$user_type = JAccess::getGroupsByUser($usr_id, false);
//$cmpId = $user->comp_list;
$session = JFactory::getSession();

$company_id=$session->get('comp_id');
if(!empty($company_id))
{
	$cmpId=$company_id;
}
else
{
	$cmpId=$user->comp_list;
}



if($usr_id !='')
{
	$totalQuerySelect = "SELECT sum(ph.current_month_value) as CMV, sum(ph.previous_yr_current_month_value) as MLY, sum(ph.yr_to_date_value) as CYTD, sum(ph.previous_yr_to_date_value) as LYTD, sum(ph.rolling_yr_value) as RYV FROM `jos_purchase_history` as ph, jos_mt_links as mt where ph.memberid='$cmpId' and ph.supplierid=mt.link_id and mt.link_published='1' ORDER by mt.link_name";
	$database->setQuery($totalQuerySelect);
	$totalPurchaseDtl = $database->loadObjectList();
	
	$querySelect = "SELECT  distinct mt.link_name, mt.link_id, mt.alias, ph.* FROM `jos_purchase_history` as ph, jos_mt_links as mt where ph.memberid='$cmpId' and ph.supplierid=mt.link_id and mt.link_published='1' ORDER by mt.link_name";
	$database->setQuery($querySelect);
	$purchaseDtl = $database->loadObjectList();
	?>
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
     <div id="printableArea">
	<table width="100%" class="total-purchase">
		<tr>
			<td colspan="5" align="center" class="purchaseDate">For : <?php echo date('l jS \of F Y'); ?></td>
		</tr>
		<tr>
			<th>Month This Year</th>
			<th>Month Last Year</th>
			<th>YTD This Year</th>
			<th>YTD Last Year</th>
			<th>Rolling 12 Months</th>
		</tr>
		<tr>
			<td align="right"><?php echo number_format($totalPurchaseDtl['0']->CMV,2); ?></td>
			<td align="right"><?php echo number_format($totalPurchaseDtl['0']->MLY,2); ?></td>
			<td align="right"><?php echo number_format($totalPurchaseDtl['0']->CYTD,2); ?></td>
			<td align="right"><?php echo number_format($totalPurchaseDtl['0']->LYTD,2); ?></td>
			<td align="right"><?php echo number_format($totalPurchaseDtl['0']->RYV,2); ?></td>
		</tr>
	</table>
	<br>
	
	<table width="100%" class="supplier-history">
		<tr>
			<th>Company Name</th>
			<th>Month This Year</th>
			<th>Month Last Year</th>
			<th>YTD This Year</th>
			<th>YTD Last Year</th>
			<th>Rolling 12 Months</th>
			<th>YOY Ratio Increase</th>
		</tr>
		<?php foreach($purchaseDtl as $purchaseDtls){
			$cmpId = $purchaseDtls->link_id;
			$cmpAlias = $purchaseDtls->alias;
			$cmpUrl = JURI::root()."supplier-search/".$cmpId."-".$cmpAlias."#tabs-2";?>
		<tr>
			<td align="right"><a class="paths" href="<?php echo $cmpUrl; ?>"><?php echo $purchaseDtls->link_name; ?></a></td>
			<td align="right"><?php echo number_format($purchaseDtls->current_month_value,2); ?></td>
			<td align="right"><?php echo number_format($purchaseDtls->previous_yr_current_month_value,2); ?></td>
			<td align="right"><?php echo number_format($purchaseDtls->yr_to_date_value,2); ?></td>
			<td align="right"><?php echo number_format($purchaseDtls->previous_yr_to_date_value,2); ?></td>
			<td align="right"><?php echo number_format($purchaseDtls->rolling_yr_value,2); ?></td>
			<td align="right"><?php echo $purchaseDtls->yoy_ratio_increase; ?></td>
		</tr>
     
		<?php } ?>
	</table>
</div>
<input type="button" onclick="printDiv('printableArea')" value="Print" class="btn btn-medium main-bg validate" style="float:right;margin-top: 18px;" />
<script>
function printDiv(divName) {
     var elements=document.getElementsByClassName('paths');
     for (var index = 0; index < elements.length; index++) {
    elements[index].removeAttribute("href");
    }
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
     location.reload();
}
</script>
 
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
}


