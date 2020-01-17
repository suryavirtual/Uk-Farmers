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
		$('#supplier_list').fadeIn(1500);
		$('.search-background').fadeOut(200);
	};

	$('#search').keyup(function(e) {
		if(e.keyCode == 13) {
			showLoader();
			var str=$("#search").val();
		var newstr=str.replace(/\s/g, '_');
			$('#supplier_list').fadeIn(1500);
			$("#supplier_list").load("search.php?val=" + newstr, hideLoader());
		}
	});
	
	$(".searchBtn").click(function(){
		//show the loading bar
		var str=$("#search").val();
		var newstr=str.replace(/\s/g, '_');
		showLoader();
		$('#supplier_list').fadeIn(1500);
		console.log($("#search").val());
		$("#supplier_list").load("search.php?val=" + newstr, hideLoader());

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
	
	<div id="supplier_list">
		
	</div>
</div>
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
} ?>
