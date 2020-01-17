<?php
/**
 * @package	Mosets Tree
 * @copyright	(C) 2011-2013 Mosets Consulting. All rights reserved.
 * @license	GNU General Public License
 * @author	Lee Cher Yeong <mtree@mosets.com>
 * @url		http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class mFieldType_texteditor extends mFieldType_text {
	var $allowHTML = true;

	function getOutput($view=1)
	{
		$output = $this->getValue();
		$this->parseMambots($output);
		return $output;
	}

	function getInputHTML() {
		$params['width'] = $this->getParam('width','100%');
		$params['height'] = $this->getParam('height','200px');
		$params['loadButtons'] = $this->getParam('loadButtons',0);

		return JEditor::getInstance(JFactory::getConfig()->get('editor'))
		->display(
			$this->getInputFieldName(1),
			htmlspecialchars($this->getInputValue()), // htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8'), 
			$params['width'],
			$params['height'], 
			'60', // cols
			'6', // rows
			($params['loadButtons']?array('pagebreak','readmore'):false)
		);
	}

	function getJSOnInit() {
		if( $this->isRequired() )
		{
			return 'jQuery(\'#'.$this->getInputFieldId(1).'\').attr(\'required\',true)';
		}
		else
		{
			return null;
		}
	}

	function getJSOnSave()
	{
		$config_editor = JFactory::getConfig()->get('editor');

		if( $config_editor == 'none' )
		{
			return null;
		} else {
			return JEditor::getInstance($config_editor)->save($this->getInputFieldName(1));
		}
	}
}
?>