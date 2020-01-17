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

$model = $this->getModel();

if($usr_id!=""){
	$dtls = $model->getTabDetails();
	
?> 
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>


<script>
jQuery(document).ready(function(){
	jQuery("#supplier_home").tabs();
	
	//jQuery('ul.ui-tabs-nav').find('li').removeClass('ui-state-active');
	jQuery('div#supplier_home').find('#tabs-1').css('display','none'); 
	
	jQuery(document).on('click', 'ul.ui-tabs-nav li:first', function(){
		jQuery(this).addClass('ui-state-active');
		jQuery('div#supplier_home').find('#tabs-1').css('display','block'); 
		jQuery('div#supplier_home').find('#tabs-4').css('display','none'); 
	});

	jQuery(document).on('click','#ui-id-2',function(){
		jQuery('div#supplier_home').find('#tabs-4').css('display','none');
	});
})
</script>

<div class="page-header">
	
	<!--<h1><?php //echo $this->escape($this->params->get('page_heading')); ?></h1>-->
</div>

<div id="supplier_home">
<ul>
	<li><a href="#tabs-1">Product Images</a></li>
	<li><a href="#tabs-2">Price Guide</a></li>
	<li><a href="#tabs-3">Catalogue</a></li>
</ul>

<div id="tabs-1" style="padding-left:5px; border:1px solid #aeaeae;">
	<p><?php echo $dtls->pImages; ?></p>
</div>
<div id="tabs-2" style="padding-left:5px; border:1px solid #aeaeae;">
	<p><?php echo $dtls->pGuid; ?></p>
</div>
<div id="tabs-3" style="padding-left:5px; border:1px solid #aeaeae;">
	<p><?php echo $dtls->catalogues;?></p>
</div>
<div id="tabs-4">
<div><p><?php echo $dtls->brandDtl;?></p>
</div>
<?php 
$l=2;
foreach($tabs as $tabdtls){ ?>
	<div id="tabs-<?php echo $l; ?>" style="padding-left:5px;"><?php echo $tabdtls->description; ?></div>
<?php $l++; 
} ?>

</div>
</div>

<?php } else {
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
}