<?php
/**
 * @package		Mb2 Portfolio
 * @version		2.3.1
 * @author		Mariusz Boloz (http://marbol2.com)
 * @copyright	Copyright (C) 2013 Mariusz Boloz (http://marbol2.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;


require_once JPATH_COMPONENT .'/helpers/mb2portfolio.php';
require_once JPATH_COMPONENT.'/helpers/route.php';


// Include dependancies
jimport('joomla.application.component.controller');


// Execute the task.
$controller	= JControllerLegacy::getInstance('Mb2portfolio');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();