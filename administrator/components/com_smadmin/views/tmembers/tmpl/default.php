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
require_once JPATH_COMPONENT .'/helpers/html/tmember.php';
JHtml::_('formbehavior.chosen', 'select');

$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);
?>
<form action="index.php?option=com_smadmin&view=tmembers" method="post" id="adminForm" name="adminForm">
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
	<table class="table table-striped table-hover">
		<thead>
		<tr>
			<th width="1%"><?php echo JText::_('COM_SMADMIN_NUM'); ?></th>
			<th width="2%">
				<?php echo JHtml::_('grid.checkall'); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'Term Name', 'termName', $listDirn, $listOrder); ?>
			</th>
			<th width="25%">
				<?php echo JHtml::_('grid.sort', 'Send By', 'sendBy', $listDirn, $listOrder); ?>
			</th>
			<th width="25%">
				<?php echo JHtml::_('grid.sort', 'Send To', 'validTo', $listDirn, $listOrder); ?>
			</th>
			<th width="5%"><?php echo 'Viewed'; ?></th>
			<th width="15%" style="text-align:center;"><?php echo 'Delete Request'; ?></th>
			<th width="10%"><?php echo 'Notify Email'; ?></th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_ID', 'id', $listDirn, $listOrder); ?>
			</th>
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) :
					$link = JRoute::_('index.php?option=com_smadmin&view=term&layout=edit&termId=' . $row->termId);
				?>
					<tr>
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td><?php echo $row->termName; ?></td>
						<td>
							<?php echo $row->sendBy; ?>
						</td>
						<td>
							<?php echo $row->link_name; ?>
						</td>
						<td style="text-align:center;">
							<?php if($row->viewEmail=='1'){?>
								<img src="templates/ukfarmer/images/admin/tick.png" alt="viewed" title="viewed" />
							<?php } else {?>
								<img src="templates/ukfarmer/images/admin/disabled.png" alt="not viewed" title="not viewed" />
							<?php } ?>
						</td>
						<td style="text-align:center;">
							<?php if($row->deleteRequest=='1'){?>
								<?php echo JHtml::_('Tmember.delreq', $row->deleteRequest, $i, 'tmembers.'); ?>
							<?php } else { ?>
								<img src="templates/ukfarmer/images/admin/disabled.png" alt="No Delete Request" title="No Delete Request" />
							<?php } ?>
						</td>
						<td style="text-align:center;">
							<?php if($row->email_sent=='0'){?>
								<?php echo JHtml::_('Tmember.notify', $row->email_sent, $i, 'tmembers.'); ?>
							<?php } else { ?>
								<img src="templates/ukfarmer/images/admin/tick.png" alt="No Delete Request" title="No Delete Request" />
							<?php } ?>
							
							
						</td>
						<td style="text-align:center;">
							<?php echo JHtml::_('Tmember.approved', $row->approved, $i, 'tmembers.'); ?>
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

