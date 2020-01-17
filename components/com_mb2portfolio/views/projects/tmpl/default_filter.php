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

$model = $this->getModel('projects');
$skills = $model->get_skills_list();


?>
<div class="mb2-portfolio-filter-nav clearfix">	
    <div class="dropdown pull-right">    	
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#"><i class="mb2portfolio-fa mb2portfolio-fa-sort"></i> <?php echo JText::_('COM_MB2PORTFOLIO_FILTER_PORTFOLIO'); ?></a>    
        <ul class="mb2-portfolio-filter-nav-list dropdown-menu">
        <?php
        if($this->params->get('filter','none') != 'normal')
        {
        ?>
        <li><a href="javascript:void(0)" class="current" data-filter="*"><?php echo JText::_('COM_MB2PORTFOLIO_FILTER_ALL'); ?></a></li>
        
        <?php
        }	
        foreach ($skills as $key=>$skill)
        {
            
            
            if($skill->access_filter && $skill->lang_filter)
            {				
                // Set data-filter attribute
                if($this->params->get('filter','none') == 'isotope')
                {			
                    $dfilter = ' data-filter=".' . $skill->alias . '"';
                }
                elseif($this->params->get('filter','none') == 'fade')
                {			
                    $dfilter = ' data-filter="' . $skill->alias . '"';
                }
                else
                {			
                    $dfilter = '';
                }
                
                // Set skill url	
                if($this->params->get('filter', 'none') == 'normal')
                {			
                    $surl = JRoute::_(Mb2portfolioHelperRoute::getProjectsRoute($skill->id, $language = 0));			
                    (JFactory::getApplication()->input->get('id') == $skill->id) ? $scls = ' class="active"' : $scls = '';			
                }
                else
                {			
                    $surl = 'javascript:void(0)';
                    $scls = '';
                }		
                
            ?>     
                <li<?php echo $scls; ?>><a href="<?php echo $surl;?>"<?php echo $dfilter; ?>><?php echo $skill->title; ?></a></li>      
            <?php
            }		
        }
        ?>
                   
        </ul>  
  	</div><!-- end .dropdown -->
</div><!-- end .mb2-portfolio-filter-nav -->