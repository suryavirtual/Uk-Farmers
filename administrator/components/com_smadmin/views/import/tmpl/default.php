<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
JHtml::_('formbehavior.chosen', 'select');
$db = JFactory::getDbo();
//echo $baseurl = JURI::base();
$baseuri = JURI::root().'export.php';	



?>

<form action="index.php?option=com_smadmin&task=imports.upload" method="post" id="accessForm" name="adminForm" enctype="multipart/form-data">
	<div id="infoMsg"></div>
	<div class="control-group">
	<form method="post" >
<input type="file" name="files">



	

	
	<input type="submit" name="Save" id="save_bottom" value="Submit" />
	</form>
	<a href="<?php echo $baseuri; ?>">
    <button class="btn btn-warning" type="button">Permission Export</button>
	</a>
		
	</div>
	<?php echo JHtml::_('form.token'); ?>
</form>



