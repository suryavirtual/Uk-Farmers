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
abstract class JHtmlItem
{
	
	
	/**
	 * 
	 * Method to make word limit for string
	 *
	 */
	public static function word_limit($string, $word_limit = 999, $end_char = '...'){	
	
	
		//check if user use word limit
		if($word_limit !='' && $word_limit < 999){
			
			$content_limit = strip_tags($string);
			$words = explode(" ",$content_limit);
			$new_string = implode(" ",array_splice($words,0,$word_limit));	
			$word_count = str_word_count($string);
			
			
			//get end of word limit
			if($end_char !=''){
				if($word_count > $word_limit){		
				$is_end_char = $end_char;
				}		
				else{
				$is_end_char = '';	
				}
			}
			else{
				$is_end_char = '';
			}			
			
			return JHTML::_('content.prepare', $new_string) . $is_end_char;		
			
		}
		
		else{		
			return JHTML::_('content.prepare', $string);		
		}
			
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	

	

}