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
     ArrausermigrateModelExport 
	 
 **** functions
     __construct();
	 getUserType();	 
	 export();	 	 
	 setExportType();
	 csv_txtExport();
	 htmlExport();
	 sqlExport();
	 mkfile();	 
	 zipExport();
	 sendSqlMail();	 
	 sendMail();	 	
	 checked();
	 getAdditionalColumns();
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.model');
require_once (JPATH_COMPONENT_ADMINISTRATOR.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'SqlExport.php');
jimport( 'joomla.filesystem.file' );

/**
 * ArrausermigrateModelExport Model
 *
 */
class ArrausermigrateModelQuick extends JModelLegacy{
	
	function __construct(){		
		parent::__construct();
	}
	
	function getGroups(){
		$db = JFactory::getDbo();
		$sql = "select id, title from #__usergroups";
		$db->setQuery($sql);
		$db->query();
		$groups = $db->loadAssocList();
		return $groups;
	}
	
	function getRows(){
		$five_lines = array();
		
		$file_name = JFactory::getApplication()->input->get("file_name", "", "raw");
		$start = JFactory::getApplication()->input->get("start", "1", "raw");
		$separator = JFactory::getApplication()->input->get("separator", ",", "raw");
		
		if(trim($file_name) != ""){
			$content = JFile::read(JPATH_SITE.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.trim($file_name));
			$lines = explode("\n", $content);
			
			for($i=($start-1); $i<($start + 4); $i++){
				if(isset($lines[$i]) && trim($lines[$i]) != ""){
					$content = str_getcsv($lines[$i], $separator);
					$five_lines[] = $content;
				}
			}
		}
		return $five_lines;
	}
	
	function startImport(){
		$db = JFactory::getDbo();
		$letters = array("A"=>"0", "B"=>"1", "C"=>"2", "D"=>"3", "E"=>"4", "F"=>"5", "G"=>"6", "H"=>"7", "I"=>"8", "I"=>"9", "K"=>"10", "L"=>"11", "M"=>"12", "N"=>"13", "O"=>"14", "P"=>"15", "Q"=>"16", "R"=>"17", "S"=>"18", "T"=>"19", "U"=>"20", "V"=>"21", "W"=>"22", "X"=>"23", "Y"=>"24", "Z"=>"25");
		
		$file_name = JFactory::getApplication()->input->get("file_name", "", "raw");
		$start = JFactory::getApplication()->input->get("start", "1", "raw");
		$name = JFactory::getApplication()->input->get("name", array(), "raw");
		$username = JFactory::getApplication()->input->get("username", "", "raw");
		$email = JFactory::getApplication()->input->get("email", "", "raw");
		$password = JFactory::getApplication()->input->get("password", "", "raw");
		$params = JFactory::getApplication()->input->get("params", "", "raw");
		$overwrite = JFactory::getApplication()->input->get("overwrite", "", "raw");
		$groups = JFactory::getApplication()->input->get("groups", "", "raw");
		$encript = JFactory::getApplication()->input->get("encript", "1", "raw");
		$separator = JFactory::getApplication()->input->get("separator", ",", "raw");
		
		$total_users = 0;
		$updated_users = 0;
		$imported_users = 0;
		$no_users = 0;
		
		if(trim($file_name) != ""){
			$content = JFile::read(JPATH_SITE.DIRECTORY_SEPARATOR."tmp".DIRECTORY_SEPARATOR."uploads".DIRECTORY_SEPARATOR.trim($file_name));
			$lines = explode("\n", $content);
			
			foreach($lines as $key=>$line){
				if(($key + 1) < $start){
					continue;
				}
				else{
					$line = str_getcsv($line, $separator);
					
					if(count($line) == 1 && trim($line["0"]) == ""){
						// empty line, do nothing
					}
					else{
						$total_users ++;
						
						$name_value = "";
						$username_value = "";
						$email_value = "";
						$password_value = "";
						$params_value = "";
						
						foreach($name as $nr=>$letter){
							if(isset($letters[$letter])){
								$name_value .= $line[$letters[$letter]]." ";
							}
						}
						
						$username_value = $line[$letters[$username]];
						$email_value = $line[$letters[$email]];
						
						if(trim($password) != "" && trim($password) != "0"){
							$password_value = $line[$letters[$password]];
							
							if($encript == 1){
								// plain
								$password_value = JUserHelper::hashPassword(trim($password_value));
							}
						}
						
						if(trim($params) != "" && trim($params) != "0"){
							$params_value = $line[$letters[$params]];
						}
						
						$sql = "select id from #__users where username='".addslashes(trim($username_value))."'";
						$db->setQuery($sql);
						$db->query();
						$id = $db->loadColumn();
						$id = @$id["0"];
						$new_user_id = 0;
						
						if(intval($id) == 0){
							$sql = "insert into #__users (name, username, email, password, params) values ('".addslashes(trim($name_value))."', '".addslashes(trim($username_value))."', '".addslashes(trim($email_value))."', '".addslashes(trim($password_value))."', '".addslashes(trim($params_value))."')";
							$db->setQuery($sql);
							if($db->query()){
								$imported_users ++;
								
								$sql = "select max(id) from #__users";
								$db->setQuery($sql);
								$db->query();
								$new_user_id = $db->loadColumn();
								$new_user_id = @$new_user_id["0"];
								
								$sql = "insert into #__user_usergroup_map (user_id, group_id) values ('".intval($new_user_id)."', '".intval($groups)."')";
								$db->setQuery($sql);
								$db->query();
							}
							else{
								$no_users ++;
							}
						}
						else{
							if($overwrite == '1'){
								$sql = "update #__users set username='".addslashes(trim($username_value))."', password='".addslashes(trim($password_value))."'";
								
								if(trim($name_value) != ""){
									$sql .= ", name='".addslashes(trim($name_value))."'";
								}
								
								if(trim($email_value) != ""){
									$sql .= ", email='".addslashes(trim($email_value))."'";
								}
								
								if(trim($params_value) != ""){
									$sql .= ", params='".addslashes(trim($params_value))."'";
								}
								
								$sql .= " where id=".intval($id);
								$db->setQuery($sql);
								if($db->query()){
									$updated_users ++;
									
									$sql = "update #__user_usergroup_map set group_id=".intval($groups)." where user_id=".intval($id);
									$db->setQuery($sql);
									$db->query();
								}
								else{
									$no_users ++;
								}
							}
						}
					}
				}
			}
		}
		
		$return = array("total_users"=>$total_users, "updated_users"=>$updated_users, "imported_users"=>$imported_users, "no_users"=>$no_users);
		return $return;
	}
}

?>