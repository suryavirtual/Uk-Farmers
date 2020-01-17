<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$database = JFactory::getDBO();
$user = JFactory::getUser();
$usr_id = $user->id;
//$comp_id=$user->comp_list;
$session = JFactory::getSession();

$company_id=$session->get('comp_id');
if(!empty($company_id))
{
	$comp_id=$company_id;
}
else
{
	$comp_id = $user->comp_list;
}



if($usr_id!="")
{
	$queryDoc = "select * from #__document_type order by doc_name";
	$database->setQuery( $queryDoc );
	$docType = $database->loadObjectList();
	//echo "<pre>";
	//print_r($docType);

$queryusertype="select group_id from #__user_usergroup_map where user_id='$usr_id '";
$database->setQuery($queryusertype);
$usertypes=$database->loadObject();
$usertype=$usertypes->group_id;
		?>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/multiple-select-functions.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/script-file.js"></script>
<script src="https://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
<script src="https://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/colorbox.css" />
<script src="<?php echo JURI::root();?>/assets/js/jquery.colorbox.js"></script>
<style>
.error{color:#ff0000;}
</style>
<script>
jQuery(function() {
	jQuery("#supplier_send_files").tabs();
});
jQuery(function() {
	var currentDate = new Date();
	jQuery( "#datepicker" ).datepicker({
		dateFormat:'dd-mm-yy',
		
		minDate: 0
		
	});
	jQuery("#datepicker").datepicker("setDate", null);
		
	
	
});

jQuery(function() {
jQuery( "#demoFiler" ).validate({
	rules: {
		sendfile1type:{
			required:true
		},
		file_description:{
			required:true
		},
		expiry:{
			required:true
		}
	}
});
jQuery("input[type=file]").each(function() {
	jQuery(this).rules("add", {
		accept: "pdf|xls|csv|doc|docx|ppt|xlsx",
		messages: {
			accept: "Only pdf, xls, csv,xlsx, doc, docx & ppt files are allowed"
		}
	});
});
});

jQuery(document).ready(function(){
	

	//Examples of how to assign the Colorbox event to elements
	jQuery(".inline").colorbox({inline:true, width:"50%"});
});

/*-- for ajax pagination --*/
jQuery(document).ready(function() {

	//jQuery("#results" ).load( "<?php echo JURI::root();?>/fetch_pages.php?user_id=<?php echo $usr_id; ?>"); //load initial records
	//jQuery("#results" ).load( "<?php echo JURI::root();?>/fetch_pages.php?user_id=<?php echo $usr_id; ?>");
	jQuery("#results" ).load( "<?php echo JURI::root();?>/fetch_pages.php?user_id=<?php echo $comp_id; ?>");
	
	jQuery('#search').keyup(function(e) {
		if(e.keyCode == 13) {
			e.preventDefault();
			jQuery(".loading-div").show(); //show loading element
			var page = jQuery(this).attr("data-page"); //get page number from link
			jQuery("#results").load("<?php echo JURI::root();?>/fetch_pages.php?user_id=<?php echo $usr_id; ?>&val=" + jQuery("#search").val(),{"page":page}, function(){ //get content from PHP page
				jQuery(".loading-div").hide(); //once done, hide loading element
			});
		}
	});
	
	jQuery(".searchBtn").click(function(e){
		//show the loading bar
		e.preventDefault();
		jQuery(".loading-div").show(); //show loading element
		var page = jQuery(this).attr("data-page"); //get page number from link
		jQuery("#results").load("<?php echo JURI::root();?>/fetch_pages.php?user_id=<?php echo $usr_id; ?>&val=" + jQuery("#search").val(),{"page":page}, function(){ //get content from PHP page
			jQuery(".loading-div").hide(); //once done, hide loading element
		});
		return false;
	});
	
	//executes code below when user click on pagination links
	jQuery("#results").on( "click", ".pagination a", function (e){
		e.preventDefault();
		jQuery(".loading-div").show(); //show loading element
		var page = jQuery(this).attr("data-page"); //get page number from link
		var value = jQuery(this).attr("data-val"); //get page number from link
		console.log(value);
		if(value!=''){
			var val = value;
		} else {
			var val = '';
		}
		
		
		
	});
});
/*-- end ajax pagination --*/
</script>


<div id="supplier_send_files">
<ul>
	<li><a href="#tabs-1">Upload Files</a></li>
	<li><a href="#tabs-2">Existing Files</a></li>
</ul>
<div id="tabs-1">
	<div id="error_note" style="color:red;"></div>
	<span class="upco">Upload Files </span>
	<p>You can upload any of these file type <span class="bgblu1"> PDF, Excel, Word, PowerPoint </span></p>
	<form name="demoFiler" id="demoFiler" enctype="multipart/form-data" method="post" action="index.php?option=com_users&task=suppliersendfiles.getsupplierdetailsfinal">
		<div class="textcen">
			<div id="filediv"><input name="supplier_files[]" type="file" id="supplier_files"/></div>
		</div>
		
		<span class="upco">Selected Files</span>
		<p>Please add the file type, description and expiry date.</p>
		<div class="sendfileu">
			<table>
			<tr>
				<th>file</th>
				<th>File Type*</th>
				<th>Description*</th>
				<th>Expires*</th>
			</tr>
			<tr>
				<td width="20%"><div id="sendfile1display"></div></td>
				<input type="hidden" name="sendfile" id="sendfile" value="" />
				<td>
					<select name="sendfile1type" id="sendfile1type">
						<option value="">Please Select</option>
					<?php foreach($docType as $docTypes){?>
					<?php if($docTypes->id=="4") :?>
						<?php if($usertype=="13"){?>
						<option value="<?php echo $docTypes->id;?>"><?php echo $docTypes->doc_name; ?></option>
						<?php }?>
					<?php else:?>
						<option value="<?php echo $docTypes->id;?>"><?php echo $docTypes->doc_name; ?></option>
					<?php endif;?>
					<?php } ?>
					</select>
				</td>
				<td><input type="text" name="file_description" id="file_description" size="30"></td><?php //echo date('d-m-Y'); ?>
				<td class="cal"><input type="text" id="datepicker" name="expiry" value=""></td>
			</tr>
			<tr>
				<td colspan="3">
					<div id="dragAndDropFilesresults"></div>
					<div class="progressBar"><div class="status"></div></div>
				</td>
				<td>
				<input type="hidden" name="comp_id" value="<?php echo $comp_id ; ?>">
					<input type="submit" name="submitHandler" id="submitHandler" value="SAVE TO SERVER" class="buttonUpload btn btn-small main-bg " />
				</td>
			</tr>
			</table>
		</div>
	</form>
</div>
<div id="tabs-2">

<div class="textBox">
	<input type="text" value="" maxlength="100" name="searchBox" id="search">
	<div class="searchBtn">&nbsp;</div>
</div>	
<br clear="all" />

<form method="post" name="share" id="share" action="index.php?option=com_users&task=suppliersendfiles.sendfilestomember">	
<input type="hidden" name="comp_id" value="<?php echo $comp_id ; ?>">
<input type="hidden" name="users_id" value="<?php echo $usr_id; ?>">	
<fieldset>
	<legend><span class="upco">Existing Files</span></legend>
	<!-- View File Listing Data -->
		<div id="results"><!-- content will be loaded here --></div>
</fieldset>
<br>

<fieldset>
	<?php
	$query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
	$database->setQuery( $query );
	$member = $database->loadObjectList();
	?>
	<legend> <span class="upco">Send Files to Members</span></legend>
	<table width="100%" border="0">
	<tr>
		<td valign="top">
		<select name="Box1" id="Box1" multiple="multiple" size=6 style="width:200px;margin:5px 0 5px 0;">
		<?php for($k=0; $k<count($member); $k++){ ?>
			<option value="<?php echo $member[$k]->link_id; ?>"><?php echo $member[$k]->link_name; ?></option>
		<?php } ?>
		</select>
		</td>
		<td valign="top" style="vertical-align: middle;">
			<p><button name="MoveRight" type="button" onclick="FM.moveSelections(this.form.Box1, this.form.Box2);">&gt;</button></p>
			<p><button name="MoveLeft" type="button" onclick="FM.moveSelections(this.form.Box2, this.form.Box1); FM.sortOptions(this.form.Box1); FM.selectAllOptions(this.form.Box2);">&lt;</button></p>
		</td>
		<td style="vertical-align: middle;">
			<select name="Box2[]" id="Box2" multiple="multiple" size=6 style="width:200px;margin:5px 0 5px 0;">
			<!-- option Data -->
			</select>
		</td>
		<td align="right" style="vertical-align: middle;"><input type="submit" value="Send Files" class="sub"></td>
	</tr>
	</table>
</fieldset>
</form>
</div>


<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
}?>
