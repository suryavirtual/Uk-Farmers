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
     ArrausermigrateViewImport 
	 
 **** functions
     display();
	 errorMessage();
	 emailSettings();
	 uploadSqlZipFile();
	 uploadCsvTxtFile();
	 allSettings();
	 checked();
	 setDefaultUsertype();	 	 
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
JHTML::_( 'behavior.modal' );

/**
 * ArrausermigrateViewImport View
 *
 */
class ArrausermigrateViewImport extends JViewLegacy{
	/**
	 * display method 
	 * @return void
	 **/
	function display($tpl = null){						
		
		// make ToolBarHelper with name of component.
		JToolBarHelper::custom('import_button', 'plus-sign.png', 'plus-sign.png', 'Import', false, false);
		JToolBarHelper::cancel('cancel', 'Cancel');
		JToolBarHelper::title(JText::_('ARRA_USER_EXPORT'), 'generic.png');
		
		$upload_file_sql_zip = $this->uploadSqlZipFile();		
		$this->assignRef('upload_file_sql_zip', $upload_file_sql_zip);
		
		$upload_file_csv_txt = $this->uploadCsvTxtFile();		
		$this->assignRef('upload_file_csv_txt', $upload_file_csv_txt);
			
		$allSettings = $this->allSettings();		
		$this->assignRef('allSettings', $allSettings);
		
		$emailSettings = $this->emailSettings();		
		$this->assignRef('emailSettings', $emailSettings);
				
		$error_message = $this->errorMessage();
		$this->assignRef('error_message', $error_message);
				
		parent::display($tpl);
	}
	
	function errorMessage(){
		
		$same_user = 0;
		$empty_column = 0;
		$username_error = 0;
		
		$session = JFactory::getSession();
		$registry = $session->get('registry');
		$link_eror = $registry->get('link_eror', "");
		$error_empty_column = $registry->get('error_empty_column', "");
		$username_error = $registry->get('username_error', "");
		
		if(isset($link_eror) && $link_eror == "error"){
			$same_user = 1;
		}
		if(isset($error_empty_column) && $error_empty_column == "error_empty_column"){
			$empty_column = 1;
		}
		if(isset($username_error) && $username_error == "error"){
			$username_error = 1;
		}
				
		$error  = "";
		$error .= "<table>";
		if($same_user == 1 && $empty_column == 0){
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=              JText::_("ARRA_ERROR_MESSAGE_SAME_EMAIL");  
			$error .=          "</td>";
			$error .=      "</tr>";
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=             "<a href=\"".Juri::base()."components/com_arrausermigrate/files/error_same_email.csv"."\" target=\"_blank\">error_same_email.csv</a>";      
			$error .=          "</td>";
			$error .=      "</tr>";
		}
		elseif($same_user == 0 && $empty_column == 1){
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=              JText::_("ARRA_NOTE_MESSAGE_EMPTY_COLUMN");  
			$error .=          "</td>";
			$error .=      "</tr>";
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=             "<a href=\"".Juri::base()."components/com_arrausermigrate/files/error_empty_column.csv"."\" target=\"_blank\">error_empty_column.csv</a>";
			$error .=          "</td>";
			$error .=      "</tr>";
		}
		elseif($same_user == 1 && $empty_column == 1){
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=              JText::_("ARRA_ERROR_MESSAGE_SAME_EMAIL");  
			$error .=          "</td>";
			$error .=      "</tr>";
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=             "<a href=\"".Juri::base()."components/com_arrausermigrate/files/error_same_email.csv"."\" target=\"_blank\">error_same_email.csv</a>";      
			$error .=          "</td>";
			$error .=      "</tr>";
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=              JText::_("ARRA_NOTE_MESSAGE_EMPTY_COLUMN");  
			$error .=          "</td>";
			$error .=      "</tr>";			
			$error .=          "<td>";
			$error .=             "<a href=\"".Juri::base()."components/com_arrausermigrate/files/error_empty_column.csv"."\" target=\"_blank\">error_empty_column.csv</a>";
			$error .=          "</td>";
			$error .=      "</tr>";
		}
		
		if($username_error == 1){
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=              JText::_("ARRA_ERROR_MESSAGE_SAME_USERNAME");  
			$error .=          "</td>";
			$error .=      "</tr>";
			$error .=      "<tr>";
			$error .=          "<td>";
			$error .=             "<a href=\"".Juri::base()."components/com_arrausermigrate/files/error_same_username.csv"."\" target=\"_blank\">error_same_username.csv</a>";      
			$error .=          "</td>";
			$error .=      "</tr>";
		}
		
		$error .= "</table>";		
		return $error;
	}
	
	function emailSettings(){
		$db = JFactory::getDBO();
	    $sql= "select params from #__extensions where element='com_arrausermigrate'";
	    $db->setQuery($sql);
	    $all_result = $db->loadResult();
		$result = "";
		$settings_saved = false;	  
		
	    if(strlen($all_result) != 0 && trim($all_result) != "{}"){
		    $all_array = json_decode($all_result, true);
			if(isset($all_array["JoomlaImport"]) && strlen(trim($all_array["JoomlaImport"]))>0){
			   $result = $all_array["JoomlaImport"];			
		       $settings_saved = true;  
			}
			else{
			   $settings_saved = false;  
			}		
		}				
	
		$defaul_email_template  = "";
		$defaul_email_template .= "Congratulations, you have been registered as a user on {sitename}.\n\n";
		$defaul_email_template .= "Below are your login credentials: \n\n";		
		$defaul_email_template .= "name: {name}\n";
		$defaul_email_template .= "username: {username}\n";
		$defaul_email_template .= "usertype: {usertype}\n";
		$defaul_email_template .= "password: {password}\n";
		
		$default_subject_template  = "";
		$default_subject_template .= "Account details for {username} from {sitename}";
		
	    $config = new JConfig();
	    $emailSettings = "";
		
		$emailSettings .= "<table width=\"100%\" cellspacing=\"5\">";
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td colspan=\"2\">";
		$emailSettings .=         	"<div class=\"alert\"><b style=\"color:red; margin-left:8px; \">".JText::_("ARRA_WARNING")."</b> ".JText::_("ARRA_EMAIL_LIMITATION")."</div>";
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";
		$emailSettings .=     "<tr>";
		$emailSettings .=        "<td width=\"55%\" valign=\"top\">";				
		$emailSettings .= "<table>";
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td colspan=\"2\"  class=\"td_settings_2\">";
		$emailSettings .=             "<div class=\"alert\"><span><a style=\"color:red;\" href=\"#\" onclick=\"javascript:hide_show('email_div'); return false;\">".JText::_("ARRA_EMAILS_TO_NEW_USERS_2_HEADER")."</a></span>".
							   			"<div id=\"email_div\" style=\"display:none; color:red;\">".JText::_("ARRA_EMAILS_TO_NEW_USERS_2")."</div></div>";
		$emailSettings .=             "<input type=\"checkbox\" name=\"send_email_to_import\" id=\"send_email_to_import\" />" . "&nbsp;&nbsp;" . JText::_("ARRA_EMAILS_TO_NEW_USERS");
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>"; 	
		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td  class=\"td_settings_options\">";
		$emailSettings .=              "<span class=\"editlinktip hasTip\" title=\"Subject::".JText::_("ARRA_TIP_SUBJECT") ."\" >".
											JText::_("ARRA_EMAIL_IMPORT_SUBJECT").
									   "</span>";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings\">";
		$emailSettings .=             "<textarea rows=\"1\" cols=\"50\" name=\"subject_template\" onkeyup=\"this.style.border='1px solid silver'\"  id=\"subject_template\">";
		if($settings_saved == true){
			$emailSettings .=				 $this->checked("subject_template",$result);
		}
		else{
			$emailSettings .=				 $default_subject_template;
		}
        $emailSettings .=             "</textarea>";
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";
			
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_options\">";
		$emailSettings .=              "<span class=\"editlinktip hasTip\" title=\"{from_email}::".JText::_("ARRA_TIP_FROM_EMAIL") ."\" >".
											"{from_email}" .
									   "</span>";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings\">";
		$emailSettings .=             "<input type=\"text\" name=\"from_email\" onkeyup=\"this.style.border='1px solid silver'\" value=\"";
		
		if($settings_saved == true){
			$emailSettings .=				 $this->checked("from_email",$result);
		}
		else{
			$emailSettings .=				 $config->mailfrom;
		}
		
		$emailSettings .=         "\" id=\"from_email\" size=\"40\">";
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_options\">";
		$emailSettings .=              "<span class=\"editlinktip hasTip\" title=\"{from_name}::".JText::_("ARRA_TIP_FROM_NAME") ."\" >".
											"{from_name}" .
									   "</span>";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings\">";
		$emailSettings .=             "<input type=\"text\" name=\"from_name\" value=\"";
		
		if($settings_saved == true){
			$emailSettings .=				 $this->checked("from_name",$result);
		}
		else{
			$emailSettings .=				 $config->fromname;
		}
		
		$emailSettings .=         "\" onkeyup=\"this.style.border='1px solid silver'\"  id=\"from_name\"  size=\"40\">";				
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_options\">";
		$emailSettings .=              "<span class=\"editlinktip hasTip\" title=\"{sitename}::".JText::_("ARRA_TIP_SITE_NAME") ."\" >".
											"{sitename}" .
									   "</span>";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings\">";
		$emailSettings .=             "<input type=\"text\" name=\"sitename\" value=\"";
		
		if($settings_saved == true){
			$emailSettings .=				 $this->checked("sitename",$result);
		}
		else{
			$emailSettings .=				 $config->sitename;
		}
		
		$emailSettings .= 		"\" onkeyup=\"this.style.border='1px solid silver'\"  id=\"sitename\"  size=\"40\">";       
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=             "{username}";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=              JText::_("ARRA_EMAILS_USERNAME"); 
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=             "{name}";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=              JText::_("ARRA_EMAILS_NAME"); 
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=             "{email}";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=              JText::_("ARRA_EMAILS_EMAIL"); 
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=             "{usertype}";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=              JText::_("ARRA_EMAILS_USERTYPE"); 
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";		
		$emailSettings .=     "<tr>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=             "{password}";
		$emailSettings .=         "</td>";
		$emailSettings .=         "<td class=\"td_settings_2\">";
		$emailSettings .=              JText::_("ARRA_EMAILS_PASSWORD"); 
		$emailSettings .=         "</td>";
		$emailSettings .=     "</tr>";			
		$emailSettings .= "</table>";		
		$emailSettings .=       "</td>";		
		$emailSettings .=       "<td valign=\"top\" align=\"center\">";
		$emailSettings .=          "<table>";		
		$emailSettings .=              "<tr>";
		$emailSettings .=                 "<td class=\"td_settings_2\">";
		$emailSettings .=                     JText::_("ARRA_IMPORT_BODY_EMAIL");
		$emailSettings .=                 "</td>";
		$emailSettings .=              "</tr>";		
		$emailSettings .=              "<tr>";
		$emailSettings .=                 "<td>";
		$emailSettings .=                     "<textarea style=\"width:400px !important;\" rows=\"16\" cols=\"50\" name=\"email_template\" onkeyup=\"this.style.border='1px solid silver'\"  id=\"email_template\">";
		
		if($settings_saved == true){
			$emailSettings .=				 $this->checked("email_template",$result);
		}
		else{
			$emailSettings .=				 $defaul_email_template;
		}
		
        $emailSettings .=                     "</textarea>";
		$emailSettings .=                     "<br /><div class=\"alert alert-info\">".JText::_("ARRA_EMAIL_TEMPLATE_NOTE")."</div>";
		$emailSettings .=                 "</td>";
		$emailSettings .=              "</tr>";
		$emailSettings .=          "</table>";  
		$emailSettings .=       "</td>";		 
		$emailSettings .=    "</tr>";
		$emailSettings .=    "<tr>";
		$emailSettings .= "</table>";
		
		return $emailSettings;
	}
	
	function uploadSqlZipFile(){
	    $config = new JConfig();
		$prefix = $config->dbprefix;
		
	    $upload_file = "";
	    $upload_file .= "<table width=\"100%\">";		
		$upload_file .= 		"<tr>";
		$upload_file .= 			"<td width=\"25%\">";		   
		$upload_file .= 				"<input name=\"sqlzip_file_upload\" type=\"FILE\" id=\"sqlzip_file_upload\" size=\"50px\" onclick=\"document.getElementById('csvtxt_file_upload').value=''; \">";				  			  
		$upload_file .= 			"</td>"; 
		$upload_file .= 			"<td align=\"right\"  width=\"75%\">";
		$upload_file .= 				"<input type=\"submit\" name=\"import_button\" class=\"btn btn-primary\" value=\"Import\" onClick=\"document.adminForm.file_import.value='sql_zip'; document.adminForm.task.value='import_file'; return validateImportForm();\">";	
		$upload_file .= 			"</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= 		"<tr>";
		$upload_file .= 			"<td class=\"td_settings_options\" colspan=\"2\">";
		$upload_file .=                  "<div class=\"space\"></div>";
		$upload_file .=                 "<span>" . JText::_("ARRA_NOTE_SQL_EXPORT"). " ".$prefix."usergroups, ".$prefix."users, ".$prefix."user_usergroup_map"."<br/><br/>".JText::_("ARRA_NOTE_SQL_EXPORT_BACK_UP")."</span>";
		$upload_file .= 			"</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= 		"<tr>";
		$upload_file .= 			"<td>";
		$upload_file .= 			  "<input type=\"submit\" class=\"btn btn-success\" name=\"back_up_button\" value=\"".JText::_("ARRA_BACK_UP_BUTTON")."\"  onClick=\"document.adminForm.task.value='back_up'\">";	
		$upload_file .= 			"</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= 		"<tr>";
		$upload_file .= 			"<td>";
		$upload_file .= 			     "<input type=\"button\" class=\"btn btn-danger\" name=\"truncate_tables_button\" value=\"".JText::_("ARRA_TRUNCATE_TABLES_BUTTON")."\" onclick=\"javascript:truncateAllTables()\">";
		$upload_file .= 			"</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= 		"<tr>";
		$upload_file .= 			"<td width=\"60%\">";
		$upload_file .= 			     "<div id=\"truncate_message\" class=\"truncate_message\">" . "";
		$upload_file .= 			     "</div>";
		$upload_file .= 			"</td>";				
		$upload_file .= 		"</tr>";		
		$upload_file .= "</table>"; 
		
		return $upload_file;
	}	
	
	function uploadCsvTxtFile(){
	    $upload_file = "";
		$upload_file .= "<table>";
		$upload_file .= 		"<tr>";
		$upload_file .= 			"<td width=\"25%\">";		   
		$upload_file .= 				"<input name=\"csvtxt_file_upload\" type=\"FILE\" id=\"csvtxt_file_upload\" size=\"50px\" onclick=\"document.getElementById('sqlzip_file_upload').value='';\">";				  			  
		$upload_file .= 			"</td>"; 
		$upload_file .= 			"<td align=\"right\"  width=\"75%\">";
		$upload_file .= 				"<input type=\"submit\" name=\"import_button\" class=\"btn btn-primary\" value=\"Import\" onClick=\"document.adminForm.file_import.value='csv_txt'; return validateImportForm();\">";	
		$upload_file .= 			"</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= 		"<tr>";		
		$upload_file .= 			"<td class=\"td_settings_options\" colspan=\"2\">";
		$upload_file .= "<div class=\"space\"></div>";
		$upload_file .=                 JText::_("ARRA_DOWNLOAD_EXAMPLE");
		$upload_file .= "<div class=\"space\"></div>";
		$upload_file .= 			    JText::_("ARRA_IMPORT_TXT_EXAMPLE")."&nbsp;&nbsp;&nbsp;&nbsp;"."<a href=\"".Juri::base()."components/com_arrausermigrate/files/txt_example.txt"."\" target=\"_blank\"> example.txt</a>"; 
		$upload_file .= "<br/>";
		$upload_file .= 			    JText::_("ARRA_IMPORT_CSV_EXAMPLE")."&nbsp;&nbsp;"."<a href=\"".Juri::base()."components/com_arrausermigrate/files/csv_example.csv"."\"> example.csv</a>"; 
		$upload_file .= 			"</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= 		"<tr>";
		$upload_file .= 		   "<td>";
		$upload_file .= 		       "<a rel=\"{handler: 'iframe', size: {x: 500, y: 315}}\"  class=\"modal\"  href=\"index.php?option=com_arrausermigrate&controller=modal&task=modal&tmpl=component\">".JText::_('VIEWPACKAGE_ALL_COLUMNS_BUTTON')."</a>";
		$upload_file .= 		   "</td>";
		$upload_file .= 		"</tr>";
		$upload_file .= "</table>";
		
		return $upload_file; 
	}	
	
	function allSettings(){
		$settings_saved = false;    	  
	    $db = JFactory::getDBO();
	    $sql = "select c.params from #__extensions c where c.element='com_arrausermigrate'";
	    $db->setQuery($sql);
	    $all_result = $db->loadResult();
		$result = "";	  
		
	    if(strlen($all_result) != 0 && trim($all_result) != "{}"){
		    $all_array = json_decode($all_result, true);						    				
			if(isset($all_array["JoomlaImport"]) && strlen(trim($all_array["JoomlaImport"])) != 0){								
			   $result = $all_array["JoomlaImport"];			
		       $settings_saved = true;  
			}
			else{
			   $settings_saved = false;  
			}		
		}
		
	    $allSettings = "";
		$allSettings .= "<table cellspacing=\"5\">";
		$allSettings .=     "<tr>";
		$allSettings .=        "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_RESET_PASSWORD")."::".JText::_("ARRA_TIP_RESET_PASSWORD") ."\" >".
					                JText::_("ARRA_RESET_PASSWORD")."<br/>".
						       "</span>";
		$allSettings .=        "</td>";
		$allSettings .=        "<td class=\"td_settings_options\">";
		$allSettings .=        '<input type="checkbox" name="requireReset" value="1" />';
		$allSettings .=        "</td>";
		$allSettings .=     "</tr>";
		
		$allSettings .=     "<tr>";
		$allSettings .=        "<td class=\"td_settings_options\">";
		$allSettings .=        JText::_("ARRA_VALIDATE_EMAIL");
		$allSettings .=        "</td>";
		$allSettings .=        "<td class=\"td_settings_options\">";
		$allSettings .=        '<input type="checkbox" name="validate_email" value="1" />';
		$allSettings .=        "</td>";
		$allSettings .=     "</tr>";
		
		$allSettings .=     "<tr>";
		$allSettings .=        "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_SEPARATOR")."::".JText::_("ARRA_TIP_SEPARATOR") ."\" >".
					                JText::_("ARRA_SEPARATOR")."<br/>".
						       "</span>";
		$allSettings .=        "<span><a style=\"color:red;\" href=\"#\" onclick=\"javascript:hide_show('separator_div')\">".JText::_("ARRA_SEPARATOR_2_HEADER")."</a></span><div id=\"separator_div\" style=\"display:none; color:red;\">".JText::_("ARRA_SEPARATOR_22")."</div>";
		$allSettings .=        "</td>";
		$allSettings .=        "<td class=\"td_settings\" valign=\"top\">";
		$allSettings .=             "<select name=\"separator\">";		
		$select = "";
		
		if($this->checked("separator", $result) == ";"){
			$select = ' selected="selected" ';
		}
		$allSettings .=    				"<option value=\";\" ".$select."> ; " . JText::_("ARRA_SEMICOLON") . "</option>";
		$select = "";
		
		if($this->checked("separator", $result)== "," || $this->checked("separator", $result) == ""){
			$select = ' selected="selected" ';
		}
		$allSettings .=     			"<option value=\",\" ".$select."> , " .JText::_("ARRA_COMMA") . "</option>";
		$select = "";
		
		if($this->checked("separator", $result) == "|"){			
			$select = ' selected="selected" ';
		}
		$allSettings .= 				"<option value=\"|\" ".$select."> | " . JText::_("ARRA_VERTICAL_BAR") . "</option>";
		$select = "";
		if($this->checked("separator", $result)== "."){
			$select = ' selected="selected" ';
		}
		$allSettings .= 				"<option value=\".\" ".$select."> . " . JText::_("ARRA_DOT") . "</option>";
		$select = "";				 
		$allSettings .= 			"</select>";
		$allSettings .=        "</td>";
		$allSettings .=     "</tr>";		
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">"; 
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_OVERWRITE_ALL_TO_EXISTING_USER")."::".JText::_("ARRA_TIP_OWRITE_ALL") ."\" >".
					                JText::_("ARRA_OVERWRITE_ALL_TO_EXISTING_USER") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		$allSettings .=             "<input type=\"checkbox\" name=\"same_user_option_checkbox\" value=\"change_all\"> "; 
		$allSettings .=         "</td>";		
		$allSettings .=     "</tr>";
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_OVERWRITE_USERTYPE_TO_EXISTING_USER")."::".JText::_("ARRA_TIP_OWRITE_USERTYPE") ."\" >".
					                 JText::_("ARRA_OVERWRITE_USERTYPE_TO_EXISTING_USER") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";
		$allSettings .=         			'<fieldset class="radio btn-group" id="same_user_option_radio_usertype">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("same_user_option_radio_usertype", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="same_user_option_radio_usertype" id="jform_same_user_option_radio_usertype1">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_usertype1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="same_user_option_radio_usertype" id="jform_same_user_option_radio_usertype0">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_usertype0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";		
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_OVERWRITE_PASSWORD_TO_EXISTING_USER")."::".JText::_("ARRA_TIP_OWRITE_PASSWORD") ."\" >".
					                  JText::_("ARRA_OVERWRITE_PASSWORD_TO_EXISTING_USER") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";		
		
		$allSettings .=         			'<fieldset class="radio btn-group" id="same_user_option_radio_password">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("same_user_option_radio_password", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="same_user_option_radio_password" id="jform_same_user_option_radio_password1">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_password1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="same_user_option_radio_password" id="jform_same_user_option_radio_password0">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_password0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";
		
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_OVERWRITE_EMAIL_TO_EXISTING_USER")."::".JText::_("ARRA_TIP_OWRITE_EMAIL") ."\" >".
					                  JText::_("ARRA_OVERWRITE_EMAIL_TO_EXISTING_USER") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";
		
		$allSettings .=         			'<fieldset class="radio btn-group" id="same_user_option_radio_email">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("same_user_option_radio_email", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="same_user_option_radio_email" id="jform_same_user_option_radio_email1">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_email1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="same_user_option_radio_email" id="jform_same_user_option_radio_email0">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_email0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";
		 
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_OVERWRITE_BLOCK_TO_EXISTING_USER")."::".JText::_("ARRA_TIP_OWRITE_BLOCK") ."\" >".
					                 JText::_("ARRA_OVERWRITE_BLOCK_TO_EXISTING_USER") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";
		
		$allSettings .=         			'<fieldset class="radio btn-group" id="same_user_option_radio_block">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("same_user_option_radio_block", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="same_user_option_radio_block" id="jform_same_user_option_radio_block1">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_block1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="same_user_option_radio_block" id="jform_same_user_option_radio_block0">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_block0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";
				
		/*$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_OVERWRITE_PARAMS_TO_EXISTING_USER")."::".JText::_("ARRA_TIP_OWRITE_PARAMS") ."\" >".
					                 JText::_("ARRA_OVERWRITE_PARAMS_TO_EXISTING_USER") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";
		
		$allSettings .=         			'<fieldset class="radio btn-group" id="same_user_option_radio_params">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("same_user_option_radio_params", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="same_user_option_radio_params" id="jform_same_user_option_radio_params1">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_params1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="same_user_option_radio_params" id="jform_same_user_option_radio_params0">';
		$allSettings .=         			'<label for="jform_same_user_option_radio_params0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";*/
		
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_ENCRYPTED_PASSWORD")."::".JText::_("ARRA_TIP_ENCRYPT_PASS") ."\" >".
					                JText::_("ARRA_ENCRYPTED_PASSWORD")."<br/>".									
						       "</span>".
							   "<span><a  style=\"color:red;\" href=\"#\" onclick=\"javascript:hide_show('encripted_div'); return false;\">".JText::_("ARRA_ENCRYPTED_PASSWORD_2_HEADER")."</a></span>".
							   "<div id=\"encripted_div\" style=\"display:none; color:red;\">".JText::_("ARRA_ENCRYPTED_PASSWORD_2")."</div>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\" valign=\"top\">";
		 
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";
		
		$allSettings .=         			'<fieldset class="radio btn-group" id="encripted_password_radio">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("encripted_password_radio", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="encripted_password_radio" id="jform_encripted_password_radio1">';
		$allSettings .=         			'<label for="jform_encripted_password_radio1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="encripted_password_radio" id="jform_encripted_password_radio0">';
		$allSettings .=         			'<label for="jform_encripted_password_radio0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";		
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_GENERATE_PASSWORD")."::".JText::_("ARRA_TIP_GENERATE_PASS") ."\" >".
					                JText::_("ARRA_GENERATE_PASSWORD")."<br/>".									
						       "</span>".
							    "<span><a  style=\"color:red;\" href=\"#\" onclick=\"javascript:hide_show('generate_div'); return false;\">".JText::_("ARRA_GENERATE_PASSWORD_2_HEADER")."</a></span>".
							   "<div id=\"generate_div\" style=\"display:none; color:red;\">".JText::_("ARRA_GENERATE_PASSWORD_2")."</div>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\" valign=\"top\">";
		
		$allSettings .=         "<table>";
		$allSettings .=         	"<tr>";
		$allSettings .=         		"<td style=\"line-height: 20px;\">";
		
		$allSettings .=         			'<fieldset class="radio btn-group" id="generate_password_radio">';
											$yes_checked = "";
											$no_checked = "";
											if($this->checked("generate_password_radio", $result) == 1){
												$yes_checked = 'checked="checked"';
											}
											else{
												$no_checked = 'checked="checked"';
											}
											
		$allSettings .=         			'<input type="radio" '.$yes_checked.' value="1" name="generate_password_radio" id="jform_generate_password_radio1">';
		$allSettings .=         			'<label for="jform_generate_password_radio1" class="btn">'.JText::_("JYES").'</label>';
                
		$allSettings .=         			'<input type="radio" '.$no_checked.' value="0" name="generate_password_radio" id="jform_generate_password_radio0">';
		$allSettings .=         			'<label for="jform_generate_password_radio0" class="btn">'.JText::_("JNO").'</label>';
		$allSettings .=         			'</fieldset>';
		$allSettings .=         		"</td>";
		$allSettings .=         	"</tr>";
		$allSettings .=         "</table>";
		
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";		
		$allSettings .=     "<tr>";
		$allSettings .=         "<td class=\"td_settings_options\">";
		$allSettings .=        "<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_DEFAULT_PASSWORD")."::".JText::_("ARRA_TIP_DEFAULT_PASS") ."\" >".
					                JText::_("ARRA_DEFAULT_PASSWORD") .
						       "</span>";
		$allSettings .=         "</td>";
		$allSettings .=         "<td class=\"td_settings\">";
		$allSettings .=             "<input type=\"text\" name=\"default_password\" size=\"30px\">";
		$allSettings .=         "</td>";
		$allSettings .=     "</tr>";		
		$allSettings .= $this->setDefaultUsertype();				
		$allSettings .= "</table>";
		
		return $allSettings;
	}
	
	function checked($radio_name, $result){
	    $rows = explode("*", $result);
		foreach($rows as $key=>$value){
		     $value=explode("=", $value);
			 if($radio_name == trim($value[0])){
			     return trim($value[1]);
			 }
		} 
	}
	
	function setDefaultUsertype(){
	    JHTML::_('behavior.combobox');				
		
		$all_user_type = $this->get('UserType');
		
	    $encripted  = "";	
		$encripted .=   "<tr>";
		$encripted .=   	"<td class=\"td_settings_options\">";
		$encripted .=       	"<span class=\"editlinktip hasTip\" title=\"" . JText::_("ARRA_DEFAULT_USERTYPE")."::".JText::_("ARRA_TIP_USERTYPE") ."\" >". JText::_("ARRA_DEFAULT_USERTYPE") .
						    	"</span>";
		$encripted .=       "</td>";
		$encripted .=       "<td  class=\"td_settings\">";
		if(isset($all_user_type) && is_array($all_user_type) && count($all_user_type)!=0){
		    $encripted .= "<select name=\"position\" class=\"combobox\" id=\"position\" />";
			foreach($all_user_type as $key=>$value){
				 $encripted .= "<option>" . $value['title'] . "</option>";
			}
			$encripted .= "</select>";
		}		
		$encripted .=      "</td>";
		$encripted .=  "</tr>";		
		return $encripted;
	}
	
	function getContactsCategories(){
		$return = "";
		$db = JFactory::getDBO();
		$sql = "select id, title from #__categories where extension='com_contact' and published=1";
		$db->setQuery($sql);
		$db->query();
		$results = $db->loadAssocList();
		if(isset($results) && count($results) > 0){
			foreach($results as $key=>$value){
				$return .= '<option value="'.intval($value["id"]).'">'.$value["title"].'</option>';
			}
		}
		return $return;
	}
	
	function contactsComponent(){
		$return  = "";
		$return .= '<table>';
		$return .= '	<tr>';
		$return .= '		<td class="td_settings_options">';
		$return .= '			<input type="checkbox" onchange="javascript:addNewUsersToContacts();" value="1" name="add_in_contacts" id="add_in_contacts" />&nbsp;'.JText::_("ARRA_CHECK_IF_ADD_IN_CONTACTS");
		$return .= '			<div id="div-contacts" style="display:none;">';
		$return .= '				<br />';
		$return .=					JText::_("ARRA_SELECT_DEFAULT_CATEGORY_FROM_CONTACS");
		$return .= '				<select name="default_contacts_category">';
		$return .= 						$this->getContactsCategories();
		$return .= '				</select>';
		$return .= '				<br />';
		$return .= 					JText::_("ARRA_AUTOPUBLISH_CONTACTS");
		$return .= '				&nbsp;&nbsp;&nbsp;
									<fieldset class="radio btn-group" id="autopublish">
										<input type="radio" value="1" name="autopublish" id="jform_autopublish1" checked="checked">
										<label for="jform_autopublish1" class="btn">'.JText::_("JYES").'</label>
										
										<input type="radio" value="0" name="autopublish" id="jform_autopublish0">
										<label for="jform_autopublish0" class="btn">'.JText::_("JNO").'</label>
									</fieldset>';
		$return .= '			</div>';
		$return .= '		</td>';
		$return .= '	</tr>';
		$return .= '</table>';
		
		return $return;
	}
}

?>