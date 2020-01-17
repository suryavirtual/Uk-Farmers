<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// no direct access
defined('_JEXEC') or die;


?>
<div class="mb2-portfolio-single-item-deatils">
	<div class="mb2-portfolio-single-item-deatils-inner">            	
		<div class="mb2-portfolio-heading">        	
        	<h2 class="title mb2-portfolio-title"><?php echo $this->item->title; ?></h2>
        </div>
      	<?php 
		//get meta
		echo $this->loadTemplate("meta");
		
		if($this->item->intro_text !='')
		{ 
		?>
      		<div class="mb2-portfolio-full-text">
         		<?php echo JHTML::_('content.prepare', $this->item->intro_text); ?>
        	</div>
       	<?php 
		}	
				
		// Get extra fields		
		echo $this->loadTemplate("extra_fields");
		
		// Get links		
		echo $this->loadTemplate("links");		
		
		// Get social shares 
		echo $this->loadTemplate("share"); 
					
		?>         
	</div><!-- end .mb2-portfolio-single-item-deatils-inner -->        	
</div><!-- end .mb2-portfolio-single-item-deatils -->
