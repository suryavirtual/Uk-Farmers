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
$obj = new SmAdminModelTerms();
?>
<link rel="stylesheet" href="assets/css/colorbox.css" />
<script src="assets/js/jquery.colorbox.js"></script>

<script>
jQuery(document).ready(function(){
	jQuery(".inline").colorbox({inline:true, width:"50%"});
	
});
</script>

<?php
require_once JPATH_COMPONENT .'/helpers/html/term.php';
JHtml::_('formbehavior.chosen', 'select');
$database = JFactory::getDbo();

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrderingUrl = 'index.php?option=com_smadmin&task=terms.saveorder';
JHtml::_('sortablelist.sortable', 'termList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
?>
<form action="index.php?option=com_smadmin&view=terms" method="post" id="adminForm" name="adminForm">
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
	<div class="fixed_top terms">
	<h2 class="headingss fixed_h1">Unpublished  Terms</h2>
	<table class="table table-striped table-hover" id="termList">
		<thead class="t1">
		<tr>

			<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>
			<th></th>
			<th width="2%">
				<?php //echo JHtml::_('grid.checkall'); ?>
<input type="checkbox" name="checkall-toggle" value="" class="hasTooltip" title="" onclick="" data-original-title="Check All">

			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_NAME', 'termName', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<a href="#">View Terms</a>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_SEND_BY', 'sendBy', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_START', 'validTo', $listDirn, $listOrder); ?>
			</th>
			<!--<th width="30%">
				<a href="#">Notifications sent?</a>
			</th>-->
			<th width="5%">
				<a href="#">Last Notifications sent?</a>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_EXPIRY', 'validTo', $listDirn, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'Delete', 'delete', $listDirn, $listOrder); ?>
			</th>
			
			
			<!--<th width="2%">
				<?php //echo JHtml::_('grid.sort', 'COM_SMADMIN_ID', 'id', $listDirn, $listOrder); ?>
			</th>-->
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
			<?php $i="";$k=0;if (!empty($this->items)) : ?>
				<?php foreach ($this->items as $i => $row) :
				if($row->ordering != "")
				{
				$orderkey = $row->ordering;	
				}
					
					$link = JRoute::_('index.php?option=com_smadmin&view=term&layout=edit&termId=' . $row->termId);
		                 /*new code addded here */
		                 $filesDtl = $obj->getTermsDetails($row->termId);
		                 /*new code for model ends here */
				?>
					<tr id="<?php echo $row->termId;?>">
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
						  <?php if(!empty($filesDtl)){?>
						  	<td class="slidedown_head" id="tog-<?php echo $row->termId;?>"><b class="plus-icon">+</b></td>
						  <?php } else { ?>
						  	<td>&nbsp;</td>
						  <?php } ?>
						<td width="2%">
							<input type="checkbox" class="boxchecked" id="cb<?php echo $i; ?>" value="<?php echo $row->termId;?>" name="cid[]" onclick="Joomla.isChecked(this.checked);">
						</td>
						<td width="30%">
							<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_TERM'); ?>">
								<?php echo $row->termName; ?>
							</a>
						</td>
                                                 <?php
					            $fPath = explode("/",$row->termsFile);
					            $cnt = count($fPath);
					            $tName = $fPath[$cnt-1];
					         ?>
						<td width="30%"><a href="<?php echo JURI::root().$row->termsFile; ?>" target="_blank"><?php echo $tName; ?></a></td>
						<td width="30%"><?php echo $row->link_name; ?></td>
						<td width="30%"><?php echo $row->effectiveFrom; ?></td>
						<?php if($row->sentNotification == 1){
						$checked = "checked";
					} else {
						$checked = "";
					} ?>
					<!--<td width="30%"><input type="checkbox" id="<?php echo $row->termId; ?>" <?php echo $checked; ?> name="cid[]" value="<?php echo $row->sentNotification; ?>" class="checkboxclass" ></td>
						<!--<td><input type="checkbox" id="cb<?php //echo $i; ?>" name="cid[]" value="<?php //echo $row->termId; ?>" class="checkboxclass" ></td>-->
						<td width="5%"><?php echo $row->lastnotification; ?></td>
						<td width="30%">
							<?php $todays_date = date("Y-m-d");
						$expiry_date = $row->validTo;
						$today = strtotime($todays_date);
						$expiration_date = strtotime($expiry_date);
						if ($expiration_date > $today) {
							echo '<span class="valid">'.$row->validTo.'</span>'; 
						} else {
							echo '<span style="color:#ff0000;">'.$row->validTo.'</span>'; 
						}
							?>
						</td>
					 
					  <td align="center"style="min-width: 60px"><?php echo JHtml::_('Term.approved', $row->status, $i, 'terms.'); ?></td>
					   <td style="min-width: 30px"><a href="index.php?option=com_smadmin&task=terms.deleteone&cid=<?php echo $row->termId;?>">
					  <img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/disabled.png"></a></td>
					  <!--<td align="center" width="2%"><?php //echo $row->termId; ?></td>-->	
					</tr>
					
					<tr class="slidedown_body" id="slides-<?php echo $row->termId;?>"></tr>


				<?php $i++; endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	</div>
	<!--new table code start from here -->

<div class="fixed_top terms">	
 <h2 class="headingss fixed_h2">Published Terms</h2>
 <div class="table-wrap">
<table class="table table-striped table-hover " id="termList1">
		<thead class="t2">
		<tr>
    	<th width="1%" class="nowrap center hidden-phone">
				<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
			</th>
			<th></th>
			<th width="2%">
				<?php //echo JHtml::_('grid.checkall'); ?>
				<input type="checkbox" name="checkall-toggle" value="" class="hasTooltip1" title="" onclick="" data-original-title="Check All">
			</th>
			<th width="30%">
				<?php //echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_NAME', 'termName', $listDirn, $listOrder); ?>
				<a href="<?php echo JURI::root(); ?>administrator/index.php?option=com_smadmin&view=terms&order=<?php echo isset($_GET['order'])?!$_GET['order']:1; ?>" class="hasTooltip" title="" data-original-title="<strong>Terms Filename</strong><br />Select to sort by this column">Terms Filename</a>
			</th>
			<th width="30%">
				<a href="#">View Terms</a>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_SEND_BY', 'sendBy', $listDirn, $listOrder); ?>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_START', 'validTo', $listDirn, $listOrder); ?>
			</th>
			<!--<th width="30%">
				<a href="#">Notifications sent?</a>
			</th>-->
			<th width="5%">
				<a href="#">Last Notifications sent?</a>
			</th>
			<th width="30%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_TERM_EXPIRY', 'validTo', $listDirn, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?>
			</th>
			<th width="5%">
				<?php echo JHtml::_('grid.sort', 'Delete', 'delete', $listDirn, $listOrder); ?>
			</th>
			
			
			<!--<th width="2%">
				<?php //echo JHtml::_('grid.sort', 'COM_SMADMIN_ID', 'id', $listDirn, $listOrder); ?>
			</th>-->
		</tr>
		</thead>
		<tfoot>
			<tr>
				<td colspan="7">
					<?php //echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
			<?php if (!empty($this->items1)) : ?>
				<?php $k=5; foreach ($this->items1 as $j => $row1) :
				if($row1->ordering !="")
				{
				$orderkey1 = $row1->ordering;	
				}
					
					$link1 = JRoute::_('index.php?option=com_smadmin&view=term&layout=edit&termId=' . $row1->termId);
					$filesDt2 = $obj->getTermsDetails($row1->termId);
		                        $supplierDtl = $obj->getSupplierDetails($row1->termId);
		                        $totalsupplier=count($supplierDtl);

		               /*ends here */
				?>
					<tr id="<?php echo $row1->termId;?>">
						<td class="order nowrap center hidden-phone" width="1%">
						   <?php
						   $iconClass1 = '';
						   $canChange1 = true;
						   $saveOrder1 = true;
						   if (!$canChange1)
						   {
							$iconClass = ' inactive';
						   }
						   elseif (!$saveOrder1)
						   {
							$iconClass1 = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
						   }
						   ?>
						   <span class="sortable-handler<?php echo $iconClass1 ?>">
							<span class="icon-menu"></span>
						   </span>
						   <?php if ($canChange1 && $saveOrder1) : ?>
							<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey1; ?>" />
						   <?php endif; ?>
						  </td>
						  <?php if(!empty($filesDt2)){?>
						  	<td class="slidedown_head" id="tog-<?php echo $row1->termId;?>"><b class="plus-icon">+</b></td>
						  <?php } else { ?>
						  	<td>&nbsp;</td>
						  <?php } ?>
						<td width="2%">
							<?php //echo JHtml::_('grid.id', $i, $row1->termId); ?>
							<input type="checkbox" class="boxchecked1" id="cb<?php echo $i; ?>" value="<?php echo $row1->termId;?>" name="cid[]" onclick="Joomla.isChecked(this.checked);">
						</td>
						<td width="30%">
							<a href="<?php echo $link1; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_TERM'); ?>">
								<?php echo $row1->termName; ?>
							</a>
						</td>
                                                <?php
					          $tPath1 = explode("/",$row1->termsFile);
					          $cnt1 = count($tPath1);
					          $tName1 = $tPath1[$cnt1-1];
					         ?>
						<td width="30%"><a href="<?php echo JURI::root().$row1->termsFile; ?>" target="_blank"><?php echo $tName1; ?></a></td>
						<!--<td>
							<?php // echo $row1->sendBy; ?>
						</td>-->
						<?php if($totalsupplier>1):?>

						<td width="30%">
						<?php foreach($supplierDtl  as $value):?>
							<?php echo $value->link_name."<br>"; ?>
						<?php endforeach; ?>	
						</td>
					<?php else:?>
						<td width="30%">
							<?php echo $row1->link_name; ?>
						</td>
					<?php endif; ?>
						<td width="30%">
						<?php echo $row1->effectiveFrom; ?>
						</td>
						<?php if($row1->sentNotification == 1){
						$checked = "checked";
					} else {
						$checked = "";
					} ?>
					<!--<td width="30%"><input type="checkbox" id="<?php echo $row1->termId; ?>" <?php echo $checked; ?> name="cid[]" value="<?php echo $row1->sentNotification; ?>" class="checkboxclass" ></td>
						<!--<td><input type="checkbox" id="cb<?php echo $i; ?>" name="cid[]" value="<?php //echo $row->termId; ?>" class="checkboxclass" ></td>-->
						<td width="5%"><?php echo $row1->lastnotification; ?></td>
						<td width="30%">
							<?php $todays_date = date("Y-m-d");
						$expiry_date = $row1->validTo;
						$today = strtotime($todays_date);
						$expiration_date = strtotime($expiry_date);
						if ($expiration_date > $today) {
							echo '<span class="valid">'.$row1->validTo.'</span>'; 
						} else {
							echo '<span style="color:#ff0000;">'.$row1->validTo.'</span>'; 
						}
							?>
						</td>
						<td style="min-width: 60px" align="center" class="checking" width="5%">
							<?php echo JHtml::_('Term.approved', $row1->status, $i, 'terms.'); ?>
						</td>
						<td style="min-width: 30px"><div class="glyphicon glyphicon-remove"><a href="index.php?option=com_smadmin&task=terms.deleteone&cid=<?php echo $row1->termId;?>">
						<img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/disabled.png"></a></td>
						

						<!--<td align="center" width="2%" >
							<?php //echo $row1->termId; ?>
						</td>-->
					</tr>
					<tr class="slidedown_body" id="slides-<?php echo $row1->termId;?>"></tr>

				<?php $i++; endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
</div>
</div>
	
	<input type="hidden" name="task" value=""/>
	<input type="hidden" name="boxchecked" value="0"/>
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
	<?php echo JHtml::_('form.token'); ?>
</form>
<script>
  jQuery(document).ready(function()
  {
   jQuery(".slidedown_body").slideUp();
 jQuery('.slidedown_head').click(function()
    { 
		var current_tr = jQuery(this).parents().attr('id');
    	var id = jQuery(this).attr('id');
  	    var txt = jQuery('#'+id).text();

      var current = jQuery(this).parent().next();
      console.log(current);
      var thenum = id.replace( /^\D+/g, ''); 
		/*new code for ajax request for fetching child table from controller */
		var sitePath = '<?php  echo JURI::root() ?>'; 
        var url = sitePath + 'administrator/index.php?option=com_smadmin&task=terms.getchildtables';
		jQuery.ajax({
				type: 'post',
				url: url,
				data: {term_id:thenum},
				success:function(data)
				{
					jQuery('#slides-'+current_tr).html(data);
					jQuery(".inline").colorbox({inline:true, width:"50%"});
				}
			});
		/*ajax function ends here */
 

  jQuery(".slidedown_body").each(function(){

//jQuery(this).slideUp(2000);
jQuery(".slidedown_body").prev("tr").find(".slidedown_head").html('<b class="plus-icon">+</b>');
jQuery(this).slideUp("fast");
  });

   jQuery(current).slideToggle();

        if(txt=='+'){
  		jQuery('#'+id).html('<b class="plus-icon">-</b>');
  	}
  	if(txt=='-'){
  		jQuery(current).slideUp();
  		jQuery('#'+id).html('<b class="plus-icon">+</b>');
  	}


   });


   });
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

