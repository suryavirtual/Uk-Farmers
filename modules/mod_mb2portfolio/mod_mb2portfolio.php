<?php 
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

// no direct access
defined('_JEXEC') or die('Restricted access');
require_once __DIR__ . '/helper.php';

// Get component helpers class
JHtml::addIncludePath(JPATH_SITE . '/components/com_mb2portfolio/helpers');


// Get items list
$items = modmb2portfolioHelper::getList($params);


// Get params from component
$gparams = JComponentHelper::getParams('com_mb2portfolio');


// Get helper functions
$carousel = Mb2portfolioHelper::items_carousel($items, $params, array('mode'=>'module'));
Mb2portfolioHelper::before_head($item= null, $params, array('mod_id'=>$module->id, 'mode'=>'module', 'carousel'=>$carousel));


// Get layout path
require(JModuleHelper::getLayoutPath('mod_mb2portfolio'));