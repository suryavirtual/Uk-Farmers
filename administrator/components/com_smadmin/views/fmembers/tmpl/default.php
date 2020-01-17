
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
?>
<link rel="stylesheet" href="assets/css/colorbox.css" />
<script src="assets/js/jquery.colorbox.js"></script>
<script>
jQuery(document).ready(function(){
	//Examples of how to assign the Colorbox event to elements
	jQuery(".inline").colorbox({inline:true, width:"50%"});
});

</script>
<?php
require_once JPATH_COMPONENT .'/helpers/html/fmember.php';
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('behavior.keepalive');
$database = JFactory::getDBO();

$listOrder     = $this->escape($this->filter_order);
$listDirn      = $this->escape($this->filter_order_Dir);
?>
<form action="index.php?option=com_smadmin&view=fmembers" method="post" id="adminForm" name="adminForm">
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
				<?php echo JHtml::_('grid.sort', 'File Description', 'description', $listDirn, $listOrder); ?>
			</th>
			<th width="20%">
				<?php echo JHtml::_('grid.sort', 'Send By', 'supplierName', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Send To', 'link_name', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'File Name', 'fname', $listDirn, $listOrder); ?>
			</th>
			<th width="5%"><?php echo 'Viewed'; ?></th>
			<th width="20%">
				<?php  echo JHtml::_('grid.sort', 'View by User', 'View by User', $listDirn, $listOrder); ?>
			</th>
			<th width="15%" style="text-align:center;"><?php echo 'Delete Request'; ?></th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'Approved', 'published', $listDirn, $listOrder); ?>
			</th>
			<!--<th width="2%">
				<?php // echo JHtml::_('grid.sort', 'COM_SMADMIN_ID', 'id', $listDirn, $listOrder); ?>
			</th>-->
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="8">
				<?php //echo "<pre>";
				//print_r($this->pagination); ?>
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) :
				//echo "<pre>"; print_r($row); echo "</pre>";
				 $query = "select sf.user_id,u.name,u.email from #__supplier_file_viewed as sf left join #__users as u on(u.id=sf.user_id) where sf.comp_id='$row->memberId' and sf.fid='$row->fileviewid'";
						$database->setQuery( $query );
						$member = $database->loadObjectList();
						//echo "<pre>";print_r($member);

						$totalusers=count($member); ?>
					<tr>
						<td><?php echo $this->pagination->getRowOffset($i); ?></td>
						<td><?php echo JHtml::_('grid.id', $i, $row->fileviewid); ?></td>
						<td><?php echo $row->description; ?></td>
						<td><?php echo $row->supplierName; ?></td>
						<td><?php echo $row->link_name; ?></td>
						<td><?php echo $row->fname; ?></td>
						<td style="text-align:center;">
							<?php if($row->viewEmail=='1'){?>
								<img src="templates/ukfarmer/images/admin/tick.png" alt="viewed" title="viewed" />
							<?php } else {?>
								<img src="templates/ukfarmer/images/admin/disabled.png" alt="not viewed" title="not viewed" />
							<?php } ?>
						</td>
						<?php 
						
						$k=0;
						if($totalusers==0)
						{
							$share = "Not Viewed";
						} else {
							$share = "<a class='inline' href=\"#inline_member$k\">Users</a>";
						}
					?>
					<td><?php echo $share;?></td>
					<td style="text-align:center;">
						<?php if($row->deleteRequest=='1'){?>
							<?php echo JHtml::_('Fmember.delreq', $row->deleteRequest, $i, 'tmembers.'); ?>
						<?php } else { ?>
							<img src="templates/ukfarmer/images/admin/disabled.png" alt="No Delete Request" title="No Delete Request" />
						<?php } ?>
					</td>
					<td align="center"><?php echo JHtml::_('Fmember.approved', $row->approve, $i, 'fmembers.'); ?></td>
				</tr>
				
				<?php if($totalusers > 0) { ?>
				<tr>
					<td colspan="5"  style='padding:0px !important; border:0px;'>
						<div style='display:none'>
							<div id="inline_member<?php echo $k; ?>">
								<table width='100%' border="1">
									<tr><th>S No</th><th>username</th><th>Email</th></tr>
									<?php $m=1; ?>
									<?php foreach($member as $users): ?>
										<tr>
										<td><?php echo $m; ?></td>
                             			<td><?php echo $users->name; ?></td>
                             			<td><?php echo $users->email; ?></td>
                             			</tr>
									<?php $m++; endforeach;?>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>
				<?php $k++;endforeach; ?>
			<?php endif; ?>
		</tbody>
		<!--view users table data-->


 <!--view users table ends here-->

	</table>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

