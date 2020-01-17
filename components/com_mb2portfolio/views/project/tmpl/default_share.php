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



//get params
$images_arr = json_decode($this->item->images);
$f_image = $images_arr->featured_image;
$google_plus = $this->params->get('single_project_gooleplus', 1);
$facebook = $this->params->get('single_project_facebook', 1);
$twitter = $this->params->get('single_project_twitter', 1);
$pinit = $this->params->get('single_project_pinit', 1);
$email = $this->params->get('single_project_email', 1);


if($google_plus == 1 || $facebook == 1 || $twitter == 1 || $pinit == 1 || $email == 1){ 
			
$item_social_link = JURI::getInstance()->toString();
			
?>
<div class="mb2-portfolio-social-shares clearfix">
	<ul class="mb2-portfolio-social-shares-list">                    	
		<?php if($google_plus == 1){ ?>
			<li class="google-plus"><a class="mb2-portfolio-tooltip" href="https://plus.google.com/share?url=<?php echo $item_social_link; ?>" title="Google Plus" target="_blank"><i class="mb2portfolio-fa mb2portfolio-fa-google-plus"></i></a></li>
		<?php	
		}
		if($facebook == 1){ ?>
			<li class="facebook"><a class="mb2-portfolio-tooltip" href="http://www.facebook.com/sharer.php?u=<?php echo $item_social_link; ?>&t=<?php echo $this->item->title; ?>" title="Facebook" target="_blank"><i class="mb2portfolio-fa mb2portfolio-fa-facebook"></i></a></li>  
        <?php	
		}
		if($twitter == 1){ ?>
			<li class="twitter"><a class="mb2-portfolio-tooltip" href="http://twitter.com/home/?status=<?php echo $this->item->title; ?> <?php echo $item_social_link; ?>" title="Twitter" target="_blank"><i class="mb2portfolio-fa mb2portfolio-fa-twitter"></i></a></li>
		<?php	
		}
		if($pinit == 1){
		?>                          
        	<li><a class="mb2-portfolio-tooltip" href="http://pinterest.com/pin/create/button/?url=<?php echo $item_social_link; ?>&description=<?php echo $this->item->title; ?>&media=<?php echo $f_image; ?>" title="Pinterest" target="_blank"><i class="mb2portfolio-fa mb2portfolio-fa-pinterest"></i></a></li>
        <?php					
        }
		if($email == 1){ ?>
			<li class="email"><a class="mb2-portfolio-tooltip" href="mailto:?subject=<?php echo $this->item->title; ?>&body=<?php echo $item_social_link; ?>" title="Email"><i class="mb2portfolio-fa mb2portfolio-fa-envelope-o"></i></a></li>
		<?php	
		}
		?>
	</ul>
</div>
<?php				
}				
?>