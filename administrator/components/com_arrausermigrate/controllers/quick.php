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
 * file: export.php
 *
 **** class 
     ArrausermigrateControllerExport 
	 
 **** functions
     __construct();
	 export();
	 exportFile();
	 cancel();
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * ArrausermigrateControllerExport Controller
 */
class ArrausermigrateControllerQuick extends ArrausermigrateController{
	/**
	 * constructor (registers additional tasks to methods)
	 * @return void
	 */
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks
		$this->registerTask( 'quick', 'quick' );
		$this->registerTask( 'upload', 'upload' );
		$this->registerTask( 'step2', 'step2' );
		$this->registerTask( 'step3', 'step3' );
		$this->registerTask( 'step3_import', 'step3Import' );
	}
	
	//set view for export tab
    function quick(){
		JFactory::getApplication()->input->set("view", "quick");
		JFactory::getApplication()->input->set("layout", "default");
		
		$model = $this->getModel('quick');		
		parent::display();
	}
	
	function upload(){
		$output_dir = JPATH_SITE.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."uploads".DS;
		
		$file = JFactory::getApplication()->input->files->get('csvfile', NULL);
		
		if(isset($file))
		{
			if (!file_exists(JPATH_SITE.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."uploads")) {
				mkdir(JPATH_SITE.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."uploads", 0777, true);
			}
		
			//Filter the file types , if you want.
			if ($file["error"] > 0)
			{
			  echo "Error: " . $file["error"] . "<br>";
			}
			else
			{
				//move the uploaded file to uploads folder;
				move_uploaded_file($file["tmp_name"],$output_dir. $file["name"]);
				echo $file["name"];
			}
		}
	}
	
    //out from export tab
    function cancel(){
		$msg = JText::_( 'ARRA_OPERATION_CANCELED' );
		$this->setRedirect( 'index.php?option=com_arrausermigrate', $msg );
	}
	
	function step2(){
		JFactory::getApplication()->input->set("hidemainmenu", "1");
		
		$view = $this->getView("Quick", "html");
		$view->setLayout("step2");
		$model = $this->getModel("Quick");
		$view->setModel($model, true);
	
		$view->step2();
	}
	
	function step3(){
		JFactory::getApplication()->input->set("hidemainmenu", "1");
		
		$view = $this->getView("Quick", "html");
		$view->setLayout("step3");
		$model = $this->getModel("Quick");
		$view->setModel($model, true);
	
		$view->step3();
	}
	
	function step3Import(){
		JFactory::getApplication()->input->set("hidemainmenu", "1");
		
		$view = $this->getView("Quick", "html");
		$view->setLayout("step3");
		$model = $this->getModel("Quick");
		$view->setModel($model, true);
	
		$view->step3();
	}
}