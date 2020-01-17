<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc =& JFactory::getDocument();
$database = JFactory::getDBO();
$user = JFactory::getUser();
$comp_id = $user->comp_list;
$usr_id = $user->id;
if($usr_id!=""){ ?>
	<div id="view_supplier_files">
	<h2><?php echo $doc->getTitle(); ?></h2>
	<h4>RECEIVED TERMS</h4>
<?php 
   $queryTerms="select stm.id,stm.viewTerm,stm.deleteRequest,stm.terms_id,st.termId,st.effectiveFrom,st.validTo,st.supplierUserId,st.termsFile,st.termName,mt.link_name from #__structured_terms_to_members as stm 
   right join #__structured_terms as st on (st.termId=stm.terms_id)
   right join #__users as u on (u.id=st.cmpId)
   left join #__mt_links as mt on(mt.link_id=u.comp_list)
   where stm.member_id='$comp_id' and stm.viewTerm='0' and stm.id NOT IN(SELECT tid FROM jos_supplier_term_viewed)";

	$database->setQuery( $queryTerms );
	$Terms = $database->loadObjectList();
	/* viewed term query start from here */
	$queryFiles1="select ftm.id,ftm.member_id,ftm.deleteRequest, sf.* , mt.link_name from jos_supplier_term_viewed as fv, jos_structured_terms_to_members as ftm, jos_structured_terms as sf, jos_users as u, jos_mt_links as mt where fv.user_id='$usr_id' and fv.tid=ftm.id and ftm.terms_id=sf.termId and u.id=sf.cmpId and u.comp_list=mt.link_id   order by mt.link_name";
     $database->setQuery( $queryFiles1 );
     $files1 = $database->loadObjectList();
	?> 
	<form action="index.php?option=com_users&task=suppliersendterms.markviewedterms" method="post" name="receiveTerms" id="receiveTerms">
		<table border="1" width="100%">
		<tr>
			<th>Select</th>
			<th>Supplier Name</th>
			<th>Description</th>
			<th>File Type</th>
			<!--<th>Files</th>-->
			<th>Publishing Date</th>
			<th>Expiry Date</th>
			<th>Status</th>
			</tr>
			<?php 
			if(count($Terms) > 0 ){
				$m=0;
				foreach($Terms as $sendTerms){ 
					if($sendTerms->viewTerm == '0'){
						$today = strtotime(date("Y-m-d"));
						$expiration_date = strtotime($sendTerms->validTo);
						if ($expiration_date > $today)
						 {
							$cls = 'valid';
						
						?>
					<tr>
						<td><input type="checkbox" name="selectedTerms[]" value="<?php echo $sendTerms->id; ?>"></td>
						<td><?php echo $sendTerms->link_name; ?></td>
						<td><a href="<?php echo JURI::root();?>upload/terms/<?php echo $sendTerms->termsFile;?>" target="_blank"><?php echo $sendTerms->termName; ?></a></td>
						<td><?php echo "Terms"; ?></td>
						<!--<td><a href="<?php echo JURI::root();?>upload/terms/<?php echo $sendTerms->termsFile;?>" target="_blank">Term File</a></td>-->
						<?php 
						     $pdate = explode(" ",$sendTerms->effectiveFrom); 
                             $test = new DateTime($pdate['0']);
                             $uploaded= date_format($test, 'd-m-Y');  ?>
						<td><?php  echo $uploaded; ?></td>

						<?php  $newdate=new DateTime($sendTerms->validTo);
						      $validTo=date_format($newdate, 'd-m-Y');?>
						<td class="<?php echo $cls; ?>"><?php echo  $validTo; ?></td>
						<td>New</td>
					</tr>
					<?php }$m++; } } ?>
					<?php if($m=='0'){ ?>
						<tr><td colspan="8" align="center">No Record Found </td></tr>
					<?php } ?>
			<?php } else { ?>
				<tr><td colspan="8" align="center">No Terms Found </td></tr>
			<?php }?>
			<input type="hidden" name="user_id" value="<?php echo $usr_id; ?>"/>
				<input type="hidden" name="comp_id" value="<?php echo $comp_id; ?>"/>
			<tr><td colspan="8" align="center"><input type="submit" name="markedView" id="markedView" value="Mark as Viewed" /></td></tr>
			</table>
		</form>
		<!-- end Received Terms -->
		
		<h4>VIEWED TERMS</h4>
		<table border="1" width="100%">
			<tr>
				<th>Supplier Name</th>
				<th>Description</th>
				<th>File Type</th>
				<!--<th>Files</th>-->
				<th>Publishing Date</th>
				<th>Expiry Date</th>
				<th>Action</th>
			</tr>
			<?php $p=0; 
			foreach($files1 as $sendTerms)
			{
				if( $sendTerms->deleteRequest=='0')
				{
					$today = strtotime(date("Y-m-d"));
					$expiration_date = strtotime($sendTerms->validTo);
					if ($expiration_date > $today) 
					{
						$cls = 'valid';
					
					?>
				<tr>
					    <td><?php echo $sendTerms->link_name; ?></td>
						<td><a href="<?php echo JURI::root();?>upload/terms/<?php echo $sendTerms->termsFile;?>" target="_blank"><?php echo $sendTerms->termName; ?></a></td>
						<td><?php echo "Terms"; ?></td>
					<!--<td><a href="<?php echo JURI::root();?>upload/terms/<?php echo $sendTerms->termsFile;?>" target="_blank">Term File</a></td>-->

					<?php 
						     $pdate = explode(" ",$sendTerms->effectiveFrom); 
                             $test = new DateTime($pdate['0']);
                             $uploaded= date_format($test, 'd-m-Y');  ?>
						<td><?php  echo $uploaded; ?></td>

						<?php  $newdate=new DateTime($sendTerms->validTo);
						      $validTo=date_format($newdate, 'd-m-Y');?>
						<td class="<?php echo $cls; ?>"><?php echo  $validTo; ?></td>
					<td><a href="index.php?option=com_users&task=suppliersendterms.unplublishTerms&id=<?php echo $sendTerms->id; ?>">Delete</a></td>
				</tr>
				<?php } $p++; } ?>
	<?php } ?>
			<?php if($p=='0'){ ?>
				<tr><td colspan="8" align="center">No Record Found </td></tr>
			<?php } ?>
		</table>
		
	</div>
	
	
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
}?>