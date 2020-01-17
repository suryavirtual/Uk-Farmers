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
 
function com_mb2portfolio_thumbnail($id,$url,$width,$height,$quality){		
	
		
		//check uploaded image format		
		$format_checker = substr($url,-4); 
				
		if ($format_checker == '.jpg'){
			$format = '.jpg';	
		}
		elseif ($format_checker == '.gif'){
			$format = '.gif';	
		}
		elseif ($format_checker == '.png'){
			$format = '.png';	
		}					
								
		// *** 1) Initialise / load image
		$resizeObj = new com_mb2portfolio_resize(''.$url.'');
			
		// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
		$resizeObj -> resizeImage($width, $height, 'crop'); 		
		
		//check if thumbnail folder exist. if not creat it
		if(!is_dir(JPATH_ADMINISTRATOR .'/cache/com_mb2portfolio')){
			jimport('joomla.filesystem.folder');
			JFolder::create( JPATH_ADMINISTRATOR .'/cache/com_mb2portfolio');
		}		
			
		// *** 3) Save image
		$resizeObj -> saveImage(''.JPATH_ADMINISTRATOR .'/cache/com_mb2portfolio/thumbnail_'.$id.'_'.$width.'x'.$height.''.$format.'', $quality);	
		
		//define thumbnail url
		//$thumbnail_url = ''.JURI::base().'cache/com_mb2portfolio/thumbnail_'.$id.'_'.$width.'x'.$height.''.$format.'';
                 // $thumbnail_url = ''.$url.$id.'_'.$width.'x'.$height.''.$format.'';		
			
		$output = '';
							
		$output .= '<img src="'.$url.'" alt=" " width="68"  height="68" />';		
		
		echo $output;						
}
