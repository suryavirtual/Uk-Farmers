<?php
/**
 * ARRA User Export Import component for Joomla! 1.6
 * @version 1.6.0
 * @author ARRA (joomlarra@gmail.com)
 * @link http://www.joomlarra.com
 * @Copyright (C) 2010 - 2011 joomlarra.com. All Rights Reserved.
 * @license GNU General Public License version 2, see LICENSE.txt or http://www.gnu.org/licenses/gpl-2.0.html
 * PHP code files are distributed under the GPL license. All icons, images, and JavaScript code are NOT GPL (unless specified), and are released under the joomlarra Proprietary License, http://www.joomlarra.com/licenses.html
 *
 * file: arrauser.php
 *
 **** class 
 **** functions
*/

// No direct access
defined('_JEXEC') or die('Restricted access');
defined('DS') or define("DS", DIRECTORY_SEPARATOR);

// Require the base controller
require_once( JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controller.php' );

// Require specific controller if requested
$pattern = '/^[A-Za-z]*$/';
if(preg_match($pattern, JFactory::getApplication()->input->get("controller", "") )){
    $controller = JFactory::getApplication()->input->get("controller", "");
	$path = JPATH_COMPONENT.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'ArrausermigrateController'.$controller;
$controller	= new $classname( );

// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get("task", "") );

// Redirect if set by the controller
$controller->redirect();