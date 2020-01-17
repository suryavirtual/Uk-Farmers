<?php
/**
* @version 1.0
* @package SalesHistory
* @copyright (C) 2008 Matt Hooker
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
?>
<script src="<?php echo JURI::root();?>/assets/js/script.js"></script>
<?php
$database = JFactory::getDbo();
$user = JFactory::getUser();
$url = JURI::current();
$usr_id = (int)$user->get('id','0');

if($usr_id!=""){
	$queryCmp = "SELECT * from jos_mt_links where user_id='".$usr_id."' and link_published='1'";
	$database->setQuery( $queryCmp );
	$rows = $database->loadObject();
	$cmpId = $rows->link_id;
	$addedBy = $user->name;
	if(!empty($_REQUEST['pid'])){
		$pid = $_REQUEST['pid'];
		$action = "index.php?option=com_users&task=supplierproducts.editproducts";
		$queryEdit = "select * from #__mt_links_products where pID='$pid'";
		$database->setQuery($queryEdit);
		$prdDtls = $database->loadObject();
		$name = $prdDtls->pName;
		$sku = $prdDtls->pSKU;
		$desc = $prdDtls->pDesc;
		$doc = $prdDtls->pDocs;
		$docDesc = $prdDtls->docDesc;
		$prdImg = explode(",",$prdDtls->pImage);
	} else {
		$pid = '';
		$action = "index.php?option=com_users&task=supplierproducts.addproducts";
		$name = '';
		$sku = '';
		$desc = '';
		$docDesc = '';
	}
		
	$querySelect = "select * from #__mt_links_products where status='1' order by createdOn desc";
	$database->setQuery($querySelect);
	$productsDtl = $database->loadObjectList();
?>
<div id="supplierProducts">
	<div id="supplier_products_list">
		<table width="100%" border="0" class="supplier_products_list">
		<tr>
			<th>S. No</th>
			<th>Product Name</th>
			<th>Product Images</th>
			<th>Product Docs</th>
			<th>Action</th>
		</tr>
		<?php 
		if(count($productsDtl) > 0){
			$i=1;
			foreach($productsDtl as $productsDtls){ 
			$imgs = explode(",",$productsDtls->pImage);//echo "<pre>"; print_r($productsDtls); echo "</pre>"; ?>
			<tr>
				<td><?php echo $i; ?></td>
				<td><a href="<?php echo JURI::root(); ?>product-details?did=<?php echo $productsDtls->pId ?>"><?php echo $productsDtls->pName; ?></a></td>
				<td>
					<?php if(!empty($imgs[0])){ 
						echo '<div class="prod-img"><img src="'.JURI::root().'/media/com_mtree/images/products/'.$imgs[0].'" width="100" height="100" /></div>';
					} else {
						echo '<div class="prod-img">--</div>';
					}?>
				</td>
				<td>
					<?php if(!empty($productsDtls->pDocs)){ ?>
						<div class="prod-doc"><a href="<?php echo JURI::root() ?>/media/com_mtree/images/products/docs/<?php echo $productsDtls->pDocs; ?>">Download</a></div>
					<?php } else { ?>
						<div class="prod-doc">--</div>
					<?php } ?>
				</td>
				<td style="text-align:center;">
					<a href="<?php echo $url."?pid=".$productsDtls->pId; ?>">Edit</a> | 
					<a href="<?php echo $url."?pid=".$productsDtls->pId; ?>&task=supplierproducts.deleteproducts">Delete</a></td>
			</tr>
			<?php $i++;
			}
		} else {
			echo '<tr><td colspan="5">Product not Available</td></tr>';
		} ?>
		</table>
	</div>
	<div id="supplier_products_forms">
		<div class="heading">Add Products</div>
		<form name="supplier_products" id="supplier_products" method="post" class="form-validate" enctype="multipart/form-data" action="<?php echo $action; ?>">
		<input type="hidden" name="uID" id="uID" value="<?php echo $usr_id; ?>">
		<input type="hidden" name="cmpId" id="cmpId" value="<?php echo $cmpId; ?>">
		<input type="hidden" name="addedBy" id="addedBy" value="<?php echo $addedBy; ?>">
		<input type="hidden" name="pid" id="pid" value="<?php echo $pid; ?>">
		<input type="hidden" name="url" id="url" value="<?php echo $url; ?>">
		<table width="100%" border="0" class="supplier_products_add_edit" style="border:none;">
			<tr>
				<th width="30%"><label for="product_name" class="labcolo" title="About Us" aria-invalid="true">Product Name: </label></th>
				<td><input type="text" name="product_name" id="product_name" value="<?php echo $name; ?>" class="" size="30"></td>
			<tr>
			<tr>
				<th><label for="product_sku" class="labcolo" title="About Us" aria-invalid="true">Product SKU: </label></th>
				<td><input type="text" name="product_sku" id="product_sku" value="<?php echo $sku; ?>" class="" size="30"></td>
			<tr>
			<tr>
				<th><label for="product_image" class="labcolo" title="Upload Product Images">Product Images: </label></th>
				<td>
					<div id="filediv"><input name="product_image[]" type="file" id="product_image"/></div>
                    <input type="button" id="add_more" class="add_more" value="Add More"/>
				</td>
			<tr>
			<tr>
				<th><label for="product_docs" class="labcolo" title="Upload Product Docs">Product Docs: </label></th>
				<td>
					<input type="file" name="product_docs" id="product_docs" size="30">
					
				</td>
			<tr>
			<tr>
				<th><label for="product_docs" class="labcolo" title="Upload Product Docs">Doc Description: </label></th>
				<td>
					<textarea name="docDesc" id="docDesc" style="width:300px; height:100px;"><?php echo $docDesc; ?></textarea>
				</td>
			<tr>
			<tr>
				<th colspan="2"><label for="product_docs" class="labcolo" title="Upload Product Docs">Product Description: </label></th>
			<tr>
			<tr>
				<td colspan="2"><textarea name="product_desc" cols="" rows=""  id="product_desc"><?php echo $desc; ?></textarea></td>
			<tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="product_save" id="product_save" value="Save" class="btn btn-medium main-bg validate" />
					<br><div id="product_success" style="  width: 70%;margin: auto;color: green;"></div>
				</td>
			<tr>
		</table>
		</form>
	</div>
</div>
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
} ?>
<script type="text/javascript" src="<?php echo JURI::base();?>media/editors/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
tinymce.init({
    selector: "#product_desc",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
	height : "300px",
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});
</script>