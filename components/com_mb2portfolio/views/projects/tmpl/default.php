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


JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');	
$i=0;


// Define portfolio container class
$container_cls = '';
$container_data = '';
$container_cls .= ' no-carousel';
$container_cls .= ' filter-'. $this->params->get('filter', 'none');
$this->params->get('filter', 'none') == 'isotope' ? $container_data = ' data-mode="' . $this->params->get('isotope_mode', 'fitRows') . '"' : '';

?>
<div class="mb2-portfolio-container<?php echo $container_cls; ?>">	
    <?php	
	
	if(count($this->items)>0){	
		
		
		// Get portfolio filter
		if($this->params->get('filter', 'none') != 'none'){
			echo $this->loadTemplate("filter");		
		}
			
		?>	
		<div class="mb2-portfolio-projects"<?php echo $container_data; ?>>
			<?php	      	
				
				foreach ($this->items as $key => &$item)
				{									
					$i++;		
					
					// Get item class
					$item_cls = Mb2portfolioHelper::item_cls($item, $this->params, array('mode'=>'projects'));										
								
					?>
					<div class="client-testimonial-page <?php echo $item_cls; ?>"> 					
					<?php 
					
						$this->item = &$item;
						echo $this->loadTemplate("item");			
					
					?>	
					</div><!-- end .mb2-portfolio-item -->
                    <?php
						
					// Get projects separator
					if($this->params->get('cols', 4) == $i && $this->params->get('filter', 'none') != 'isotope')
					{
						echo '<div class="mb2-portfolio-separator"></div>';	
						$i=0;
					}								
							
				}// End foreach
				
				?>  
		</div><!-- end .mb2-portfolio-projects -->	
		<?php
		if($this->params->get('pagination', 1) == 1)
		{ ?>			
			<div class="mb2-portfolio-pagination pagination"> 
				<?php echo $this->pagination->getPagesLinks(); ?>
			</div><!-- end mb2-portfolio-pagination -->
		<?php		
		}
	
	} // End if($count>0)
	else{		
		echo '<p>' . JText::_('COM_MB2PORTFOLIO_NOT_FOUND') . '</p>';		
	}
	
	?>
</div><!-- end .mb2-portfolio-container -->