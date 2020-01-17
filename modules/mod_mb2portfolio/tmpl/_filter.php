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


$model = JModelLegacy::getInstance('Projects', 'Mb2portfolioModel', array('ignore_request' => true));
$skills = $model->get_skills_list();


?>
<div class="mb2-portfolio-filter-nav clearfix">	
    <div class="dropdown pull-right">    	
        <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-sort"></i> <?php echo JText::_('MOD_MB2PORTFOLIO_FILTER_PORTFOLIO'); ?></a>    
        <ul class="mb2-portfolio-filter-nav-list dropdown-menu">
        <?php
        if($gparams->get('filter','none') != 'normal')
        {
        ?>
        <li class="active"><a href="javascript:void(0)" class="current" data-filter="*"><?php echo JText::_('MOD_MB2PORTFOLIO_FILTER_ALL'); ?></a></li>
        
        <?php
        }	
        foreach ($skills as $key=>$skill)
        {
            
            
            if($skill->access_filter && $skill->lang_filter)
            {				
                // Set data-filter attribute
                if($gparams->get('filter','none') == 'isotope')
                {			
                    $dfilter = ' data-filter=".' . $skill->alias . '"';
                }
                elseif($gparams->get('filter','none') == 'fade')
                {			
                    $dfilter = ' data-filter="' . $skill->alias . '"';
                }
                else
                {			
                    $dfilter = '';
                }
                
                // Set skill url	
                if($gparams->get('filter', 'none') == 'normal')
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