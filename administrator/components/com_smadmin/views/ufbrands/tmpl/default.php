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
require_once JPATH_COMPONENT .'/helpers/html/ufbrand.php';
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrderingUrl = 'index.php?option=com_smadmin&task=ufbrands.saveorder';
JHtml::_('sortablelist.sortable', 'ufbrandList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);

//echo "<pre>"; print_r($this); echo "</pre>";
?>
<form action="index.php?option=com_smadmin&view=ufbrands" method="post" id="adminForm" name="adminForm">
	<div class="row-fluid">
		<div class="span6">
			<?php echo JText::_('COM_SMAMDIN_SMADMINS_FILTER'); ?>
			<?php
				echo JLayoutHelper::render(
					'joomla.searchtools.default',
					array('view' => $this)
				);
			?>
		</div>
	</div>
	<table class="table table-striped table-hover" id="ufbrandList">
		<thead>
		<tr>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>
			<th width="2%"><?php echo JHtml::_('grid.checkall'); ?></th>
			<th width="20%"><?php echo JHtml::_('grid.sort', 'Title', 'brandDtl', $listDirn, $listOrder); ?></th>
			<th width="5%"><?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?></th>
			<th width="2%"><?php echo JHtml::_('grid.sort', 'COM_SMADMIN_ID', 'id', $listDirn, $listOrder); ?></th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="5">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) :
					$orderkey = $row->ordering;
					$link = JRoute::_('index.php?option=com_smadmin&view=ufbrand&layout=edit&id=' . $row->id);
				?>
					<tr>
						<td class="order nowrap center hidden-phone">
						   <?php
						   $iconClass = '';
						   $canChange = true;
						   $saveOrder = true;
						   if (!$canChange)
						   {
							$iconClass = ' inactive';
						   }
						   elseif (!$saveOrder)
						   {
							$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
						   }
						   ?>
						   <span class="sortable-handler<?php echo $iconClass ?>">
							<span class="icon-menu"></span>
						   </span>
						   <?php if ($canChange && $saveOrder) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey; ?>" />
						   <?php endif; ?>
						  </td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td>
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_UFBRAND'); ?>">
								<?php echo "Country UF Brand" ?>
							</a>
						</td>
						
						<td align="center">
							<?php echo JHtml::_('Ufbrand.approved', $row->published, $i, 'ufbrands.'); ?>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

