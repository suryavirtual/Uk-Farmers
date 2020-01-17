<?php
/**
 * @package		Mb2 Module in Tabs
 * @version		1.1.1
 * @author		Mariusz Boloz (http://mb2extensions.com)
 * @copyright	Copyright (C) 2014 Mariusz Boloz (http://mb2extensions.com). All rights reserved
 * @license		GNU/GPL (http://www.gnu.org/copyleft/gpl.html)
**/
 

// no direct access
defined('_JEXEC') or die;


jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldMb2modules extends JFormField
{
	
	
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $_name = 'Mb2modules';

	 protected function getInput()
    {
		$db = JFactory::getDBO();
		$attr = '';
        // Initialize some field attributes.
        $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
        // To avoid user's confusion, readonly="true" should imply disabled="true".
        if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') { 
            $attr .= ' disabled="disabled"';
        }
        $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
        $attr .= $this->multiple ? ' multiple="multiple"' : '';
        // Initialize JavaScript field attributes.
        $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
        
		$query = "SELECT * FROM #__modules where client_id=0 AND published=1 ORDER BY title ASC";
		$db->setQuery($query);
		$groups = $db->loadObjectList();	
		$groupHTML = array();
		if ($groups && count ($groups)) {
			$groupHTML[] = JHTML::_('select.option', '', JText::_('JNONE'));
			foreach ($groups as $tvalue=>$item){
				$groupHTML[] = JHTML::_('select.option', $item->id, $item->title.' (Id: '.$item->id.')');
			}
		}
		if( !empty($value) && !is_array($value) )
			$value = explode("|", $value);
            
		$lists = JHTML::_('select.genericlist', $groupHTML, $this->name,trim($attr), 'value', 'text', $this->value, $this->id);	
				 
		return $lists; 
	}
} 