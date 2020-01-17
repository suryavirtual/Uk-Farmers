<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die; 

$db = JFactory::getDBO();
$user = JFactory::getUser();
$usr_id = $user->id;
$session = JFactory::getSession();
$company_id=$session->get('comp_id');
if(!empty($company_id))
{
	$comps_id=$company_id;
}
else
{
	$comps_id=$user->comp_list;
}

if($usr_id!=""){
	$queryTab = "SELECT ct.*,mt.link_desc,mt.link_id,mt.link_name,mi.filename FROM #__company_tab as ct,#__mt_links as mt,jos_mt_images as mi  where ct.comp_id='$comps_id'  and mt.link_id=ct.comp_id  and mt.link_id=mi.link_id and mi.ordering='1'";
	$db->setQuery( $queryTab );
	$tabs = $db->loadObjectList();
?> 
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>

<script>
jQuery(document).ready(function(){
	jQuery("#supplier_home").tabs();
})
</script>

<div class="page-header">
	
	<!--<h1><?php //echo $this->escape($this->params->get('page_heading')); ?></h1>-->
</div>

<div id="supplier_home">
<ul>
	<li><a href="#tabs-1">Home</a></li>
	<?php 
	$m=2;
	foreach($tabs as $tabList){ ?>
		<li><a href="#tabs-<?php echo $m; ?>"><?php echo $tabList->label; ?></a></li>
	<?php $m++; 
	} ?>
</ul>

<div id="tabs-1" style="padding-left:5px;">
<?php 
	/* new code added here for fetching images from custom folder structure */
	    $linkids=$tabs['0']->link_id;
	    $db = JFactory::getDbo();
	    $uQuery = "select folder_name from #__folder where link_id='$linkids'";
		$db->setQuery($uQuery);
        $resfolder = $db->loadObject();
        $foldername=$resfolder->folder_name;
        $usertypes="Supplier";

			   if($usertypes=="Supplier")
					{
                    $usertypes="suppliers";
					}
					if($usertypes=="Member")
					{
					$usertypes="members";	
					}
					
 

     $queryimg = "SELECT mi.filename FROM #__mt_images as mi where mi.link_id='$comps_id'";
	$db->setQuery($queryimg);
	$img = $db->loadObjectList();
	//echo "<pre>";
       //print_r($img);
	$countimages=count($img);
	if($countimages>1)
	{
		?>
		<ul class="thumbnails">
		<?php 
		foreach ($img as $images_name) 
		{
		 $image=JURI::root()."/uf_data/".$usertypes."/".$foldername."/logos/".$images_name->filename;
		?>

 
 	<li class="uk_pro_list">
	<!--<img id="mainimage" src="<?php echo JURI::root(); ?>/media/com_mtree/images/listings/m/<?php echo $tabs['0']->filename; ?>" alt="" title="<?php echo $tabs['0']->link_name; ?>">-->
	<img id="mainimage" src="<?php echo $image; ?>" alt="" title="<?php //echo $tabs['0']->link_name; ?>">

	</li>
	
	
			
		<?php } ?>
		</ul>
		<?php

	}
	else
	{ 
		$image=JURI::root()."/uf_data/".$usertypes."/".$foldername."/logos/".$img['0']->filename;
		?>
<div>
	<!--<img id="mainimage" src="<?php echo JURI::root(); ?>/media/com_mtree/images/listings/m/<?php echo $tabs['0']->filename; ?>" alt="" title="<?php echo $tabs['0']->link_name; ?>">-->
	<img id="mainimage" src="<?php echo $image; ?>" alt="" title="<?php echo $tabs['0']->link_name; ?>">

	</div>
	<?php } ?>
	<p><?php echo $tabs['0']->link_desc;
	 ?>
</div>

<?php 
$l=2;
foreach($tabs as $tabdtls){ ?>
	<div id="tabs-<?php echo $l; ?>" style="padding-left:5px;"><?php echo $tabdtls->description; ?></div>
<?php $l++; 
} ?>

</div>
<?php } else {
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
}
