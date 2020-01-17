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
 * file: controller.php
 *
 **** class 
     ArrausersmigrateController 
 **** functions
     display();
*/

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

ini_set( 'display_errors', true );  
error_reporting( E_ALL );

class ArrausermigrateController extends JControllerLegacy{
	/**
	 * Method to display the view
	 * @access	public
	 */
	function display($cachable = false, $urlparams = array()){		
	    $controller = JFactory::getApplication()->input->get("controller", "");
		$true_export = false;
		$true_quick = false;
		$true_import = false;
		$true_language = false;
		$true_about = false;
		$true_main = true;
		$true_utf = false;
		$true_delete = false;
		$true_statistics = false;
		$true_userprofile = false;
		
		$class_export = "";
		$class_import = "";
		$class_language = "";
		$class_about = "";
		$class_main = 'class="arra-menu-active"';
		$class_utf = "";
		$class_delete = "";
		$class_statistics = "";
		$class_userprofile = "";
		$class_quick = "";
		
		if($controller == "export"){
		    $true_export = true;
			$true_main = false;
			$class_main = '';
			$class_export = 'class="arra-menu-active"';
		}
		elseif($controller == "quick"){
		    $true_quick = true;
			$true_main = false;
			$class_main = '';
			$class_quick = 'class="arra-menu-active"';
		}
		elseif($controller == "import"){
		    $true_import = true;
			$true_main = false;
			$class_main = '';
			$class_import = 'class="arra-menu-active"';
		}
		elseif($controller == "language"){
		    $true_language = true;
			$true_main = false;
			$class_main = '';
			$class_language = 'class="arra-menu-active"';
		}
		elseif($controller == "about"){
		    $true_about = true;
			$true_main = false;
			$class_main = '';
			$class_about = 'class="arra-menu-active"';
		}		
		elseif($controller == "modal"){
			$true_main = false;
			$class_main = '';
		}		
		elseif($controller == "utf"){
			$true_utf = true;
			$true_main = false;
			$class_main = '';
			$class_utf = 'class="arra-menu-active"';
		}
		elseif($controller == "statistics"){
			$true_statistics = true;
			$true_main = false;
			$class_main = '';
			$class_statistics = 'class="arra-menu-active"';
		}
		elseif($controller == "userprofile"){
			$true_userprofile = true;
			$true_main = false;
			$class_main = '';
			$class_userprofile = 'class="arra-menu-active"';
		}
		elseif($controller == "delete"){
			$true_delete = true;
			$true_main = false;
			$class_main = '';
			$class_delete = 'class="arra-menu-active"';
		}
		else{
		    $true_main = true;
			$class_main = 'class="arra-menu-active"';
		}
		
		$document = JFactory::getDocument();
		$document->addStyleSheet("components/com_arrausermigrate/css/ij30.css");
		
		$tmpl = JFactory::getApplication()->input->get("tmpl", "");
		
		if($tmpl == ""){
			echo '		
				<div class="clearfix"></div>
	
				<div class="ui-app">
					<div class="navbar publisher-navbar">
						<div class="navbar-inner">
							<div class="container-fluid">
								<div class="nav-collapse collapse">
									<ul class="nav">
										<li>
											<a href="index.php?option=com_arrausermigrate" '.$class_main.'>
												<i class="icon-home"></i>
												'.JText::_("ARRA_MAIN_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&task=quick&controller=quick" '.$class_quick.'>
												<i class="icon-plus-sign"></i>
												'.JText::_("ARRA_USER_QUICK_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&task=export&controller=export" '.$class_export.'>
												<i class="icon-minus-sign"></i>
												'.JText::_("ARRA_USER_EXPORT_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&task=import&controller=import" '.$class_import.'>
												<i class="icon-plus-sign"></i>
												'.JText::_("ARRA_USER_IMPORT_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&task=userprofile&controller=userprofile" '.$class_userprofile.'>
												<i class="icon-user"></i>
												'.JText::_("ARRA_USERPROFILE_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&controller=utf" '.$class_utf.'>
												<i class="icon-list-alt"></i>
												'.JText::_("ARRA_UTF_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&controller=delete" '.$class_delete.'>
												<i class="icon-trash"></i>
												'.JText::_("ARRA_DELETE_MENU").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&controller=statistics" '.$class_statistics.'>
												<i class="icon-align-left"></i>
												'.JText::_("ARRA_STATISTICS").'
											</a>
										</li>
										
										<li>
											<a href="index.php?option=com_arrausermigrate&task=language&controller=language" '.$class_language.'>
												<i class="icon-font"></i>
												'.JText::_("ARRA_LANGUAGE_MENU").'
											</a>
										</li>
										
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				
				<div class="clearfix"></div>
			';	
		}
		parent::display();
	}
}

?>