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


<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>-->
<link type="text/css" rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/pgwslider.css"></link>
<script type="text/javascript" src="<?php echo JURI::root();?>/assets/js/pgwslider.js"></script>

<script language="javascript">
jQuery(document).ready(function() {
    jQuery('.pgwSlider').pgwSlider();
});
</script>

<?php
$document=& JFactory::getDocument();
  
$database = JFactory::getDbo();
$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');//$user->id;//220

if($usr_id!=""){
	$pid = $_REQUEST['did'];
	$queryEdit = "select * from #__mt_links_products where pID='$pid'";
	$database->setQuery($queryEdit);
	$prdDtls = $database->loadObject();
	$prdImage = explode(",",$prdDtls->pImage);
	$document->setTitle( $prdDtls->pName );
?>
<div id="product_detail">
	<div class="page-header"><h2 itemprop="name"><?php echo $prdDtls->pName; ?></h2></div>
	<!-- Image gallery -->
	<ul class="pgwSlider">
	<?php for($m=0; $m < count($prdImage); $m++){
		echo '<li><img src="'.JURI::root().'/media/com_mtree/images/products/'.$prdImage[$m].'" alt="" /></li>';				
	}
	?>
	</ul>
		
	<!-- end Image Gallery -->
	<?php echo $prdDtls->pDesc; ?>
	<?php if(!empty($prdDtls->pDocs)){ ?>
	<h3>ABOUT DOCUMENT</h3>
	<p><?php echo $prdDtls->docDesc; ?></p>
	<ol>
		<li><b>Attached Document:</b> <a href="<?php echo JURI::root() ?>/media/com_mtree/images/products/docs/<?php echo $prdDtls->pDocs; ?>" target="_blank">Document</a></li>
	</ol>
	<?php } ?>
</div>
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
} ?>