<?php
/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/



// no direct access
defined('_JEXEC') or die;


?>
<div class="mb2moduleintabs-accordion" data-show="<?php echo $params->get('mb2_item_active', 1) !='' ? $params->get('mb2_item_active', 1) : '';?>">
	<?php
	$i=0;
	foreach ($items as $key=>$item)
	{
		if ($item[0] || $item[2])
		{	
		
		$i++;		
		
		// Add current class	
		$pcls = ($params->get('mb2_item_active', '') && ($params->get('mb2_item_active', '') == ($key+1))) ? ' iscurrent' : '';
		
		// Add first and last class
		$pcls .= ($key==0) ? ' first' : '';
		$pcls .= (!$items[$i][0]) ? ' last' : '';
			
		?>
       		<div class="mb2moduleintabs-panel-group<?php echo $pcls; ?>">
        		<h4 class="mb2moduleintabs-panel-heading"><?php echo $item[1]; ?></h4>
         		<div class="mb2moduleintabs-panel-content mb2moduleintabs-clr">
          			<?php 
					$attrs = array('modid'=>$item[0], 'thismodid'=>$module->id, 'artid'=>$item[2], 'arth'=>'h5');
					echo modMb2moduleintabsHelper::get_mod_item($params, $attrs); 
					?>
     			</div>
           	</div>
		<?php
		} // If $item[0]
	} // End foreach
	?>
</div>