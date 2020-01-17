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
defined('_JEXEC') or die('Restricted access');
?>
<?php if($displayDropdown && count($description)): ?>
<div class="<?php echo $dropdownPosition; ?>">
	<select id="cd-dropdown" name="cd-dropdown" class="cd-select">
		<option value="-1" selected><?php echo $params->get('marker_selection_txt', JText::_('JAI_CHOOSE_OFFICE_LOCATION'));?></option>
		<?php
		foreach($description AS $des){
			$title = ($des->title) ? $des->title : JText::sprintf('JAI_TITLE_DEFAULT', $des->imgid);
			echo '<option value="ja-marker-'.$des->imgid.'">'.$title.'</option>';
		}
		?>
	</select>
</div>
<?php endif; ?>