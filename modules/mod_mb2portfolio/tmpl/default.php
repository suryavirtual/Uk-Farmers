<?php 
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;

// Basic variables
$i=0;
$module_cls='';
$carousel_cls='';
$carousel_data='';
$module_data='';
$uniqid=uniqid();



// Check if items are display within carousel
$carousel_cls = Mb2portfolioHelper::carousel_cls($items, $params, array('mode'=>'module', 'pos'=>'mb2-portfolio-carousel', 'neg'=>''));
$module_cls .= ' ' . Mb2portfolioHelper::carousel_cls($items, $params, array('mode'=>'module', 'pos'=>'is-carousel', 'neg'=>'no-carousel'));
$carousel_data = Mb2portfolioHelper::carousel_data($items, $params, array('mode'=>'module', 'uniqid'=>$uniqid));

// Portfolio filter
if ($params->get('show_filter', 0) == 1 && !$carousel)
{
	$module_cls .= ' filter-' . $gparams->get('filter', 'none');
	$module_data .= ' data-mode="' . $gparams->get('isotope_mode', 'fitRows') . '"';
}


?>
<div class="mb2-portfolio-container mb2-portfolio-module-<?php echo $module->id . $module_cls; ?>">
<?php

// Get project filter
if ($gparams->get('filter', 'none') != 'none' && $params->get('show_filter', 0) == 1)
{
	require JModuleHelper::getLayoutPath('mod_mb2portfolio', '_filter');	
}

// Get carousel navigation
echo Mb2portfolioHelper::carousel_nav($items, $params, array('mode'=>'module', 'uniqid'=>$uniqid)); 

?>
<div class="mb2-portfolio-mod-projects mb2-portfolio-projects clearfix <?php echo $carousel_cls; ?>"<?php echo $carousel_data . $module_data; ?>>
<?php
if (count($items>0))
{
		
	foreach ($items as $key=>$item)
	{		
		// Check item access and langugae
		if ($item->access_filter && $item->lang_filter)
		{	
			$i++;
				
			// Get item class
			$item_cls = Mb2portfolioHelper::item_cls($item, $params, array('mode'=>'module'));
				
		?>             
            <div class="mb2-portfolio-item mb2-portfolio-col <?php echo $item_cls; ?>"> 
            	 <div class="mb2-portfolio-item-inner clearfix">
            		<?php
					if (json_decode($item->images)->featured_image !='')
					{
					?>
                  	<div class="mb2-portfolio-item-media mb2-portfolio-media"> 
                    	<div class="mb2-portfolio-item-media-inner">
                    		<?php echo JHtml::_('media.item_media', $item, $params, array('mode'=>'module', 'uniqid'=>$uniqid)); ?> 
                    	</div><!-- end .mb2-portfolio-item-media-inner -->
                    </div><!-- end .mb2-portfolio-item-media -->
                    <?php				
					
					}// End if media
					
					if ($params->get('item_layout', 'media-desc-below') != 'only-media')
					{
						
					?>
                  	<div class="mb2-portfolio-item-details mb2-portfolio-details">
       					<div class="mb2-portfolio-item-details-inner">  
                			<h4 class="title mb2-portfolio-item-title">
                            	<?php
								if ($item->title_link == 1)
								{
								?>
                                    <a href="<?php echo $item->link; ?>">
                                        <?php echo JHtml::_('item.word_limit', $item->title, $params->get('title_limit', 999), '...') ;?>
                                    </a>                                   
                                <?php							
								}
								else
								{
									echo JHtml::_('item.word_limit', $item->title, $params->get('title_limit', 999), '...') ;									
								}
								?>                          
                            </h4>                            
                            <?php
							
							$itemmeta = ( $params->get('project_skills', 1) == 1 || $params->get('projects_date', 'created') != 'none' || $hits = $params->get('hits', 1) == 1);
							
							if ($itemmeta)
							{
							?>
                      			<div class="mb2-portfolio-meta clearfix"> 
									<ul class="mb2-portfolio-meta-list"> 
                                    	<?php		
										if ($params->get('project_skills', 1) == 1)
										{
										?>
											<li class="mb2-portfolio-skills"><!--<i class="mb2portfolio-fa mb2portfolio-fa-folder-o"></i>-->
												<?php echo JHtml::_('meta.skills_list', $item, $params, array('mode'=>'module', 'sep'=>' / ')); ?>																	
											</li>
										<?php
										}
										/*if ($params->get('projects_date', 'created') != 'none')
										{
										?>
											<li class="mb2-portfolio-created"><i class="mb2portfolio-fa mb2portfolio-fa-calendar"></i>                
												<?php echo JHtml::_('meta.item_date', $item, $params, array('mode'=>'module')); ?>
											</li>
										<?php				
										}*/
										/*if ($params->get('hits', 1) == 1)
										{
											?>				
											<li class="mb2portfolio-hits"><i class="mb2portfolio-fa mb2portfolio-fa-eye"></i>			
												<?php echo  $item->hits; ?>	
											</li>
										<?php	
										}*/			
										?>                              
                                    </ul>  
                                </div><!-- end .mb2-portfolio-meta -->                             
                            <?php								
								
							}// End if item meta
							
							if ($item->intro_text !='' && $params->get('description', 1) == 1)
							{								
							?>
                            	<div class="mb2-portfolio-item-desc">                              
                                	<?php echo JHtml::_('item.word_limit', $item->intro_text, $params->get('desc_limit', 999), '...') ;?>
                                </div><!-- end .mb2-portfolio-item-desc -->
                            <?php						
							
								if ($params->get('read_more', 1) == 1)
								{
								?>
									<p class="mb2-portfolio-read-more">
										<a class="btn btn-default" href="<?php echo $item->link; ?>"><?php echo JText::_('MOD_MB2PORTFOLIO_READ_MORE'); ?> <i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i></a>
									</p>
								<?php						
									
								}
															
							} // End if item description						
							?>
                        </div><!-- end .mb2-portfolio-item-details -->
                   	</div><!-- end .mb2-portfolio-item-details-inner -->
                    <?php
									
					}// End if details 
					                  
                    ?>
            	</div><!-- end .mb2-portfolio-item-inner -->
            </div><!-- end .mb2-portfolio-item --> 	
			<?php	
			
			// Get item separator
			$separator = ((!$carousel && $params->get('cols', 3) == $i) && ($params->get('show_filter') == 0 || $gparams->get('filter', 'none') != 'isotope'));			
			if($separator){ ?><div class="mb2-portfolio-separator clearfix"></div><?php $i=0; }
		
		} // End if access and language flter
				
	}// End foreach

}
else
{	
	echo JText::_('MOD_MB2PORTFOLIO_ITEMS_NOT_FOUND');	
	
} // End if item counts

?>
</div><!-- end .mb2-portfolio-mod-projects -->
</div><!-- end .mb2-portfolio-container -->