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
     ArrausermigrateViewStatistics
	 
 **** functions
     display();
 */

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );

/**
 * ArrausermigrateViewStatistics View
 *
 */
class ArrausermigrateViewStatistics extends JViewLegacy{
	/**
	 * display method 
	 * @return void
	 **/
	 
	 var $users1 = "";
	 var $users = "";
	 
	function display($tpl = null){		
		
		// make ToolBarHelper with name of component.
		JToolBarHelper::title(   JText::_( 'ARRA_USER_EXPORT' ), 'generic.png' );
		JToolBarHelper::cancel ('cancel', 'Cancel');		  		
		$this->users1 = $this->get("AllUsers");
		$this->users = $this->get("AllUsersMapped");
						
		parent::display($tpl);
	}
	
	function getUserType(){
		$all_types = $this->get("AllTypes");
		$existing_types = $this->get("ExistingTypes");
		$return = $this->createCheckBox($all_types, $existing_types);
		return $return;
	}
	
	function getBlockUnblock(){
		$return  = "";
		$return .= "<table>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="block" onclick="javascript:statistics('.$this->users.');" value="1">&nbsp;'.JText::_("ARRA_BLOCK");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="block" onclick="javascript:statistics('.$this->users.');" value="0">&nbsp;'.JText::_("ARRA_UNBLOCK");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="block" onclick="javascript:statistics('.$this->users.');" value="-1">&nbsp;'.JText::_("ARRA_COLUMN_DEFAULT_VAL");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= "</table>";
		return $return;
	}
	
	function getLastVisitedDate(){
		$return  = "";
		$return .= "<table>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" colspan=\"2\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="visited" onclick="javascript:statistics('.$this->users.');" value="0">&nbsp;'.JText::_("ARRA_AT_LEAST_ONE_VISIT");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" colspan=\"2\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="visited" onclick="javascript:statistics('.$this->users.');" value="1">&nbsp;'.JText::_("ARRA_NO_VISITE");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" colspan=\"2\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="visited" onclick="javascript:statistics('.$this->users.');" value="-1">&nbsp;'.JText::_("ARRA_COLUMN_DEFAULT_VAL");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JText::_("ARRA_FROM").": ";
		$return .= 			"</td>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JHTML::_('calendar', '', 'start_date', 'start_date', '%Y-%m-%d', array('size'=>'25',  'maxlength'=>'19', 'onchange'=>"javascript:statistics(".$this->users.")"));
		$return .= 			"</td>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JText::_("ARRA_TO").": ";
		$return .= 			"</td>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JHTML::_('calendar', '', 'end_date', 'end_date', '%Y-%m-%d', array('size'=>'25',  'maxlength'=>'19', 'onchange'=>"javascript:statistics(".$this->users.")"));
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= "</table>";
		return $return;
	}
	
	function getSendEmail(){
		$return  = "";
		$return .= "<table>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="send_email" onclick="javascript:statistics('.$this->users.');" value="1">&nbsp;'.JText::_("ARRA_SYSTEM_SEND_EMAIL");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="send_email" onclick="javascript:statistics('.$this->users.');" value="0">&nbsp;'.JText::_("ARRA_SYSTEM_DO_NOT_SEND_EMAIL");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="send_email" onclick="javascript:statistics('.$this->users.');" value="-1">&nbsp;'.JText::_("ARRA_COLUMN_DEFAULT_VAL");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= "</table>";
		return $return;
	}
	
	function getActivatedUsers(){
		$return  = "";
		$return .= "<table>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="activated" onclick="javascript:statistics('.$this->users.');" value="0">&nbsp;'.JText::_("ARRA_ACTIVATED_USER");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="activated" onclick="javascript:statistics('.$this->users.');" value="1">&nbsp;'.JText::_("ARRA_PENDING_ACTIVATION");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= 		"<tr>";
		$return .= 			"<td style=\"line-height:20px;\" class=\"td_settings_options\">";
		$return .= 				'<input type="radio" name="activated" onclick="javascript:statistics('.$this->users.');" value="-1">&nbsp;'.JText::_("ARRA_COLUMN_DEFAULT_VAL");
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= "</table>";
		return $return;
	}
	
	function getRegisterDate(){
		$return  = "";
		$return .= "<table>";		
		$return .= 		"<tr>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JText::_("ARRA_FROM").": ";
		$return .= 			"</td>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JHTML::_('calendar', '', 'start_register_date', 'start_register_date', '%Y-%m-%d', array('size'=>'25',  'maxlength'=>'19', 'onchange'=>"javascript:statistics(".$this->users.")"));
		$return .= 			"</td>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JText::_("ARRA_TO").": ";
		$return .= 			"</td>";
		$return .= 			"<td class=\"td_settings_options\">";
		$return .= 				JHTML::_('calendar', '', 'end_register_date', 'end_register_date', '%Y-%m-%d', array('size'=>'25',  'maxlength'=>'19', 'onchange'=>"javascript:statistics(".$this->users.")"));
		$return .= 			"</td>";
		$return .= 		"</tr>";
		$return .= "</table>";
		return $return;
	}
	
	function createCheckBox($all_types, $existing_types){
		$columns = 4;
		$rows = 0;
		$k = 0;
		$existing_types_temp = array();
		
		if(is_array($all_types) && is_array($existing_types)){
			$rows = ceil((count($all_types) + count($existing_types))/$columns);
			foreach($existing_types as $key=>$values){
				$existing_types_temp[$values["id"]] = $values["title"];
			}
			$existing_types	= $existing_types_temp;		
		}
				
		$return  = "";
		$return .= '<table width="100%">';
		for($i=0; $i<$rows; $i++){
			$return .= '<tr>';
			for($j=0; $j<$columns; $j++){				
				if(isset($existing_types[$k]) || isset($all_types[$k])){
					$return .= '<td style="line-height:20px;" class="td_settings_options">';
					if(isset($all_types[$k]) && in_array($all_types[$k]["title"], $existing_types)){
						$return .= '<input type="checkbox" onclick="javascript:statistics('.$this->users.')" id="usertype" name="usertype['.$all_types[$k]["id"].']" value="'.$all_types[$k]["id"].'" />&nbsp;'.$all_types[$k]["title"];						
					}
					else{
						@$return .= $all_types[$k]["title"];														
					}
					$return .= '</td>';
					$k ++;
				}					
			}
			$return .= '</tr>';
		}
		$return .= '</table>';
		return $return;
	}
	
	function countUsers(){
		$model = $this->getModel();
		return $model->countUsers();
	}
	
	function createCalcul($tipe_count){
		$result = ($tipe_count * 100)/$this->users;		
		$result = number_format($result, 0, '.', '');		
		return $result;		
	}
	
	function createDiagram($tipe_count, $group){
		$scale_height = 270;
		$result = $this->createCalcul($tipe_count);
		$return  = "";
		
		$return .= '<div class="row-fluid">';
		$return .= '	<div class="span2" style="font-weight: bold;">';
		$return .= 			$group["title"];
		$return .= '	</div>';
		$return .= '	<div class="span1">';
		$return .= 			$this->createCalcul($tipe_count)." %";
		$return .= '	</div>';
		$return .= '	<div class="span9">';
		$return .= '		<div class="progress progress-striped active">
								<div class="bar bar-success" style="width: '.$result.'%;"></div>
    						</div>';
		$return .= '	</div>';
		$return .= '</div>';
		
		return $return;		
	}
	
}