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
JHtml::_('behavior.formvalidation');

$database = JFactory::getDbo();
$dtl = '';
if(!empty($_REQUEST['pId'])){
	$pid = $_REQUEST['pId'];
	$querySelect = "select * from #__mt_links_products where pId='$pid'";
	$database->setQuery($querySelect);
	$productsDtl = $database->loadObject();
	if(!empty($productsDtl->pDesc)){
		$dtl = $productsDtl->pDesc;
	} else {
		$dtl='';
	}
	
	if(!empty($productsDtl->docDesc)){
		$docDesc = $productsDtl->docDesc;
	} else {
		$docDesc='';
	}
}

$editor = JFactory::getEditor();
$html = $editor->display('pDesc', $dtl, '100%', '400', '70', '15',false); 

$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$addedBy = $user->name;
?>
<script src="<?php echo JURI::root();?>/assets/js/script.js"></script>

<form action="<?php echo JRoute::_('index.php?option=com_smadmin&view=product&layout=edit&pId=' . (int) $this->item->pId); ?>"
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
							<div class="control-label">Product Images</div>
							<div class="controls">
								<div id="filediv"><input name="product_image[]" type="file" id="product_image"/></div>
								<input type="button" id="add_more" class="add_more" value="Add More"/>
							</div>
						</div>
						<div class="control-group">
							<div class="control-label">Product Docs</div>
							<div class="controls"><input type="file" name="pDocs" id="pDocs" size="30"></div>
						</div>
						<div class="control-group">
							<div class="control-label">Document Description</div>
							<div class="controls"><textarea name="docDesc" id="docDesc" style="width:300px; height:100px;"><?php echo $docDesc; ?></textarea></div>
						</div>
						<div class="control-group">
							<input type="hidden" name="uId" id="uId" value="<?php echo $usr_id; ?>">
							<input type="hidden" name="createdBy" id="createdBy" value="<?php echo $addedBy; ?>">
							<div class="control-label">Product Description</div>
							<div class="controls"><?php echo $html; ?></div>
						</div>
					</div>
				</div>
			</fieldset>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="product.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
