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

require_once JPATH_SITE . '/components/com_mb2portfolio/models/projects.php';

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');	

$container_cls = ' layout-'. $this->params->get('related_item_layout', 'media-desc-below');

?>

<div class="mb2-portfolio-container<?php echo $container_cls; ?> single-project">
	<?php
    if($this->item){    
    
        $layout = $this->item->layout;        
        
        ?>	
        <div class="mb2-portfolio-single-item clearfix <?php echo $layout; ?>">
            <?php 
				
				// Get item navigation
				if($this->params->get('item_navigation', 1) == 1)
				{
					echo $this->loadTemplate("navigation");					
				}
					
                echo $this->loadTemplate("media");       
                echo $this->loadTemplate("details");			 
            ?>
        </div><!-- end .mb2-portfolio-single-item -->
            
        <?php 
        
        
        if($this->params->get('related_projects', 1) == 1){
            echo $this->loadTemplate("related");	
        }
    
        
    }else{ 
        echo JText::_('COM_MB2PORTFOLIO_NOT_FOUND');
    }
    
    ?>
</div><!-- end .mb2-portfolio-container -->