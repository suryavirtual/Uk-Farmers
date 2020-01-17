<?php 
/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/



// no direct access
defined('_JEXEC') or die();

require_once __DIR__ . '/helper.php';

// Define some variables
$uniqid = uniqid();

// Load module scripts and styles
modMb2moduleintabsHelper::before_head($params, array('modid'=>$module->id));

// Get module items
$items = modMb2moduleintabsHelper::get_items($params, array());

// Get module layout
require JModuleHelper::getLayoutPath('mod_mb2moduleintabs', $params->get('layout', 'default'));