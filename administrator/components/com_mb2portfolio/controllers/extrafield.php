<?php
/**
 * @version     1.0.0
 * @package     com_mb2portfolio
 * @copyright   Copyright (C) 2013. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Mariusz Boloz <mariuszboloz@gmail.com> - http://marbol2.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controllerform');
jimport('joomla.filesystem.file');



class Mb2portfolioControllerExtrafield extends JControllerForm
{

    function __construct() {
        $this->view_list = 'extrafields';
        parent::__construct();
    }	
	
	
	
	
	
	

}

