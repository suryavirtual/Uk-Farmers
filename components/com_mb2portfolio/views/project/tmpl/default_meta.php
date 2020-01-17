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


$project_meta = ($this->params->get('single_project_skills', 1) == 1 || $this->params->get('single_project_date', 'created') != 'none' || $this->params->get('single_project_hits', 1) == 1);



if($project_meta){ ?>
	<div class="mb2-portfolio-meta clearfix">
    	<ul class="mb2-portfolio-meta-list">
        <?php		
			if ($this->params->get('single_project_skills', 1) == 1)
			{
			?>
				<li class="mb2-portfolio-skills"><i class="mb2portfolio-fa mb2portfolio-fa-folder-o"></i>
                	<?php echo JHtml::_('meta.skills_list', $this->item, $this->params, array('mode'=>'project', 'sep'=>' / ')); ?>																	
				</li>
          	<?php
			}
			if ($this->params->get('single_project_date', 'created') != 'none')
			{
			?>
            	<li class="mb2-portfolio-created"><i class="mb2portfolio-fa mb2portfolio-fa-calendar"></i>                
                	<?php echo JHtml::_('meta.item_date', $this->item, $this->params, array('mode'=>'project')); ?>
                </li>
            <?php				
			}
			if ($this->params->get('single_project_hits', 1) == 1)
			{
				?>				
				<li class="mb2portfolio-hits"><i class="mb2portfolio-fa mb2portfolio-fa-eye"></i>			
					<?php echo  $this->item->hits; ?>	
				</li>
            <?php	
			}			
			?>	
        </ul>                           
    </div><!-- end .mb2-portfolio-meta -->	
<?php	
}