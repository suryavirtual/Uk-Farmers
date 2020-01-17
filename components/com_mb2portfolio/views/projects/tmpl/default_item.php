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


// Get project url
$skillsarr = array($this->item->skill_1, $this->item->skill_2, $this->item->skill_3, $this->item->skill_4, $this->item->skill_5);
$link = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($this->item->slug, $skillsarr, $language = 0));

?>

<div class="mb2-portfolio-item-inner clearfix">
	<?php if(json_decode($this->item->images)->featured_image != '')
	{
	?>
	<div class="mb2-portfolio-item-media mb2-portfolio-media">
		<div class="mb2-portfolio-item-media-inner clearfix">
			<?php echo JHtml::_('media.item_media', $this->item, $this->params, array('mode'=>'projects', 'uniqid'=>$this->uid)); ?>
		</div><!-- end .mb2-portfolio-item-media-inner -->
	</div><!-- end .mb2-portfolio-item-thumb -->
	<?php
	}
	if($this->params->get('projects_item_layout', 'media-desc-below') != 'only-media')
	{
	?>
	<div class="mb2-portfolio-item-details mb2-portfolio-details">
		<div class="mb2-portfolio-item-details-inner clearfix">
        	<h2 class="title mb2-portfolio-item-title">
			<?php if($this->item->title_link == 1)
			{
			?>
				<a class="title-link" href="<?php echo $link; ?>">
					<?php echo JHtml::_('item.word_limit', $this->item->title, $this->params->get('title_text_limit', 999), '...');  ?>
				</a>
			<?php
			}
			else
			{
				echo JHtml::_('item.word_limit', $this->item->title, $this->params->get('title_text_limit', 999), '...');
			}
			?>
            </h2>
            <?php
			$this->itemmeta = ( $this->params->get('project_skills', 1) == 1 || $this->params->get('projects_date', 'created') != 'none' || $hits = $this->params->get('projects_hits', 1) == 1);

			if($this->itemmeta)
			{
			?>
			<div class="mb2-portfolio-meta clearfix">
				<ul class="mb2-portfolio-meta-list">
					<?php
                    if ($this->params->get('project_skills', 1) == 1)
                    {
                    ?>
                        <li class="mb2-portfolio-skills"><!--<i class="mb2portfolio-fa mb2portfolio-fa-folder-o"></i>-->
                            <?php echo JHtml::_('meta.skills_list', $this->item, $this->params, array('mode'=>'projects', 'sep'=>' / ')); ?>
                        </li>
                    <?php
                    }
                    /*if ($this->params->get('projects_date', 'created') != 'none')
                    {
                    ?>
                        <li class="mb2-portfolio-created"><i class="mb2portfolio-fa mb2portfolio-fa-calendar"></i>
                            <?php echo JHtml::_('meta.item_date', $this->item, $this->params, array('mode'=>'projects')); ?>
                        </li>
                    <?php
                    }*/
                    /*if ($this->params->get('single_project_hits', 1) == 1)
                    {
                        ?>
                        <li class="mb2portfolio-hits"><i class="mb2portfolio-fa mb2portfolio-fa-eye"></i>
                            <?php echo  $this->item->hits; ?>
                        </li>
                    <?php
                    }*/
                    ?>
            	</ul>
 			</div><!-- end .mb2-portfolio-meta -->
            <?php
			}
                           $total_word_count=str_word_count($this->item->intro_text);
		          $word_limits=$this->params->get('text_limit');
			if($this->item->intro_text !='' && $this->params->get('desc', 1) != 0){ ?>
			<div class="mb2-portfolio-item-desc">
				<?php echo JHtml::_('item.word_limit', $this->item->intro_text, $this->params->get('text_limit'), '...');	?>
			</div> <!-- end .mb2-portfolio-item-intro-text -->
			<?php
			}
			if(($this->params->get('read_more', 1) == 1)&&($total_word_count > $this->params->get('text_limit')) )
			{
			?>
			 <p class="mb2-portfolio-read-more">
				<a class="btn" href="<?php echo $link; ?>">
					<?php echo JText::_('COM_MB2PORTFOLIO_READ_MORE'); ?>
                    <i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i>
				</a>
			</p>	
			<?php
			}
			?>
		</div><!-- end .mb2-portfolio-item-deatils-inner -->
	</div><!-- end .mb2-portfolio-item-deatils -->
	<?php
	}
	?>
</div><!-- end .mb2-portfolio-item-inner -->
