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

$uniqid = uniqid();


// Get related projects
$related_items = $this->item->related_projects;


// Check if items are display within carousel
$c_carousel_cls = Mb2portfolioHelper::carousel_cls($related_items, $this->params, array('mode'=>'project-related', 'pos'=>' is-carousel', 'neg'=>' no-carousel'));
$carousel_cls = Mb2portfolioHelper::carousel_cls($related_items, $this->params, array('mode'=>'project-related', 'pos'=>' mb2-portfolio-carousel', 'neg'=>''));
$carousel_data = Mb2portfolioHelper::carousel_data($related_items, $this->params, array('mode'=>'project-related', 'uniqid'=>$uniqid));



if (count($related_items)>0)
{	
?>


<div class="mb2-portfolio-related-items<?php echo $c_carousel_cls; ?>">
	<div class="mb2-portfolio-related-items-heading"><h3 class="title"><?php echo JText::_('COM_MB2PORTFOLIO_RELATED_PROJECTS');?></h3></div>   
    
    <div class="mb2-portfolio-related-items-content">
    
    	<?php echo Mb2portfolioHelper::carousel_nav($related_items, $this->params, array('mode'=>'project-related', 'uniqid'=>$uniqid)); ?>
    	<div class="clearfix<?php echo $carousel_cls; ?>"<?php echo $carousel_data;?>>
<?php
	
	foreach ($related_items as $key=>$relitem)
	{		
		
		if($relitem->related && $relitem->access_filter && $relitem->lang_filter)
		{
			
			// Get project link
			$skillsarr = array($relitem->skill_1, $relitem->skill_2, $relitem->skill_3, $relitem->skill_4, $relitem->skill_5);						
			$project_url = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($relitem->id, $skillsarr, $language = 0));
			
			// Get item class
			$item_cls = Mb2portfolioHelper::item_cls($relitem, $this->params, array('mode'=>'project-related'));
			
			?>		
			<article class="mb2-portfolio-item mb2-portfolio-col item-<?php echo $relitem->id; ?> <?php echo $item_cls; ?>">
            	<div class="mb2-portfolio-item-inner clearfix">                   
					<?php if(json_decode($relitem->images)->featured_image != '')
					{
					?>
					<div class="mb2-portfolio-item-media mb2-portfolio-media">
						<div class="mb2-portfolio-item-media-inner clearfix">
							<?php echo JHtml::_('media.item_media', $relitem, $this->params, array('mode'=>'project-related', 'uniqid'=>$uniqid)); ?> 
						</div><!-- end .mb2-portfolio-item-media-inner -->                                       
					</div><!-- end .mb2-portfolio-item-thumb -->                
					<?php 
					} 					
					if ($this->params->get('related_item_layout', 'media-desc-below') != 'only-media')
					{ ?>						
                    <div class="mb2-portfolio-item-details mb2-portfolio-details">
                        <div class="mb2-portfolio-item-details-inner clearfix">                         	
                            <h4 class="title mb2-portfolio-item-title">
                            	<?php 
								if ($relitem->title_link == 1)
								{ ?>														
									<a class="title-link" href="<?php echo $project_url; ?>">
										<?php echo JHtml::_('item.word_limit', $relitem->title, $this->params->get('related_title_text_limit', 999), '...'); ?>
                                    </a>
								<?php }				
								else{ ?>
									<?php echo JHtml::_('item.word_limit', $relitem->title, $this->params->get('related_title_text_limit', 999), '...'); ?>               
								<?php } ?>                        	
                        	</h4>
                            <?php
							$project_meta = ($this->params->get('related_project_skills', 1) == 1 || $this->params->get('related_project_date', 'created') != 'none' || $this->params->get('related_project_hits', 1) == 1);
														
							if ($project_meta)
							{
							?>
							<div class="mb2-portfolio-meta clearfix"> 
								<ul class="mb2-portfolio-meta-list">							
									<?php		
									if ($this->params->get('related_project_skills', 1) == 1)
									{
									?>
										<li class="mb2-portfolio-skills"><i class="mb2portfolio-fa mb2portfolio-fa-folder-o"></i>
											<?php echo JHtml::_('meta.skills_list', $relitem, $this->params, array('mode'=>'project', 'sep'=>' / ')); ?>																	
										</li>
									<?php
									}
									if ($this->params->get('related_project_date', 'created') != 'none')
									{
									?>
										<li class="mb2-portfolio-created"><i class="mb2portfolio-fa mb2portfolio-fa-calendar"></i>                
											<?php echo JHtml::_('meta.item_date', $relitem, $this->params, array('mode'=>'project')); ?>
										</li>
									<?php				
									}
									if ($this->params->get('related_project_hits', 1) == 1)
									{
										?>				
										<li class="mb2portfolio-hits"><i class="mb2portfolio-fa mb2portfolio-fa-eye"></i>			
											<?php echo  $relitem->hits; ?>	
										</li>
									<?php	
									}			
									?>
                            	</ul>
                            </div><!-- end .mb2-portfolio-meta -->                            
                            <?php
							
							} // End if project meta 
							
							if ($relitem->intro_text !='' && $this->params->get('related_desc', 1) == 1)
							{ 
							?>
								<div class="mb2-portfolio-item-desc">
									<?php echo JHtml::_('item.word_limit', $relitem->intro_text, $this->params->get('related_text_limit', 999), '...'); ?>
								</div> <!-- end .mb2-portfolio-item-desc --> 
							<?php 
							}
													
							if ($this->params->get('related_read_more', 1) == 1)
							{ ?>							
								<p class="mb2-portfolio-read-more"><a class="btn btn-default" href="<?php echo $project_url; ?>"><?php echo JText::_('COM_MB2PORTFOLIO_READ_MORE'); ?> <i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i></a></p>								
							<?php 
							} 
							?>
                        </div><!-- end .mb2-portfolio-item-details -->
                    </div><!-- end .mb2-portfolio-item-details-inner -->                    
                    <?php 
					} // End if project details
					?>                    
            	</div><!-- end .mb2-portfolio-item-inner -->
            </article><!-- end .mb2-portfolio-item -->	
        <?php			
		} // End if filter access and language
				
	} // End foreach	
	?>
		</div><!-- end .mb2-portfolio-related-items-list -->
	</div><!-- end .mb2-portfolio-related-items-content -->
</div><!-- end .mb2-portfolio-related-items -->		
<?php
}// End if count of related items