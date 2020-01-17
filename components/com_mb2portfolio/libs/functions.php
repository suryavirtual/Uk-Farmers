<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.2.0
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/


defined('_JEXEC') or die;


/*----------------------------------------*/
/*	Check template
/*----------------------------------------*/
function com_mb2portfolio_check_template(){	
	
	$app = JFactory::getApplication();
	$current_template = $app->getTemplate();
	
	//add to array templates name
	$templates = array('gamma');
			
		
	//get template author name
	$templ_url = JURI::base(false).'/templates/'.$current_template.'/templateDetails.xml';		
	$tmpl_xml=simplexml_load_file($templ_url);	
	$xml_tmpl_aurhor = $tmpl_xml->author;
	$no_space_tmpl_author = str_replace(' ','',$xml_tmpl_aurhor);		
	$tmpl_author = strtolower($no_space_tmpl_author);
	
	
	
	foreach($templates as $template){		
		
		if($template === $current_template && $tmpl_author === 'mariuszboloz'){			
			return true;	
		}		
		else{			
			return false;	
		}		
		
	}	
	
			
}







/*----------------------------------------*/
/*	Change 1 to 'true' and 0 to 'false'
/*----------------------------------------*/
function com_mb2portfolio_true_false($param){	
	if($param == 0){
		$param = 'false';	
	}
	else{
		$param = 'true';
	}	
	return $param;			
}
















/*----------------------------------------*/
/*	Style declaration
/*----------------------------------------*/	
function com_mb2portfolio_style($custom_css,$fa,$custom_color1){	
	
	$doc = JFactory::getDocument();		
	$inline_style = '';
	
	
	if($fa == 1 && !com_mb2portfolio_check_template()){
		$doc->addStyleSheet(JURI::base(true) .'/media/com_mb2portfolio/css/font-awesome/css/font-awesome.min.css');
	}
			
			
	//core style of component
	$doc->addStyleSheet(JURI::base(true) .'/media/com_mb2portfolio/css/com_mb2portfolio.css');
	
	
	
	
	//get custom color styles
	if($custom_color1 !=''){
		
		//color
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-social-shares li a';
		$inline_style .= '{color:'.$custom_color1.';}';		

		//background color
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-social-shares li a:hover,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-social-shares li a:focus,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-nav ul li a:hover,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-nav ul li a:focus,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-carousel-nav .prev:hover,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-carousel-nav .next:hover,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-carousel-nav .prev:focus,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-carousel-nav .next:focus,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-carousel-nav .pager a.selected,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-img:hover .mb2-portfolio-mark a,';
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-img-caption';
		$inline_style .= '{background-color:'.$custom_color1.';}';	
		
		//border color
		$inline_style .= '.mb2-portfolio-container .mb2-portfolio-social-shares li a';
		$inline_style .= '{border-color:'.$custom_color1.';}';
		
		
	}
		
	
	//custom inline styles
	$inline_style .= $custom_css;
	
	
	
	
	$doc->addStyleDeclaration($inline_style);
	
	
		
}



































/*----------------------------------------*/
/*	Scripts declaration
/*----------------------------------------*/	
function com_mb2portfolio_scripts($jq,$easing_script,$lightbox,$pp_script,$nivo_l_script,$flexslider,$filter,$carousel_script,$isotope_script,$slider_type){	
	
	$doc = JFactory::getDocument();	
	//$app = JFactory::getApplication();
	//$tmpl = $app->getTemplate();
	

	//java script frameworks
	$jq == 1 ? JHtml::_('jquery.framework', true, true) : '';	
	JHtml::_('bootstrap.framework');	
	
		
		
		
				
				
	//lightbox scripts		
	if($lightbox == 'prettyphoto' && $pp_script == 1 && !com_mb2portfolio_check_template()){
		$doc->addStyleSheet(JURI::base(true).'/media/com_mb2portfolio/css/prettyPhoto/css/prettyPhoto.css');						
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/jquery.prettyPhoto.js');		
	}		
	elseif($lightbox == 'nivo' && $nivo_l_script == 1){
		$doc->addStyleSheet(JURI::base(true).'/media/com_mb2portfolio/css/nivo-lightbox/nivo-lightbox.css');
		$doc->addStyleSheet(JURI::base(true).'/media/com_mb2portfolio/css/nivo-lightbox/themes/default/default.css');		
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/nivo-lightbox.min.js');					
	}	
	
	
	
	
	
	
			
	//flexslider			
	if($flexslider == 1 && $slider_type == 2 && !com_mb2portfolio_check_template()){
		$doc->addStyleSheet(JURI::base(true) .'/media/com_mb2portfolio/css/flexslider/flexslider.css');	
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/jquery.flexslider-min.js');		
	}	
		
		
		
		
		
		
		
	//easing plugin
	if($easing_script == 1 && !com_mb2portfolio_check_template()){
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/jquery.easing.1.3.js');
	}
		
		
		
		
		
		
		
		
	//isotope portfolio
	if($filter == 'isotope' && $isotope_script == 1 && !com_mb2portfolio_check_template()){	
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/jquery.isotope.min.js');
	}
	
	
	
	
	
	
	
	
	//carousel
	if($carousel_script == 1 && !com_mb2portfolio_check_template()){		
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/jquery.carouFredSel-6.2.1-packed.js');
		$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/jquery.touchSwipe.min.js');		
	}
	
	
	
	
	
	
	
	//custom component scripts
	$doc->addScript(JURI::base(true) .'/media/com_mb2portfolio/js/com_mb2portfolio.js');
	
	
	
	
		
	
		
}














/*----------------------------------------*/
/*	Carousel function
/*----------------------------------------*/
function com_mb2portfolio_carousel($id,$item_margin_lr,$carousel,$cols,$direct_nav,$control_nav,$nav_top_position,$auto,$min_item,$scroll,$pause_time,$swipe){
	
	
	$doc = JFactory::getDocument();	
	//$id = uniqid();
	
	
	
	if($carousel){
				
		
		//carousel direct nav
		if($direct_nav == 1){
			$prev = 'prev: \'#mb2-portfolio-prev-'.$id.'\',';
			$next = 'next: \'#mb2-portfolio-next-'.$id.'\',';				
		}
		else{
			$prev = '';
			$next = '';
		}			
			
		//carousel control nav
		$control_nav == 1 ? $pager = 'pagination: \'#mb2-portfolio-pager-'.$id.'\',' : $pager = '';
		
		
		
		//carousel navigation
		if($direct_nav == 1 || $control_nav == 1){ ?>
            <div class="mb2-portfolio-carousel-nav">
                <div class="mb2-portfolio-carousel-nav-inner">          
                    <?php if($direct_nav == 1){?>
                        <a id="mb2-portfolio-prev-<?php echo $id; ?>" class="prev" href="#"><i class="fa fa-angle-left"></i></a>
                        <a id="mb2-portfolio-next-<?php echo $id; ?>" class="next" href="#"><i class="fa fa-angle-right"></i></a>				
                    <?php } ?>                               
                    <?php if($control_nav == 1){?>
                        <div id="mb2-portfolio-pager-<?php echo $id; ?>" class="pager"></div><!-- end .mb2-portfolio-pager -->					
                    <?php } ?>                
                </div><!-- end .mb2-portfolio-carousel-nav-inner -->	
            </div><!-- end .mb2-portfolio-carousel-nav -->	
    	<?php
		
			
			
		//navigation position style			
		if($direct_nav == 1){
			$doc->addStyleDeclaration('.mb2-portfolio-container .mb2-portfolio-carousel-nav{top:' . $nav_top_position . 'px;right:' . $item_margin_lr . '%;}');	
		}				
			
		if($direct_nav == 0 && $control_nav == 1){				
			$doc->addStyleDeclaration('.mb2-portfolio-container .mb2-portfolio-carousel-nav .pager{right:' . $item_margin_lr . '%;}');					
		}
			
			
			
		}		
		
		
	}	
	
	
	
	
	
}





















/*----------------------------------------*/
/*	Custom word limit
/*----------------------------------------*/	
function com_mb2portfolio_word_limit($string='',$word_limit=999,$end_char='...'){	
		
		
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
		
		echo ''.$new_string.''.$is_end_char.'';		
		
	}
	
	else{		
		echo $string;		
	}
}







/*----------------------------------------*/
/*	Portfolio page layout
/*----------------------------------------*/	
function com_mb2portfolio_layout($item_margin_b,$item_margin_lr,$cols,$item_layout,$item_media_width,$single_item_layout,$single_media_width,$module_id=null){	
	
	
	$doc = JFactory::getDocument();
	$app = JFactory::getApplication('site');
	
	//detecting active variables
	$option   = $app->input->getCmd('option', '');
	$view     = $app->input->getCmd('view', '');
	//$layout   = $app->input->getCmd('layout', '');
	//$task     = $app->input->getCmd('task', '');
	//$itemid   = $app->input->getCmd('Itemid', '');
	
	$inline_style = '';	
		
	
	!empty($module_id) ? $portfolio_container = '.mb2-portfolio-module-' .$module_id : $portfolio_container = '.mb2-portfolio-container';
	
	
	
	//----------------carousel styles
	$c_item_width = (100/$cols);
	$porfolio_container_c = $portfolio_container .'.is-carousel';
	$porfolio_related_container_c = $portfolio_container .' .mb2-portfolio-related-items.is-carousel';
	
	//calculate margin left and right for carousel items
	if($item_margin_lr == 0){
		$c_item_margin_lr = 0;	
	}
	elseif($item_margin_lr > 0 && $item_margin_lr < 1.1){
		$c_item_margin_lr = 10;		
	}
	elseif($item_margin_lr > 1.1 && $item_margin_lr < 1.9){
		$c_item_margin_lr = 15;
	}
	elseif($item_margin_lr > 1.9){
		$c_item_margin_lr = 23;
	}
	
	
	$inline_style .= $porfolio_container_c .'{margin-left:-' . $c_item_margin_lr . 'px;}';
	
	$inline_style .= $porfolio_container_c .' .mb2-portfolio-col';
	$inline_style .= '{width:' . $c_item_width . '%;margin-left:0;margin-right:0;}';
	
	$inline_style .= $porfolio_container_c .' .mb2-portfolio-item-inner';
	$inline_style .= '{margin-left:' . $c_item_margin_lr . 'px;margin-right:' . $c_item_margin_lr . 'px;}';
	
	$inline_style .= $porfolio_container_c .' .mb2-portfolio-carousel-nav';
	$inline_style .= '{right:'. $c_item_margin_lr .'px;}';
	
	$inline_style .= $porfolio_container_c .' .mb2-portfolio-carousel-nav .pager';
	$inline_style .= '{right:'. ($c_item_margin_lr + 42) .'px;}';	
	//---------------------------
		
	
	
	
	//portfolio container style
	$container_width = (100 + (2*$item_margin_lr));
	$container_margin_left = -$item_margin_lr;	
	
	$inline_style .= $portfolio_container .'{width:' . $container_width . '%;margin-left:' . $container_margin_left . '%;}';		
	
			
	
	
	//portfolio item core style
	$item_width = ((100/$cols)-((2*$item_margin_lr)+0.05));
	
	$inline_style .= $portfolio_container .' .mb2-portfolio-col';
	$inline_style .= '{width:' . $item_width . '%;margin-left:' . $item_margin_lr . '%;margin-right:' . $item_margin_lr . '%;margin-bottom:' . $item_margin_b . 'px;}';
	
	
	
	
	
	//style for portfolio item elements (media and description)		
	$item_layout === 'desc-media' ? $item_parts_float = 'right' : $item_parts_float = 'left';
	
		
	if($item_layout === 'media-desc' || $item_layout === 'desc-media'){		
		$item_media_width = $item_media_width;		
		$item_desc_width = 100 - $item_media_width;			
	}
	else{		
		$item_media_width = 100;
		$item_desc_width = 100;		
	}
	
	
	
	$inline_style .= $portfolio_container .' .mb2-portfolio-media';
	$inline_style .= '{width:' . $item_media_width . '%;float:' . $item_parts_float . ';}';
	
	$inline_style .= $portfolio_container .' .mb2-portfolio-details';
	$inline_style .= '{width:' . $item_desc_width . '%;float:' . $item_parts_float . ';}';
	
	
	
	
	
	
	//style for other elements
	$inline_style .= $portfolio_container .' .mb2-portfolio-filter-nav,';	
	$inline_style .= $portfolio_container .' .pagination';
	$inline_style .= '{margin-left:'. $item_margin_lr .'%!important;margin-right:'. $item_margin_lr .'%!important;}';
	
		
	
	
	
	
	
	/*----------------------------------------*/
	/*	Single project page
	/*----------------------------------------*/	
	if($option === 'com_mb2portfolio' && $view === 'project'){
				
		
		$single_item_layout === 'desc-media' ? $item_parts_float = 'right' : $item_parts_float = 'left';	
		
		//calculate width of media and details containers
		if($single_item_layout === 'media-desc' || $single_item_layout === 'desc-media'){					
			$cal_single_media_width = ($single_media_width-((2*$item_margin_lr)+0.05));		
			$cal_single_desc_width = ((100 - $single_media_width)-((2*$item_margin_lr)+0.05));			
		}
		else{			
			$cal_single_media_width = (100-(2*$item_margin_lr));
			$cal_single_desc_width = (100-(2*$item_margin_lr));			
		}
		
		
		//style for media and description
		$inline_style .= $portfolio_container .' .mb2-portfolio-single-item-media,';	
		$inline_style .= $portfolio_container .' .mb2-portfolio-single-item-deatils';
		$inline_style .= '{margin-left:'. $item_margin_lr .'%;margin-right:'. $item_margin_lr .'%;}';		
		
		//style for media
		$inline_style .= $portfolio_container .' .mb2-portfolio-single-item-media';	
		$inline_style .= '{width:' . $cal_single_media_width . '%;float:' . $item_parts_float . ';}';
		
		//style for description
		$inline_style .= $portfolio_container .' .mb2-portfolio-single-item-deatils';
		$inline_style .= '{width:' . $cal_single_desc_width . '%;float:' . $item_parts_float . ';}';
		
		
		
		//style for other elements
		$inline_style .= $portfolio_container .' .mb2-portfolio-related-items-heading';
		$inline_style .= '{margin-left:'. $item_margin_lr .'%;margin-right:'. $item_margin_lr .'%;}';
		
		
		
		//related carousel layout-----------------
		//$inline_style .= $porfolio_related_container_c .'{margin-left:-' . $c_item_margin_lr . 'px;}';
	
		$inline_style .= $porfolio_related_container_c .' .mb2-portfolio-col';
		$inline_style .= '{width:' . $c_item_width . '%;margin-left:0;margin-right:0;}';
		
		$inline_style .= $porfolio_related_container_c .' .mb2-portfolio-item-inner';
		$inline_style .= '{margin-left:' . $c_item_margin_lr . 'px;margin-right:' . $c_item_margin_lr . 'px;}';
		
		$inline_style .= $porfolio_related_container_c .' .mb2-portfolio-carousel-nav';
		$inline_style .= '{right:'. $c_item_margin_lr .'px;}';
		
		$inline_style .= $porfolio_related_container_c .' .mb2-portfolio-carousel-nav .pager';
		$inline_style .= '{right:'. ($c_item_margin_lr + 42) .'px;}';	
		//---------------------------
			
		
				
		
		
	}
	
	
	
	
		
		
		
	//output portfolio layout styles	
	$doc->addStyleDeclaration($inline_style);
		
	
}

























/*----------------------------------------*/
/*	Thumbnail url
/*----------------------------------------*/	
function com_mb2portfolio_thumbnail_url($crop=1,$url='',$width=100,$height=100,$quality=100,$echo=0){	
		
	$output = '';	

	
	//thumbnail script	
	if($url !=''){
		
		
		
		if($crop == 1){			
	
					
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
			$resizeObj = new resize($url);
				
			// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
			$resizeObj -> resizeImage($width, $height, 'crop'); 
			
			
			//check if thumbnail folder exist. If not creat it
			if(!is_dir(JPATH_CACHE.'/com_mb2portfolio')){
				jimport('joomla.filesystem.folder');
				JFolder::create( JPATH_CACHE.'/com_mb2portfolio');
			}	
			
			
			//get thumbnail name
			$thumb_name = com_mb2portfolio_thumbnail_name($url);	
				
				
			// *** 3) Save image
			$resizeObj -> saveImage(JPATH_CACHE .'/com_mb2portfolio/'.$thumb_name.'_'.$width.'x'.$height.''.$format, $quality);							
			
			
			//define thumbnail url
			$output .= JURI::base(true) .'/cache/com_mb2portfolio/'.$thumb_name.'_'.$width.'x'.$height.''.$format;		
		
		
		}
		else{
			
			$output .= JURI::base(true) .'/'. $url;		
			
		}	
		
			
	
	}	
	
	
	if($echo == 1){
		echo $output;
	}
	else{
		return $output;	
	}
	
	
	
}

















/*----------------------------------------*/
/*	Thumbnail name
/*----------------------------------------*/	
function com_mb2portfolio_thumbnail_name($url,$show_format=0){	
	
	//get file name
	$img_parts = pathinfo($url);
					
	if(!isset($img_parts['filename'])){
		$img_parts['filename'] = substr($img_parts['basename'], 0, strrpos($img_parts['basename'], '.'));
	} 
	
	
	
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
	
	
	if($show_format == 1){
		return $img_parts['filename'].$format;
	}
	else{
		return $img_parts['filename'];
	}
		
						
	
		
	
}













/*----------------------------------------*/
/*	Slider images
/*----------------------------------------*/	
function com_mb2portfolio_thumbnail_slider($images=array(),$options=array(),$f_image='',$f_image_on=1,$width=100,$height=100,$crop_images=1,$alt_text=''){
	
	
	$doc = JFactory::getDocument();
	$output = '';
	$uniqid = uniqid();
	$f_image_on == 0 ? $start = 1 : $start = 0;
	
	
	
	//start slider container
	$output .= '<div class="mb2-portfolio-img align-none" style="width:'.$width.'px;max-width:100%;height:auto;">';
	
	
	
	//bootstrap carousel
	if($options['slider_type'] == 1){		
		
		$output .= '<div id="mb2-portfolio-bs-carousel-'.$uniqid.'" class="mb2portfolio-bs-carousel carousel slide">';			
			$output .= '<ol class="carousel-indicators">';
			
			
			
				$x=0;
				foreach($images as $indicator){					
					$x++;				
					
					$slide_indicator = $indicator[0];						
						
					if(!$slide_indicator){
						continue;
					}				
					
					if($x>$start){
						$x == ($start+1) ? $class_active = ' class="active"' : $class_active = '';
									
						$output .= '<li'.$class_active.' data-target="mb2-portfolio-bs-carousel-'.$uniqid.'" data-slide-to="'.$x.'"></li>';	
					}			
				
				}
		
			$output .= '</ol>';		
			$output .= '<div class="carousel-inner">';
				
				$y=0;
				foreach($images as $slide){
					
					$y++;
					
					$image_url = $slide[0];
					$image_alt_text = $slide[1];
					$image_caption = $slide[2];	
						
						
					if(!$image_url){
						continue;
					}					
					
					
					if($y>$start){			
					
						$y == ($start+1) ? $class_active1 = 'active ' : $class_active1 = '';			
									
												
						if($image_url !=''){									
							$slide_thumbnail_url = com_mb2portfolio_thumbnail_url($crop_images,$slide[0],$width,$height); 			
							$output .= '<div class="'.$class_active1.'item">';						
							$output .='<img src="'.$slide_thumbnail_url.'" width="'.$width.'" height="'.$height.'" alt="'.$slide[1].'" />';	
							
							
							if($options['caption'] == 1 && ($image_alt_text !='' || $image_caption !='')){
								
								$output .= '<div class="carousel-caption">';
								
									$image_alt_text !='' ? $output .='<h4>'. $image_alt_text .'</h4>' : $output .='';
									$image_caption !='' ? $output .='<p>'. $image_caption .'</p>' : $output .='';
														
								$output .= '</div>';				
								
							}							
											
							$output .= '</div>';
											
						}			
					
					}	
				
				}
			
			$output .= '</div>';	
			
			
			//carousel direct navigation
			$output .= '<a class="carousel-control left" href="#mb2-portfolio-bs-carousel-'.$uniqid.'" data-slide="prev">&lsaquo;</a>';
			$output .= '<a class="carousel-control right" href="#mb2-portfolio-bs-carousel-'.$uniqid.'" data-slide="next">&rsaquo;</a>';
			
			
		
		$output .= '</div>';
		
		
			
	
	}
	
	//flexslider
	elseif($options['slider_type'] == 2){
		
		
		
		//slider data
		$data_slideshow_speed =  ' data-slideshow_speed="'. $options['slideshow_speed'] .'"';
		$data_animation_speed =  ' data-animation_speed="'. $options['animation_speed'] .'"';
		$data_control_nav =  ' data-control_nav="'. $options['control_nav'] .'"';
		$data_direct_nav =  ' data-direct_nav="'. $options['direct_nav'] .'"';
		$data_slideshow =  ' data-slideshow="'. $options['slideshow'] .'"';
				
		$slider_data = $data_slideshow_speed . $data_animation_speed . $data_control_nav . $data_direct_nav . $data_slideshow;
		
		
		//start flexslider container
		$output .= '<div class="mb2-portfolio-flexslider flexslider"'. $slider_data .'><ul class="slides">';	
		
		
		$z=0;
		
		//get slidet images						
		foreach($images as $slider_image){				
			
			$z++;			
			
			$image_url = $slider_image[0];
			$image_alt_text = $slider_image[1];
			$image_caption = $slider_image[2];	
				
				
			if(!$image_url){
				continue;
			}				
									
			if($image_url !='' && $z > $start){									
				$slide_thumbnail_url = com_mb2portfolio_thumbnail_url($crop_images,$slider_image[0],$width,$height); 				
				
				
				
				$output .='<li>';		
				
					$output .='<img src="'.$slide_thumbnail_url.'" width="'.$width.'" height="'.$height.'" alt="'.$slider_image[1].'" />';	
				
					
					//flexslider cation
					if($options['caption'] == 1 && ($image_alt_text !='' || $image_caption !='')){
							
						$output .= '<div class="flex-caption">';
							
							$image_alt_text !='' ? $output .='<h4>'. $image_alt_text .'</h4>' : $output .='';
							$image_caption !='' ? $output .='<p>'. $image_caption .'</p>' : $output .='';
													
						$output .= '</div>';
							
							
					}
									
				
				$output .='</li>';
				
								
			}		
			
						
		}
			
		$output .= '</ul></div>';	
		
		
	}
	
	
	$output .= '</div>';	
	//emd slider	
	
	
	
	return $output;
	
	

}







/*----------------------------------------*/
/*	Thumbnail video
/*----------------------------------------*/	
function com_mb2portfolio_thumbnail_video($video_id='',$width=100,$height=100,$align='none',$echo=0){
	
	$output = '';	
		
	
	//check if video is Youtube or Vimeo
	if(preg_match('/^[0-9]+$/', $video_id)){			
		$video_url = '//player.vimeo.com/video/'. $video_id;			
	}
	else{
		$video_url = '//www.youtube.com/embed/'. $video_id;		
	}		
	
	
	$output .= '<div class="mb2-portfolio-img align-'.$align.'" style="width:'.$width.'px;max-width:100%;height:auto;"><div class="video-container">';
		
	$output .= '<iframe width="'.$width.'" height="'.$height.'" src="'.$video_url.'?wmode=transparent" frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>';	
	
	$output .= '</div></div>';	
	
	
	
	if($echo == 1){
		echo $output;
	}
	else{
		return $output;
	}
		
	
	
}













/*----------------------------------------*/
/*	Thumbnail multiple images
/*----------------------------------------*/	
function com_mb2portfolio_thumbnail_multiple_images($images=array(),$intro_image=1,$crop_images=1,$width=100,$height=100,$lightbox=0,$margin='0 10px 10px 0',$title='',$class='',$rel='',$data_lightbox='',$link_title='',$lazy=1,$echo=0){
	
	$output = '';	
	$w=0;	
	$intro_image == 0 ? $start = 1 : $start = 0;
	$lazy == 1 ? $img_src_attr = 'data-original' : $img_src_attr = 'src';
	$img_cls = '';		
	if($lazy == 1){$img_cls .= 'js-load';}
	
	
	
	//foreach project images
	foreach($images as $image){	
	
		$w++;
		
		//define vars
		$image_url = $image[0];
		$image_alt_text = $image[1];
		$image_caption = $image[2];
		
		
		
		if(!$image_url){
           continue;
        }	
		
		
		if($w>$start){		
			
			$image[1] !='' ? $link_title = ' title="'.$image[1].'"' : $link_title = ' title="'.$title.'"';				
				
			if($image_url !=''){	
								
				$thumb_url = com_mb2portfolio_thumbnail_url($crop_images,$image[0],$width,$height);
				
				$lazy == 1 ? $img_data = ' data-src="'.$thumb_url.'"' : $img_data = '';
				$lazy == 1 ? $img_src = '' : $img_src = ' src="'.$thumb_url.'"';
					
				//image caption					
				$caption = $image[2] !='' ? '<p class="mb2-portfolio-img-caption">'.$image[2].'</p>' : '';				
					
				
				$output .= '<div class="mb2-portfolio-img" style="width:'.$width.'px;max-width:100%;height:auto;margin:'.$margin.';">';		
				$output .= '<img class="'.$img_cls.'"'.$img_data.$img_src.' width="'.$width.'" height="'.$height.'" alt="'.$image[1].'" />';	
				if($lightbox == 1){
					$output .= '<div class="mb2-portfolio-mark"><div class="link">';	
					$output .= '<a href="'.$image_url.'"'.$class.$rel.$data_lightbox.$link_title.'><i class="fa fa-expand"></i></a>';	
					$output .= '</div></div>';	
				}
				$output .= $caption.'</div>';					
									
						
			}		
			
			
		}	
	
	}	
	
	
	
	if($echo == 1){
		echo $output;		
	}
	else{
		return $output;	
	}	
	
	
	
}