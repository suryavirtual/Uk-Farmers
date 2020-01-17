<?php
/**
 * @package	Mosets Tree
 * @copyright	(C) 2014-present Mosets Consulting. All rights reserved.
 * @license	GNU General Public License
 * @author	Lee Cher Yeong <mtree@mosets.com>
 * @url		http://www.mosets.com/tree/
 */

defined('_JEXEC') or die('Restricted access');

class mUpgrade_3_5_7 extends mUpgrade
{
	function upgrade() {

		updateVersion(3,5,7);
		$this->updated = true;
		return true;
	}
}
?>