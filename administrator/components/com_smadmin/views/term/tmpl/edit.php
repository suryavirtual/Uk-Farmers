<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die('Restricted access');
//JHtml::_('formbehavior.chosen', 'select');
?>
<style>
.disables {
    pointer-events: none;
    cursor: default;
}
</style>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/cmxform.css">
<script src="<?php echo JURI::root();?>/assets/js/multiple-select-functions.js"></script>
<?php
JHtml::_('behavior.formvalidation');

$database = JFactory::getDbo();

//$editor = JFactory::getEditor();
//$html = $editor->display('pDesc', $dtl, '100%', '400', '70', '15',false); 

$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$addedBy = $user->name;


	$termid=$this->item->termId;
	if($termid)
	{
	$query2="select termsFile,contactName from #__structured_terms WHERE termId ='$termid' ";
			  $database->setQuery( $query2 );
			  $database->query();
			  $rows = $database->loadObject();
	          $filepath = $rows->termsFile;	

	          ?>
	          <script>
	          jQuery(function() {
	         // jQuery('#jform_supplierUserId').prop("disabled", true);
	          jQuery('#jform_supplierUserId').attr('readonly', true);
	          jQuery('#jform_supplierUserId').mousedown(function(event) {
        event.preventDefault();
    });
	           
	      });
	          </script>
	          <?php
	}
			   

					

?>
<script>
function selectuser(supplier_ids,contactname)
{
	


	 var sitePath = '<?php  echo JURI::root() ?>';
        
    var url = sitePath + 'administrator/index.php?option=com_smadmin&task=term.getusers';

    jQuery.ajax({
        type: 'post',
        url: url,
        data: {supplier_id:supplier_ids},
        success:function(data)
        {
        	console.log(data);
        	if(data)
        	{
        	jQuery('#jform_contactName').append('<option value="">select name</option>');
            var parsedJson = jQuery.parseJSON(data);
            jQuery.each(parsedJson, function (i,k) 
              {

              	jQuery('#jform_contactName').append('<option value="'+k.name+'" id="'+k.id+'">'+k.name+'</option>');
              	
              	if(contactname)
              	{
              		//alert(contactname);
              		
              		if(k.name==contactname)
              		{
              			console.log(contactname);
              			
              			jQuery("#jform_contactName").val(contactname);

console.log("'"+contactname+"'");

              			//jQuery("#jform_contactName option:contains(" + contactname + ")").attr('selected', 'selected');
              		}

              	}
              	
              	//console.log(k.username);
              	//jQuery('#jform_contactName').append('<option value="'+k.id+'">'+k.name+'</option>');
             

              });
        	}
        	else
        	{

        	}
        }
     });
}

function fetchingterms(supplier_ids)
{
	var supplier_ids=supplier_ids
   jQuery("#results" ).load("<?php echo JURI::root();?>/fetch_terms_admin.php",{user_id: supplier_ids}); 	
}
jQuery(function() {
	var passid='';
	jQuery("#add_terms").tabs();
	jQuery("#edit_terms").tabs();
	jQuery('a.modal').addClass('disables');
	jQuery("#supplier").val(jQuery("#jform_supplierUserId").val());
	//jQuery('#supplier').prop("disabled", true); 
	jQuery('#supplier').attr('readonly', true);
	       jQuery('#supplier').mousedown(function(event) {
        event.preventDefault();
    });

	
	 passid = jQuery("#jform_supplierUserId").val();
	if(passid != "")
	{
	var contactname = "<?php echo stripslashes($rows->contactName);?>";
   fetchingterms(passid);
   selectuser(passid,contactname);
	}

	/* dynamic table code start from here */
	//jQuery("#results" ).load( "<?php echo JURI::root();?>/fetch_terms.php?user_id=<?php echo $usrs_id; ?>",function(){ jQuery(".loading-div").hide();});
	jQuery("#supplier").on("change", function()
    {
    	var supplier_ids=jQuery(this).val();
    	fetchingterms(supplier_ids);
    	
    });

    jQuery("#results").on( "click", ".pagination a", function (e){
		e.preventDefault();
		jQuery(".loading-div").show(); //show loading element
		var page = jQuery(this).attr("data-page"); //get page number from link
		var value = jQuery(this).attr("data-val"); //get page number from link
		console.log(value);
		if(value!=''){
			var val = value;
		} else {
			var val = '';
		}
		var supplier_ids=jQuery("#supplier").val();
		jQuery("#results" ).load("<?php echo JURI::root();?>/fetch_terms_admin.php",{user_id: supplier_ids,val:val,page:page},function(){//get content from PHP page
			jQuery(".loading-div").hide(); //once done, hide loading element
		});
	});
	/* dynamic table code ends here */
	/*new code for dynamic email id */
	jQuery("#jform_contactName").on("change", function()
    {
    	
    	jQuery('#jform_contactEmail').empty();
    	var users_ids=jQuery(this).children(":selected").attr("id");
    //alert("hiiiiiiiiiii"+users_ids);
      
      var sitePath = '<?php  echo JURI::root() ?>';
        
    var url = sitePath + 'administrator/index.php?option=com_smadmin&task=term.getemails';

    jQuery.ajax({
        type: 'post',
        url: url,
        data: {users_id:users_ids},
        success:function(data)
        {
        	var parsedJson = jQuery.parseJSON(data);
        	if(data)
        	{

        	jQuery('#jform_contactEmail').val(parsedJson.email);
            
        	}
        	else
        	{
        		
        		//jQuery('#jform_contactEmail').val('');

        	}
        }
     });

    });
    /* new code ends here for dynamic email id */
	/* dynamic select dropdown code start from here */
jQuery("#jform_supplierUserId").on("change", function()
    {
    	jQuery("#jform_contactName").empty();

        
        var supplier_ids=jQuery(this).val();
        jQuery("#supplier").val(supplier_ids);

        //new code for file manger 

        if(supplier_ids != "")
        {
        	jQuery('a.modal').removeClass('disables');
        }
        else
        {
        	jQuery('a.modal').addClass('disables');
        }

    	//new code ends here
        fetchingterms(supplier_ids);
        //dynammic filepath code start from here
        var sitePath = '<?php  echo JURI::root() ?>';
        var linkids="";
     
        var foldername="";
        //var foldername=jQuery("#jform_supplierUserId option:selected").text();
        linkids=jQuery("#jform_supplierUserId option:selected").val();
        
        var url = sitePath + 'administrator/index.php?option=com_smadmin&task=term.getfoldername';

        jQuery.ajax({
        type: 'post',
        url: url,
        data: {link_id:linkids},
        success:function(data)
        {

        	console.log(data);
        	var parsedJson = jQuery.parseJSON(data);
        	if(data)
        	{

        	 foldername=parsedJson.folder_name;
        	 jQuery(".modal").attr("href","index.php?option=com_media&view=images&tmpl=component&asset=com_smadmin&author=created_by&fieldid=termsFile&folder=/suppliers/"+foldername+"/terms/");
        	}
        	
        }
     });
        
   
        //dynamic filepath code ends here

        
        
    var url = sitePath + 'administrator/index.php?option=com_smadmin&task=term.getusers';

    jQuery.ajax({
        type: 'post',
        url: url,
        data: {supplier_id:supplier_ids},
        success:function(data)
        {
        	console.log(data);
        	if(data)
        	{
        	jQuery('#jform_contactName').append('<option value="">select name</option>');
            var parsedJson = jQuery.parseJSON(data);
            jQuery.each(parsedJson, function (i,k) 
              {
              	//console.log(k.username);
              	//jQuery('#jform_contactName').append('<option value="'+k.id+'">'+k.name+'</option>');
              	jQuery('#jform_contactName').append('<option value="'+k.name+'" id="'+k.id+'">'+k.name+'</option>');
              });
        	}
        	else
        	{

        	}
        }
     });
        

    });


/* end here */
});
</script>
<?php if($this->item->termId !=""): ?>
	<script>
	jQuery(function() {
	jQuery('a.modal').removeClass('disables');
    });
	</script>
<div id="edit_terms">
<ul>
	<li><a href="#tabs-3">Edit Terms</a></li>
	<li><a href="#tabs-4">Allocate Terms To Members</a></li>
</ul>

<div id="tabs-3">
<form action="<?php echo JRoute::_('index.php?option=com_smadmin&view=term&layout=edit&termId=' . (int) $this->item->termId); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">

	
	<div class="form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>
		<?php //echo "<pre>";print_r($fieldset);?>
			<fieldset class="adminform col-md-6 border-none">
				<legend><?php echo JText::_($fieldset->label); ?></legend>
				<?php  if($fieldset->name=="details"):?>
				<div class="row-fluid">
					<div class="span6">
						<?php foreach ($this->form->getFieldset($name) as $field): ?>

							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endforeach; ?>
					</div>
					<?php
					$termid=$this->item->termId;
			   $query2="select termsFile,contactName from #__structured_terms WHERE termId ='$termid' ";
			  $database->setQuery( $query2 );
			  $database->query();
			  $rows = $database->loadObject();
	          $filepath = $rows->termsFile;
	          /*new code for fetching dynamic folder name */
	          $comp_ids=$this->item->supplierUserId;
	          $filepath = $rows->termsFile;
	          $uQuery = "select folder_name from #__folder where link_id='$comp_ids'";
		      $database->setQuery($uQuery);
              $resfolder = $database->loadObject();
               $foldername=$resfolder->folder_name;

					?>
					
					<div class="control-group group-format">
					<div class="span12 term-spacing">
        <div class="control-label term-margin">
         <label id="specSheet-lbl" for="specSheet" class="hasTooltip" title="Select an Spec Sheet PDF file" data-original-title="<strong>Select File</strong><br />Choose an pdf from your computer">
         Term File *</label>
        </div>
        <div class="controls ">
         <div class="input-prepend input-append">
          <input type="text" name="jform[termsFile]" class="hasTooltip" id="termsFile" value="<?php echo $filepath; ?>" readonly="readonly" title="<?php echo $filepath; ?>" class="input-large field-media-input hasTipImgpath" size="10" data-basepath="<?= JURI::root() ?>">
           <a class="modal btn" title="Select" href="index.php?option=com_media&view=images&tmpl=component&asset=com_smadmin&author=created_by&fieldid=termsFile&folder=/suppliers/<?php echo $foldername; ?>/terms/" rel="{handler: 'iframe', size: {x: 800, y: 500}}">Select File</a>
            <a class="btn hasTooltip" title="" href="#" onclick="jInsertFieldValue('', 'termsFile'); return false;" data-original-title="Clear">
            <i class="icon-remove"></i>
           </a>
         </div>
         </div>
        </div>
       </div>
	</div>
				</div>
			<?php else:?>
			<div class="row-fluid display-form-none">
					<div class="span12">
						<?php foreach ($this->form->getFieldset($name) as $field): ?>
                        
							<div class="control-group">
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
		<?php endif; ?>

			</fieldset>
		<?php endforeach; ?>
	</div>
	<input type="hidden" name="task" value="term.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>

<!--2nd tab start from here-->
<div id="tabs-4">
<?php 
$querySupplier="SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name";
$database->setQuery($querySupplier);
$supplierList = $database->loadObjectList();

$query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
			$database->setQuery( $query );
			$member = $database->loadObjectList();
			//echo "<pre>";print_r($member);
			/* query for fething terms */
			$queryterm = "SELECT termId, termName, effectiveFrom, validTo, termsFile FROM #__structured_terms";
			$database->setQuery( $queryterm );
			$terms = $database->loadObjectList();
			//echo "<pre>"; print_r($terms);
			$url = JURI::current();

	?>
		<!--<form method="post" id="accessForm" name="adminForm">-->
		<!--<form action="index.php?option=com_users&task=suppliersendterms.sendtermstomember" method="post" name="share" id="share">-->
	<form action="index.php?option=com_smadmin&task=terms.sendtermstomember" method="post" id="adminForm" name="adminForm">
	
	<div class="control-group">
		<div class="control-label select-left">Select Supplier</div>
		<div class="controls">
			<select name="supplier" id="supplier" class="select-right">
				<option value="">Select Supplier</option>
				<?php foreach($supplierList as $supplierLists){ ?>
					<option value="<?php echo $supplierLists->link_id; ?>"><?php echo $supplierLists->link_name; ?></option>
				<?php } ?>
			</select>
			
		</div>
		<div id="results">
		
		</div>
			<div class="member-container">
				<div class="member-top">
					<h3 class="em_h3">Members</h3>
					<select name="Box1" id="Box1" multiple="multiple" size="7">
						<?php foreach($member as $members){
							echo "<option value=\"$members->link_id\">$members->link_name</option>";
						} ?>
					</select>
					</div>

				
				<div class="member-middle">
					<p><button name="MoveRight" type="button" onclick="FM.moveSelections(this.form.Box1, this.form.Box2);">&gt;</button></p>
					<p><button name="MoveLeft" type="button" onclick="FM.moveSelections(this.form.Box2, this.form.Box1); FM.sortOptions(this.form.Box1); FM.selectAllOptions(this.form.Box2);">&lt;</button></p>
				</div>
				<div class="member-bottom">
					<h3 class="em_h3">Selected Members</h3>
					<select name="member_id[]" id="Box2" multiple="multiple" size="10">
					</select>
				</div><br/>
			<div class="member-button"><input type="submit" class="email_mem_submit btn-success" name="send" value="Send Terms"></div>
			<!--<button onclick="Joomla.submitbutton('term.save')" class="btn btn-small btn-success">
	<span class="icon-apply icon-white"></span>
	Send Terms</button>-->
		
</div>
		
	    </div>
	
	
	
	
	
	<!-- member's users with the permissions -->
	
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="url" id="url" value="<?php echo $url; ?>" />

	    </div>
	
	
	
	
	
	<!-- member's users with the permissions -->
	
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="url" id="url" value="<?php echo $url; ?>" />
</form>

</div>
</div>



<?php else:?>
<div id="add_terms">
<ul>
	<li><a href="#tabs-1">Upload Terms</a></li>
	<li><a href="#tabs-2">Allocate Terms To Members</a></li>
</ul>
 
<div id="tabs-1">
<form action="<?php echo JRoute::_('index.php?option=com_smadmin&view=term&layout=edit&termId=' . (int) $this->item->termId); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate">
	
	
<div class="row my-form-3">
    
	<div class="form-horizontal">
		<?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>
		<?php //echo "<pre>";print_r($fieldset);?>
			<fieldset class="adminform col-md-6">
				
				<?php  if($fieldset->name=="details"):?>
				<div class="row-fluid">
					<div class="span6">
						<?php foreach ($this->form->getFieldset($name) as $field): ?>
							<div class="control-group">
							
								<div class="control-label"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
								
							</div>
						
						<?php endforeach; ?>
					</div>

					<div class="control-group">
					<div class="span6 term-spacing">
        <div class="control-label term-margin">
         <label id="specSheet-lbl" for="specSheet" class="hasTooltip" title="Select an Spec Sheet PDF file" data-original-title="<strong>Select File</strong><br />Choose an pdf from your computer">
         Term File *</label>
        </div>
        <div class="controls ">
         <div class="input-prepend input-append">
          <input type="text" name="jform[termsFile]" id="termsFile" value="" readonly="readonly" title="Pdf File Source/Path" class="input-large field-media-input hasTipImgpath" size="10" required="required" data-basepath="<?= JURI::root() ?>">
           <a class="modal btn" title="Select" href="index.php?option=com_media&view=images&tmpl=component&asset=com_smadmin&author=created_by&fieldid=termsFile&folder=upload/terms/" rel="{handler: 'iframe', size: {x: 800, y: 500}}">Select File</a>
            <a class="btn hasTooltip" title="" href="#" onclick="jInsertFieldValue('', 'termsFile'); return false;" data-original-title="Clear">
            <i class="icon-remove"></i>
           </a>
         </div>
         </div>
        </div>
       </div>
	</div>



				</div>
				</fieldset>

			<?php else:?>
				
			<div class="row-fluid">
					<div class="span12"><br>
						<?php foreach ($this->form->getFieldset($name) as $field): ?>
							
							<?php if($field->label == '<label id="jform_termsDetails-lbl" for="jform_termsDetails" class="">
	Files</label>'):?>
								<div class="control-group" style="display:none;">
								<div class="control-label overall-spacing"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
							<?php else: ?>
								<div class="control-group">
								<div class="control-label overall-spacing"><?php echo $field->label; ?></div>
								<div class="controls"><?php echo $field->input; ?></div>
							</div>
							<?php endif; ?>
							

						
						<?php endforeach; ?>
					</div>
				</div>
				</fieldset>
				
		<?php endif; ?>

			
		<?php endforeach; ?>
  
		
	<input type="hidden" name="task" value="term.edit" />
	<?php echo JHtml::_('form.token'); ?>
</form>
</div>
</div>
<div id="tabs-2">
	<?php 
$querySupplier="SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name";
$database->setQuery($querySupplier);
$supplierList = $database->loadObjectList();

$query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
			$database->setQuery( $query );
			$member = $database->loadObjectList();
			//echo "<pre>";print_r($member);
			/* query for fething terms */
			$queryterm = "SELECT termId, termName, effectiveFrom, validTo, termsFile FROM #__structured_terms";
			$database->setQuery( $queryterm );
			$terms = $database->loadObjectList();
			//echo "<pre>"; print_r($terms);
			$url = JURI::current();

	?>
		<!--<form method="post" id="accessForm" name="adminForm">-->
		<form action="index.php?option=com_smadmin&task=terms.sendtermstomember" method="post" id="adminForm" name="adminForm">
		
	<div class="control-group">
		<div class="control-label select-left">Select Supplier</div>
		<div class="controls">
			<select name="supplier" id="supplier" class="select-right">
				<option value="">Select Supplier</option>
				<?php foreach($supplierList as $supplierLists){ ?>
					<option value="<?php echo $supplierLists->link_id; ?>"><?php echo $supplierLists->link_name; ?></option>
				<?php } ?>
			</select>
			
		</div>
		<div id="results">
		
		</div>
			<div class="member-container">
				<div class="member-top">
					<h3 class="em_h3">Members</h3>
					<select name="Box1" id="Box1" multiple="multiple" size="7">
						<?php foreach($member as $members){
							echo "<option value=\"$members->link_id\">$members->link_name</option>";
						} ?>
					</select>
					</div>

				
				<div class="member-middle">
					<p><button name="MoveRight" type="button" onclick="FM.moveSelections(this.form.Box1, this.form.Box2);">&gt;</button></p>
					<p><button name="MoveLeft" type="button" onclick="FM.moveSelections(this.form.Box2, this.form.Box1); FM.sortOptions(this.form.Box1); FM.selectAllOptions(this.form.Box2);">&lt;</button></p>
				</div>
				<div class="member-bottom">
					<h3 class="em_h3">Selected Members</h3>
					<select name="member_id[]" id="Box2" multiple="multiple" size="10">
					</select>
				</div>
			<div class="member-button"><input type="submit" class="email_mem_submit btn-success" name="send" value="Send Terms"></div>
			<!--<button onclick="Joomla.submitbutton('term.save')" class="btn btn-small btn-success">
	<span class="icon-apply icon-white"></span>
	Send Terms</button>-->
		

		
	    </div></div>

	
	
	
	<!-- member's users with the permissions -->
	
	<?php echo JHtml::_('form.token'); ?>
	<input type="hidden" name="url" id="url" value="<?php echo $url; ?>" />
</form>
</div>
</div>
<?php endif;?>

