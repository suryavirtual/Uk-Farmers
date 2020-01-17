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
?>
<div class="mb2-portfolio-container skills no-carousel">
	<div class="mb2-portfolio-skills clearfix">
    	<?php 
		
		if(count($this->items)>0){
			
			foreach ($this->items as $key=>$item){					
					
				$i++;
							
							
				//get project link								
				$link = JRoute::_(Mb2portfolioHelperRoute::getProjectsRoute($item->slug, $language = 0));
				$projects_count='';
				$is_project_count='';
				
				
				?>
							
				<div class="mb2-portfolio-skill-item mb2-portfolio-col">
					<div class="mb2-portfolio-skill-item-inner clearfix">
						<?php if($item->image != '' && $this->params->get('skills_image', 1) == 1)
						{
							?>
						<div class="mb2-portfolio-skill-item-media mb2-portfolio-media">
							<div class="mb2-portfolio-skill-item-media-inner clearfix">
								<a href="<?php echo $link; ?>"><img src="<?php echo $item->image; ?>" alt="<?php echo $item->title; ?>" /></a>							
							</div><!-- end .mb2-portfolio-skill-item-media-inner -->
						</div><!-- end .mb2-portfolio-skill-item-media -->               
						<?php 
						} 
						if($this->params->get('skills_title', 1) == 1 || $this->params->get('skills_desc', 1) == 1 || $this->params->get('skills_read_more', 1) == 1) {?>        	
						<div class="mb2-portfolio-skill-item-details mb2-portfolio-details"> 
							<div class="mb2-portfolio-skill-item-details-inner clearfix">               
								<?php 
								if($this->params->get('skills_title', 1) == 1)
								{ 
								?>
                                <h2 class="title mb2-portfolio-skill-item-title">
                      				<?php
                                               
                                    if($this->params->get('skills_title_link', 1) == 1)
                                    {
                                    ?>													
                                    <a class="title-link" href="<?php echo $link ?>">
                              			<?php 
										echo JHtml::_('item.word_limit', $item->title, $this->params->get('skills_title_limit', 999), '...');
                                        echo $is_project_count;?>
                                    </a>																		
                                  	<?php 
                               		}
                                 	else
                                    {													
                                		echo JHtml::_('item.word_limit', $item->title, $this->params->get('skills_title_limit', 999), '...');
                                   	}
                            		?>
                      			</h2>	           
								<?php                       
								}										
                                            									
								if($this->params->get('skills_desc', 1) == 1)
								{
								?>							
									<div class="mb2-portfolio-skill-item-desc">
										<?php echo JHtml::_('item.word_limit', $item->description, $this->params->get('skills_desc_limit', 999), '...'); ?>                            
									</div><!-- end .mb2-portfolio-skill-item-desc -->						
								<?php 
								}
								if($this->params->get('skills_read_more', 1) == 1){ ?>												
									<p class="mb2-portfolio-skill-item-read-more">
										<a class="btn btn-default" href="<?php echo $link ?>">
											<?php echo JTEXT::_('COM_MB2PORTFOLIO_VIEW_PROJECTS'); ?>
                                            <i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i>
										</a>
									</p>											
                         		<?php	
								}									                       
								?> 
							</div><!-- end .mb2-portfolio-skill-item-details-inner -->                   
						</div><!-- end .mb2-portfolio-skill-item-details --> 					
						<?php			   
						}	   
								    
						?> 								                     
					</div><!-- end .mb2-portfolio-skill-item-inner -->
				</div><!-- end .mb2-portfolio-skill-item -->      
				<?php 
				
				// Get projects separator
				if($this->params->get('skills_cols', 3) == $i)
				{
					echo '<div class="mb2-portfolio-separator"></div>';	
					$i=0;
				}			
							
			}//end foreach		
		
		}
		else{			
			echo '<p>'.JText::_('COM_MB2PORTFOLIO_NOT_FOUND').'</p>';	
		}		
		?>
    </div><!-- end .mb2-portfolio-skills -->
</div><!-- end .mb2-portfolio-container -->