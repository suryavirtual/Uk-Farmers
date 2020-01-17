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


$mcls = ' mb2moduleintabs_' . $module->id;
$mcls .= $params->get('moduleclass_sfx', '') ? ' ' . $params->get('moduleclass_sfx', '') : '';

?>
<div class="mb2moduleintabs<?php echo $mcls; ?> mb2moduleintabs-clr">
<?php
	

	// Check what is the layout which user want to use
	// 1 is tabs layout
	// 2 is panels (accrodion) layout
	if ($params->get('mb2_ltype', 1) == 2)
	{
		require JModuleHelper::getLayoutPath('mod_mb2moduleintabs', 'panels');
	}
	else
	{
		require JModuleHelper::getLayoutPath('mod_mb2moduleintabs', 'tabs');
	}
	
?>
</div>