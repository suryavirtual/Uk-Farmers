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
 * file: default.php
 *
 **** class     
 **** functions
 */

// No direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
	
	$lang = JFactory::getLanguage();
	$lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
	$field = $this->field_details;	
	
	$name = isset($field["0"]["name"]) ? trim($field["0"]["name"]) : "";
	$type = isset($field["0"]["type"]) ? trim($field["0"]["type"]) : "text";
	$id = isset($field["0"]["id"]) ? trim($field["0"]["id"]) : "";
	$description = isset($field["0"]["id"]) ? JText::_(trim(@$field["0"]["description"])) : "";	
	$filter = isset($field["0"]["filter"]) ? trim($field["0"]["filter"]) : "";
	$label = isset($field["0"]["id"]) ? JText::_(trim($field["0"]["label"])) : "";
	$message = isset($field["0"]["message"]) ? JText::_(trim($field["0"]["message"])) : "";
	$size = isset($field["0"]["size"]) ? trim($field["0"]["size"]) : "";
	$cols = isset($field["0"]["cols"]) ? trim($field["0"]["cols"]) : "";
	$rows = isset($field["0"]["rows"]) ? trim($field["0"]["rows"]) : "";
	$option = isset($field["0"]["option"]) ? trim($field["0"]["option"]) : "";	
	
	$display_size = "none";
	$display_cols = "none";
	$display_rows = "none";
	$display_option = "none";
	
	if($type == "calendar" || $type == "radio" || $type == "textarea"){		
		$display_size = "none";
	}	
	else{
		$display_size = "table-row";
	}
	
	if($type == "textarea"){
		$display_cols = "table-row";
		$display_rows = "table-row";
	}
	
	if($type == "radio" || $type == "list" || $type == "checkboxes"){
		$display_option = "table-row";
	}
	
	
?>

<form method="post" name="adminForm" id="adminForm" class="form-horizontal">	
	<fieldset class="adminform">
		<legend>
			  <span class="editlinktip hasTip" title="<?php echo JText::_("ARRA_EDIT_PROFILE") . "::" .JText::_("ARRA_TOOLTIP_EDIT_PROFILE"); ?>" >
				   <?php echo JText::_("ARRA_EDIT_PROFILE"); ?>
			  </span>
		</legend>
		
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_NAME');?>
                <span class="star">*</span>
            </label>
            <div class="controls">
                <input disabled="disabled" type="text" style="float: none !important;" value="<?php echo $name; ?>" name="field_name" id="field_name">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_TYPE');?>
            </label>
            <div class="controls">
                <input type="text" disabled="disabled" style="float: none !important;" name="type" id="type" value="<?php echo $type; ?>" />
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_ID');?>
            </label>
            <div class="controls">
                <input type="text" style="float: none !important;" value="<?php echo $id; ?>" name="field_id" id="field_id">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_DESCRIPTION');?>
            </label>
            <div class="controls">
                <textarea rows="2" name="field_description" style="width: auto !important; float: none !important;"><?php echo $description; ?></textarea>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_FILTER');?>
            </label>
            <div class="controls">
                <select style="float: none !important;" id="field_filter" name="field_filter">
                    <option value="string" <?php if($filter=="text"){echo 'selected="selected"';} ?> >string</option>
                    <option value="safehtml" <?php if($filter=="text"){echo 'selected="selected"';} ?> >safehtml</option>
                    <option value="array" <?php if($filter=="array"){echo 'selected="selected"';} ?> >array</option>
                </select>
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_LABEL');?>
            </label>
            <div class="controls">
                <input type="text" style="float: none !important;" value="<?php echo $label; ?>" name="field_label" id="field_label">
            </div>
        </div>
        
        <div class="control-group">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_MESSAGE');?>
            </label>
            <div class="controls">
                <input type="text" style="float: none !important;" value="<?php echo $message; ?>" name="field_message" id="field_message">
            </div>
        </div>
        
        <div class="control-group" style="display:<?php echo $display_size; ?>;">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_SIZE');?>
            </label>
            <div class="controls">
                <input type="text" style="float: none !important;" value="<?php echo $size; ?>" size="5" name="field_size" id="field_size">
            </div>
        </div>
        
        <div class="control-group" style="display:<?php echo $display_cols; ?>;">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_COLS');?>
            </label>
            <div class="controls">
                <input type="text" style="float: none !important;" value="<?php echo $cols; ?>" size="5" name="field_cols" id="field_cols">
            </div>
        </div>
        
        <div class="control-group" style="display:<?php echo $display_rows; ?>;">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_ROWS');?>
            </label>
            <div class="controls">
                <input type="text" style="float: none !important;" value="<?php echo $rows; ?>" size="5" name="field_rows" id="field_rows">
            </div>
        </div>
        
        <div class="control-group" style="display:<?php echo $display_option; ?>;">
            <label class="control-label">
                <?php echo JText::_('ARRA_FIELD_OPTION');?>
            </label>
            <div class="controls">
                <textarea rows="2" name="field_options" style="width: auto !important; float: none !important;"><?php echo $option; ?></textarea>
            </div>
        </div>
        
        <input type="button" class="btn btn-success" value="Save &amp; Close" onclick="javascript:Joomla.submitbutton('save')"/>
        <input type="button" class="btn" value="Cancel" onclick="javascript:Joomla.submitbutton('cancel')"/>			
	</fieldset>	
	
	<input type="hidden" name="option" value="com_arrausermigrate" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="for_delete" value="" />
	<input type="hidden" name="controller" value="editfield" />
	<input type="hidden" name="field_type" value="<?php echo $type; ?>" />
</form>
