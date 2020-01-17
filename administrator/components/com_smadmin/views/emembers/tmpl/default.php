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
require_once JPATH_COMPONENT .'/helpers/html/emember.php';
JHtml::_('formbehavior.chosen', 'select');

$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);
?>
<form action="index.php?option=com_smadmin&view=emembers" method="post" id="adminForm" name="adminForm">
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
				<?php echo JHtml::_('grid.sort', 'Email Subject', 'subject', $listDirn, $listOrder); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'Send By', 'supplierName', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Send To', 'link_name', $listDirn, $listOrder); ?>
			</th>
			<th width="2%">
				<?php echo JHtml::_('grid.sort', 'file', 'files', $listDirn, $listOrder); ?>
			</th>
			<th width="15%">
				<?php echo JHtml::_('grid.sort', 'Send Date', 'addedon', $listDirn, $listOrder); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'Approve Date', 'approveDate', $listDirn, $listOrder); ?>
			</th>
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
				<td colspan="7">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) :
					//$link = JRoute::_('index.php?option=com_smadmin&view=term&layout=edit&termId=' . $row->id);
				?>
					<tr>
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td>
							<?php echo JHtml::_('grid.id', $i, $row->id); ?>
						</td>
						<td><?php echo $row->subject; ?>
							<!--<a href="<?php //echo $link; ?>" title="<?php //echo JText::_('COM_SMADMIN_EDIT_TERM'); ?>">
								
							</a>-->
						</td>
						<td>
							<?php echo $row->supplierName; ?>
						</td>
						<td>
							<?php echo $row->link_name; ?>
						</td>
						<td>
						<?php $filepath=JURI::root()."upload/email/".$row->file; ?>
						<?php if($row->file): ?>
							<a href="<?php echo $filepath; ?>" target="_blank"><?php  if($row->file){echo $row->file;} else { echo "File not available";} ?></a>
						<?php else :?>
					    File not available
					    <?php endif; ?>		
						</td>
						
						<td>
							<?php $added = explode(" ",$row->addedon); echo $added['0']; ?>
						</td>
						
						<td>
							<?php echo $row->approveDate; ?>
						</td>
						
						<td align="center">
							<?php echo JHtml::_('Emember.approved', $row->approve, $i, 'emembers.'); ?>
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

