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
$obj = new SmAdminModelEmails();
?>
<link rel="stylesheet" href="assets/css/colorbox.css" />
<script src="assets/js/jquery.colorbox.js"></script>

<script>
jQuery(document).ready(function(){
	//Examples of how to assign the Colorbox event to elements
	jQuery(".inline").colorbox({inline:true, width:"50%"});
});


</script>
<style>

</style>

<?php 
$database = JFactory::getDbo();
require_once JPATH_COMPONENT .'/helpers/html/email.php';
JHtml::_('formbehavior.chosen', 'select');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrderingUrl = 'index.php?option=com_smadmin&task=emails.saveorder';
JHtml::_('sortablelist.sortable', 'emailList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
?>
<form action="index.php?option=com_smadmin&view=emails" method="post" id="adminForm" name="adminForm">
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
	<div class="fixed_top emails">
	<h2 class="headingss fixed_h1">Unpublished  Emails</h2>
	<table class="table table-striped table-hover" id="emailList">
		<thead class="t1">
		<tr>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>
			<th width="2%">
				<input name="checkall-toggle" value="" class="hasTooltip" title="" onclick=" " data-original-title="Check All" type="checkbox">
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Email Subject', 'subject', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Supplier Name', 'sendBy', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<a herf="#">Email Sent By</a>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'File', 'File', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Sent Date', 'addedon', $listDirn, $listOrder); ?>
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
			<?php $i=""; if (!empty($this->items)) : ?>
				<?php $k=0; foreach ($this->items as $i => $row) :
					$orderkey = $row->ordering;
					$link = JRoute::_('index.php?option=com_smadmin&view=email&layout=edit&id=' . $row->id);
					$member = $obj->getMemberDetails($row->id);
		                 $totalusers=count($member);
					/* code ends here */
				?>
					<tr>
						<td class="order nowrap center hidden-phone" width="1%">
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
						<td width="2%">
							<input type="checkbox" class="boxchecked" id="cb<?php echo $i; ?>" value="<?php echo $row->id;?>" name="cid[]" onclick="Joomla.isChecked(this.checked);">
						</td>
						<td width="30%">
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_TERM'); ?>">
								<?php echo $row->subject; ?>
							</a>
						</td>
						<td width="30%">
							<?php echo $row->link_name;  ?>
						</td>
						<?php if($totalusers==0)
						{
							$share = "Not Sent";
						} else {
							$share = "<a class='inline' href=\"#inline_member$k\">Members</a>";
						} ?>
						<td width="30%"><?php echo $row->email; ?></td>
						<td width="30%">
							<?php $filepath=JURI::root()."upload/email/".$row->file; ?>
						<?php if($row->file): ?>
							<a href="<?php echo $filepath; ?>" target="_blank"><?php echo $row->file ?></a>
						<?php else :?>
					    File not available
					    <?php endif; ?>	
						</td>
						<td width="30%">
							<?php $send_date = explode(" ",$row->addedon);
							echo $send_date['0']; ?>
						</td>
						
						<td align="center" width="5%">
							<?php echo JHtml::_('Email.approved', $row->status, $i, 'emails.'); ?>
						</td>
						<td align="center" width="2%">
							<?php echo $row->id; ?>
						</td>
					</tr>
					 <?php if($totalusers > 0) { ?>
				<tr>
					<td colspan="5"  style='padding:0px !important; border:0px;'>
						<div style='display:none'>
							<div id="inline_member<?php echo $k; ?>">
								<table width='100%' border="1">
									<tr><th>S No</th><th>username</th><th>Approve Date</th><th> Email</th><th>Send Email</th></tr>
									<?php $m=1; ?>
									<?php foreach($member as $users): ?>
										<tr>
										<td><?php echo $m; ?></td>
                             			<td><?php echo $users->link_name; ?></td>
                             			
                             			<td><?php echo $users->approveDate; ?></td>
                             			<td><?php echo $users->email; ?></td>
                             			<td>
                             			<?php if($users->approve == "0"): ?>
                             			<a href="index.php?option=com_smadmin&task=emembers.approved&cid=<?php echo $users->id;?>"><img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/disabled.png" alt=""></a>
                             		<?php else: ?>
                             			<img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/tick.png" alt="">
                             		<?php endif; ?>
                             			</td>
                             			</tr>
									<?php $m++; endforeach;?>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>

				<?php $i++; $k++; endforeach;  ?>
			<?php endif; ?>
		</tbody>
	</table>
	</div>
		<!--new table code start from here -->
 
 <div class="fixed_top emails">	
 <h2 class="headingss fixed_h2">Published Emails</h2>
 <table class="table table-striped table-hover" id="emailList1">
		<thead class="t2">
		<tr>
			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>
			<th width="2%">
				<input name="checkall-toggle" value="" class="hasTooltip1" title="" onclick=" " data-original-title="Check All" type="checkbox">
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Email Subject', 'subject', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Supplier Name', 'sendBy', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<a herf="#">Email Sent By</a>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'File', 'File', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'Sent Date', 'addedon', $listDirn, $listOrder); ?>
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
			<?php if (!empty($this->items1)) : ?>
				<?php $k=0; foreach ($this->items1 as $j => $row) :
					$orderkey = $row->ordering;
					$link = JRoute::_('index.php?option=com_smadmin&view=email&layout=edit&id=' . $row->id);
					$member = $obj->getMemberDetails($row->id);
		            $totalusers=count($member);
					/* code ends here */
				?>
					<tr>
						<td class="order nowrap center hidden-phone" width="1%">
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
						<td width="2%">
							<?php //echo JHtml::_('grid.id', $i, $row->id); ?>
							<input type="checkbox" class="boxchecked1" id="cb<?php echo $i; ?>" value="<?php echo $row->id;?>" name="cid[]" onclick="Joomla.isChecked(this.checked);">
						</td>
						<td width="30%">
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_TERM'); ?>">
								<?php echo $row->subject; ?>
							</a>
						</td>
						<td width="30%">
							<?php echo $row->link_name; ?>
						</td>
						<?php if($totalusers==0)
						{
							$share = "Not Sent";
						} else {
							$share = "<a class='inline' href=\"#inline_member$k\">Members</a>";
						} ?>
						<td width="30%"><?php echo $row->email; ?></td>
						<td>
							<?php $filepath=JURI::root()."upload/email/".$row->file; ?>
						<?php if($row->file): ?>
							<a href="<?php echo $filepath; ?>" target="_blank"><?php echo $row->file ?></a>
						<?php else :?>
					    File not available
					    <?php endif; ?>	
						</td>
						<td>
							<?php $send_date = explode(" ",$row->addedon);
							echo $send_date['0']; ?>
						</td>
						
						<td align="center" style="min-width: 60px;">
							<?php echo JHtml::_('Email.approved', $row->status, $i, 'emails.'); ?>
						</td>
						<td align="center">
							<?php echo $row->id; ?>
						</td>
					</tr>
					 <?php if($totalusers > 0) { ?>
				<tr>
					<td colspan="5"  style='padding:0px !important; border:0px;'>
						<div style='display:none'>
							<div id="inline_member<?php echo $k; ?>">
								<table width='100%' border="1">
									<tr><th>S No</th><th>username</th><th>Approve Date</th><th>Email</th><th>Send Email</th></tr>
									<?php $m=1; foreach($member as $users): ?>
										<tr>
										<td><?php echo $m; ?></td>
                             			<td><?php echo $users->link_name; ?></td>
                             			<td><?php echo $users->approveDate; ?></td>
                             			<td><?php echo $users->email; ?></td>
                             			<td>
                             			<?php if($users->approve == "0"): ?>
                             			<a href="index.php?option=com_smadmin&task=emembers.approved&cid=<?php echo $users->id;?>"><img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/disabled.png" alt=""></a>
                             		<?php else: ?>
                             			<img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/tick.png" alt="">
                             		<?php endif; ?>
                             			</td>
                             			</tr>
									<?php $m++; endforeach;?>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<?php } ?>

				<?php $i++; $k++; endforeach;  ?>
			<?php endif; ?>
		</tbody>
	</table>
	</div>
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>

<script>
//new code for  unpublished checkbox
 jQuery(document).on('click', '.hasTooltip', function()
 {
 if(jQuery(this). prop("checked") == true)
 {
  jQuery('.boxchecked').prop('checked', true);

  Joomla.isChecked(this.checked);
  
  }
 else
 {
 jQuery('.boxchecked').prop('checked', false);
 }

  });
 //new code for checkbox ends here
 //new code for  unpublished checkbox
 jQuery(document).on('click', '.hasTooltip1', function()
 {
 if(jQuery(this). prop("checked") == true)
 {
  jQuery('.boxchecked1').prop('checked', true);

  Joomla.isChecked(this.checked);
  
  }
 else
 {
 jQuery('.boxchecked1').prop('checked', false);
 }

 });
 //new code for checkbox ends here
	
</script>

