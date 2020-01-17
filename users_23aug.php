<?php
phpinfo();


$from = "admin@united-farmers.org";
$to = "ve.akanshajaiswal@gmail.com";
$subject = "Simple test for mail function";
$message = "This is a test to check if php mail function sends out the email";
$headers = "From:" . $from;
if (mail($to, $subject, $body, $header)) {
   echo("
      Message successfully sent!
   ");
} else {
   echo("
      Message delivery failed...
   ");
}
die("stop here for testing purpose");
error_reporting(0);
include("configuration.php");

$obj = new JConfig();
$db_host = $obj->host;
$db_username = $obj->user;
$db_password = $obj->password;
$db_name = $obj->db;
$item_per_page = 1; //item to display per page

$link = mysql_connect($db_host, $db_username, $db_password);
@mysql_select_db($db_name,$link);

$querySupplier=mysql_query("select st.termName,st.termId,st.lastnotification,st.sentNotification,st.termsFile,st.effectiveFrom,st.validTo,st.status,st.unpublished,u.name as sendBy,mt.link_name,stm.* from jos_structured_terms as st left join jos_structured_terms_to_members as stm on(st.termId=stm.terms_id) left join jos_users as u on(st.cmpId=u.id) left join jos_mt_links as mt on(st.supplierUserId=mt.link_id) Where st.status='0' group by st.termId  order by st.termId desc ");
	
  $totalmembers=array();

	while($supplierdata = mysql_fetch_object($querySupplier))
	{
		echo "<pre>";
        print_r($supplierdata);		

	}
die("stop here for testing purpose1");


$memId = $_REQUEST['memid'];
$supid = $_REQUEST['supid'];
/* new code for all supplier and member start from here */
function getalluser($supplierid,$memberid)
{ 
	
	$selAllUsers = mysql_query("SELECT u.*, ug.group_id as grpId FROM `jos_users` as u,  jos_user_usergroup_map as ug where comp_list='$memberid' and u.id=ug.user_id order by name");
		
		if(mysql_num_rows($selAllUsers) >=1)
		{
			while ($getAllRes = mysql_fetch_object($selAllUsers))
			{
				echo '<tr>';
					echo '<td>'.$getAllRes->name.'</td>';
					echo '<td>'.$getAllRes->email.'</td>';
					
					$uid = $getAllRes->id;
					$getAllDocValues = mysql_query("SELECT * FROM `jos_member_access` where supplierId='$supplierid' and memberId='$memberid' and userId='$uid'");
					$docAllRes = mysql_fetch_object($getAllDocValues);
										
					$docAllArr = array();
					if(mysql_num_rows($getAllDocValues) >= 1){
						$docAllArr = explode(",", $docAllRes->docIds);
					} else {
						$docAllArr = '';
					}
					
					$selAllDoc = mysql_query("SELECT * FROM `jos_document_type`");
					while ($getAllDoc = mysql_fetch_object($selAllDoc))
					{
						if(in_array($getAllDoc->id, $docAllArr)){
							$checked = 'checked';
						} else {
							$checked = '';
						}
						
						if(($getAllDoc->doc_name == 'Terms') && ($getAllRes->grpId != '12')){
							echo '<td class="docAlign">&nbsp;</td>';
						} else {
							if($getAllDoc->doc_name == 'Marketing'){
								$class="chk_M_".str_replace(' ', '_', $allMem->link_name);
							} else if($getAllDoc->doc_name == 'Offers'){
								$class="chk_O_".str_replace(' ', '_', $allMem->link_name);
							} else if($getAllDoc->doc_name == 'Price List'){
								$class="chk_P_".str_replace(' ', '_', $allMem->link_name);
							} else if($getAllDoc->doc_name == 'Terms'){
								$class="chk_T_".str_replace(' ', '_', $allMem->link_name);
							}
							echo '<td class="docAlign">';
							echo '<input id="checkbox_'.$getAllRes->id.'_'.$getAllDoc->id.'" '.$checked.' type="checkbox" name="document['.$getAllRes->id.']['.$supplierid.']" value="'.$getAllDoc->id.'" class="'.$class.'">';
							echo '</td>';	
						}
					}
				echo '</tr>';
			}
		} else {
			echo '<tr><td colspan="6" class="docAlign">No User Found</td></tr>';
		}
}


if(($memId == "all") && ($supid == "all")) 
{
	//$querySupplier=mysql_query("SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name");
	//$queryMember=mysql_query("SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Member' and mt.link_published='1' order by mt.link_name");

	$querySupplier=mysql_query("SELECT mt.link_name,mt.link_id, sup.value as sup_value, mem.value as mem_value FROM `jos_mt_links` as mt left Join jos_mt_cfvalues as sup on sup.link_id=mt.link_id and sup.value='supplier' left Join jos_mt_cfvalues as mem on mem.link_id=mt.link_id and mem.value='Member' where mt.link_published='1' order by mt.link_name");
	
  $totalsuppliers=array();
  $totalmembers=array();

	while($supplierdata = mysql_fetch_object($querySupplier))
	{
		if(!empty($supplierdata->sup_value))
		{
        $totalsuppliers[]=$supplierdata;
		}

		if(!empty($supplierdata->mem_value))
		{
		$totalmembers[]=$supplierdata;	
		}
		

	}
	
	
	$totalcountsuppliers=count($totalsuppliers);
	 $totalcountmembes=count($totalmembers);

	
	for($i=0;$i<=$totalcountsuppliers;$i++)
	{
		for($j=0;$j<=$totalcountmembes;$j++)
		{
		$supplierid=$totalsuppliers[$i]->link_id;
		$memberid=$totalmembers[$j]->link_id;

		$suppliername=$totalsuppliers[$i]->link_name;
		$membername=$totalmembers[$j]->link_name;

		echo '<h3>Supplier: '.$suppliername.'</h3>';
		echo '<h3>Member: '.$membername.'</h3>';
    echo '<table class="table table-striped table-hover" id="membersList">
			<thead>
			<tr>
				<th width="20%" rowspan="2">Name</th>
				<th width="20%" rowspan="2" class="docAlign">Email Address</th>
				<th width="15%" class="docAlign">Marketing<br><input type="checkbox" class="all" data-chk="chk_M" data-comp="'.str_replace(' ', '_', $totalmembers[$j]->link_name).'" /></th>
				<th width="15%" class="docAlign">Offers<br><input type="checkbox" class="all" data-chk="chk_O" data-comp="'.str_replace(' ', '_', $totalmembers[$j]->link_name).'" /></th>
				<th width="15%" class="docAlign">Price List<br><input type="checkbox" class="all" data-chk="chk_P" data-comp="'.str_replace(' ', '_', $totalmembers[$j]->link_name).'" /></th>
				<th width="15%" class="docAlign">Terms<br><input type="checkbox" class="all" data-chk="chk_T" data-comp="'.str_replace(' ', '_', $totalmembers[$j]->link_name).'" /></th>
			</tr>
			</thead>';
		echo '<tbody>';

		getalluser($supplierid,$memberid);
		echo '</tbody>';
		echo '</table>';
		//print_r($userdata);
    
    
			


		}
	}

}
//die("stop here");

/* code ends here for all supplier and members */

// Single Member And All Supplier START OF CODING
if($supid == 'all'){
    
	$querySup=mysql_query("SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name");
        

        while($allSup = mysql_fetch_object($querySup))
	{
            
            $tableOpen  = '<table class="table table-striped table-hover" id="membersList">
	<thead>
	<tr>
		<th width="20%" rowspan="2">Name</th>
		<th width="20%" rowspan="2" class="docAlign">Email Address</th>
		<th width="15%" class="docAlign">Marketing<br><input type="checkbox" class="all" data-chk="chk_M" data-comp="'.str_replace(' ', '_', $allSup->link_name).'" /></th>
		<th width="15%" class="docAlign">Offers<br><input type="checkbox" class="all" data-chk="chk_O" data-comp="'.str_replace(' ', '_', $allSup->link_name).'"/></th>
		<th width="15%" class="docAlign">Price List<br><input type="checkbox" class="all" data-chk="chk_P" data-comp="'.str_replace(' ', '_', $allSup->link_name).'"/></th>
		<th width="15%" class="docAlign">Terms<br><input type="checkbox" class="all" data-chk="chk_T" data-comp="'.str_replace(' ', '_', $allSup->link_name).'"/></th>
	</tr>
	</thead>';
$tableClose = '</table>';
            
            
            $supplierIdS = $allSup->link_id;
            $selUsers = mysql_query("SELECT u.*, ug.group_id as grpId FROM `jos_users` as u,  jos_user_usergroup_map as ug where u.comp_list='$memId' and u.id=ug.user_id order by u.name");    
            echo '<h3>Supplier: '.$allSup->link_name.'</h3>';
            echo $tableOpen; ?>
	<tbody>
	<?php if(mysql_num_rows($selUsers) >=1){ ?>
		<?php while ($getRes = mysql_fetch_object($selUsers)){?>
		<tr>
			<td><?php echo $getRes->name; ?></td>
			<td><?php echo $getRes->email; ?></td>
			<?php 
			$uid = $getRes->id;
			$getDocValues = mysql_query("SELECT * FROM `jos_member_access` where supplierId='$allSup->link_id' and memberId='$memId' and userId='$uid'");
			$docRes = mysql_fetch_object($getDocValues);
			$docArr = array();
			if(mysql_num_rows($getDocValues) >= 1){
				$docArr = explode(",", $docRes->docIds);
			} else {
				$docArr = '';
			}
						
			$selDoc = mysql_query("SELECT * FROM `jos_document_type`");
			while ($getDoc = mysql_fetch_object($selDoc)){
				if(in_array($getDoc->id, $docArr)){
					$checked = 'checked';
				} else {
					$checked = '';
				}
				if(($getDoc->doc_name == 'Terms') && ($getRes->grpId != '12')){?>
					<td class="docAlign">&nbsp;</td>
				<?php } else {
					if($getDoc->doc_name == 'Marketing'){
						$class="chk_M";
					} else if($getDoc->doc_name == 'Offers'){
						$class="chk_O";
					} else if($getDoc->doc_name == 'Price List'){
						$class="chk_P";
					} else if($getDoc->doc_name == 'Terms'){
						$class="chk_T";
					}
				?>
				<td class="docAlign">
					<input id="checkbox_<?php echo $getRes->id; ?>_<?php echo $getDoc->id; ?>" class="<?php echo $class.'_'.str_replace(' ', '_', $allSup->link_name); ?>" <?php echo $checked; ?>  type="checkbox" name="document[<?php echo $uid; ?>][<?php echo $supplierIdS; ?>]" value="<?php echo $getDoc->id; ?>">
				</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<?php } ?>
	<?php } else {
		echo '<tr><td colspan="6" class="docAlign">No User Found </td></tr>';
	} ?>
	</tbody>
	<?php echo $tableClose;
        }
}
// Single Member And All Supplier END OF CODING

if($memId == 'all'){
	$queryMember=mysql_query("SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Member' and mt.link_published='1' order by mt.link_name");
} else {
	$selUsers = mysql_query("SELECT u.*, ug.group_id as grpId FROM `jos_users` as u,  jos_user_usergroup_map as ug where u.comp_list='$memId' and u.id=ug.user_id order by u.name");
}

$tableOpen  = '<table class="table table-striped table-hover" id="membersList">
	<thead>
	<tr>
		<th width="20%" rowspan="2">Name</th>
		<th width="20%" rowspan="2" class="docAlign">Email Address</th>
		<th width="15%" class="docAlign">Marketing<br><input type="checkbox" class="all" data-chk="chk_M" /></th>
		<th width="15%" class="docAlign">Offers<br><input type="checkbox" class="all" data-chk="chk_O" /></th>
		<th width="15%" class="docAlign">Price List<br><input type="checkbox" class="all" data-chk="chk_P" /></th>
		<th width="15%" class="docAlign">Terms<br><input type="checkbox" class="all" data-chk="chk_T" /></th>
	</tr>
	</thead>';
$tableClose = '</table>';


if($memId == 'all'){ 
	while($allMem = mysql_fetch_object($queryMember))
	{
		echo '<h3>Member: '.$allMem->link_name.'</h3>';
		echo '<table class="table table-striped table-hover" id="membersList">
			<thead>
			<tr>
				<th width="20%" rowspan="2">Name</th>
				<th width="20%" rowspan="2" class="docAlign">Email Address</th>
				<th width="15%" class="docAlign">Marketing<br><input type="checkbox" class="all" data-chk="chk_M" data-comp="'.str_replace(' ', '_', $allMem->link_name).'" /></th>
				<th width="15%" class="docAlign">Offers<br><input type="checkbox" class="all" data-chk="chk_O" data-comp="'.str_replace(' ', '_', $allMem->link_name).'" /></th>
				<th width="15%" class="docAlign">Price List<br><input type="checkbox" class="all" data-chk="chk_P" data-comp="'.str_replace(' ', '_', $allMem->link_name).'" /></th>
				<th width="15%" class="docAlign">Terms<br><input type="checkbox" class="all" data-chk="chk_T" data-comp="'.str_replace(' ', '_', $allMem->link_name).'" /></th>
			</tr>
			</thead>';
		echo '<tbody>';
		$selAllUsers = mysql_query("SELECT u.*, ug.group_id as grpId FROM `jos_users` as u,  jos_user_usergroup_map as ug where comp_list='$allMem->link_id' and u.id=ug.user_id order by name");
		
		if(mysql_num_rows($selAllUsers) >=1)
		{
			while ($getAllRes = mysql_fetch_object($selAllUsers))
			{
				echo '<tr>';
					echo '<td>'.$getAllRes->name.'</td>';
					echo '<td>'.$getAllRes->email.'</td>';
					
					$uid = $getAllRes->id;
					$getAllDocValues = mysql_query("SELECT * FROM `jos_member_access` where supplierId='$supid' and memberId='$allMem->link_id' and userId='$uid'");
					$docAllRes = mysql_fetch_object($getAllDocValues);
										
					$docAllArr = array();
					if(mysql_num_rows($getAllDocValues) >= 1){
						$docAllArr = explode(",", $docAllRes->docIds);
					} else {
						$docAllArr = '';
					}
					
					$selAllDoc = mysql_query("SELECT * FROM `jos_document_type`");
					while ($getAllDoc = mysql_fetch_object($selAllDoc))
					{
						if(in_array($getAllDoc->id, $docAllArr)){
							$checked = 'checked';
						} else {
							$checked = '';
						}
						
						if(($getAllDoc->doc_name == 'Terms') && ($getAllRes->grpId != '12')){
							echo '<td class="docAlign">&nbsp;</td>';
						} else {
							if($getAllDoc->doc_name == 'Marketing'){
								$class="chk_M_".str_replace(' ', '_', $allMem->link_name);
							} else if($getAllDoc->doc_name == 'Offers'){
								$class="chk_O_".str_replace(' ', '_', $allMem->link_name);
							} else if($getAllDoc->doc_name == 'Price List'){
								$class="chk_P_".str_replace(' ', '_', $allMem->link_name);
							} else if($getAllDoc->doc_name == 'Terms'){
								$class="chk_T_".str_replace(' ', '_', $allMem->link_name);
							}
							echo '<td class="docAlign">';
							echo '<input id="checkbox_'.$getAllRes->id.'_'.$getAllDoc->id.'" '.$checked.' type="checkbox" name="document['.$getAllRes->id.'][]" value="'.$getAllDoc->id.'" class="'.$class.'">';
							echo '</td>';	
						}
					}
				echo '</tr>';
			}
		} else {
			echo '<tr><td colspan="6" class="docAlign">No User Found </td></tr>';
		}
		echo '</tbody>';
		echo $tableClose;
	}
} else {
	echo '<h3>&nbsp;</h3>';
	echo $tableOpen; ?>
	<tbody>
	<?php if(mysql_num_rows($selUsers) >=1){ ?>
		<?php while ($getRes = mysql_fetch_object($selUsers)){?>
		<tr>
			<td><?php echo $getRes->name; ?></td>
			<td><?php echo $getRes->email; ?></td>
			<?php 
			$uid = $getRes->id;
			$getDocValues = mysql_query("SELECT * FROM `jos_member_access` where supplierId='$supid' and memberId='$memId' and userId='$uid'");
			$docRes = mysql_fetch_object($getDocValues);
			
			$docArr = array();
			if(mysql_num_rows($getDocValues) >= 1){
				$docArr = explode(",", $docRes->docIds);
			} else {
				$docArr = '';
			}
						
			$selDoc = mysql_query("SELECT * FROM `jos_document_type`");
			while ($getDoc = mysql_fetch_object($selDoc)){
				if(in_array($getDoc->id, $docArr)){
					$checked = 'checked';
				} else {
					$checked = '';
				}
				if(($getDoc->doc_name == 'Terms') && ($getRes->grpId != '12')){?>
					<td class="docAlign">&nbsp;</td>
				<?php } else {
					if($getDoc->doc_name == 'Marketing'){
						$class="chk_M";
					} else if($getDoc->doc_name == 'Offers'){
						$class="chk_O";
					} else if($getDoc->doc_name == 'Price List'){
						$class="chk_P";
					} else if($getDoc->doc_name == 'Terms'){
						$class="chk_T";
					}
				?>
				<td class="docAlign">
					<input id="checkbox_<?php echo $getRes->id; ?>_<?php echo $getDoc->id; ?>" class="<?php echo $class; ?>" <?php echo $checked; ?>  type="checkbox" name="document[<?php echo $getRes->id; ?>][]" value="<?php echo $getDoc->id; ?>">
				</td>
				<?php } ?>
			<?php } ?>
		</tr>
		<?php } ?>
	<?php } else {
		echo '<tr><td colspan="6" class="docAlign">No User Found </td></tr>';
	} ?>
	</tbody>
	<?php echo $tableClose; ?>
<?php } ?>


