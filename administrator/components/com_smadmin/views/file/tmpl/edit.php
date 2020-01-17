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
?>
<style>
.control-group:nth-child(6n) {
    display: none;
}
.disables {
    pointer-events: none;
    cursor: default;
}
</style>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/jquery-ui.css">
<script src="<?php echo JURI::root();?>/assets/js/jquery-ui.min.js"></script>
<link rel="stylesheet" href="<?php echo JURI::root();?>/assets/css/cmxform.css">
<script src="<?php echo JURI::root();?>/assets/js/multiple-select-functions.js"></script>
<script src="<?php echo JURI::root();?>/assets/js/script-file.js"></script>
<script>
function fetchingfiles(supplier_ids)
{
  var supplier_ids=supplier_ids
   jQuery("#results" ).load("<?php echo JURI::root();?>/fetch_pages_admin.php",{user_id: supplier_ids});  
}
jQuery(function() 
{
  jQuery("#add_files").tabs();
  jQuery("#edit_files").tabs();
  // jQuery('a.modal').addClass('disables');

  var passid=jQuery("#jform_cmpId").val();
  if(passid != "")
  {
   fetchingfiles(passid);
  }



  jQuery("#supplier").on("change", function()
    {

      var supplier_ids=jQuery(this).val();
      fetchingfiles(supplier_ids);  
  
    });
    jQuery("#jform_cmpId").on("change", function()
    {
    
      jQuery("#jform_contactName").empty();
      var supplier_ids=jQuery(this).val();
      //new code for disable file manager
      var docvalue="";
        docvalue=jQuery("#jform_type").val();
        //console.log(docvalue);
        if((docvalue != "") && (supplier_ids != ""))
        {
          jQuery('a.modal').removeClass('disables');
        }
        else
        {
          jQuery('a.modal').addClass('disables');
        }

      //new code ends here 
      
       // jQuery("#supplier").val(supplier_ids);
       jQuery("#supplier").val(jQuery("#jform_cmpId option:selected").text());
        fetchingfiles(supplier_ids);
        //ajax code start form here for fetching users of supplier
        var sitePath = '<?php  echo JURI::root() ?>';
        
     var url = sitePath + 'administrator/index.php?option=com_smadmin&task=term.getusers';

     jQuery.ajax({
        type: 'post',
        url: url,
        data: {supplier_id:supplier_ids},
        success:function(data)
        {
          //console.log(data);
          if(data)
          {
          jQuery('#jform_contactName').append('<option value="">select name</option>');
            var parsedJson = jQuery.parseJSON(data);
            jQuery.each(parsedJson, function (i,k) 
              {
                
                jQuery('#jform_contactName').append('<option value="'+k.name+'">'+k.name+'</option>');
              });
          }
          else
          {

          }
        }
     });


        //ajax code ends here for fetching users

    });

   //executes code below when user click on pagination links
  jQuery("#results").on( "click", ".pagination a", function (e)
  {
    e.preventDefault();
    jQuery(".loading-div").show(); //show loading element
    var page = jQuery(this).attr("data-page"); //get page number from link
    var value = jQuery(this).attr("data-val"); //get page number from link
  
    if(value!=''){
      var val = value;
    } else {
      var val = '';
    }
    var supplier_ids=jQuery("#supplier").val();
      jQuery("#results" ).load("<?php echo JURI::root();?>/fetch_pages_admin.php",{user_id: supplier_ids,val:val,page:page},function(){
      jQuery(".loading-div").hide(); //once done, hide loading element
    });
  });
  /* code for creating dyanamic path */
  jQuery("#jform_type").on("change", function()
    {
      var userfile=jQuery("#jform_cmpId option:selected").text();
      var foldername=jQuery(this).val();
      var userfiles="";


//new code for disable file manager
      var supplier_ids="";
        supplier_ids=jQuery("#jform_cmpId").val();
        //console.log(supplier_ids);
        if((foldername != "") && (supplier_ids != ""))
        {
          jQuery('a.modal').removeClass('disables');
        }
        else
        {
          jQuery('a.modal').addClass('disables');
        }

      //new code ends here 

      
      
      if(foldername=="1")
      {
      foldername="marketing"; 

      }
      if(foldername=="2")
      {
      foldername="offers";  

      }
      if(foldername=="3")
      {
      foldername="pricelists";  

      }
      if(foldername=="4")
      {
      foldername="terms"; 

      }

      /*new code for dynamic folder name by ajax */
      var sitePath = '<?php  echo JURI::root() ?>';
      var url = sitePath + 'administrator/index.php?option=com_smadmin&task=term.getfoldername';

        jQuery.ajax({
        type: 'post',
        url: url,
        data: {link_id:supplier_ids},
        success:function(data)
        {

          console.log(data);
          var parsedJson = jQuery.parseJSON(data);
          if(data)
          {

           userfiles=parsedJson.folder_name;
           jQuery(".modal").attr("href","index.php?option=com_media&view=images&tmpl=component&asset=com_smadmin&author=created_by&fieldid=filename&folder=/suppliers/"+userfiles+"/"+foldername);
          }
          
        }
     });

      /*code ends here */
      

    });
  

  
});
</script>

<?php
JHtml::_('behavior.formvalidation');

$database = JFactory::getDbo();

$user = JFactory::getUser();
$usr_id = (int)$user->get('id','0');
$addedBy = $user->name;

 $q1 = "select * from #__supplier_files where id='".$_REQUEST['id']."'";
$database->setQuery( $q1 );
$fileDtl = $database->loadObject();
//$getsupplierid=$fileDtl->comp_id;
$filenamearray=explode('/',$fileDtl->filename);
$newsupplierid=$fileDtl->comp_id;

if(!empty($fileDtl->filename))
{
$file_suppliername=$filenamearray[2];
$file_type=$filenamearray[3];
$file_name=$filenamearray[4]; 
}
else
{    if($newsupplierid)
   {
    $Query = "select folder_name from #__folder where link_id=".$newsupplierid;
    $database->setQuery($Query);
    $resultsfolder=$database->loadObject();
    $file_suppliername=$resultsfolder->folder_name;
    $file_type_data=$fileDtl->type;

  switch ($file_type_data) 
  {
    case 1:
      $file_type="marketing";
      break;
    case 2:
      $file_type="offers";
      break;
    case 3:
      $file_type="pricelists";
      break;
    case 4:
      $file_type="terms";
      break;
  }

}
}
?>
<?php if($newsupplierid):?>
<script>
jQuery(function() 
{
jQuery("#jform_cmpId").val('<?php echo $newsupplierid; ?>');
jQuery("#supplier").val(jQuery("#jform_cmpId option:selected").text());

jQuery('a.modal').removeClass('disables');
fetchingfiles('<?php echo $newsupplierid; ?>'); 
});

</script>
<?php else:?>
<script>
jQuery(function() 
{
jQuery('a.modal').addClass('disables');
});
</script>
<?php endif; ?>

<div id="add_files">
<ul>
  <li><a href="#tabs-5"><?php if($newsupplierid):?> Edit Files <?php else: ?>Add Files<?php endif;?></a></li>
  <li><a href="#tabs-6">Allocate Files To Members</a></li>
</ul>
<div id="tabs-5">
<?php if((int) $this->item->id !=""): ?>
  <form action="<?php echo JRoute::_('index.php?option=com_smadmin&view=file&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
  <div class="form-horizontal">
    
    <?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>

      <fieldset class="adminform">
        <?php //echo JText::_($fieldset->label); ?>
        <div class="row-fluid">
          <div class="span6">
            <?php foreach ($this->form->getFieldset($name) as $field): ?>
              <div class="control-group">
                <div class="control-label"><?php echo $field->label; ?></div>
                <div class="controls"><?php echo $field->input; ?></div>
              </div>
            <?php endforeach; ?>
            
            
      <div class="control-group group-format">
          <div class="span12 term-spacing">
        <div class="control-label term-margin">
         <label id="specSheet-lbl" for="specSheet" class="hasTooltip" title="Select an Spec Sheet PDF file" data-original-title="<strong>Select File</strong><br />Choose an pdf from your computer">
         Upload Files *</label>
        </div>
        <div class="controls ">
         <div class="input-prepend input-append">
          <input type="text" name="jform[filename]" class="hasTooltip" id="filename" value="<?php echo $fileDtl->filename; ?>" readonly="readonly" title="<?php echo $fileDtl->filename; ?>" class="input-large field-media-input hasTipImgpath" size="10" data-basepath="<?= JURI::root() ?>">
           <a class="modal btn" title="Select" href="https://unfoat.f4f.com/administrator/index.php?option=com_media&view=images&tmpl=component&asset=com_smadmin&author=created_by&fieldid=filename&folder=/suppliers/<?php echo $file_suppliername."/".$file_type."/";?> " rel="{handler: 'iframe', size: {x: 800, y: 500}}">Select File</a>
            <a class="btn hasTooltip" title="" href="#" onclick="jInsertFieldValue('', 'filename'); return false;" data-original-title="Clear">
            <i class="icon-remove"></i>
           </a>
         </div>
         </div>
        </div>
       </div>
          </div>
        </div>
      </fieldset>
    <?php endforeach; ?>
  </div>
  <input type="hidden" name="task" value="file.edit" />
  <?php echo JHtml::_('form.token'); ?>
</form>
<?php else: ?>

<form action="<?php echo JRoute::_('index.php?option=com_smadmin&view=file&layout=edit&id=' . (int) $this->item->id); ?>"
    method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data">
  <div class="form-horizontal">
    
    <?php foreach ($this->form->getFieldsets() as $name => $fieldset): ?>

      <fieldset class="adminform">
        <?php //echo JText::_($fieldset->label); ?>
        <div class="row-fluid">
          <div class="span6">
            <?php foreach ($this->form->getFieldset($name) as $field): ?>
              <div class="control-group">
                <div class="control-label"><?php echo $field->label; ?></div>
                <div class="controls"><?php echo $field->input; ?></div>
              </div>
            <?php endforeach; ?>

      <div class="control-group group-format">
          <div class="span12 term-spacing">
        <div class="control-label term-margin">
         <label id="specSheet-lbl" for="specSheet" class="hasTooltip" title="Select an Spec Sheet PDF file" data-original-title="<strong>Select File</strong><br />Choose an pdf from your computer">
         Upload Files *</label>
        </div>
        <div class="controls ">
         <div class="input-prepend input-append">
          <input type="text" name="jform[filename]" id="filename" value="<?php //echo $filepath; ?>" readonly="readonly" title="Pdf File Source/Path" class="input-large field-media-input hasTipImgpath" size="10" data-basepath="<?= JURI::root() ?>">
           <a class="modal btn" title="Select" href="https://unfoat.f4f.com/index.php?option=com_media&view=images&tmpl=component&asset=com_smadmin&author=created_by&fieldid=filename&folder=uf_data/suppliers/" rel="{handler: 'iframe', size: {x: 800, y: 500}}">Select File</a>
            <a class="btn hasTooltip" title="" href="#" onclick="jInsertFieldValue('', 'filename'); return false;" data-original-title="Clear">
            <i class="icon-remove"></i>
           </a>
         </div>
         </div>
        </div>
       </div>
          </div>
        </div>
      </fieldset>
    <?php endforeach; ?>
  </div>
  <input type="hidden" name="task" value="file.edit" />
  <?php echo JHtml::_('form.token'); ?>
</form>

  <?php endif;?>

</div>
<div id="tabs-6">
<?php 
$querySupplier="SELECT mt.link_id, mt.link_name FROM `jos_mt_links` as mt, jos_mt_cfvalues as cf where mt.link_id=cf.link_id and cf.value='Supplier' and mt.link_published='1' order by mt.link_name";
$database->setQuery($querySupplier);
$supplierList = $database->loadObjectList();
$query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
      $database->setQuery( $query );
      $member = $database->loadObjectList();
?>

<!--- alocate members code start from here -->

<form action="index.php?option=com_smadmin&task=files.sendfilestomember" method="post" id="adminForm" name="adminForm">

<div class="control-label select-left">Supplier Name</div>
    <div class="controls">
    <!--  <select name="supplier" id="supplier" class="select-right">
        <option value="">Select Supplier</option>
        <?php foreach($supplierList as $supplierLists){ ?>
          <option value="<?php echo $supplierLists->link_id; ?>"><?php echo $supplierLists->link_name; ?></option>
        <?php } ?>
      </select>-->
      <input type="text" name="supplier" id="supplier" class="select-right" readonly="true" />
      <input type="hidden" name="supplier_id" value="<?php echo $getsupplierid; ?>">
      
    </div>

<fieldset>
  
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
          <select name="Box2[]" id="Box2" multiple="multiple" size="10">
          </select>
        </div>
      <div class="member-button"><input type="submit" class="email_mem_submit btn-success" name="send" value="Send File"></div></div>
    

    
      </div>
</fieldset>
</form>

<!-- alocate member code ends here ---> 
</div>
</div>


