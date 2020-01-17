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
<div class="mb2-portfolio-nav clearfix">
    <ul>
		<?php 
		if($this->item->previtem)
		{		
			
			// Get prev item link
			$prevskillsarr = array(
				$this->item->previtem->skill_1, 
				$this->item->previtem->skill_2, 
				$this->item->previtem->skill_3, 
				$this->item->previtem->skill_4, 
				$this->item->previtem->skill_5
			);	
										
			$prevlink = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($this->item->previtem->id, $prevskillsarr, $language = 0));
			
			?>
		 	<li class="item-prev">
            	<a href="<?php echo $prevlink; ?>" class="mb2-portfolio-tooltip" data-placement="top" title="<?php echo $this->item->previtem->title; ?>">
                	<i class="mb2portfolio-fa mb2portfolio-fa-angle-double-left"></i> <?php echo JText::_('COM_MB2PORTFOLIO_PREV_ITEM'); ?>
                </a>
            </li>
            <?php				
		}
		       
		if($this->item->nextitem)
		{				
			
			// Get next item link
			$nextskillsarr = array(
				$this->item->nextitem->skill_1, 
				$this->item->nextitem->skill_2, 
				$this->item->nextitem->skill_3, 
				$this->item->nextitem->skill_4, 
				$this->item->nextitem->skill_5
			);	
										
			$nextlink = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($this->item->nextitem->id, $nextskillsarr, $language = 0));
			
			?>			
            <li class="item-next">
                <a href="<?php echo $nextlink; ?>" class="mb2-portfolio-tooltip" data-placement="top" title="<?php echo $this->item->nextitem->title; ?>">
                	<?php echo JText::_('COM_MB2PORTFOLIO_NEXT_ITEM'); ?> <i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i>
                </a>
            </li>
            <?php						
     	}		
        ?>
    </ul>
</div><!-- end .mb2-portfolio-single-item-nav -->