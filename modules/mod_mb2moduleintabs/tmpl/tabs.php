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


// $item[0]: module id
// $item[1]: tab title

?>
<div class="mb2moduleintabs-tabs tabs-<?php echo $params->get('mb2_tabs_pos', 'top');?>">
<?php ($params->get('mb2_tabs_pos', 'top') != 'bottom') ? require JModuleHelper::getLayoutPath('mod_mb2moduleintabs', 'tabs_list') : ''; ?>	
	<div class="mb2moduleintabs-tabs-content">
		<?php
        $j=0;
        foreach ($items as $key=>$item)
        {	
            if ($item[0] || $item[2])
            {    
			
				$j++;
				
				// Add first and last class
			 	$pcls = ($key == 0) ? ' first' : '';
				$pcls .= (!$items[$j][0]) ? ' last' : '';			
			            
                ?>
                <div class="mb2moduleintabs-tab-content<?php echo $pcls; ?> mb2moduleintabs-clr" id="<?php echo $uniqid . '_' . $key; ?>">
                    <?php 
					$attrs = array('modid'=>$item[0], 'thismodid'=>$module->id, 'artid'=>$item[2], 'arth'=>'h4');
					echo modMb2moduleintabsHelper::get_mod_item($params, $attrs); 
					?>
                </div>	
                <?php
                
            } // End if $item[0]
        } // End foreach
        
        ?>	
    </div>
    <?php ($params->get('mb2_tabs_pos', 'top') == 'bottom') ? require JModuleHelper::getLayoutPath('mod_mb2moduleintabs', 'tabs_list') : ''; ?>
</div>