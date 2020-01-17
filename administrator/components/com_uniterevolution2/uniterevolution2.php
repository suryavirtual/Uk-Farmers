<?php

/**
 * @package Unite Revolution Slider for Joomla 1.7-2.5
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
// no direct access
defined('_JEXEC') or die('Restricted access');

$user = JFactory::getUser();

// Access check.
if ($user->authorise('core.manage', "com_uniterevolution2") == false) {
  return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}

$currentDir = dirname(__FILE__) . "/";

$currentFile = __FILE__;
$currentFolder = dirname($currentFile);

require_once $currentFolder . "/includes.php";
require_once $currentFolder . "/revslider_admin.php";



$action = UniteFunctionsRev::getPostGetVariable("action");
switch ($action) {
  case "getcaptions":
    RevOperations::putRevCssCaptions();
    break;
}


//add tiny box dropdown menu
$tinybox = new RevSlider_TinyBox();
$productAdmin = new RevSliderAdmin($currentFile);


//set global title
$title = JText::_('COM_UNITEREVOLUTION2');
JToolBarHelper::title($title, 'generic.png');

//enable mootools on sliders view when the permissions option is
//enable toolbar options button on a sliders view.
$manager_permission = $user->authorise('core.admin', GlobalsRevSlider::PLUGIN_NAME);
$view = UniteFunctionsRev::getPostGetVariable("view");
if ((!$view || $view == "sliders") && $manager_permission) {
  JToolBarHelper::preferences('com_uniterevolution2', 550, 875);
} else {
  UniteFunctionJoomlaRev::disableMootools();
}
?>