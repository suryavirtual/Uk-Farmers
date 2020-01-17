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
JHtml::_('formbehavior.chosen', 'select');
$db = JFactory::getDbo();

// get supplier company list
$querySupplier="SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name";
$db->setQuery($querySupplier);
$supplierList = $db->loadObjectList();

// get member company list
$queryMember="SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Member' and mt.link_published='1' order by mt.link_name";
$db->setQuery($queryMember);
$memberList = $db->loadObjectList();
?>

<form method="post" id="accessForm" name="adminForm">
	<div id="infoMsg"></div>
	<div class="control-group">
		<div class="control-label"></div>
		<div class="controls">
			<select name="supplier" id="supplier">
				<option value="">Select Supplier</option>
				<option value="all">All</option>
				<?php foreach($supplierList as $supplierLists){ ?>
					<option value="<?php echo $supplierLists->link_id; ?>"><?php echo $supplierLists->link_name; ?></option>
				<?php } ?>
			</select>
			<div class='msg'></div>
		</div>
	</div>
	
	<div class="control-group">
		<div class="control-label"></div>
		<div class="controls">
			<select name="member" id="member">
				<option value="">Select Member</option>
				<option value="all">All</option>
				<?php foreach($memberList as $memberLists){ ?>
					<option value="<?php echo $memberLists->link_id; ?>"><?php echo $memberLists->link_name; ?></option>
				<?php } ?>
			</select>
			<div class='msg'></div>
		</div>
	</div>
	
	<input type="button" name="Save" id="save" value="Submit" style="display:none" />
	
	<!-- member's users with the permissions -->
	<div id="member_user_list"></div>
	<input type="button" name="Save" id="save_bottom" value="Submit" style="display:none" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<script language="javascript">
$=jQuery;
$(document).ready(function(){
	$("#member").on('change', function(){
		$('.successMsg').css("display","none");
		var memval=$("#member").val();
		var supval=$("#supplier").val();


		if($("#supplier").val() == ''){
			console.log('not selected');
			$('#supplier').parent('div').find('div.msg').css({'color':'#ff0000'}).text('Please select the Supplier Company!');
			$('#save').fadeOut(100);
			$('#save_bottom').fadeOut(100);
		}
		 else if($("#member").val() == ''){
			console.log('not selected');
			$('#member').parent('div').find('div.msg').css({'color':'#ff0000'}).text('Please select the Member Company!');
			$('#save').fadeOut(100);
			$('#save_bottom').fadeOut(100);
		}

	 else {
	 	if((memval == "all") && (supval == "all"))
      {
      	    //$('#supplier').parent('div').find('div.msg').html('');
			//$('#member').parent('div').find('div.msg').html('');
      	//console.log("condition checked");
	   $('#member').parent('div').find('div.msg').css({'color':'#ff0000'}).text('please do not select all suppliers and members!');
       $('#save').fadeIn(100);
       $('#save_bottom').fadeIn(100);
       $('#member_user_list').fadeIn(1500);
	   $("#member_user_list").load("../users.php?memid=" + $("#member").val()+"&supid="+$("#supplier").val());
       }
       else
       {
			$('#supplier').parent('div').find('div.msg').html('');
			$('#member').parent('div').find('div.msg').html('');
			$('#save').fadeIn(100);
			$('#save_bottom').fadeIn(100);
			
			//show the loading bar
			$('#member_user_list').fadeIn(1500);
			$("#member_user_list").load("../users.php?memid=" + $("#member").val()+"&supid="+$("#supplier").val());
		}
	}
	});
	
	$("#supplier").on('change', function(){
		
		$('.successMsg').css("display","none");
		
		if($("#supplier").val() == ''){
			console.log('not selected');
			$('#supplier').parent('div').find('div.msg').css({'color':'#ff0000'}).text('Please select the Supplier Company!');
			$('#save').fadeOut(100);
			$('#save_bottom').fadeOut(100);
		} else if($("#member").val() == ''){
			console.log('not selected');
			$('#member').parent('div').find('div.msg').css({'color':'#ff0000'}).text('Please select the Member Company!');
			$('#save').fadeOut(100);
			$('#save_bottom').fadeOut(100);
		} 
       
		else {
			$('#supplier').parent('div').find('div.msg').html('');
			$('#save').fadeIn(100);
			$('#save_bottom').fadeIn(100);
			
			//show the loading bar
			$('#member_user_list').fadeIn(1500);
			$("#member_user_list").load("../users.php?memid=" + $("#member").val()+"&supid="+$("#supplier").val());
		}
	});

});

$(document).on('click', '.all', function(){
	if($(this).prop('checked')){
		if($(this).attr('data-comp') != undefined){
			$("." +$(this).attr('data-chk') + '_' +$(this).attr('data-comp')).each(function(){
				$(this).prop('checked', true);
			});
		} else {
			$("." +$(this).attr('data-chk')).each(function(){
				$(this).prop('checked', true);
			});
		}
	} else {
		if($(this).attr('data-comp') != undefined){
			$("." +$(this).attr('data-chk') + '_' +$(this).attr('data-comp')).each(function(){
				$(this).prop('checked', false);
			});
		} else {
			$("." +$(this).attr('data-chk')).each(function(){
				$(this).prop('checked', false);
			});
		}
   }
});

$(document).on('click', '#save, #save_bottom', function(){
	console.log('save clicked!!!');
	var siteURL = '<?=JURI::root();?>administrator';
	var values = $('form#accessForm').serializeArray();

	
	console.log(values);
	
	jQuery.ajax({
		type: "POST",
		url: siteURL+'/index.php?option=com_smadmin&task=accessmembers.saveaccess',
		data: {
			'value': values
		},
		success: function(msg){
			if(msg){
			
				setTimeout(function(){ 
					jQuery('#infoMsg').html("<p class='successMsg'>Data Updated Successfully!!!</p>");
				}, 100);
			} else {
				console.log('Fail');
			}
		}
	});
});
</script>

