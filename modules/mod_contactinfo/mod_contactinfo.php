<?php
/**
* Contact Information Module 3.3
* Joomla Module
* Author: Edward Cupler
* Website: www.digitalgreys.com
* Contact: ecupler@digitalgreys.com
* @copyright Copyright (C) 2013 Digital Greys. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* Contact Information Module is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if(!function_exists('contactImage')) {
function contactImage($imageInfo, $widthheight=35) {
	$database = JFactory::getDBO();
	if ($imageInfo->image == '') {
		$imageInfo->image = '/modules/mod_contactinfo/default.jpg';
	}
	$timthumbPath=JURI::base() . 'modules/mod_contactinfo/includes/timthumb.php?src=' . JURI::root() . $imageInfo->image . '&w='.$widthheight.'&h='.$widthheight ;
	if (is_file( JPATH_BASE . '/'. $imageInfo->image )) {
		$iconimage = '<img src="'. $timthumbPath .'" title="' . $imageInfo->name . '" alt="' . $imageInfo->name . '" class="contactIcon"  />';
	} else {
		$iconimage = 'No image found';
	}
	return $iconimage ;
}
}

$db	= JFactory::getDBO();
$contact_id = $params->get( 'contact_cid', 0 );

	global $mainframe, $database, $my, $Itemid;
/*************************************************/
//Set up ordering
	$contact_array = explode(",", $contact_id);
	$contactordering = 'ORDER BY ';
	for ( $i=0; $i<sizeof($contact_array); $i++ ) {
		$contactordering .= "id=" . $contact_array[$i] . " DESC, ";
	}
	$contactordering = rtrim($contactordering, ', ');
/*************************************************/
	$query = "SELECT * FROM #__contact_details WHERE id IN ( $contact_id ) $contactordering";
	$db->setQuery( $query );
	$contacts = $db->loadObjectList();

	if ($params->get( 'layout_style', '' )=="SeperateLines") {
		$linebreak="<br />";
		$newspace="";
	} else {
		$linebreak="";
		$newspace=" ";
	}
	if ($params->get( 'separate_code', '' )=="div") {
		$separate_code="<div class=\"contact_sep\"></div>";
	} else if ($params->get( 'separate_code', '' )=="br") {
		$separate_code="<br />";
	} else if ($params->get( 'separate_code', '' )=="hr") {
		$separate_code="<hr class=\"contact_sep\" />";
	} else {
		$separate_code="";
	}

	$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));
	$separate_num=sizeof($contacts);?>

	<div class="contactinfo<?php echo $moduleclass_sfx; ?>">
	<?php
	for ( $i=0; $i<sizeof($contacts); $i++ ) {

		$telephone_array=explode(",",$contacts[$i]->telephone);
		if ($params->get( 'show_image', '' ) == 1 && $contacts[$i]->name != '') {
			if ($params->get( 'link_image', '' ) == 1) {
				$url = JURI::root() . 'index.php?option=com_contact&view=contact&id=' . $contacts[$i]->id;
				echo "<span class=\"info_image\"><a href=\"". $url . "\">" . contactImage( $contacts[$i], $params->get( 'thumb_size', 100 ) ) . "</a></span>$linebreak$newspace\n";
			} else {
				echo "<span class=\"info_image\">" . contactImage( $contacts[$i], $params->get( 'thumb_size', 100 ) ) . "</span>$linebreak$newspace\n";
			}
		}

		if ($params->get( 'show_name', '' ) == 1 && $contacts[$i]->name != '') {
			if ($params->get( 'link_to', '' ) == 1) {
				$url = JURI::root() . 'index.php?option=com_contact&view=contact&id=' . $contacts[$i]->id;
				echo "<span class=\"info_name\"><a href=\"". $url . "\">" . $contacts[$i]->name . "</a></span>$linebreak$newspace\n";
			} else {
				echo "<span class=\"info_name\">" . $contacts[$i]->name . "</span>$linebreak$newspace\n";
			}
		}
		if ($params->get( 'show_alias', '' ) == 1 && $contacts[$i]->alias != '') {
			if ($params->get( 'link_to', '' ) == 1 && $params->get( 'show_name', '' ) != 1) {
				echo "<span class=\"info_name\"><a href=\"index.php?option=com_contact&task=view&id=" . $contacts[$i]->id . "\">" . $contacts[$i]->alias . "</a></span>$linebreak$newspace\n";
			} else {
				echo "<span class=\"info_alias\">" . $contacts[$i]->alias . "</span>$linebreak$newspace\n";
			}
		}
		if ($params->get( 'con_position', '' ) == 1 && $contacts[$i]->con_position != '') {
			echo "<span class=\"info_position\">".$contacts[$i]->con_position . "</span>$linebreak$newspace\n";
		}
		
		echo '<div class="left-contact">';
		if ($params->get( 'show_address', '' ) == 1 && $contacts[$i]->address != '') {
			echo "<i class=\"fa fa-home\"></i><span class=\"info_address\">".$contacts[$i]->address . "</span><br><br> ";
		}
		if ($params->get( 'show_suburb', '' ) == 1 && $contacts[$i]->suburb != '') {
			echo "<span class=\"info_suburb\">".$contacts[$i]->suburb . "</span><br/> \n";
		}
		if ($params->get( 'show_email_to', '' ) == 1 && $contacts[$i]->email_to != "") {
			if ($params->get( 'email_text' ) != '') {
				$displayed_email=JHTML::_('email.cloak', $contacts[$i]->email_to, true, $params->get( 'email_text'), false);
			} else {
				$displayed_email=JHTML::_('email.cloak', $contacts[$i]->email_to, true );
			}
			echo "<i class=\"fa fa-globe\"></i><span class=\"info_email\">" . $displayed_email . "</span>$linebreak$newspace\n<br>";
		}
		echo '</div>';
		
		if ($params->get( 'show_state', '' ) == 1 && $contacts[$i]->state != '') {
			echo "<span class=\"info_state\">".$contacts[$i]->state . "</span> \n";
		}
		if ($params->get( 'show_postcode', '' ) == 1 && $contacts[$i]->postcode != '') {
			echo "<span class=\"info_postcode\">".$contacts[$i]->postcode . "</span>$linebreak$newspace\n";
		}
		if ($params->get( 'show_country', '' ) == 1 && $contacts[$i]->country != '') {
			echo "<span class=\"info_country\">".$contacts[$i]->country . "</span>$linebreak$newspace\n";
		}
		
		echo '<div class="right-contact">';
		if ($params->get( 'show_telephone', '' ) == 1 && $contacts[$i]->telephone != "") {
			if (sizeof($telephone_array) > 1) {
				$telNum=1;
				foreach ($telephone_array AS $telephone) {
					echo "<i class=\"fa fa-phone\"></i><span class=\"info_telephone info_telephone".($telNum++)."\">".$telephone . "</span>$linebreak$newspace\n<br>";
				}
			} else {
				echo "<i class=\"fa fa-phone\"></i><span class=\"info_telephone\">".$telephone_array[0]. "</span>$linebreak$newspace\n<br>";
			}
		}
		if ($params->get( 'show_mobile', '' ) == 1 && $contacts[$i]->mobile != "") {
			echo "<span class=\"info_mobile\">Mobile: " . $contacts[$i]->mobile ."</span>$linebreak$newspace\n";
		}
		if ($params->get( 'show_fax', '' ) == 1 && $contacts[$i]->fax != "") {
			echo "<i class=\"fa fa-fax\"></i><span class=\"info_fax\"> " . $contacts[$i]->fax ."</span>$linebreak$newspace\n";
		}
		echo '</div>';
		
		if ($params->get( 'show_webpage', '' ) == 1 && $contacts[$i]->webpage != "") {
			$displayAddress=str_replace("http://", "", $contacts[$i]->webpage);
			$displayAddress=str_replace("https://", "", $displayAddress);
			if ($params->get( 'link_website', '' ) == 1) {
				if ($params->get( 'website_target', '' ) == 1) {
					$target=' target="new"';
				} else {
					$target='';
				}

				if ( preg_match("/http:\/\//", $contacts[$i]->webpage ) || preg_match("/https:\/\//", $contacts[$i]->webpage ) ) {
					echo "<span class=\"info_webpage\"><a href=\"".$contacts[$i]->webpage ."\"". $target .">".$displayAddress ."</a></span>$linebreak$newspace\n";
				} else {
					echo "<span class=\"info_webpage\"><a href=\"http://".$contacts[$i]->webpage ."\"". $target .">".$displayAddress ."</a></span>$linebreak$newspace\n";
				}
			} else {
				echo "<span class=\"info_webpage\">".$contacts[$i]->webpage ."</span>$linebreak$newspace\n";
			}
		}
		if ($params->get( 'show_misc', 0 ) == 1 && $contacts[$i]->misc != "") {
			echo "<span class=\"info_misc\">".$contacts[$i]->misc ."</span>$linebreak$newspace\n";
		}

		if ($params->get( 'show_vcard', 0 ) == 1 ) {
			echo '<span class="info_vcard"><a href="' . JRoute::_('index.php?option=com_contact&amp;view=contact&amp;id=' . $contacts[$i]->id . '&amp;format=vcf') . '">' . JText::_('MOD_CONTENT_DGCONTACT_VCARD') . '</a></span>' . $linebreak . $newspace ."\n";
		}

		if ($separate_num > 1) {
			echo $separate_code."\n";
		}
		$separate_num=$separate_num-1;
	}

	?></div>
