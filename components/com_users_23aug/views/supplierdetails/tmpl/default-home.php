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
//echo "<pre>";
//print_r($user);
$usr_id = $user->id;
if($usr_id!=""){
	 $queryTab = "SELECT ct.*, mt.link_desc, mt.link_name,mt.link_id, mi.filename FROM #__users as u, #__mt_links as mt, jos_mt_images as mi,  #__company_tab as ct where u.id='$usr_id' and u.comp_list=mt.link_id and mt.link_id=ct.comp_id and mt.link_id=mi.link_id and mi.ordering='1'";
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
	//print_r($tabs);
	/* new code added here for fetching images from custom folder structure */
	    $linkids=$tabs['0']->link_id;
	    $db = JFactory::getDbo();
	    $uQuery = "select folder_name from #__folder where link_id='$linkids'";
		$db->setQuery($uQuery);
        $resfolder = $db->loadObject();
        $foldername=$resfolder->folder_name;
        $usertypes="Supplier";
              //$foldername=strtolower($tabs['0']->link_name);
              
              /*$foldername = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '',$foldername);
               if (preg_match('/\s/',$foldername) )
			   {
			   	$foldername = str_replace(' ','_', $foldername);
			   }
			   else
			   {
			   	$foldername = $foldername;
			   }*/
			   if($usertypes=="Supplier")
					{
                    $usertypes="suppliers";
					}
					if($usertypes=="Member")
					{
					$usertypes="members";	
					}
					
 

      $img=JURI::root()."/uf_data/".$usertypes."/".$foldername."/logos/".$tabs['0']->filename; 

			/* code ends here */  ?>
	<div>
	<!--<img id="mainimage" src="<?php echo JURI::root(); ?>/media/com_mtree/images/listings/m/<?php echo $tabs['0']->filename; ?>" alt="" title="<?php echo $tabs['0']->link_name; ?>">-->
	<img id="mainimage" src="<?php echo $img ?>" alt="" title="<?php echo $tabs['0']->link_name; ?>">

	</div>
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