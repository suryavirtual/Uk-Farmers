<?php
/**
 * @package	Mosets Tree
 * @copyright	(C) 2014-present Mosets Consulting. All rights reserved.
 * @license	GNU General Public License
 * @author	Lee Cher Yeong <mtree@mosets.com>
 * @url		http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class mUpgrade_3_5_8 extends mUpgrade
{
	function upgrade() {
		$database = JFactory::getDBO();

		// Adds index for link_created
		$database->setQuery(
			'ALTER TABLE `#__mt_links` ADD INDEX `link_created` (`link_created`);'
		);
		$database->execute();

		updateVersion(3,5,8);
		$this->updated = true;
		return true;
	}
}
?>