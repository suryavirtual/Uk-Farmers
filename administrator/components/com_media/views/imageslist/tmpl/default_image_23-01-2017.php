<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_media
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\Registry\Registry;

$params     = new Registry;
$dispatcher = JEventDispatcher::getInstance();
$dispatcher->trigger('onContentBeforeDisplay', array('com_media.file', &$this->_tmp_img, &$params));

$ext = strtolower(JFile::getExt($this->_tmp_img->name));
?>

<li class="imgOutline thumbnail height-80 width-80 center">
	<a class="img-preview" href="javascript:ImageManager.populateFields('<?php echo $this->_tmp_img->path_relative; ?>')" title="<?php echo $this->_tmp_img->name; ?>" >
		<div class="height-30">
			<?php if($ext == 'pdf') { ?>
				<img src="<?php echo JURI::root() . 'images/pdf.gif' ?>" alt="<?php echo $this->_tmp_img->title; ?>" width="<?php //echo $this->_tmp_img->width_60; ?>" height="<?php //echo $this->_tmp_img->height_60; ?>" />
			<?php } else { ?>
			<?php echo JHtml::_('image', $this->baseURL . '/' . $this->_tmp_img->path_relative, JText::sprintf('COM_MEDIA_IMAGE_TITLE', $this->_tmp_img->title, JHtml::_('number.bytes', $this->_tmp_img->size)), array('width' => $this->_tmp_img->width_60, 'height' => $this->_tmp_img->height_60)); ?>
			<?php } ?>
		</div>
		<div class="small">
			<?php echo JHtml::_('string.truncate', $this->_tmp_img->name, 30, false); ?>
		</div>
	</a>
</li>
<?php
$dispatcher->trigger('onContentAfterDisplay', array('com_media.file', &$this->_tmp_img, &$params));
