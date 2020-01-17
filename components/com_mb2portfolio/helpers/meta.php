<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/


defined('_JEXEC') or die;

/**
 * Content Component HTML Helper
 *
 * @package     Joomla.Site
 * @subpackage  com_mb2portfolio
 * @since       1.5
 */
abstract class JHtmlMeta
{
	
	
	/**
	 * Method to generate project skills list
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item skills list
	 */
	public static function skills_list($item, $params, $attribs = array())
	{
		
		
		
		$output = '';
		$i = 0;
		
		
		if ($attribs['mode'] == 'project')
		{
			$show_skills = $params->get('single_project_skills', 1);
			$skills_link = $params->get('single_project_skills_link', 0);
		}
		elseif ($attribs['mode'] == 'project-related') 
		{
			$show_skills = $params->get('related_project_skills', 1);
			$skills_link = $params->get('related_project_skills_link', 0);
		}
		else
		{			
			$show_skills = $params->get('project_skills', 1);
			$skills_link = $params->get('project_skills_link', 0);
		}
		
		
		// Get item skills array
		$itemskillsarr = array(
			array($item->skill_1, $item->skill1_alias, $item->skill1_title, 'skill1'),	
			array($item->skill_2, $item->skill2_alias, $item->skill2_title, 'skill2'),
			array($item->skill_3, $item->skill3_alias, $item->skill3_title, 'skill3'),
			array($item->skill_4, $item->skill4_alias, $item->skill4_title, 'skill4'),
			array($item->skill_5, $item->skill5_alias, $item->skill5_title, 'skill5'),	
		);	
		
		
		
		
		if ($show_skills == 1)
		{		
			
			$output .= '<ul class="mb2-portfolio-skill-list">';
			
			foreach ($itemskillsarr as $skill) 
			{
					
					
					
				$skillid = $skill[0];
				$skillalias = $skill[1];
				$skilltitle = $skill[2];
	
				if (! $skillid) :
					continue;
				endif;	
				
				$i++;
				
				
				$skillslug = $skillalias ? $skillid . ':' . $skillalias : $skillid;
				
				
				$skillurl = JRoute::_(Mb2portfolioHelperRoute::getProjectsRoute($skillslug, $language = 0));	
			
				
				$output .= '<li>';
				
				$skills_link == 1 ? $output .= '<a href="' . $skillurl . '">' : '';
				
				$output .= $skilltitle;
				
				$skills_link == 1 ? $output .= '</a>' : '';
				
				
				// Add skill separator
				$output .= $itemskillsarr[$i][0] ? $attribs['sep'] : '';
				
								
				
				$output .= '</li> ';
				
				
			
	
			} // End foreach
			
			
			$output .= '</ul><!-- end .mb2-portfolio-skill-list -->';
			

		} // End if project skills


		return $output;	
		
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate project date
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item date
	 */
	public static function item_date($item, $params, $attribs = array())
	{
		
		
		
		$output = '';
		
		
		
		if ($attribs['mode'] == 'project')
		{
			$pdate = $params->get('single_project_date', 'created');
		}
		elseif ($attribs['mode'] == 'related-projects')
		{
			$pdate = $params->get('related_project_date', 'created');
		}
		else
		{
			$pdate = $params->get('projects_date', 'created');
		}	
		
		
		
				
		
		if ($pdate != 'none')
		{
			
			
			
			// Created																				
			$created = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_' . $params->get('date_format', 'LC3')));			
			
			// Modified																							
			$modified = JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_' . $params->get('date_format', 'LC3')));
			
			
			if ($pdate == 'created')
			{
				echo $created;
			}
			elseif ($pdate == 'modified' && $item->modified != '0000-00-00 00:00:00')
			{
				echo $modified;
			}
			elseif ($pdate == 'all')
			{
				echo $created;					
				echo $item->modified != '0000-00-00 00:00:00' ? ', ' . $modified : '';
			}	
			
			
			
			
		} // End if pdate	
		


		return $output;	
		
		
		
		
	}
	
	
	
	
	
	
	

	

}