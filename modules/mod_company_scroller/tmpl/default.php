<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_breadcrumbs
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
?>
<!--<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/demo.css" type="text/css" media="screen" />-->
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/flexslider.css" type="text/css" media="screen" />

<div id="container" class="cf">
<h2 style="display:none;">&nbsp;</h2>

<div id="main" role="main">
	<div class="flexslider carousel">
		<ul class="slides">
			<?php foreach($list as $lists) {?>
			<?php 
			$linkids=$lists->link_id;
          $db = JFactory::getDbo();  
         // $foldername = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '',$foldername);
		$uQuery = "select folder_name from #__folder where link_id='$linkids'";
		$db->setQuery($uQuery);
        $resfolder = $db->loadObject();
        $foldername=$resfolder->folder_name;
                   // $foldername = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '',$foldername);
              /* if (preg_match('/\s/',$foldername) )
			   {
			   	$foldername = str_replace(' ','_', $foldername);
			   }
			   else
			   {
			   	$foldername = $foldername;
			   }
			   */
			   ?>
				<li>
				<?php//echo $foldername;?>
					<!--<img src="<?php //echo JURI::root();?>/media/com_mtree/images/listings/m/<?php //echo $lists->filename; ?>" />-->
					<img src="<?php echo JURI::root();?>/uf_data/members/<?php echo $foldername;?>/logos/<?php echo $lists->filename; ?>" />
				</li>
			<?php } ?>
		</ul>
	</div>
	
</div>
</div>

<!-- jQuery -->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->
  
<!-- FlexSlider -->
<script defer src="<?php echo JURI::root();?>/assets/jquery.flexslider.js"></script>

<script type="text/javascript">
jQuery(window).load(function(){
	jQuery('.flexslider').flexslider({
		animation: "slide",
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 5,
        minItems: 2,
        maxItems: 4,
        start: function(slider){
			jQuery('body').removeClass('loading');
		}
	});
});
</script>