<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_sendterm
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

abstract class JHtmlUfbrand
{
	static function approved($value = 0, $i)
	{ 
		$states = array('0'=> array('disabled.png','Ufbrands.publish','','Toggle to publish'),
			'1'=> array('tick.png', 'Ufbrands.unpublish','', 'Toggle to unpublish'));
		
		$state = JArrayHelper::getValue($states, $value, $states[1]);
		$html = JHtml::_('image', 'admin/'.$state[0], JText::_($state[2]), NULL, true);
		$html = '<a href="#" onclick="return listItemTask(\'cb'.$i.'\',\''.$state[1].'\')" title="'.JText::_($state[3]).'">'. $html.'</a>';
		return $html;
	}
} ?>