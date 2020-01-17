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
abstract class JHtmlMedia
{
	
	
	
	
	/**
	 * Method to generate project skills list
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the create item media
	 */
	public static function item_media($item, $params, $attribs = array())
	{
		
			
		$output = '';
		
		
		if($attribs['mode'] == 'project')
		{			
			$output .= JHtmlMedia::single_media($item, $params, $attribs);			
		}
		else 
		{		
			$output .= JHtmlMedia::items_media($item, $params, $attribs);	
		}
		
		return $output;
		
		
	}
	
	
	
	
	
	
	
	
	/**
	 * Method to generate project media on single project page
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the project images or slider or video
	 */
	public static function single_media($item, $params, $attribs)
	{
		
		// Basic variables
		$output = '';
		$uniqid=uniqid();		
		$videoarr = json_decode($item->video);
		$imgarr = json_decode($item->images);
		$imgarr->image_on == 0 ? $start = 1 : $start = 0;
		$pimages =  JHtmlMedia::item_images($item, $params, $attribs);	
			
		
		// Single prject video		
		if($videoarr->video_id !='')
		{		
			
			if(preg_match('/^[0-9]+$/', $videoarr->video_id))
			{			
				$video_url = '//player.vimeo.com/video/' . $videoarr->video_id;			
			}
			else
			{
				$video_url = '//www.youtube.com/embed/' . $videoarr->video_id;		
			}				
			
			$output .= '<div class="mb2-portfolio-img" style="width:' . $imgarr->image_width . 'px;max-width:100%;height:auto;"><div class="video-container">';				
			$output .= '<iframe width="' . $imgarr->image_width . '" height="' . $imgarr->image_height . '" src="'.$video_url.'?wmode=transparent" frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>';				
			$output .= '</div></div>';		
			
		}
		
		
		
		//-------------------------------------------------------------------------
		
		
		
		// Single project multiple images		
		elseif($imgarr->multiple == 1)
		{	
			
			$w=0;
			$cls='';
			$rel='';
			$data='';			
			
			foreach ($pimages as $image)
			{				
				
				$w++;
		
				// Define variables
				$mimgurl = $image[0];
				$mimgaltext = $image[1];
				$mimgcaption = $image[2];			
				
				if(!$mimgurl)
				{
                  continue;
              	}				
				
				if($w>$start)
				{					
					// Get thumbnail url			
					$thumburl = JHtmlMedia::crop_image($mimgurl, $item, $params, 100, $attribs);
					
					$attribs = array(
						'url'=>$mimgurl, 
						'turl'=>$thumburl, 
						'vurl'=>'', 
						'alttext'=>$mimgaltext, 
						'caption'=>$mimgcaption, 
						'img-style'=>'margin:' . $imgarr->multiple_margin . ';', 
						'mode'=>'project',
						'gal'=>1,
						'galid'=>$uniqid
					);					
										
					$output .= JHtmlMedia::image_html($item, $params, $attribs);						
				}			
			} // End foreach	
			
		}
		
		
		
		//-------------------------------------------------------------------------
		
		
		
		// Slider image
		elseif($imgarr->slider == 1 && $imgarr->multiple == 0)
		{			
			
			$output .= JHtmlMedia::slider_html($item, $params, $attribs);
		
		}		
		
		
		
		//-------------------------------------------------------------------------
		
		
		
		// Normal image		
		else
		{					
			
			// Get thumbnail url			
			$thumburl = JHtmlMedia::crop_image($imgarr->featured_image, $item, $params, 100, $attribs);
					
			$attribs = array(
				'url'=>$imgarr->featured_image, 
				'turl'=>$thumburl, 
				'vurl'=>'', 
				'alttext'=>$imgarr->featured_image_alt, 
				'caption'=>$imgarr->featured_image_caption, 
				'img-style'=>'', 
				'mode'=>'project',
				'gal'=>0,
				'galid'=>''
			);					
										
			$output .= JHtmlMedia::image_html($item, $params, $attribs);
						
		}	
		
		
		
		return $output;
		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate projet thumbnails on portfolio page
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the project images or slider or video on portfolio page
	 */
	public static function items_media($item, $params, $attribs)
	{
			
		// Basic variables
		$output = '';
		$output = '';
		$uniqid=$attribs['uniqid'];		
		$videoarr = json_decode($item->video);
		$imgarr = json_decode($item->images);
		$pimages =  JHtmlMedia::item_images($item, $params, $attribs);	
		
		
		// Get media width and height
		if ($attribs['mode'] == 'project-related')
		{	
			$mw = $params->get('related_thumbnail_width', 480);
			$mh = $params->get('related_thumbnail_height', 350);		
		}
		elseif ($attribs['mode'] == 'skills')
		{
			$mw = $params->get('skills_thumbnail_width', 480);
			$mh = $params->get('skills_thumbnail_height', 350);
		}
		else
		{
			$mw = $params->get('thumbnail_width', 480);
			$mh = $params->get('thumbnail_height', 350);
		}
		
		
		
		// Live video
		if($videoarr->video_id !='' && $params->get('video_embed', 0) == 1)
		{
			if(preg_match('/^[0-9]+$/', $videoarr->video_id))
			{			
				$video_url = '//player.vimeo.com/video/' . $videoarr->video_id;			
			}
			else
			{
				$video_url = '//www.youtube.com/embed/' . $videoarr->video_id;		
			}				
			
			$output .= '<div class="mb2-portfolio-img" style="width:' . $mw . 'px;max-width:100%;height:auto;"><div class="video-container">';				
			$output .= '<iframe width="' . $mw . '" height="' . $mh . '" src="'.$video_url.'?wmode=transparent" frameborder="0" allowfullscreen="" mozallowfullscreen="" webkitallowfullscreen=""></iframe>';				
			$output .= '</div></div>';		
			
		}
		
		// Thumbnail as slider
		elseif($imgarr->slider == 1 && $params->get('projects_slider', 1) == 1)
		{			
			$output .= JHtmlMedia::slider_html($item, $params, $attribs);			
		}
		
		// Normal images
		else
		{			
			
			// Get thumbnail url			
			$thumburl = JHtmlMedia::crop_image($imgarr->featured_image, $item, $params, 100, $attribs);
								
			$attribs = array(
				'url'=>$imgarr->featured_image, 
				'turl'=>$thumburl, 
				'alttext'=>$imgarr->featured_image_alt, 
				'caption'=>$imgarr->featured_image_caption, 
				'img-style'=>'', 
				'mode'=>'projects',
				'gal'=>1,
				'galid'=>$uniqid
			);					
										
			$output .= JHtmlMedia::image_html($item, $params, $attribs);
					
		}	
		
		return $output;	
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate prtfolio image
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the portfolio image
	 */	
	public static function image_html($item, $params, $attribs)
	{
			
		
		// Basic variables		
		$output = '';
		$videoarr = json_decode($item->video);
		$imgarr = json_decode($item->images);
		$url = $attribs['url'];
		
		
		// Get item url
		$skillarr = array($item->skill_1, $item->skill_2, $item->skill_3, $item->skill_4, $item->skill_5);
		$urlitem = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($item->id, $skillarr, $language = 0));
		
		
		// Get width of image
		if ($attribs['mode'] == 'project')
		{
			$imgw = $imgarr->image_width;
			$imgh = $imgarr->image_height;
		}
		elseif ($attribs['mode'] == 'project-related')
		{	
			$imgw = $params->get('related_thumbnail_width', 480);
			$imgh = $params->get('related_thumbnail_height', 350);			
			
		}
		else
		{
			$imgw = $params->get('thumbnail_width', 480);
			$imgh = $params->get('thumbnail_height', 350);
		}
				
				
		// Add nivo lightbox class and data for image link
		if ($attribs['mode'] == 'project')
		{
			($params->get('lightbox', 1) == 1 && $imgarr->multiple_lightbox == 1) ? $lcls = ' class="mb2-portfolio-link"' : $lcls = '';		
			($params->get('lightbox', 1) == 1 && $attribs['gal'] == 1  && $imgarr->multiple_lightbox == 1) ? 
			$ldata = ' data-lightbox-gallery="mb2-portfolio-gal' . $attribs['galid'] . '"' : $ldata = '';
		}
		else
		{
			($params->get('lightbox', 1) == 1 && $imgarr->image_link != 2) ? $lcls = ' class="mb2-portfolio-link"' : $lcls = '';		
			($params->get('lightbox', 1) == 1 && $attribs['gal'] == 1  && $imgarr->image_link != 2) ? 
			$ldata = ' data-lightbox-gallery="mb2-portfolio-gal' . $attribs['galid'] . '"' : $ldata = '';
			
		}
			
				
				
		// Add hover class for image container for js hover effect
		$params->get('img_hover_effect', 1) == 1 ? $ccls = ' h-effect' : $ccls = '';
		
		
		// Add attributes for link and image
		$attribs['alttext'] !='' ? $ltitle = ' title="' . $attribs['alttext'] . '"' : $ltitle = ' title="' . $item->title . '"';
		$attribs['alttext'] !='' ? $alt = $attribs['alttext'] : $alt = $item->title;
		if($attribs['mode'] == 'project')
		{
			$attribs['caption'] !='' ? $cpt = '<p class="mb2-portfolio-img-caption">' . $attribs['caption'] . '</p>' : $cpt = '';				
		}
		else
		{
			($attribs['caption'] !='' && $params->get('projects_item_caption', 0) == 1) ? $cpt = '<p class="mb2-portfolio-img-caption">' . $attribs['caption'] . '</p>' : $cpt = '';
			
		}		
		
				
		// Define image html				
		//$img = '<img src="' . $attribs['turl'] . '"  width="' . $imgw . '" height="' . $imgh . '" alt="' . $alt . '" />';
                 $img = '<img src="' . $attribs['url'] . '"  width="' . $imgw . '" height="' . $imgh . '" alt="' . $alt . '" />';
				
		
		$output .= '<div class="mb2-portfolio-img'. $ccls .'" style="width:' . $imgw . 'px;max-width:100%;' . $attribs['img-style'] . '">';
				
		// Image on single project page		
		if ($attribs['mode'] == 'project')
		{							
			if($imgarr->multiple_lightbox == 1)
			{							
				if($params->get('img_hover_effect', 1) == 1)
				{					
					
					$output .= JHtmlMedia::image_html_el($item, $params, $attribs);	
					$output .= $img;
													
				}
				else
				{					
					$output .= '<a href="' . $attribs['url'] . '"'. $lcls . $ldata . $ltitle .'>' . $img . '</a>';								
				}			
			}
			else
			{				
				$output .= $img;				
			}					
		}
		
		// Imgae on projects page and related projects
		elseif($attribs['mode'] == 'projects' || $attribs['mode'] == 'project-related')
		{			
			
			if($imgarr->image_link == 1 || $imgarr->image_link == 2 || $imgarr->image_link == 3)
			{							
				if($params->get('img_hover_effect', 1) == 1)
				{					
					
					$output .= JHtmlMedia::image_html_el($item, $params, $attribs);	
					$output .= $img;					
									
				}
				else
				{		
										
					$imgarr->image_link == 2 ? $url = $urlitem : $url = $url;	
					$output .= '<a href="' . $url . '"'. $lcls . $ldata . $ltitle .'>' . $img . '</a>';							
													
				}			
			}
			else
			{				
				$output .= $img;				
			}
						
		}		
		
		$output .= $cpt . '</div>';			
			
		return $output;	
		
		
	}
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate elements of portfolio image
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the portfolio image elements
	 */	
	public static function image_html_el($item, $params, $attribs)
	{
		
		
		// Basic variables
		$output = '';
		$videoarr = json_decode($item->video);
		$imgarr = json_decode($item->images);
		$url = $attribs['url'];
		$vurl = '';	
		$lcls = '';
		$ldata = '';	
		
		
		// Get video url
		if($videoarr->video_id !='')
		{
			preg_match('/^[0-9]+$/', $videoarr->video_id) ? 
			$vurl = 'http://vimeo.com/' . $videoarr->video_id : 
			$vurl = 'http://www.youtube.com/watch?v=' . $videoarr->video_id;			
		}
		
		
		// Get item url
		$skillarr = array($item->skill_1, $item->skill_2, $item->skill_3, $item->skill_4, $item->skill_5);
		$urlitem = JRoute::_(Mb2portfolioHelperRoute::getProjectRoute($item->id, $skillarr, $language = 0));
		
		
		// Add nivo lightbox class and data for image link
		if($params->get('lightbox', 1) == 1)
		{			
			if($attribs['mode'] != 'project')
			{
				$imgarr->image_link != 2 ? $lcls .= ' mb2-portfolio-link' : $lcls .= '';		
				($imgarr->image_link != 2 && $attribs['gal'] == 1) ? $ldata .= ' data-lightbox-gallery="mb2-portfolio-gal' . $attribs['galid'] . '"' : $ldata .= '';
			}
			else
			{
				$lcls = ' mb2-portfolio-link';		
				$attribs['gal'] == 1 ? $ldata = ' data-lightbox-gallery="mb2-portfolio-gal' . $attribs['galid'] . '"' : $ldata = '';
			}
		}			
				
		
		// Add attributes for link and image
		$attribs['alttext'] !='' ? $ltitle = ' title="' . $attribs['alttext'] . '"' : $ltitle = ' title="' . $item->title . '"';
		
		
		// Define image elements
		$mexpand = '<a href="' . $url . '" class="mexpand'. $lcls .'"' . $ldata . $ltitle .'><i class="mb2portfolio-fa mb2portfolio-fa-expand"></i></a>';
		$mvideo = '<a href="' . $vurl . '" class="mvideo'. $lcls .'"' . $ldata . $ltitle .'><i class="mb2portfolio-fa mb2portfolio-fa-play"></i></a>';
		$markev = ($videoarr->video_id !='' && $params->get('video_embed',0) == 0) ? $mvideo : $mexpand;
		$murl = '<a href="' . $urlitem . '" class="murl" title="' . $item->title . '"><i class="mb2portfolio-fa mb2portfolio-fa-link"></i></a>';
		
		
		// Case when is single project page
		// Allow to use link to big image only
		// When video id is not empty show video embed
		if ($attribs['mode'] == 'project')
		{			
			$output .= '<div class="mb2-portfolio-mark"><div class="link">' . $mexpand . '</div></div>';			
		}
		else
		{
			
			// Change class if mark div have more than one link
			$imgarr->image_link == 3 ? $mcls = 'links' : $mcls = 'link';				
										
			$output .= '<div class="mb2-portfolio-mark">';
			
			$output .= '<div class="'. $mcls .'">';
					
			if($imgarr->image_link == 1)
			{						
				$output .= $markev;										
			}
			elseif($imgarr->image_link == 2)
			{
				$output .= $murl;
			}
			elseif($imgarr->image_link == 3)
			{
				$output .= $markev . $murl;
			}	
								
			$output .= '</div>';	
			
			$output .= '</div>';
		}
		
		
		return $output;	
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate project slider
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  The HTML markup for the project slider images
	 */
	public static function slider_html($item, $params, $attribs)
	{
		
		
		$output = '';
		$uniqid=uniqid();		
		$videoarr = json_decode($item->video);
		$imgarr = json_decode($item->images);
		($attribs['mode'] == 'project' && $imgarr->image_on == 0) ? $start = 1 : $start = 0;
		$simages =  JHtmlMedia::item_images($item, $params, $attribs);
		
		
		// Get width of slider
		if ($attribs['mode'] == 'project')
		{
			$sw = $imgarr->image_width;
			$sh = $imgarr->image_height;
		}
		elseif ($attribs['mode'] == 'project-related')
		{	
			$sw = $params->get('related_thumbnail_width', 480);
			$sh = $params->get('related_thumbnail_height', 350);			
			
		}
		else
		{
			$sw = $params->get('thumbnail_width', 480);
			$sh = $params->get('thumbnail_height', 350);
		}		
		
		
		$output .= '<div id="mb2-portfolio-bs-carousel-' . $uniqid . '" class="mb2portfolio-bs-carousel carousel slide">';			
		$output .= '<ol class="carousel-indicators">';			
		$x=0;
			
		foreach ($simages as $indicator)
		{
											
			$x++;			
				
			$slide_indicator = $indicator[0];						
					
			if(!$slide_indicator)
			{
				continue;
			}				
			
			if($x>$start)
			{
				$x == ($start+1) ? $class_active = ' class="active"' : $class_active = '';									
				$output .= '<li' . $class_active . ' data-target="mb2-portfolio-bs-carousel-' . $uniqid . '" data-slide-to="' . $x . '"></li>';	
			}				
			
		} // End foreach		
		
		
		$output .= '</ol>';			
		$output .= '<div class="carousel-inner">';
		
				
		$y=0;
		
		foreach ($simages as $slide)
		{
							
			$y++;
	
			// Define variables
			$imgurl = $slide[0];
			$imgaltext = $slide[1];
			$imgcaption = $slide[2];
			
			
			if(!$imgurl)
			{
				continue;             	
			}				
			
			if($y>$start)
			{										
				
				$y == ($start+1) ? $class_active1 = 'active ' : $class_active1 = '';											
												
				$thumburl = JHtmlMedia::crop_image($imgurl, $item, $params, 100, $attribs);
							
				$output .= '<div class="'.$class_active1.'item">';						
				$output .='<img src="' . $thumburl . '" width="' . $sw . '" height="' . $sh . '" alt="' . $imgaltext . '" />';						
					
					
				// Get slider description				
				$sdesc = '<div class="carousel-caption">';
				$sdesc .= $imgaltext !='' ? '<h4>'. $imgaltext .'</h4>' : '';
				$sdesc .= $imgcaption !='' ? '<p>'. $imgcaption .'</p>' : '';
				$sdesc .= '</div>';
				
						
				if($attribs['mode'] != 'project')
				{														
					($params->get('projects_item_caption', 0) == 1) && ($imgaltext !='' || $imgcaption !='') ? $output .= $sdesc : '';											
				}
				else
				{						
					($imgaltext !='' || $imgcaption !='') ? $output .= $sdesc : '';					
				}							
										
				$output .= '</div>';							
				
			}			
			
		} // End foreach
		
		$output .= '</div>';		
		
		// Carousel direct navigation
		$output .= '<a class="carousel-control left" href="#mb2-portfolio-bs-carousel-' . $uniqid . '" data-slide="prev"><i class="mb2portfolio-fa mb2portfolio-fa-angle-double-left"></i></a>';
		$output .= '<a class="carousel-control right" href="#mb2-portfolio-bs-carousel-' . $uniqid . '" data-slide="next"><i class="mb2portfolio-fa mb2portfolio-fa-angle-double-right"></i></a>';	
		
		$output .= '</div>';
	
	
		return $output;	
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate project images array
	 *
	 * @param   object     $item  	  The item 
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  array  	project images
	 */
	public static function item_images($item, $params, $attribs)
	{
		
		
		// Basic params
		$imgarr = json_decode($item->images);						
							
							
		$item_images = array(
			array($imgarr->featured_image, $imgarr->featured_image_alt, $imgarr->featured_image_caption, 'f_image'),
			array($imgarr->image_1, $imgarr->image_1_alt, $imgarr->image_1_caption, 'image_1'),
			array($imgarr->image_2, $imgarr->image_2_alt, $imgarr->image_2_caption, 'image_2'),
			array($imgarr->image_3, $imgarr->image_3_alt, $imgarr->image_3_caption, 'image_3'),
			array($imgarr->image_4, $imgarr->image_4_alt, $imgarr->image_4_caption, 'image_4'),
			array($imgarr->image_5, $imgarr->image_5_alt, $imgarr->image_5_caption, 'image_5'),
			array($imgarr->image_6, $imgarr->image_6_alt, $imgarr->image_6_caption, 'image_6'),
			array($imgarr->image_7, $imgarr->image_7_alt, $imgarr->image_7_caption, 'image_7'),
			array($imgarr->image_8, $imgarr->image_8_alt, $imgarr->image_8_caption, 'image_8'),
			array($imgarr->image_9, $imgarr->image_9_alt, $imgarr->image_9_caption, 'image_9'),
			array($imgarr->image_10, $imgarr->image_10_alt, $imgarr->image_10_caption, 'image_10')		
		);
		
		
		
		if(!empty($item_images))
		{
			return $item_images;
		}
		else
		{
			return '';	
		}		
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to generate image thumbnail
	 *
	 * @param   string     $url  	  The url of the original image
	 * @param   object     $item  	  The item
	 * @param   JRegistry  $params    The item parameters
	 * @param   array      $attribs   Optional attributes for the link
	 *
	 * @return  string  url of cropped image
	 */	
	public static function crop_image($url, $item, $params, $quality = 100, $attribs){	
		
		
		$output = '';	
		
		
		// Set width and height of image in a different mode
		
		$imgarr = json_decode($item->images);
		
		if ($attribs['mode'] == 'project')
		{
			$cropimg = ($imgarr->crop_images == 1 && $params->get('crop_images', 1) == 1);
			$imgw = $imgarr->image_width;
			$imgh = $imgarr->image_height;
		}
		elseif ($attribs['mode'] == 'project-related')
		{	
			$cropimg = $params->get('crop_images', 1);
			$imgw = $params->get('related_thumbnail_width', 480);
			$imgh = $params->get('related_thumbnail_height', 350);			
			
		}
		elseif ($attribs['mode'] == 'skills')
		{
			$cropimg = $params->get('crop_images', 1);
			$imgw = $params->get('skills_thumbnail_width', 480);
			$imgh = $params->get('skills_thumbnail_height', 350);
		}
		else
		{
			$cropimg = $params->get('crop_images', 1);
			$imgw = $params->get('thumbnail_width', 480);
			$imgh = $params->get('thumbnail_height', 350);
		}		
		
		
		
		if($url !='' && $cropimg){			
		
						
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
			
			// Get class to resize image
			
			if(!class_exists('resize'))
			{
				require_once JPATH_SITE . '/components/com_mb2portfolio/libs/image_resize_class.php';
			} 			
			
			$resizeObj = new resize($url);
				
			// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
			$resizeObj -> resizeImage($imgw, $imgh, 'crop'); 
			
			
			//check if thumbnail folder exist. If not creat it
			if(!is_dir(JPATH_CACHE . '/com_mb2portfolio')){
				jimport('joomla.filesystem.folder');
				JFolder::create( JPATH_CACHE . '/com_mb2portfolio');
			}	
			
			
			// Get image name
			$thumbname = JHtmlMedia::get_img_name($url);	
				
			
				
			// *** 3) Save image
			$resizeObj -> saveImage(JPATH_CACHE . '/com_mb2portfolio/' . $thumbname . '_' . $imgw . 'x' . $imgh . $format, $quality);							
			
			
			//define thumbnail url
			$output .= JURI::base(true) . '/cache/com_mb2portfolio/' . $thumbname . '_' . $imgw.'x' . $imgh . $format;	
		
		
		}
		else{
			
			$output .= JURI::base(true) . '/' . $url;		
			
		}
	
	
		return $output;	
		
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * Method to get name from image
	 *
	 * @param   string     $url  	  The url of the original image
	 * @param   boolean				  If true, image will return with format
	 *
	 * @return  string  name of image
	 */	
	public static function get_img_name($url, $format = 0){	
	
		
		
		// Get file name
		$img_parts = pathinfo($url);
						
		if(!isset($img_parts['filename']))
		{
			$img_parts['filename'] = substr($img_parts['basename'], 0, strrpos($img_parts['basename'], '.'));
		} 		
		
		
		// Check uploaded image format		
		$format_checker = substr($url,-4); 
		
					
		if ($format_checker == '.jpg')
		{
			$imgformat = '.jpg';	
		}
		elseif ($format_checker == '.gif')
		{
			$imgformat = '.gif';	
		}
		elseif ($format_checker == '.png')
		{
			$imgformat = '.png';	
		}					
		
		
		if($format == 1)
		{
			return $img_parts['filename'] . $imgformat;
		}
		else
		{
			return $img_parts['filename'];
		}					
		
			
		
	}
	

	

}
