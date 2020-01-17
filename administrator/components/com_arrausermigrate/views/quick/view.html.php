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
 * file: view.html.php
 *
 **** class 
     ArrausermigrateViewExport 
	 
 **** functions
     display();
     userType();
	 firstColumnExport();
	 secondColumnExport1();
	 secondColumnExport2();
	 AdditionalColumns();	 
     fileType();
	 tableFileType();	 
	 generateCheckbox();
	 setSeparators();
	 setOrdering();
	 setEmailTo();	 
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
JHTML::_( 'behavior.modal' );
/**
 * ArrausermigrateViewExport View
 *
 */
class ArrausermigrateViewQuick extends JViewLegacy{
	/**
	 * display method 
	 * @return void
	 **/
	function display($tpl = null){
		// make ToolBarHelper with name of component.
		JToolBarHelper::title(JText::_( 'ARRA_USER_EXPORT' ), 'generic.png');
		JToolBarHelper::cancel('cancel', 'Cancel');
		
		parent::display($tpl);
	}
	
	function step2($tpl = null){
		$this->groups = $this->get("Groups");
		$this->rows = $this->get("Rows");
		
		parent::display($tpl);
	}
	
	function step3($tpl = null){
		$task = JFactory::getApplication()->input->get("task", "", "raw");
		
		if($task == "step3_import"){
			$model = $this->getModel("Quick");
			$this->return_import = $model->startImport();
		}
		
		parent::display($tpl);
	}
}