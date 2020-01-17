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
$url = JURI::current();
$usr_id = (int)$user->get('id','0');

if($usr_id!=""){
	$querySelect = "select * from #__mt_links_products where status='1' order by createdOn desc";
	$database->setQuery($querySelect);
	$productsDtl = $database->loadObjectList();
?>
<script language="javascript">
$=jQuery;
$(document).ready(function(){
	//show loading bar
	function showLoader(){
		$('.search-background').fadeIn(200);
	}

	//hide loading bar
	function hideLoader(){
		$('#supplier_products_list').fadeIn(1500);
		$('.search-background').fadeOut(200);
	};

	$('#search').keyup(function(e) {
		if(e.keyCode == 13) {
			showLoader();
			$('#supplier_products_list').fadeIn(1500);
			$("#supplier_products_list").load("search.php?val=" + $("#search").val(), hideLoader());
		}
	});
	
	$(".searchBtn").click(function(){
		//show the loading bar
		showLoader();
		$('#supplier_products_list').fadeIn(1500);
		$("#supplier_products_list").load("search.php?val=" + $("#search").val(), hideLoader());
	});

});
</script>
<div id="supplierProducts">
	<?php if ($this->params->get('show_page_heading')) : ?>
		<div class="page-header">
			<h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
		</div>
	<?php endif; ?>
		
	<div class="textBox">
		<input type="text" value="" maxlength="100" name="searchBox" id="search">
		<div class="searchBtn">&nbsp;</div>
	</div>	
	<br clear="all" />
	
	<div id="supplier_products_list">
		<?php foreach($productsDtl as $productsDtls){
			$imgs = explode(",",$productsDtls->pImage);?>
		<div class="memberProductList">
			<div class="product-list-summary">
				<div class="list-title">
					<h3><a href="<?php echo JURI::root(); ?>product-details?did=<?php echo $productsDtls->pId ?>"><span itemprop="name"><?php echo $productsDtls->pName; ?></span></a></h3>
				</div>
				<div class="prod-img">
					<img src="<?php echo JURI::root(); ?>/media/com_mtree/images/products/<?php echo $imgs[0]; ?>" width="100" height="100" class="image-left" alt="" />
				</div>
				<p><?php echo substr($productsDtls->pDesc,0,150); ?> <b>...</b></p>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login!!!', 'error'));
} ?>
