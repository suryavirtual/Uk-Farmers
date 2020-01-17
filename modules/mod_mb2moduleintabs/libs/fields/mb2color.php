<?php
/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/

defined('_JEXEC') or die;



class JFormFieldMb2color extends JFormField
{
	
	
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.3
	 */
	protected $type = 'Mb2color';

	
	
	/**
	 * Method to get the field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.3
	 */
	protected function getInput()
	{
		
		$output='';		
		$uniqid = uniqid();
		
		// Load js color script
		$doc = JFactory::getDocument();		
		$doc->addScript(JURI::root(true) . '/modules/mod_mb2moduleintabs/libs/jscolor/jscolor.js');
		
		
		// Initialize some field attributes.
		$style = ' style="width:65px;margin-right:0;"';
		$style_color = ' style="width:20px;margin-left:5px;"';
		$class = ' class="mb2color';
		$class .= ' {';
		$class .= 'required:false,';
		$class .= 'hash:true,';
		$class .= 'styleElement:el_' . $uniqid . ',';
		$class .= 'pickerBorder:1,';
		$class .= 'pickerFace:8,';
		$class .= 'pickerFaceColor:\'#dadbdc\',';
		$class .= 'pickerBorderColor:\'#ccc\',';
		$class .= 'pickerInsetColor:\'#dadbdc\'';
		$class .= '}"';


		// Initialize JavaScript field attributes.
		$onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';
				
		$output .= '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $style . $onchange . '/>';			
			
		$output .= '<input type="text" id="el_' . $uniqid . '" class=""' . $style_color . ' disabled />';
			
			
		return $output;		
		
	}
}