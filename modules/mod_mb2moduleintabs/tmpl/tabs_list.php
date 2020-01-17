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
<ul class="mb2moduleintabs-tabs-list mb2moduleintabs-clr">
	<?php
   	$i=0;
    foreach ($items as $key=>$item)
	{		
		if ($item[0] || $item[2])
		{
			$i++;
					                
   			// Add active class			
       		$lcls='';	
			if (is_numeric($params->get('mb2_item_active', '')))
            {
       			if ($params->get('mb2_item_active', '') == ($key+1))
  			{
   				$lcls .= ' active';	
      		}				
      	}
     	else
 		{
			if ($key == 0)
      		{
          		$lcls .= ' active';
      		}				
		}				
				
		// Add first and last class
		$lcls .= ($key == 0) ? ' first' : '';
		$lcls .= (!$items[$i][0]) ? ' last' : '';
				
                
		?>	
    		<li class="mb2moduleintabs-tabs-list-item">
         		<a class="mb2moduleintabs-tabs-list-item-link<?php echo $lcls; ?>" href="#<?php echo $uniqid . '_' . $key; ?>">
        		 	<?php echo $item[1]; ?>
				</a>
      		</li>
        <?php
		}// End if $item[0]				
	} // End foreach
    
	?>
</ul>