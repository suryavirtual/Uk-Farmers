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


function com_mb2portfolio_skill_titles($skill_1,$skill_2,$skill_3,$skill_4,$skill_5){
				
		$db = JFactory::getDbo();	
		$db->setQuery(		
		$db->getQuery(true)
			->select('a.*')
			->from('#__mb2portfolio_skills As a')
			->where('state=1 AND (id='.$skill_1.' OR id='.$skill_2.' OR id='.$skill_3.' OR id='.$skill_4.' OR id='.$skill_5.')')
		);	
		$skill_titles = $db->loadObjectList();						
		
		
		return $skill_titles;
			
}