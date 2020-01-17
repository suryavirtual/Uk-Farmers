<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; 
?>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/colorbox.css" />
<script src="<?php echo JURI::root();?>/assets/js/jquery.colorbox.js"></script>
<script>
jQuery(document).ready(function(){
	//Examples of how to assign the Colorbox event to elements
	jQuery(".inline").colorbox({inline:true, width:"50%"});
});
</script>
<?php
$user = JFactory::getUser();
$Itemid = JRequest::getVar('Itemid');
$url = JURI::current();

if($user->id!=""){
	$currentuser=$user->id;
	$db = JFactory::getDbo();
	
	$db->setQuery('SELECT mt.*, mtimg.filename, cf.value FROM #__mt_links as mt, #__mt_images as mtimg, #__mt_cfvalues as cf 
		WHERE mt.user_id ='.(int)$currentuser.' and mt.link_published=1 and mt.link_id=mtimg.link_id and mt.link_id=cf.link_id and cf.cf_id="35"');
	$results = $db->loadObject();

	$available_suppliers = count($results);
	if($available_suppliers>0){
		$about_us=$results->link_desc;
		$company_name=$results->link_name;
		$main_address=$results->address;
		$company_logo=$results->filename;
		$phone_number=$results->telephone;
		$fax_number=$results->fax;
		$moreDtl = $results->value;
	} else {
		$about_us="";
		$company_name="";
		$main_address="";
		$company_logo="";
		$phone_number="";
		$fax_number="";
	}
?>

<div class="page-header">
	<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
</div>
<!--<form id="supplier-details" action="<?php echo JURI::root(); ?>index.php?option=com_users&task=supplierdetails.getsupplierdetailsfinal&Itemid=<?php //echo $Itemid; ?>" method="post" class="form-validate" enctype="multipart/form-data">-->
<div class="my-dtl">
	<?php $target_dir1 = JURI::root()."media/com_mtree/images/listings/s/Loading.gif";
	if($company_logo!=""){
		$target_dir = JURI::root()."media/com_mtree/images/listings/o/".$company_logo; ?>
		<img src="<?php echo $target_dir;?>" width="272" height="200" id="logo_supplier_preview" class="cmp-img" style="border: 1px solid #DDD;padding: 5px;" />
	<?php } else {
		echo "No Logo Available to Preview";
	} ?>
	<h3 class="cmp-name"><?php echo $company_name; ?></h3>
	<p class="cmp-dtl"><?php echo $about_us; ?></p>
	
	<div class="cmp-add">
		<h4 class="">Main Address</h4>
		<?php echo $main_address; ?>
	</div>
	
	<div class="cmp-phone">
		<h4 class="">Phone Number</h4>
		<?php echo $phone_number; ?>
	</div>
	
	<div class="cmp-fax">
		<h4 class="">Fax Number</h4>
		<?php echo $fax_number; ?>
	</div>
	
	<div class="cmp-email">
		<h4 class="">Email Address</h4>
		<?php echo $user->email; ?>
	</div>
	
	<div class="cmp-email">
		<h5 class="">For more details please <a href="<?php echo $moreDtl; ?>" target="_blank">Click Here</a></h5>
	</div>
	
	<div class="controls savef">
		<a class="inline" href="#inline_email"><button type="submit" class="btn btn-small main-bg " id="supplier_submit">SEND REQUEST</button></a>
		<img src=""  class="supplier_loader" style="display:none"/>
	</div>
</div>

<div style="display:none">
	<div id="inline_email" style="padding:10px; background:#fff; height:300px;">
		<form name="emailEdit" action="<?php echo JURI::root(); ?>index.php?option=com_users&task=supplierdetails.sendEmail" method="post" >
		<div class="myleftEmail">
			<div class="form-input">
				<label id="supplier_message_label" for="supplier_email" class="labcolo" title="Email Message" aria-invalid="true">Email Message</label>
				<textarea name="supplier_message" cols="100" rows="7" id="supplier_message" ></textarea>
				<input type="hidden" name="user_name" value="<?php echo $user->name; ?>" id="" />
				<input type="hidden" name="user_email" value="<?php echo $user->email; ?>" id="" />
			</div>
			<div class="controls savef">
				<button type="submit" class="btn btn-small main-bg " id="supplier_submit">SEND EMAIL</button>
			</div>
		</div>
	</div>
	</form>
</div>

<!--</form>-->
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
} ?>