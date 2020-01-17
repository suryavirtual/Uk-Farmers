<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
JHtml::_('behavior.formvalidation');?>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/cmxform.css">
<script src="<?php echo JURI::root();?>/assets/js/multiple-select-functions.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/script-file.js"></script>
<script>
jQuery(function() {
	//alert("this is just testings");
	jQuery("#add_files").tabs();
	});
</script>



<?php 

$database = JFactory::getDbo();

$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$addedBy = $user->name;
$dc = explode(",",$this->item->document_type);

$queryDocument = "select * from #__document_type";
$database->setQuery($queryDocument);
$res = $database->loadObjectList();

$querySupplier = "SELECT mt.* FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name";
$database->setQuery($querySupplier);
$resSupplier = $database->loadObjectList();

$editor = JFactory::getEditor();
$html = $editor->display('body', $this->item->body, '100%', '300', '70', '15',false);

$q1 = "select * from #__email_member where id='".$_REQUEST['id']."'";
$database->setQuery( $q1 );
$fileDtl = $database->loadObject();
?>
<div id="add_files">
<ul>
	<li><a href="#tabs-5">Add Emails</a></li>
	<li><a href="#tabs-6">Allocate Emails To Members</a></li>
</ul>
<!-- add form start from here--> 
<div id="tabs-5">
  <form action="<?php echo JRoute::_('index.php?option=com_smadmin&view=email&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
	<div class="form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>
			<fieldset class="adminform">
				<legend><?php echo JText::_($fieldset->label); ?></legend>
				<div class="row-fluid">
					<div class="span6">
						<?php foreach ($this->form->getFieldset($name) as $field): ?>
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endforeach; ?>
						
						<div class="control-group">
							<div class="control-label"><?php echo "Select Supplier"; ?></div>
							<div class="controls">
								<select id="supplierCmp" name="supplierCmp">
								<option value="">Select Supplier</option>
								<?php foreach($resSupplier as $resSuppliers){
										if($resSuppliers->link_id == $this->item->supplierCmpId){
											$select="selected"; 
										} else {
											$select = "";
										}?>
										<option value="<?php echo $resSuppliers->link_id; ?>" <?php echo $select; ?>><?php echo $resSuppliers->link_name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo "Document Type"; ?></div>
							<div class="controls">
								<select id="document_type" name="document_type[]" multiple="">
									<?php foreach($res as $ress){
										if(in_array($ress->id, $dc)){
											$select="selected"; 
										} else {
											$select = "";
										}?>
										<option value="<?php echo $ress->id; ?>" <?php echo $select; ?>><?php echo $ress->doc_name; ?></option>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="control-group">
				<div class="control-label">Attachment File</div>
				<div class="controls">
					<div id="filediv1"><input name="supplier_files[]" type="file" id="supplier_files"/></div>
					<?php if(!empty($fileDtl->file)){ ?>
						<a href="<?php echo JURI::root() ?>/upload/email/<?php echo $fileDtl->file; ?>" target="_blank">Download</a>
					<?php }?>
					<!--<input type="button" id="add_more" class="add_more" value="Add More"/>-->
				</div>
			</div>
						
						<div class="control-group">
							<div class="control-label"><?php echo "Email Body"; ?></div>
							<div class="controls"><?php echo $html; ?></div>
						</div>
						
					</div>
				</div>
			</fieldset>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="term.edit" />
	<?php echo JHtml::_('form.token'); ?>
   <!--</form>-->
</div>
<!-- add form ends here--> 
<!--Allocate Emails to member start from here-->
<div id="tabs-6">
<div id="supplier_email_member">
	<!--<form action="index.php?option=com_users&task=sendemails.sendEmails" enctype="multipart/form-data"  method="post" name="sendEmail" id="sendEmail" class="cmxform">-->
		<fieldset>
			
			<?php $query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
			$database->setQuery( $query );
			$member = $database->loadObjectList(); ?>
			<table border="0">
			<tr>
				<td valign="top">
					<h3 class="em_h3">Members</h3>
					<select name="Box1" id="Box1" multiple="multiple" size="10">
						<?php foreach($member as $members){
							echo "<option value=\"$members->link_id\">$members->link_name</option>";
						} ?>
					</select>
					
				</td>

				<td valign="top" style="vertical-align: middle;" class="but">
					<p><button name="MoveRight" type="button" onclick="FM.moveSelections(this.form.Box1, this.form.Box2);">&gt;</button></p>
					<p><button name="MoveLeft" type="button" onclick="FM.moveSelections(this.form.Box2, this.form.Box1); FM.sortOptions(this.form.Box1); FM.selectAllOptions(this.form.Box2);">&lt;</button></p>
				</td>
				<td style="vertical-align: top;">
					<h3 class="em_h3">Selected Members</h3>
					<select name="member_id[]" id="Box2" multiple="multiple" size="10">
					</select>
				</td>
				
			</tr>
			
			
			
			</table>
		</fieldset>
	</form>
 </div>
</div>

<!--Allocate Emails to member ends here-->
</div>


