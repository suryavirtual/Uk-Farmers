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


$queryCmp = "SELECT * from jos_mt_links where user_id='".$usr_id."' and link_published='1'";
$database->setQuery( $queryCmp );
$dtlAddress = $database->loadObject();
$url = JURI::current();

$query = "SELECT * FROM #__structured_terms WHERE cmpId=$usr_id";
$database->setQuery( $query );
$rows_existing_terms = $database->loadObjectList();

if(!empty($usr_id)){
	$queryCmp = "SELECT * from jos_mt_links where user_id='".$usr_id."' and link_published='1'";
	$database->setQuery( $queryCmp );
	$rows = $database->loadObject();
	//$cmpId = $rows->link_id;
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
//echo "compidsssss".$cmpId;
?>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/multiple-select-functions.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/script-file.js"></script>
<script>
jQuery(function() {
	jQuery("#supplier_send_terms").tabs();
});
jQuery(function() {
	var currentDate = new Date();
	jQuery( "#effective_from" ).datepicker({
		dateFormat:'dd-mm-yy',
		showOn: "both",
        buttonImageOnly: true,
        buttonImage: "<?php echo JURI::root();?>/assets/images/calender.png",
        buttonText: "Calendar"
	});
	jQuery("#effective_from").datepicker("setDate", currentDate);
	
	jQuery( "#valid_to" ).datepicker({
		dateFormat:'dd-mm-yy',
		showOn: "both",
        buttonImageOnly: true,
        buttonImage: "<?php echo JURI::root();?>/assets/images/calender.png",
        buttonText: "Calendar"
	});
	jQuery("#valid_to").datepicker("setDate", currentDate);
});

jQuery("input[type=file]").each(function() {
	jQuery(this).rules("add", {
		accept: "pdf",
		messages: {
			accept: "Only pdf files are allowed"
		}
	});
});



/*-- for ajax pagination --*/
jQuery(document).ready(function() 
{
	jQuery(".loading-div").show();

	jQuery("#results" ).load( "<?php echo JURI::root();?>/fetch_terms.php?user_id=<?php echo $usr_id; ?>&cmp_ids=<?php echo $cmpId; ?>",function(){ jQuery(".loading-div").hide();}); //load initial records
	
	jQuery('#search').keyup(function(e) {
		if(e.keyCode == 13) {
			e.preventDefault();
			jQuery(".loading-div").show(); //show loading element
			var page = jQuery(this).attr("data-page"); //get page number from link
			jQuery("#results").load("<?php echo JURI::root();?>/fetch_terms.php?user_id=<?php echo $usr_id; ?>&cmp_ids=<?php echo $cmpId; ?>&val=" + jQuery("#search").val(),{"page":page}, function(){ //get content from PHP page
				jQuery(".loading-div").hide(); //once done, hide loading element
			});
		}
	});
	
	jQuery(".searchBtn").click(function(e){
		//show the loading bar
		e.preventDefault();
		jQuery(".loading-div").show(); //show loading element
		var page = jQuery(this).attr("data-page"); //get page number from link
		jQuery("#results").load("<?php echo JURI::root();?>/fetch_terms.php?user_id=<?php echo $usr_id; ?>&cmp_ids=<?php echo $cmpId; ?>&val=" + jQuery("#search").val(),{"page":page}, function(){ //get content from PHP page
			jQuery(".loading-div").hide(); //once done, hide loading element
		});
		return false;
	});
	
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
		
		jQuery("#results").load("<?php echo JURI::root();?>/fetch_terms.php?user_id=<?php echo $usr_id; ?>&cmp_ids=<?php echo $cmpId; ?>&val="+val,{"page":page}, function(){ //get content from PHP page
			jQuery(".loading-div").hide(); //once done, hide loading element
		});
	});
});

/*-- end ajax pagination --*/
</script>

<div id="supplier_send_terms">


	
	
	<div id="tabs-2">

		<?php 
		$query = "SELECT termId, termName, effectiveFrom, validTo, termsFile FROM #__structured_terms WHERE cmpId = $usr_id";
		$database->setQuery( $query );
		$rows = $database->loadObjectList(); 
		?>
		
		<form action="index.php?option=com_users&task=suppliersendterms.sendtermstomember" method="post" name="share" id="share">
		
				<legend><b>View Terms</b></legend>
				<br>

				<input type="hidden" name="url" id="url" value="<?php echo $url; ?>" />
				<input type="hidden" id="user_id" name="user_id" value="<?php echo $usr_id;?>" />
				<div class="loading-div" style="display:none;"><img src="<?php echo JURI::root();?>/images/gif-load.gif"></div>
				<div id="results"><!-- content will be loaded here --></div>
				
			<br>
			<!--<fieldset>
				<?php
				$query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
				$database->setQuery( $query );
				$member = $database->loadObjectList();
				?>
				<legend><b>Send Terms to Members</b></legend>
				<table width="100%" border="0" class="send-members">
					<tr>
						<td valign="top">
							<select name="Box1" id="Box1" multiple="multiple" size="6">
								<?php foreach($member as $members){
									echo "<option value=\"$members->link_id\">$members->link_name</option>";
								} ?>
							</select>
						</td>
						<td valign="top" style="vertical-align: middle;">
							<p><button name="MoveRight" type="button" onclick="FM.moveSelections(this.form.Box1, this.form.Box2);">&gt;</button></p>
							<p><button name="MoveLeft" type="button" onclick="FM.moveSelections(this.form.Box2, this.form.Box1); FM.sortOptions(this.form.Box1); FM.selectAllOptions(this.form.Box2);">&lt;</button></p>
						</td>
						<td style="vertical-align: middle;">
							<select name="Box2[]" id="Box2" multiple="multiple" size=6>
							</select>
						</td>
						<td align="right"><input type="submit" value="Send"></td>
					</tr>
				</table>
			</fieldset>-->
		
	</div>
</div>
</div><!--supplier_send_terms_print end here-->

<script>
$ = jQuery;
function validate(){
	var valid = true;
	$('#supplier_create_edit_terms input').each(function(){
		var isRequired = $(this).data('required');
		if(isRequired){
			if($(this).val() === '' || $(this).val == null){
				$(this).css({'border':'1px solid #b94a48','box-shadow': '2px 2px 2px rgba(255, 0, 0, 0.5)'});
				valid = false;
			} 
			else {
				$(this).css({'border':'1px #e9e9e9 solid','box-shadow': '2px 2px 2px rgba(0, 70, 0, 0.5)'});
				valid = true;
			}
		} 
	});
	return valid;
}
$(document).on('click', '#save', function(){
	if(validate()){
		$("#supplier_create_edit_terms").submit();
	}
});
</script>

<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
} ?>


<script type="text/javascript" src="<?php echo JURI::base();?>media/editors/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "#terms_details_editor",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
	height : "300px",
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});

<?php echo 'var siteURL="'.JURI::root().'"';?>
//<![CDATA[
jQuery(document).ready(function(){
	jQuery("#terms_id").change(function () {
		var existing_terms=jQuery("#terms_id option:selected").val();

		
		jQuery.ajax({
			url:siteURL+'index.php?option=com_users&task=suppliersendterms.getterms_onchange_existingterms',
			type: 'POST',
			//data:formData.serialize(),
			data:{ 
				existing_terms:existing_terms,
				user_id: jQuery("#user_id").val(),
			},
			//async: false,
			success: function (data) {
				var parsedJson = jQuery.parseJSON(data);
				jQuery("#termName").val(parsedJson.termName);
				jQuery("#company_address").html(parsedJson.address);
				jQuery("#company_phone").val(parsedJson.company_phone);
				jQuery("#company_fax").val(parsedJson.company_fax);
				jQuery("#company_email").val(parsedJson.company_email);
				jQuery("#contact_name").val(parsedJson.contact_name);
				jQuery("#contact_position").val(parsedJson.contact_position);
				jQuery("#contact_mobile").val(parsedJson.contact_mobile);
				jQuery("#contact_phone").val(parsedJson.contact_phone);
				jQuery("#contact_fax").val(parsedJson.contact_fax);
				jQuery("#contact_email").val(parsedJson.contact_email);
				jQuery("#effective_from").val(parsedJson.effective_from);
				jQuery("#valid_to").val(parsedJson.valid_to);
				jQuery(tinymce.get('terms_details_editor').getBody()).html(parsedJson.terms_details);
			},
		});

	return false;
	});  
});
//]]>
</script>
