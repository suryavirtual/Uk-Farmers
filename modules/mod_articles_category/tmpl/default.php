<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_category
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$menu = JFactory::getApplication()->getMenu();
if ($menu->getActive() == $menu->getDefault()) {
?>
<!--<ul class="category-module<?php echo $moduleclass_sfx; ?>">-->
	<?php if ($grouped) : ?>
		<?php foreach ($list as $group_name => $group) : ?>
		<div>
			<div class="mod-articles-category-group"><?php echo $group_name;?></div>
			<ul>
				<?php foreach ($group as $item) : ?>
					<li>
						<?php if ($params->get('link_titles') == 1) : ?>
							<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
								<?php echo $item->title; ?>
							</a>
						<?php else : ?>
							<?php echo $item->title; ?>
						<?php endif; ?>
	
						<?php if ($item->displayHits) : ?>
							<span class="mod-articles-category-hits">
								(<?php echo $item->displayHits; ?>)
							</span>
						<?php endif; ?>
	
						<?php if ($params->get('show_author')) : ?>
							<span class="mod-articles-category-writtenby">
								<?php echo $item->displayAuthorName; ?>
							</span>
						<?php endif;?>
	
						<?php if ($item->displayCategoryTitle) : ?>
							<span class="mod-articles-category-category">
								(<?php echo $item->displayCategoryTitle; ?>)
							</span>
						<?php endif; ?>
	
						<?php if ($item->displayDate) : ?>
							<span class="mod-articles-category-date"><?php echo $item->displayDate; ?></span>
						<?php endif; ?>
	
						<?php if ($params->get('show_introtext')) : ?>
							<p class="mod-articles-category-introtext">
								<?php echo $item->displayIntrotext; ?>
							</p>
						<?php endif; ?>
	
						<?php if ($params->get('show_readmore')) : ?>
							<p class="mod-articles-category-readmore">
								<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
									<?php if ($item->params->get('access-view') == false) : ?>
										<?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
									<?php elseif ($readmore = $item->alternative_readmore) : ?>
										<?php echo $readmore; ?>
										<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
											<?php if ($params->get('show_readmore_title', 0) != 0) : ?>
												<?php echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit')); ?>
											<?php endif; ?>
									<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
										<?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
									<?php else : ?>
										<?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
										<?php echo JHtml::_('string.truncate', ($item->title), $params->get('readmore_limit')); ?>
									<?php endif; ?>
								</a>
							</p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
		<?php endforeach; ?>
	<?php else :  ?>
	    <?php //echo "<pre>"; print_r($list); echo "</pre>"; ?>
		<?php foreach ($list as $item) : ?>
		<?php 
		$rghts = explode('rights":"',$item->metadata);
		$val = explode('","',$rghts['1']);
		$link = $val['0'];
				
		$xref = explode('xreference":"',$item->metadata);
		$val = explode('"}',$xref['1']);
		$icon = $val['0']; ?>
		<div class="cell-3 service-box-1 fx" data-animate="fadeInUp">
			<div class="box-top">
				<i class="<?php echo $icon; ?>"></i>
			<!--<li>-->
				<?php if ($params->get('link_titles') == 1) : ?>
					<h3><a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
						<?php echo $item->title; ?>
					</a></h3>
				<?php else : ?>
					<h3><?php echo $item->title; ?></h3>
				<?php endif; ?>
				<?php echo $item->introtext; ?>
				<?php if ($item->displayHits) : ?>
					<span class="mod-articles-category-hits">
						(<?php echo $item->displayHits; ?>)
					</span>
				<?php endif; ?>
	
				<?php if ($params->get('show_author')) : ?>
					<span class="mod-articles-category-writtenby">
						<?php echo $item->displayAuthorName; ?>
					</span>
				<?php endif;?>
	
				<?php if ($item->displayCategoryTitle) : ?>
					<span class="mod-articles-category-category">
						(<?php echo $item->displayCategoryTitle; ?>)
					</span>
				<?php endif; ?>
	
				<?php if ($item->displayDate) : ?>
					<span class="mod-articles-category-date">
						<?php echo $item->displayDate; ?>
					</span>
				<?php endif; ?>
	
				<?php if ($params->get('show_introtext')) : ?>
					<p class="mod-articles-category-introtext">
						<?php echo $item->displayIntrotext; ?>
					</p>
				<?php endif; ?>
	
				<?php if ($params->get('show_readmore')) : ?>
					<p class="mod-articles-category-readmore">
						<a class="mod-articles-category-title <?php echo $item->active; ?>" href="<?php echo $item->link; ?>">
							<?php if ($item->params->get('access-view') == false) : ?>
								<?php echo JText::_('MOD_ARTICLES_CATEGORY_REGISTER_TO_READ_MORE'); ?>
							<?php elseif ($readmore = $item->alternative_readmore) : ?>
								<?php echo $readmore; ?>
								<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
							<?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
								<?php echo JText::sprintf('MOD_ARTICLES_CATEGORY_READ_MORE_TITLE'); ?>
							<?php else : ?>
								<?php echo JText::_('MOD_ARTICLES_CATEGORY_READ_MORE'); ?>
								<?php echo JHtml::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
							<?php endif; ?>
						</a>
					</p>
				<?php endif; ?>
			<!--</li>-->
			<a href="<?php echo $link; ?>" class="more-btn">READ MORE</a>
			</div></div>
		<?php endforeach; ?>
	<?php endif; ?>
<!--</ul>-->
<?php } else { ?>
<div class="cell-4 plan-block lft-plan">
	<?php 
	$i=1;
	foreach ($list as $item) :
		if($i % 2 != 0){?>
		<div class="block">
		<h3><?php echo $item->title;?> ...</h3>
		<p><?php echo $item->introtext; ?></p>
		<div class="plan-year"><span>
			<?php 
				$year = explode("-",$item->publish_up);
				echo $year['0']; ?>
		</span></div>
		</div>
		<?php } $i++; endforeach; ?>
</div>

<div class="cell-2 plan-title">
	<div class="main-color extraBold">Our <br><span>Plan</span></div>
</div>

<div class="cell-4 plan-block rit-plan">
    <?php 
	$i=1;
	foreach ($list as $item) :
		if($i % 2 == 0){?>
		<div class="block">
		<h3><?php echo $item->title;?> ...</h3>
		<p><?php echo $item->introtext; ?></p>
		<div class="plan-year"><span>
			<?php 
				$year = explode("-",$item->publish_up);
				echo $year['0']; ?>
		</span></div>
		</div>
		<?php } $i++; endforeach; ?>
</div>
							
<?php } ?>
<div style="clear:both;"></div>