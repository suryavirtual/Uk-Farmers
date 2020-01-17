<?php
/**
 * ------------------------------------------------------------------------
 * JA Image Hotspot Module for Joomla 2.5 & 3.4
 * ------------------------------------------------------------------------
 * Copyright (C) 2004-2011 J.O.O.M Solutions Co., Ltd. All Rights Reserved.
 * @license - GNU/GPL, http://www.gnu.org/licenses/gpl.html
 * Author: J.O.O.M Solutions Co., Ltd
 * Websites: http://www.joomlart.com - http://www.joomlancers.com
 * ------------------------------------------------------------------------
 */

defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.form.formfield');

require_once(dirname(__FILE__).'/../behavior.php');

class JFormFieldJamedia extends JFormField {
    
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'Jamedia';

	/**
	 * The initialised state of the document object.
	 *
	 * @var    boolean
	 * @since  11.1
	 */
	protected static $initialised = false;

	public function getControlGroup()
	{
		if ($this->hidden) {
			return $this->getInput();
		}

		return
			'<div class="control-group control-xfgroup span8">'
			. '<div class="controls">' . $this->getInput() . '</div>'
			. '</div>';
	}

	/**
	 * Method to get the field input markup for a media selector.
	 * Use attributes to identify specific created_by and asset_id fields
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$assetField = $this->element['asset_field'] ? (string) $this->element['asset_field'] : 'asset_id';
		$authorField = $this->element['created_by_field'] ? (string) $this->element['created_by_field'] : 'created_by';
		$asset = $this->form->getValue($assetField) ? $this->form->getValue($assetField) : (string) $this->element['asset_id'];
		if ($asset == '')
		{
			$asset = JRequest::getCmd('option');
		}

		$link = (string) $this->element['link'];
		if (!self::$initialised)
		{

			// Load the modal behavior script.
			JHtml::_('behavior.modal');

			// Build the script.
			$script = "
function jInsertFieldValue(value, id) {
	var old_value = document.id(id).value;
	if (old_value != value) {
		var elem = document.id(id);
		elem.value = value;
		elem.fireEvent('change');
		if (typeof(elem.onchange) === 'function') {
			elem.onchange();
		}
		jMediaRefreshPreview(id);
	}
}

function jMediaRefreshPreview(id) {
	var value = document.id(id).value;
	var img = document.id(id + '_preview');
	if (img) {
		if (value) {
			img.src = '" . JURI::root() . "' + value;
			document.id(id + '_preview_empty').setStyle('display', 'none');
			document.id(id + '_preview_img').setStyle('display', '');
			if(!document.id('jai_add').isDisplayed()){
				document.id('jai_add').setStyle('display', 'inline-block');
			};
		} else {
			img.src = '';
			document.id(id + '_preview_empty').setStyle('display', '');
			document.id(id + '_preview_img').setStyle('display', 'none');
			//remove markers
			jQuery('#'+id + '_preview_img span.point').remove();
			jQuery('#extrafieldimg .adminformlist').removeClass('active').addClass('deactive');
			jQuery('#extrafieldimg #jai_remove').hide();

			desc = [];
			jQuery('#jform_params_description').val('[]');
			//
			if(document.id('extrafieldimg').getElements('ul.adminformlist').isDisplayed()){
				document.id('extrafieldimg').getElements('ul.adminformlist').removeClass('active').addClass('deactive');
			};
			if(document.id('jai_add').isDisplayed()){
				document.id('jai_add').setStyle('display', 'none');
			};
		}
	}
}

function jMediaRefreshPreviewTip(tip)
{
	tip.setStyle('display', 'block');
	var img = tip.getElement('img.media-preview');
	var id = img.getProperty('id');
	id = id.substring(0, id.length - '_preview'.length);
	jMediaRefreshPreview(id);
}";

			// Add the script to the document head.
			JFactory::getDocument()->addScriptDeclaration($script);

			self::$initialised = true;
		}

		// Initialize variables.
		$html = array();
		$attr = '';

		// Initialize some field attributes.
		$attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
		$attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';

		// Initialize JavaScript field attributes.
		$attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

		// The text field.
		$html[] = '<div class="fltlft">';
		$html[] = '	<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
			. htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . ' readonly="readonly"' . $attr . ' />';

		$directory = (string) $this->element['directory'];
		if ($this->value && file_exists(JPATH_ROOT . '/' . $this->value))
		{
			$folder = explode('/', $this->value);
			array_shift($folder);
			array_pop($folder);
			$folder = implode('/', $folder);
		}
		elseif (file_exists(JPATH_ROOT . '/' . JComponentHelper::getParams('com_media')->get('image_path', 'images') . '/' . $directory))
		{
			$folder = $directory;
		}
		else
		{
			$folder = '';
		}
		// The button.
		$html[] = '		<a class="btn modal" title="' . JText::_('JLIB_FORM_BUTTON_SELECT') . '"' . ' href="'
			. ($this->element['readonly'] ? ''
			: ($link ? $link
				: 'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=' . $asset . '&amp;author='
				. $this->form->getValue($authorField)) . '&amp;fieldid=' . $this->id . '&amp;folder=' . $folder) . '"'
			. ' rel="{handler: \'iframe\', size: {x: 800, y: 600}}">';
		$html[] = JText::_('JLIB_FORM_BUTTON_SELECT') . '</a>';

		$html[] = '		<a class="btn" title="' . JText::_('JLIB_FORM_BUTTON_CLEAR') . '"' . ' href="#" onclick="';
		$html[] = 'jInsertFieldValue(\'\', \'' . $this->id . '\');';
		$html[] = 'return false;';
		$html[] = '">';
		$html[] = JText::_('JLIB_FORM_BUTTON_CLEAR') . '</a>';
		$html[] = '</div>';

		// The Preview.
		$preview = (string) $this->element['preview'];
		$showPreview = true;
		$showAsTooltip = false;
		switch ($preview)
		{
			case 'false':
			case 'none':
				$showPreview = false;
				break;
			case 'true':
			case 'show':
				break;
			case 'tooltip':
			default:
				$showAsTooltip = true;
				$options = array(
					'onShow' => 'jMediaRefreshPreviewTip',
				);
				JHtml::_('behavior.tooltip', '.hasTipPreview', $options);
				break;
		}

		if ($showPreview) {
			if ($this->value && file_exists(JPATH_ROOT . '/' . $this->value)) {
				$src = JURI::root() . $this->value;
			} else {
				$src = '';
			}

			$attr = array(
				'id' => $this->id . '_preview',
				'class' => 'media-preview',
				'style' => 'width:100%;'
			);
			$img = JHtml::image($src, JText::_('JLIB_FORM_MEDIA_PREVIEW_ALT'), $attr);
			$previewImg = '<div id="' . $this->id . '_preview_img"' . ($src ? '' : ' style="display:none;"') . '>' . $img . '</div>';
			$previewImgEmpty = '<div id="' . $this->id . '_preview_empty"' . ($src ? ' style="display:none"' : '') . '>'
				. JText::_('JLIB_FORM_MEDIA_PREVIEW_EMPTY') . '</div>';

			$html[] = '<div class="media-preview fltlft" style="clear:both;">';
			if ($showAsTooltip) {
				$tooltip = $previewImgEmpty . $previewImg;
				$options = array(
					'title' => JText::_('JLIB_FORM_MEDIA_PREVIEW_SELECTED_IMAGE'),
					'text' => JText::_('JLIB_FORM_MEDIA_PREVIEW_TIP_TITLE'),
					'class' => 'hasTipPreview'
				);
				$html[] = JHtml::tooltip($tooltip, $options);
			} else {
				$html[] = ' ' . $previewImgEmpty;
				$html[] = ' ' . $previewImg;
			}
			$html[] = '</div>';
		}

		return implode("\n", $html);
	}
}