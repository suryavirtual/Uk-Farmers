<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.2.0
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/
 

// no direct access
defined('_JEXEC') or die; 

 
function com_mb2portfolio_image($single,$f_image_on,$f_image,$video_id,$link,$view,$align,$project_id,$project_url,$width,$height,$slider,$slider_options,$multiple,$multiple_lightbox,$multiple_margin,$project_images,$gallery,$video_embed,$lightbox,$title,$crop_images,$gall_id='projects',$alt_text='',$caption='',$lazy=0,$cosik=array()){		
	
	$doc = JFactory::getDocument();	
	$output = '';
	$unigid = uniqid();
	$link_rel = '';
	$link_cls = '';
	$link_data = '';
	$img_cls = '';	
	$thumb_name = com_mb2portfolio_thumbnail_name($f_image,1);
	$f_image_on == 0 ? $start = 1 : $start = 0;		
	if($lazy == 1){$img_cls .= 'js-load img-responsive';}
	
	
	
	// Check if project have images
	$is_project_image = !empty($project_images[0][0]) || !empty($project_images[1][0]) || !empty($project_images[2][0]) || !empty($project_images[3][0]) || !empty($project_images[4][0]) || !empty($project_images[5][0]) || !empty($project_images[6][0]) || !empty($project_images[7][0]) || !empty($project_images[8][0]) || !empty($project_images[9][0]) || !empty($project_images[10][0]);	
	
	
	// Enable gallery for multiple images on single project page
	($single == 1 && $is_project_image && $multiple == 1 && $multiple_lightbox == 1) ? $gallery = 1 : $gallery = $gallery;
	
	
	// Title value
	$alt_text !='' ? $link_title = ' title="'.$alt_text.'"' : $link_title = ' title="'.$title.'"';	
	
	
	// Caption image
	$img_caption = $caption !='' ? '<p class="mb2-portfolio-img-caption">'.$caption.'</p>' : '';	
	
	
	// Lightbox attributes
	if($lightbox == 'prettyphoto'){		
		if($gallery == 1){			
			$link_rel=' data-rel="prettyPhoto[com_mb2portfolio_gallery_'.$gall_id.']"';
		}
		else {
			$link_rel .= ' data-rel="prettyPhoto"';	
		}		
		$link_cls .= ' class="mb2-portfolio-pp-link"';			
	}
	elseif($lightbox == 'nivo'){		
		if($gallery == 1){
			$link_data .= ' data-lightbox-gallery="com_mb2portfolio_gallery'.$gall_id.'"'; 			
		}		
		$link_cls .= ' class="mb2-portfolio-nivo-link"';
	}	
		
	
	// Thumbnail url
	$thumb_url = com_mb2portfolio_thumbnail_url(1,$f_image,$width,$height);		
	
	$lazy == 1 ? $img_data = ' data-src="'.$thumb_url.'"' : $img_data = '';
	$lazy == 1 ? $img_src = '' : $img_src = ' src="'.$thumb_url.'"';	
	//$img_src = ' src="http://demo.mb2extensions.com/media/com_mb2portfolio/images/blank.gif"';
	
	
	
	
	/*----------------------------------------*/
	/*	Thumbnail types
	/*----------------------------------------*/	
	// Live video
	if($video_embed == 1 && $video_id !=''){						
		$output .= com_mb2portfolio_thumbnail_video($video_id,$width,$height,$align);		
	}
	
	
	
	// Multiple images on single project page		
	elseif($multiple == 1 && $is_project_image && $single == 1){	
		$output .= com_mb2portfolio_thumbnail_multiple_images($project_images,$f_image_on,$crop_images,$width,$height,$multiple_lightbox,$multiple_margin,$title,$link_cls,$link_rel,$link_data,$link_title,$lazy);	
	}
	
	
	
	// Slider		
	elseif($slider == 1 && $is_project_image){
		$output .= com_mb2portfolio_thumbnail_slider($project_images,$slider_options,$f_image,$f_image_on,$width,$height,$crop_images,$alt_text);
	}	
	
		
	
	// Thumbnail link to big image or video
	elseif($link == 1 || $link == 3){	
			
		// Check if user add link to video
		if($video_id !=''){
			if(preg_match('/^[0-9]+$/', $video_id)){			
				$f_image = 'http://vimeo.com/'.$video_id;			
			}
			else{
				$f_image = 'http://www.youtube.com/watch?v='.$video_id;		
			}					
			$fa_type = 'fa-play';			
		}
		else {
			$f_image = $f_image;
			$fa_type = 'fa-expand';	
		}			
		
		$link == 3 ? $mark_link_cls = 'links' : $mark_link_cls = 'link';				
		
		$output .= '<div class="mb2-portfolio-img align-'.$align.'" style="width:'.$width.'px;max-width:100%;height:auto;">';
		$output .= '<img class="'.$img_cls.'"'.$img_data.$img_src.' height="'.$height.'" width="'.$width.'"  alt="'.$alt_text.'" />';
		$output .= '<div class="mb2-portfolio-mark"><div class="'.$mark_link_cls.'">';
		$output .= '<a href="'.$f_image.'"'.$link_cls.$link_rel.$link_data.$link_title.'><i class="fa '.$fa_type.'"></i></a>';
		if($link == 3){ $output .= '<a href="'.$project_url.'"><i class="fa fa-link"></i></a>'; }		
		$output .= '</div></div>';
		$output .= $img_caption.'</div>';	
							
	}	
	
	
		
	// Thumbnail link to post	
	elseif($link == 2){					
		$output .= '<div class="mb2-portfolio-img align-'.$align.'" style="width:'.$width.'px;max-width:100%;height:auto;">';
		$output .= '<img class="'.$img_cls.'"'.$img_data.$img_src.' height="'.$height.'" width="'.$width.'"  alt="'.$alt_text.'" />';
		$output .= '<div class="mb2-portfolio-mark"><div class="link"><a href="'.$project_url.'"><i class="fa fa-link"></i></a></div></div>';
		$output .= $img_caption.'</div>';
	}
		
		
		
	// No-link thumbnail
	else {			
		$output .= '<div class="mb2-portfolio-img align-'.$align.'" style="width:'.$width.'px;max-width:100%;height:auto;">';	
		$output .= '<img class="'.$img_cls.'"'.$img_data.$img_src.' height="'.$height.'" width="'.$width.'"  alt="'.$alt_text.'" />';	
		$output .= $img_caption.'</div>';		
	}	
		
		
						
	echo $output;	
		
				
							
}