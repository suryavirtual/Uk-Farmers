<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<script>
jQuery(function() {
	jQuery("#supplier_detail").tabs();

});
</script>

<div id="supplier_detail">
<div class="span12">
<h2 class="row-fluid">
<?php
$link_name = $this->fields->getFieldById(1);
$this->plugin( 'ahreflisting', $this->link, $link_name->getOutput(1), '', array("edit"=>false,"delete"=>false,"link"=>false) );

if (
	$this->my->id == $this->link->user_id
	&&
	(
		$this->config->get('user_allowmodify') == 1
		||
		$this->config->get('user_allowdelete') == 1
	)
	&&
	$this->my->id > 0
) {
	?>
	<div class="btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#" role="button"> <span class="icon-cog"></span> <span class="caret"></span> </a>
		<ul class="dropdown-menu">
			<?php if( $this->config->get('user_allowmodify') == 1) { ?>
			<li class="edit-icon">
				<a href="<?php echo JRoute::_('index.php?option=com_mtree&task=editlisting&link_id='.$this->link->link_id); ?>">
					<span class="icon-edit"></span>
					<?php echo JText::_( 'COM_MTREE_EDIT' ); ?>
				</a>
			</li>
			<?php
			}

			if( $this->link->link_published && $this->link->link_approved && $this->config->get('user_allowdelete') == 1) { ?>
			<li class="delete-icon">
				<a href="<?php echo JRoute::_('index.php?option=com_mtree&task=deletelisting&link_id='.$this->link->link_id); ?>">
					<span class="icon-delete"></span>
					<?php echo JText::_( 'COM_MTREE_DELETE' ); ?>
				</a>
			</li>
			<?php } ?>
		</ul>
	</div>
	<?php
}
?></h2>
<script>
jQuery(function(){
	
jQuery(".checked").click(function() {

  var termid=jQuery(this).attr("data-termid");
  var user = jQuery(this).attr("data-user");
  var company = jQuery(this).attr("data-comp");
 
var sitePath = '<?php  echo JURI::root() ?>';
        
     var url = sitePath + 'index.php?option=com_users&task=Suppliersendterms.markviewedterms';

     jQuery.ajax({
        type: 'post',
        url: url,
        data: {user_id:user,comp_id:company,termid:termid},
        success:function(data)
        {
        	console.log(data);
        
        }
     });

});  

});


</script>
<?php 
/* Tab code starts from here */
global $database;
$database = JFactory::getDbo();
$linkId = $this->link->link_id;
$qTab = ("select * from #__company_tab where comp_id='$linkId'");
$database->setQuery($qTab);
$resTab = $database->loadObjectList();
//echo "<pre>"; print_r($resTab);

$user = JFactory::getUser();

$user_id = (int)$user->get('id','0');
$session = JFactory::getSession();

 $company_id=$session->get('comp_id');
if(!empty($company_id))
{
	$comp_list=$company_id;
}
else
{
	$comp_list=$user->comp_list;
}


   $queryTerms="select stm.id,stm.viewTerm,stm.deleteRequest,stm.terms_id,st.termId,st.effectiveFrom,st.validTo,st.supplierUserId,st.status,st.termsFile,st.termName,mt.link_name from #__structured_terms_to_members as stm 
   left join #__structured_terms as st on (st.termId=stm.terms_id)
   left join #__users as u on (u.id=st.cmpId)
   left join #__mt_links as mt on(mt.link_id=stm.user_id)
   where stm.member_id='$comp_list' and st.supplierUserId='$linkId' and  stm.viewTerm='0' and st.status='1'";

	$database->setQuery($queryTerms);
	$Terms = $database->loadObjectList();

    $total_term=count($Terms);
$queryusertype="select group_id from #__user_usergroup_map where user_id='$user_id'";
$database->setQuery($queryusertype);
$usertypes=$database->loadObject();
$usertype=$usertypes->group_id;
/* code for fetching permission for tabs */
$querypermission="select docIds from #__member_access where supplierId='$linkId' and userId='$user_id' and memberId='$comp_list' ";
$database->setQuery($querypermission);
$accesstypes=$database->loadObject();
$accesstype=explode(',',$accesstypes->docIds);
$checkacess= in_array(4,$accesstype);
/*code ends here for fetching permission here */



?>
<ul>
	<li><a href="#tabs-1">Home</a></li>
	<?php if(($usertype=="12" || $usertype=="8")):?>
     <li><a href="#tabs-2">Terms</a></li>
 <?php endif; ?>
	<?php $k=3; foreach($resTab as $tabs) :?>
	<li><a href="#tabs-<?php echo $k; ?>"><?php echo $tabs->label; ?></a></li>
	<?php $k++; endforeach; ?>
</ul>
<?php if(($usertype=="12" || $usertype=="8")):?>
 <div id="tabs-2">
 <style>
 #supplier_detail.ui-tabs .ui-tabs-panel table th, #supplier_detail.ui-tabs .ui-tabs-panel table td {
    text-transform: capitalize;
    border-right: 1px solid #e2e2e2;
    *background: #ffffff;
    border-bottom: 1px #e2e2e2 solid;
    text-align: center;
    padding: 10px !important;
    border-top: 1px solid #e2e2e2;
    border-left: 1px solid #e2e2e2;
    text-align:center;
    font-family: 'Open Sans', sans-serif, Arial, sans-serif;
    font-size: 13px;
    line-height: 18px;
    color: #777;
 }

 #supplier_detail.ui-tabs .ui-tabs-panel table td a {color: #08c;text-decoration: none;}
 #supplier_detail.ui-tabs .ui-tabs-panel table td a:hover{color: #005580;text-decoration: underline;}

 </style>
 	<table border="1" class="view_supplier_terms" width="100%">
		<tr>
			<!--<th>Sr No</th>-->
			<th>Supplier Name</th>
			<th>Description</th>
			<!--<th>File Type</th>-->
			<!--<th>Files</th>-->
			<th>Publishing Date</th>
			<th>Expiry Date</th>
		
			</tr>
			<?php 
			if(count($Terms) > 0)
			{
				$m=0;
				$r=1;
				foreach($Terms as $sendTerms){
					if($sendTerms->viewTerm == '0')
					{
						$today = strtotime(date("Y-m-d"));
						$expiration_date = strtotime($sendTerms->validTo);
						?>
					   <tr>
					  <!--<td><?php // echo $r; ?></td>-->
						<!--<td><input type="checkbox" name="selectedTerms[]" value="<?php //echo $sendTerms->id; ?>"></td>-->
						<td><?php echo $sendTerms->link_name; ?></td>
						<td><a class="checked"  data-termid="<?php echo $sendTerms->termId ?>" data-user="<?php echo $user_id;?>" data-comp="<?php echo $comp_list; ?>" href="<?php echo JURI::root().$sendTerms->termsFile;?>" target="_blank"><?php echo $sendTerms->termName; ?></a></td>
						<!--<td><?php //echo "Terms"; ?></td>-->
						<!--<td><a href="<?php echo JURI::root();?>upload/terms/<?php echo $sendTerms->termsFile;?>" target="_blank">Term File</a></td>-->
						<?php 
						     $pdate = explode(" ",$sendTerms->effectiveFrom); 
                             $test = new DateTime($pdate['0']);
                             $uploaded= date_format($test, 'd-m-Y');  ?>
						<td><?php  echo $uploaded; ?></td>

						<?php  $newdate=new DateTime($sendTerms->validTo);
						      $validTo=date_format($newdate, 'd-m-Y');?>
						      <?php if ($expiration_date < $today) : ?> 
						<td style="color:red"><?php echo  $validTo; ?></td>
					    <?php else : ?>
						<td class="<?php echo $cls; ?>"><?php echo  $validTo; ?></td>
					<?php endif; ?>	
					
					 </tr>
					<?php $m++; $r++; } } ?>
					<?php if($m=='0'){ ?>
						<tr><td colspan="8" align="center">No Record Found </td></tr>
					<?php } ?>
			<?php } 
			else {
			 ?>
				<tr><td colspan="8" align="center">No terms datasheet exists for this supplier</td></tr>
			<?php }?>
			<input type="hidden" name="user_id" value="<?php echo $usr_id; ?>"/>
				<input type="hidden" name="comp_id" value="<?php echo $comp_id; ?>"/>
			<!--<tr><td colspan="8" align="center"><input type="submit" name="markedView" id="markedView" value="Mark as Viewed" /></td></tr>-->
			</table>
 </div>
 <!--tab2 ends here -->

 	<?php endif; ?>

<div id="tabs-1">
<div id="listing" class="link-id-<?php echo $this->link_id; ?> cat-id-<?php echo $this->link->cat_id; ?> tlcat-id-<?php echo $this->link->tlcat_id; ?>" itemscope itemtype="http://schema.org/Thing">

<?php 
if (!empty($this->images)) include $this->loadTemplate( 'sub_images.tpl.php' );
if ( !empty($this->mambotAfterDisplayTitle) ) { 
	echo trim( implode( "\n", $this->mambotAfterDisplayTitle ) );
}

if ( !empty($this->mambotBeforeDisplayContent) && $this->mambotBeforeDisplayContent[0] <> '' ) { 
	echo trim( implode( "\n", $this->mambotBeforeDisplayContent ) ); 
}

echo '<p class="listing-desc">';
if ($this->config->getTemParam('skipFirstImage','0') == 1) {
	array_shift($this->images);
}

if(!is_null($this->fields->getFieldById(2))) { 
	$link_desc = $this->fields->getFieldById(2);
	echo '<span itemprop="description">';
	if( $link_desc->hasValue() )
	{
		echo $link_desc->getDisplayPrefixText(); 
		echo $link_desc->getOutput(1);
		echo $link_desc->getDisplaySuffixText(); 
	}
	echo '</span>';
}
echo '</p>';?>
<br>
<?php
 $linkId = $this->link->link_id;
$keywordquery = ("SELECT value FROM jos_mt_cfvalues WHERE cf_id = '28' and  link_id='$linkId'");
$database->setQuery($keywordquery);
$keywords = $database->loadObjectList();
//print_r($keywords);
if(!empty($keywords))
{?>
<p style="font-size:10px;"><?php echo $keywords[0]->value; ?></p>
<?php }
?>
</div>
</div>
<?php $k=3; foreach($resTab as $tabs) :?>
<div id="tabs-<?php echo $k; ?>">
	<div class="listing-desc">
	
	<?php if($tabs->label=="UF Terms")
	{
		/*$totals_query = "SELECT sum(current_month_value) as current_month_value, 
	sum(previous_yr_current_month_value) as previous_yr_current_month_value,
	sum(yr_to_date_value) as yr_to_date_value, 
	sum(previous_yr_to_date_value) as previous_yr_to_date_value,
	sum(rolling_yr_value) as rolling_yr_value, 
	sum(yoy_ratio_increase) as yoy_ratio_increase
	FROM jos_purchase_history where supplierid= '".$this->link->link_id."' ";

    $database->setQuery( $totals_query);
    $rows = $database->loadObject();
		
			if($rows->current_month_value !=''){
				$cmv = $rows->current_month_value;
			} else {
				$cmv = '--';
			}

			if($rows->previous_yr_current_month_value !=''){
				$mly = $rows->previous_yr_current_month_value;
			} else {
				$mly = '--';
			}

			if($rows->yr_to_date_value !=''){
				$ytd = $rows->yr_to_date_value;
			} else {
				$ytd = '--';
			}

			if($rows->previous_yr_to_date_value !=''){
				$prd = $rows->previous_yr_to_date_value;
			} else {
				$prd = '--';
			}

			if($rows->rolling_yr_value !=''){
				$ryv = $rows->rolling_yr_value;
			} else {
				$ryv = '--';
			}

			if($rows->yoy_ratio_increase !=''){
				$yri = $rows->yoy_ratio_increase;
			} else {
				$yri = '--';
			}
			?>
			<table border="1">
				<tr>
				<th>Month This Year</th>
				<th>Month Last Year</th>
				<th>YTD This Year</th>
				<th>YTD Last Year</th>
				<th>Rolling 12 Months</th>
				<th>YOY Ratio Increase</th>
				</tr>
				<tr>
				<td><?php echo round($cmv,2);?></td>
				<td><?php echo round($mly,2);?></td>
				<td><?php echo round($ytd,2);?></td>
				<td><?php echo round($prd,2);?></td>
				<td><?php echo round($ryv,2);?></td>
				<td><?php echo round($yri,2);?></td>
				</tr>

				</table>
				<br>
		
	<?php */ }
	?>
	<?php echo $tabs->description; ?>
	
	</div>
</div>

<?php $k++; endforeach; ?>
<?php 
if ( !empty($this->mambotAfterDisplayTitle) ) { 
	echo trim( implode( "\n", $this->mambotAfterDisplayTitle ) );
}

if ( !empty($this->mambotBeforeDisplayContent) && $this->mambotBeforeDisplayContent[0] <> '' ) { 
	echo trim( implode( "\n", $this->mambotBeforeDisplayContent ) ); 
}

/* echo '<div class="listing-desc">';
if ($this->config->getTemParam('skipFirstImage','0') == 1) {
	array_shift($this->images);
}

if(!is_null($this->fields->getFieldById(2))) { 
	$link_desc = $this->fields->getFieldById(2);
	echo '<span itemprop="description">';
	if( $link_desc->hasValue() )
	{
		echo $link_desc->getDisplayPrefixText(); 
		echo $link_desc->getOutput(1);
		echo $link_desc->getDisplaySuffixText(); 
	}
	echo '</span>';
}
echo '</div>'; */

$address = '';
if( $this->config->getTemParam('displayAddressInOneRow','1') ) {
	$address_parts = array();
	$address_displayed = false;
	foreach( array( 4,5,6,7,8 ) AS $address_field_id )
	{
		$field = $this->fields->getFieldById($address_field_id);
		if( isset($field) && $output = $field->getOutput(1) )
		{
			$address_parts[] = $output;
		}
	}
	if( !empty($address_parts) ) { $address = implode(', ',$address_parts); }
}

// Other custom fields
 /* echo '<ul class="fields">';
$number_of_columns = $this->config->getTemParam('numOfColumnsInDetailsView','2');
$field_count = 0;
$need_div_closure = false;

$this->fields->resetPointer();
while( $this->fields->hasNext() ) {
	$field = $this->fields->getField();
	$value = $field->getValue();
	$hasValue = $field->hasValue();
	if( 
		( 
			(
				(!$field->hasInputField() && !$field->isCore() && empty($value)) 
				||
				(!empty($value) || $value == '0')
			)
			&& 
			// This condition ensure that fields listed in array() are skipped
			!in_array($field->getName(),array('link_name','link_desc'))
			&&
			(
				(
					$this->config->getTemParam('displayAddressInOneRow','1') == 1
					&& 
					!in_array($field->getId(),array(5,6,7,8)) 
				)
				||
				$this->config->getTemParam('displayAddressInOneRow','1') == 0
			)
			&&
			$hasValue
		) 
		||
		// Fields in array() are always displayed regardless of its value.
		in_array($field->getName(),array('link_featured'))
	) {
		echo '<li id="field_'.$field->getId().'" class="fieldRow'.(($field_count % $number_of_columns == ($number_of_columns -1))?' lastFieldRow':'').'" style="width:'.floor(96/intval($number_of_columns)).'%">';
		
		if($this->config->getTemParam('displayAddressInOneRow','1') && in_array($field->getId(),array(4,5,6,7,8)) && $address_field = $this->fields->getFieldById(4)) {
			if( $address_displayed == false ) {
				echo '<div class="caption">';
				if($address_field->hasCaption()) {
					echo $address_field->getCaption();
				}
				echo ': </div>';
				echo '<div class="output">';
				echo $address_field->getDisplayPrefixText(); 
				echo $address;
				echo $address_field->getDisplaySuffixText(); 
				echo '</div>';
				$address_displayed = true;
			}
		} else {
			echo '<div class="caption">';
			if($field->hasCaption()) {
				echo $field->getCaption();
			}
			echo ': </div>';
			echo '<div class="output">';
			switch($field->getFieldType())
			{
				case ( $field->getFieldType() == 'coreprice' && $field->getValue() == 0 ):
					echo $field->getOutput(1);
					break;

				default:
					echo $field->getDisplayPrefixText(); 
					echo $field->getOutput(1);
					echo $field->getDisplaySuffixText(); 
			}
			echo '</div>';
		}
		echo '</li>';

		$field_count++;
	}
	$this->fields->next();
}

echo '</ul>'; */
?>
</div>


<div class="row-fluid">
<?php

// echo '<div class="column first">';
echo '<div class="span8">';



if ( !empty($this->mambotAfterDisplayContent) ) { echo trim( implode( "\n", $this->mambotAfterDisplayContent ) ); }

if( $this->config->get('show_favourite') == 1 || $this->config->get('show_rating') == 1 )
{
	echo '<div class="rating-fav">';
	if($this->config->get('show_rating')) {
		echo '<div class="rating">';
		$this->plugin( 'ratableRating', $this->link, $this->link->link_rating, $this->link->link_votes); 
		echo '<div id="total-votes">';
		if( $this->link->link_votes <= 1 ) {
			echo $this->link->link_votes . " " . strtolower(JText::_( 'COM_MTREE_VOTE' ));
		} elseif ($this->link->link_votes > 1 ) {
			echo $this->link->link_votes . " " . strtolower(JText::_( 'COM_MTREE_VOTES' ));
		}
		echo '</div>';
		echo '</div>';
	}

	if($this->config->get('show_favourite')) {
	?>
	<div class="favourite">
	<span class="fav-caption"><?php echo JText::_( 'COM_MTREE_FAVOURED' ) ?>:</span>
	<div id="fav-count"><?php echo number_format($this->total_favourites,0,'.',',') ?></div><?php 
		if($this->my->id > 0){ 
			if($this->is_user_favourite) {
				?><div id="fav-msg"><a href="javascript:fav(<?php echo $this->link->link_id ?>,-1);"><?php echo JText::_( 'COM_MTREE_REMOVE_FAVOURITE' ) ?></a></div><?php 
			} else {
				?><div id="fav-msg"><a href="javascript:fav(<?php echo $this->link->link_id ?>,1);"><?php echo JText::_( 'COM_MTREE_ADD_AS_FAVOURITE' ) ?></a></div><?php 
				}
		} ?>
	</div><?php
	}
	echo '</div>';
}

echo '</div>';

// echo '<div class="column second">';
echo '<div class="span4">';

//echo '<h3>';
//echo MText::_( 'LISTING_DETAILS', $this->tlcat_id );
//echo '</h3>';
// Address


echo '</div>';

echo '</div>'; // End of .row


if( $this->show_actions_rating_fav ) { ?>
	<div class="row-fluid">
	<div class="span12 actions-rating-fav">
	<?php if( $this->show_actions ) { ?>
	<div class="actions">
	<?php 
		$this->plugin( 'ahrefreview', $this->link, array("class"=>"btn", "rel"=>"nofollow") ); 
		$this->plugin( 'ahrefrecommend', $this->link, array("class"=>"btn", "rel"=>"nofollow") );	
		$this->plugin( 'ahrefprint', $this->link, array("class"=>"btn", "rel"=>"nofollow") );
		$this->plugin( 'ahrefcontact', $this->link, array("class"=>"btn", "rel"=>"nofollow") );
		$this->plugin( 'ahrefvisit', $this->link, '', 1, array("class"=>"btn", "rel"=>"nofollow") );
		$this->plugin( 'ahrefreport', $this->link, array("class"=>"btn", "rel"=>"nofollow") );
		$this->plugin( 'ahrefclaim', $this->link, array("class"=>"btn", "rel"=>"nofollow") );
		$this->plugin( 'ahrefownerlisting', $this->link, array("class"=>"btn") );
		$this->plugin( 'ahrefmap', $this->link, array("class"=>"btn", "rel"=>"nofollow") );
	?></div>
	</div>
	</div><?php
	}
?><!-- </div> -->
<?php }

// Load User Profile
if( $this->config->get('show_user_profile_in_listing_details') )
{
	include $this->loadTemplate( 'sub_userProfile.tpl.php' );
}

// Load Contact Owner Form
if( $this->config->get('contact_form_location') == 2 )
{
	include $this->loadTemplate( 'sub_contactOwnerForm.tpl.php' );
}

?>
</div>
</div>

<style>
#supplier_detail #tabs-1 .row-fluid{ padding-top: 0px; }
#supplier_detail.ui-tabs .ui-tabs-panel { padding: 1em 1.4em 1em 0;}
/*#supplier_detail.ui-tabs .ui-tabs-nav li { font-size: 12px; } */
#supplier_detail.ui-tabs .ui-tabs-panel table th, #supplier_detail.ui-tabs .ui-tabs-panel table td{ padding: 0px 5px; }
</style>