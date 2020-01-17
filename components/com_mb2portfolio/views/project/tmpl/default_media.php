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



$images_arr = json_decode($this->item->images);
$video_arr = json_decode($this->item->video);


//get params
$single_item_layout = $this->item->layout;	
$single_media_width = $this->item->media_width;
$lightbox = $this->params->get('lightbox', 'nivo');
$video_id = $video_arr->video_id;
//$video_embed = $video_arr->video_embed;
$f_image = $images_arr->featured_image;
$f_image_alt = $images_arr->featured_image_alt;
$f_image_caption = $images_arr->featured_image_caption;
$width = $images_arr->image_width;
$height = $images_arr->image_height;
$image_on = $images_arr->image_on;
$slider = $images_arr->slider;
$multiple = $images_arr->multiple;
$multiple_lightbox = $images_arr->multiple_lightbox;
$multiple_margin = $images_arr->multiple_margin;
$link = $images_arr->image_link;
$crop_images = $images_arr->crop_images;
$project_images = array(
	array($images_arr->featured_image, $images_arr->featured_image_alt, $images_arr->featured_image_caption, 'f_image'),
	array($images_arr->image_1, $images_arr->image_1_alt, $images_arr->image_1_caption, 'image_1'),
	array($images_arr->image_2, $images_arr->image_2_alt, $images_arr->image_2_caption, 'image_2'),
	array($images_arr->image_3, $images_arr->image_3_alt, $images_arr->image_3_caption, 'image_3'),
	array($images_arr->image_4, $images_arr->image_4_alt, $images_arr->image_4_caption, 'image_4'),
	array($images_arr->image_5, $images_arr->image_5_alt, $images_arr->image_5_caption, 'image_5'),
	array($images_arr->image_6, $images_arr->image_6_alt, $images_arr->image_6_caption, 'image_6'),
	array($images_arr->image_7, $images_arr->image_7_alt, $images_arr->image_7_caption, 'image_7'),
	array($images_arr->image_8, $images_arr->image_8_alt, $images_arr->image_8_caption, 'image_8'),
	array($images_arr->image_9, $images_arr->image_9_alt, $images_arr->image_9_caption, 'image_9'),
	array($images_arr->image_10, $images_arr->image_10_alt, $images_arr->image_10_caption, 'image_10')		
);
$slider_options = array(
	'slider_type' => $this->params->get('slider_type', 2),
	'caption' => $this->params->get('slider_caption', 1),	
	'animation' => $this->params->get('flexslider_animation', 'fade'),
	'slideshow_speed' => $this->params->get('flexslider_slideshow_speed', 5000),
	'animation_speed' => $this->params->get('flexslider_animation_speed', 600),
	'control_nav' => $this->params->get('flexslider_control_nav', 0),
	'direct_nav' => $this->params->get('flexslider_direct_nav', 1),
	'easing' => $this->params->get('flexslider_easing', 'easeInOutExpo'),
	'direction' => $this->params->get('flexslider_direction', 'horizontal'),
	'slideshow' => $this->params->get('flexslider_slideshow', 1)
);




if($link == 3 || $link == 2){
	$link = 1;	
}
else{
	$link = $link;	
}





?>
<div class="mb2-portfolio-single-item-media<?php echo $multiple == 1 ? ' multiple-images' : ''; ?>"> 
	<div class="mb2-portfolio-single-item-media-inner">
		<?php
		echo JHtml::_('media.item_media', $this->item, $this->params, array('mode'=>'project')); 
		//com_mb2portfolio_image(1,$image_on,$f_image,$video_id,$link,'project','none',0,0,$width,$height,$slider,$slider_options,$multiple,$multiple_lightbox,$multiple_margin,$project_images,0,$video_embed,$lightbox,$this->item->title,$crop_images,$this->item->id,$f_image_alt,$f_image_caption); 		
		?>        
	</div><!-- end .mb2-portfolio-single-item-media-inner -->
</div><!-- end .mb2-portfolio-single-item-media -->