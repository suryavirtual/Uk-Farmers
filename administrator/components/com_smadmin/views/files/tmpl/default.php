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
$obj = new SmAdminModelFiles();

?>
<link rel="stylesheet" href="assets/css/colorbox.css" />
<script src="assets/js/jquery.colorbox.js"></script>
<script>
jQuery(document).ready(function(){
	jQuery(".slidedown_body").slideUp();
	//Examples of how to assign the Colorbox event to elements
	jQuery(".member").colorbox({inline:true, width:"50%"});
});

</script>

<?php
require_once JPATH_COMPONENT .'/helpers/html/file.php';
JHtml::_('formbehavior.chosen', 'select');

$database = JFactory::getDbo();
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));

$saveOrderingUrl = 'index.php?option=com_smadmin&task=files.saveorder';
JHtml::_('sortablelist.sortable', 'fileList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
?>
<form action="index.php?option=com_smadmin&view=files" method="post" id="adminForm" name="adminForm">
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
<div class="fixed_top files">	
<h2 class="headingss fixed_h1">Unpublished Files</h2>
<table class="table table-striped table-hover" id="fileList">
<thead class="t1">
	<tr>
		<th width="1%" class="nowrap center hidden-phone">
			<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
		</th>
		<th><!-- for + icon --></th>
		<th >
		<input type="checkbox" name="checkall-toggle" value="" class="hasTooltip" title="" onclick="" data-original-title="Check All"></th>
		<th width="20%"><?php echo JHtml::_('grid.sort', 'File Description', 'description', $listDirn, $listOrder); ?></th>
		<th width="15%"><?php echo JHtml::_('grid.sort', 'File Name', 'name', $listDirn, $listOrder); ?></th>
		<th width="15%"><?php echo JHtml::_('grid.sort', 'Supplier Name', 'suppliername', $listDirn, $listOrder); ?></th>
		<th width="20%"><?php echo JHtml::_('grid.sort', 'Sent By', 'sentby', $listDirn, $listOrder); ?></th>
		<th width="20%"><a href="#">Last Notifications sent</a></th>
		<th width="20%"><?php echo JHtml::_('grid.sort', 'Date Added', 'dateadded', $listDirn, $listOrder); ?></th>
		<th width="30%"><?php echo JHtml::_('grid.sort', 'File Expiry', 'expiry', $listDirn, $listOrder); ?></th>
	<!--	<th width="30%">sent To</th>-->
		<th width="3%"><?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?></th>
		<th width="10%"><?php echo JHtml::_('grid.sort', 'Delete', 'Delete', $listDirn, $listOrder); ?></th>
	</tr>
</thead>

<tfoot>
	<tr>
		<td colspan="13">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>

<tbody>
	<?php $q=0;$i="";$k=0;
	if (!empty($this->items)) : 
		foreach ($this->items as $i => $row) :
			$orderkey = $row->ordering;
			$link = JRoute::_('index.php?option=com_smadmin&view=file&layout=edit&id=' . $row->id);
			$filesDtl1 = $obj->getFileDetails($row->id);
			$emailDtl = $obj->getEmailDtail($row->filename);
			$totalemail=count($emailDtl); ?>
			<tr id="<?php echo $row->id;?>">
				<td class="order nowrap center hidden-phone" width="1%" >
					<?php $iconClass = '';
					$canChange = true;
					$saveOrder = true;
					if (!$canChange){ $iconClass = ' inactive'; } 
					elseif(!$saveOrder){$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');} ?>
					<span class="sortable-handler<?php echo $iconClass ?>"><span class="icon-menu"></span></span>
					<?php if ($canChange && $saveOrder) : ?>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey; ?>" />
					<?php endif; ?>
				</td>
				<?php if(!empty($filesDtl1)){?>
					<td class="slidedown_head" id="tog-<?php echo $row->id;?>"><b class="plus-icon">+</b></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
				<td width="1%">
				<input type="checkbox" class="boxchecked" id="cb<?php echo $i; ?>" value="<?php echo $row->id;?>" name="cid[]" onclick="Joomla.isChecked(this.checked);">
				</td>
				<td width="20%">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_FILE'); ?>">
						<?php echo $row->description; ?>
					</a>
				</td>
				<td width="15%">
					<?php
					$fPath = explode("/",$row->filename);
					$cnt = count($fPath);
					$fName = $fPath[$cnt-1]; ?>
					<a href="<?php echo JURI::root().$row->filename; ?>" target="_blank"><?php echo  $fName; ?></a></td>
				
				<td width="15%"><?php echo $row->link_name;?></td>
				<td width="20%">
				<?php 
				if($row->added_by=="1")
				{
                 echo "UF Admin";
				}
				else
				{

               $emailDtlsss = $obj->getEmailDtails($row->id);
			   echo $emailDtlsss->email; 
				}
				?></td>
				<td width="20%"><?php echo $row->lastnotification; ?></td>
				<td width="20%">
					<?php $upload=$row->uploaded;
					$uploadate = new DateTime($upload);
					echo $uploadates= date_format($uploadate, 'Y-m-d'); ?>
				</td>
				<td width="30%"><?php $todays_date = date("Y-m-d");
					$expiry_date = $row->expiry;
					$today = strtotime($todays_date);
					$expiration_date = strtotime($expiry_date);
					if ($expiration_date > $today) {
						echo '<span class="valid">'.$row->expiry.'</span>'; 
					} else {
						echo '<span style="color:#ff0000;">'.$row->expiry.'</span>'; 
					} ?>
				</td>
				<?php 
		               $totalsentmemberDtl1=count($filesDtl1);
		        if($totalsentmemberDtl1== "0"){
			                 $share = "Not Share";
		             } else 
		             {
			         $share = "<a class='member' href=\"#inline_member1$q\">Members</a>";

		            }?>
		       <!--  <td><?php echo $share; ?></td>-->
				<td width="3%">
					<?php echo JHtml::_('File.approved', $row->approved, $i, 'files.'); ?>
				</td>
				<td width="10%">
					<a href="index.php?option=com_smadmin&task=files.deleteone&cid=<?php echo $row->id;?>">
					<img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/disabled.png"></a>
				</td>
			</tr>	
			<tr class="slidedown_body" id="slides-<?php echo $row->id;?>">
				
			</tr>
			<tr>
        <?php if(!empty($filesDtl1)){?>
			<td colspan="5"  style='padding:0px !important; border:0px;'>

				<div style='display:none'>

					<div id="inline_member1<?php echo $q; ?>" style='padding:10px; background:#fff; height:400px; overflow-x:scroll;'>

						<table width='100%' border="1">

							<tr><th>S No</th><th>Members</th></tr>

							<?php $m=1;

							foreach($filesDtl1  as $memberFiles )
							{

								echo "<tr><td>".$m."</td><td>".$memberFiles->link_name."</td></tr>";

								$m++;

							} ?>

						</table>

					</div>

				</div>

			</td>

		</tr>
		<?php } ?>
			<?php $q++;$i++;endforeach; ?>
			<?php endif; ?>
</tbody>
</table>
</div>
<!--new table code start from here -->
<div class="fixed_top files">	
 <h2 class="headingss fixed_h2">Published Files</h2>
<table class="table table-striped table-hover ufiles" id="fileList">
<thead class="t1">
	<tr>
		<th width="1%" class="nowrap center hidden-phone">
			<?php echo JHtml::_('searchtools.sort', '', 'a.lft', $listDirn, $listOrder, null, 'asc', 'JGRID_HEADING_ORDERING', 'icon-menu-2'); ?>
		</th>
		<th><!-- for + icon --></th>
		<th >
		<input type="checkbox" name="checkall-toggle" value="" class="hasTooltip1" title="" onclick="" data-original-title="Check All"></th>
		<th width="20%"><?php echo JHtml::_('grid.sort', 'File Description', 'description', $listDirn, $listOrder); ?></th>
		<th width="15%"><?php echo JHtml::_('grid.sort', 'File Name', 'name', $listDirn, $listOrder); ?></th>
		<th width="15%"><?php echo JHtml::_('grid.sort', 'Supplier Name', 'suppliername', $listDirn, $listOrder); ?></th>
		<th width="20%"><?php echo JHtml::_('grid.sort', 'Sent By', 'sentby', $listDirn, $listOrder); ?></th>
		<th width="20%"><a href="#">Last Notifications sent</a></th>
		<th width="20%"><?php echo JHtml::_('grid.sort', 'Date Added', 'dateadded', $listDirn, $listOrder); ?></th>
		<th width="30%"><?php echo JHtml::_('grid.sort', 'File Expiry', 'expiry', $listDirn, $listOrder); ?></th>
		<th width="30%">Resend Mail</th>
		<th width="3%"><?php echo JHtml::_('grid.sort', 'COM_SMADMIN_PUBLISHED', 'published', $listDirn, $listOrder); ?></th>
		<th width="10%"><?php echo JHtml::_('grid.sort', 'Delete', 'Delete', $listDirn, $listOrder); ?></th>
	</tr>
</thead>

<tfoot>
	<tr>
		<td colspan="13">
			<?php //echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>

<tbody>
<?php //echo "<pre>"; print_r($this->items1); ?>
	<?php $k=0; $p=0;if (!empty($this->items1)) :
		foreach ($this->items1 as $j => $row) :
			$orderkey = $row->ordering;
			$link = JRoute::_('index.php?option=com_smadmin&view=file&layout=edit&id=' . $row->id);
			$filesDtl = $obj->getFileDetails($row->id);
			$emailDtl = $obj->getEmailDtail($row->filename);
              ##########  to hide expiry publish data############
			     $todays_date = date("Y-m-d");
					$expiry_date = $row->expiry;
					$today = strtotime($todays_date);
					$expiration_date = strtotime($expiry_date);
					//if ($expiration_date > $today) { ?>
			<tr id="<?php echo $row->id;?>">
				<td class="order nowrap center hidden-phone" width="1%">
					<?php $iconClass = '';
					$canChange = true;
					$saveOrder = true;
					if (!$canChange){ $iconClass = ' inactive'; }
					elseif (!$saveOrder){
						$iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
					} ?>
					<span class="sortable-handler<?php echo $iconClass ?>"><span class="icon-menu"></span></span>
					<?php if ($canChange && $saveOrder) : ?>
						<input type="text" style="display:none" name="order[]" size="5" value="<?php echo $orderkey; ?>" />
					<?php endif; ?>
				</td>
				<?php if(!empty($filesDtl)){?>
					<td class="slidedown_head" id="tog-<?php echo $row->id;?>"><b class="plus-icon">+</b></td>
				<?php } else { ?>
					<td>&nbsp;</td>
				<?php } ?>
				<td width="1%">
				<?php //echo JHtml::_('grid.id', $i, $row->id); ?>
				<input type="checkbox" class="boxchecked1" id="cb<?php echo $i; ?>" value="<?php echo $row->id;?>" name="cid[]" onclick="Joomla.isChecked(this.checked);"></td>
				<td style="min-width: 259px">
					<a href="<?php echo $link; ?>" title="<?php echo JText::_('COM_SMADMIN_EDIT_FILE'); ?>">
						<?php echo $row->description; ?>
					</a>
				</td>
				<td width="15%">

					<a href="<?php echo JURI::root().$row->filename; ?>" target="_blank">
						<?php echo end(explode('/', $row->filename)); ?>
					</a>
				</td>
				<td width="20%"><?php echo $row->link_name;?></td>
				<td><?php 
				if($row->added_by=="1")
				{
                 echo "UF Admin";
				}
				else
				{
                   $emailDtlsss = $obj->getEmailDtails($row->id);
				echo $emailDtlsss->email;
				}?></td>
				<td width="20%"><?php echo $row->lastnotification; ?></td>
				<td style="max-width: 100px"><?php $upload=$row->uploaded;
					$uploadate = new DateTime($upload);
					echo $uploadates= date_format($uploadate, 'Y-m-d');  ?>
				</td>
				 <td width="30%"><?php $todays_date = date("Y-m-d");
                                        $expiry_date = $row->expiry;
                                        $today = strtotime($todays_date);
                                        $expiration_date = strtotime($expiry_date);
                                        if ($expiration_date > $today) {
                                                echo '<span class="valid">'.$row->expiry.'</span>';
                                        } else {
                                                echo '<span style="color:#ff0000;">'.$row->expiry.'</span>';
                                        } ?>
                                </td>
				<?php //$sentmemberDtl = $obj->getsharememberdetails($row->id);
		               $totalsentmemberDtl=count($filesDtl);
		        if($totalsentmemberDtl== "0"){
			                 $share = "Not Share";
		             } else 
		             {
			         $share = "<a class='' href=\"#inline_sharemember$p\">Members</a>";

		            }?>
		        <!--<td><?php  echo $share; ?></td> -->
		        <td><a href="index.php?option=com_smadmin&task=files.resendmail&fid=<?php echo $row->id;?>&comp_id=<?php echo $row->comp_id; ?>">
						<img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/resend.png">
					</a></td> 
				<td align="center" style="min-width: 30px"><?php echo JHtml::_('File.approved', $row->approved, $i, 'files.'); ?></td>
				<td align="center" style="min-width: 60px">
					<a href="index.php?option=com_smadmin&task=files.deleteone&cid=<?php echo $row->id;?>">
						<img src="<?php echo JURI::root(); ?>/administrator/templates/ukfarmer/images/admin/disabled.png">
					</a>
			</td>
			</tr>
			<?php // } ?>
			<tr class="slidedown_body" id="slides-<?php echo $row->id;?>">
				
			</tr>
			
		<?php $p++;$i++; endforeach;
	endif; ?>
</tbody>
</table>
</div>

<!--new table ends here -->
<input type="hidden" name="task" value=""/>
<input type="hidden" name="boxchecked" value="0"/>
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>"/>
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>"/>
<?php echo JHtml::_('form.token'); ?>
</form>

<script>

jQuery(document).ready(function(){
	
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
        var url = sitePath + 'administrator/index.php?option=com_smadmin&task=files.getchildtable';
		jQuery.ajax({
				type: 'post',
				url: url,
				data: {file_id:thenum},
				success:function(data)
				{
					console.log(data);
					jQuery('#slides-'+current_tr).html(data);
					jQuery(".inline").colorbox({inline:true, width:"50%"});
				}
			});
		/*ajax function ends here */
		
		jQuery(".slidedown_body").each(function(){
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
	
	//new code for checkbox
	$('.checkboxclass').click(function(){
		if(jQuery(this).is(":checked")){
			var userids=jQuery(this).val();
    		var fileid=jQuery(this).attr('id');
			var sitePath = '<?php  echo JURI::root() ?>';
			//var url = sitePath + 'administrator/index.php?option=com_smadmin&task=fmembers.notification';
			
			jQuery.ajax({
				type: 'post',
				url: url,
				data: {user_id:userids,file_id:fileid},
				success:function(data){
					//console.log(data);
				}
			});
		} else {
    		//alert("unchecked");
    	}
	});
	//new code for checkbox ends here
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
