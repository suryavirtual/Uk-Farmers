
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
$url = JURI::current();
$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');

if(!empty($doc_id))
{
$query = "SELECT * FROM #__document_type WHERE id in ($doc_id)";
$database->setQuery( $query );
$existing_doc = $database->loadObjectList();
}
else
{
$query = "SELECT * FROM #__document_type ";
$database->setQuery( $query );
$existing_doc = $database->loadObjectList();

}
?>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/cmxform.css">
<script src="<?php echo JURI::root();?>/assets/js/multiple-select-functions.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/jquery.validate.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/custom.js"></script>

<?php if(!empty($usr_id)){ ?>
<div id="supplier_email_member">
	<form action="index.php?option=com_users&task=sendemails.sendEmails" enctype="multipart/form-data" method="post" name="sendEmail" id="sendEmail" class="cmxform">
		<fieldset>
			<input type="hidden" name="url" id="url" value="<?php echo $url; ?>" />
			<input type="hidden" id="user_id" name="user_id" value="<?php echo $usr_id;?>" />
			<input type="hidden" id="cmp_list" name="cmp_list" value="<?php echo $user->comp_list;?>" />
			<input type="hidden" id="user_email" name="user_email" value="<?php echo $user->email;?>" />
			<?php $query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
			$database->setQuery( $query );
			$member = $database->loadObjectList(); ?>
			
			<legend><b>Email Members</b></legend>
			<table width="100%" border="0" class="send-members">
			<tr>
				<td valign="top">
					<h3 class="em_h3">Members</h3>
					<select name="Box1" id="Box1" multiple="multiple" size="10">
						<?php foreach($member as $members){
							echo "<option value=\"$members->link_id\">$members->link_name</option>";
						} ?>
					</select>
					<h3 class="em_h3">Subject :</h3>
					<input type="text" name="subject" id="subject" value="" size="38">
				</td>
				<td valign="top" style="vertical-align: middle;">
					<p><button name="MoveRight" type="button" onclick="FM.moveSelections(this.form.Box1, this.form.Box2);">&gt;</button></p>
					<p><button name="MoveLeft" type="button" onclick="FM.moveSelections(this.form.Box2, this.form.Box1); FM.sortOptions(this.form.Box1); FM.selectAllOptions(this.form.Box2);">&lt;</button></p>
				</td>
				<td style="vertical-align: top;">
					<h3 class="em_h3">Selected Members</h3>
					<select name="member_id[]" id="Box2" multiple="multiple" size="10">
					</select>
				</td>
				<td >
					<h3 class="em_h3">Document Type</h3>
					<select name="doc_type[]" multiple="multiple" size="10" id="Box3">
						<?php foreach($existing_doc as $existing_docs){ ?>
								<option value="<?php echo $existing_docs->id; ?>"><?php echo $existing_docs->doc_name; ?></option>
						<?php } ?>
					</select>
				</td>
			</tr>
			<tr>
			<td colspan="4">
			<div id="filediv1"><input name="supplier_files[]" type="file" id="supplier_files"/></div>
			</td></tr>
			<tr>
				<td colspan="4">
					<h3 class="em_h3">Email Body :</h3>
					<!--<textarea name="body" id="body" rows="13" cols="49"></textarea>-->
					<textarea name="body" id="body" rows="13" cols="49" class="ckeditor"></textarea>
				</td>
			</tr>
			<tr><td colspan="4"><input type="submit" class="email_mem_submit" name="send" value="Send Mail"></td></tr>
			</table>
		</fieldset>
	</form>
</div><!--supplier_send_terms_print end here-->

<script type="text/javascript" src="<?php echo JURI::base();?>media/editors/tinymce/tinymce.min.js"></script>
<script type="text/javascript">

   CKEDITOR.replace( 'body',
{
  filebrowserBrowseUrl : '/uk_farmers/ckfinder/ckfinder.html',
  filebrowserImageBrowseUrl : '/uk_farmers/ckfinder/ckfinder.html?type=Images',
  filebrowserFlashBrowseUrl : '/uk_farmers/ckfinder/ckfinder.html?type=Flash',
  filebrowserUploadUrl : '/uk_farmers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
  filebrowserImageUploadUrl : '/uk_farmers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
  filebrowserFlashUploadUrl : '/uk_farmers/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
  filebrowserWindowWidth : '1000',
  filebrowserWindowHeight : '700'
});
  
</script>

<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login!!!', 'error'));
} ?>