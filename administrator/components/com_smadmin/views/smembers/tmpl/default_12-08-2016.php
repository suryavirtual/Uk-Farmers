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
//require_once JPATH_COMPONENT .'/helpers/html/smember.php';
//JHtml::_('formbehavior.chosen', 'select');

//$listOrder     = $this->escape($this->filter_order);
//$listDirn      = $this->escape($this->filter_order_Dir);

if(!empty($_COOKIE['company']))
{
    $comp=$_COOKIE['company'];

}
else
{
  $comp="";  
}



$model=$this->getModel();
$company = $model->getcompany ();
//echo "<pre>";
//print_r($company);
$documents=$model->getdocument();
//print_r($documents);

$supplier=$model->getsupplier();
//echo "<pre>";
//print_r($supplier);
?>
<hi>Add Supplier Access</hi>
<form name="supplier_form"  id="supplier" action="<?php  echo JURI::root(); ?>/administrator/index.php?option=com_smadmin&task=Smembers.updateuser" method="post">
<strong>select Supplier:</strong><select name="supplier" id="supplier_name">
<option value="">Select the Supplier</option>
<?php
if(!empty($supplier))
{
 foreach ($supplier as $sup) 
{?>
<option value="<?php echo $sup->link_id;?>" <?php if($sup->link_id==$supp){ echo "selected";} ?>><?php echo $sup->link_name;?></option>
<?php } } ?>
</select>
</br>

<strong>Select Member:</strong><select name="company" id="company_name">
<option value="">Select the company</option>
<span id="msg"></span>
<div id="dynamictable"></div>
<?php
if(!empty($company))
{
 foreach ($company as $com) 
{?>
<option value="<?php echo $com->link_id;?>" <?php if($com->link_id==$comp){ echo "selected";} ?>><?php echo $com->link_name;?></option>
<?php } } ?>
</select>
</br>

<table class="table table-striped table-hover">
</table>

</form>
<span id="msg"></span>
<script>
function datapass(company_ids)
{
  var sitePath = '<?php  echo JURI::root() ?>';
        
         var url = sitePath + 'administrator/index.php?option=com_smadmin&task=Smembers.getusers';

        jQuery.ajax({
        type: 'post',
        url: url,
        data: {company_id:company_ids},
        success:function(data)
        {
        if(data)
        {
            console.log(data);    
              var parsedJson = jQuery.parseJSON(data);
              var doc_length=parsedJson.doc.length;
              var html='<tr><th>Name</th><th>Email Address</th>';
              jQuery.each(parsedJson.doc, function (i,v) 
              {
                html+='<th>'+v.doc_name+'</th>';
              });
              html+='</tr>';

              jQuery('table').append(html);
              jQuery.each(parsedJson.users, function (i,v) 
              {
                var row='<tr><td>'+ v.name+
                    '</td><td>'+v.email+'</td>';
                jQuery.each(parsedJson.doc, function (k,m) 
              {
                row+='<td><input id="checkbox_'+m.id+'"';
                 if(jQuery.inArray(m.id,v.doc_name)>-1)
                    row+= 'checked="checked"';
                row+='type="checkbox" name="document['+v.id+'][]" value="'+m.id+'">';

              });
               row+='</tr>';
               jQuery('table').append(row);
                
              });
              jQuery('table').after("<input type='submit' name='save' id='updatebutton' value='update' class='btn btn-small btn-success'>");
        }
        else
        {
            jQuery("#msg").text("Please select the company");

        }

        }

    });  
}

jQuery(function(){
jQuery("#company_name").on("change", function()
    {
        jQuery("#user_name").empty();
        jQuery("#updatebutton").remove();
        
       jQuery('table').children().empty();
        var company_ids=jQuery(this).val();
    datapass(company_ids);
        //alert(company_ids);
        

    });



});
</script>
<?php 
if(!empty($_COOKIE['company']))
{
  // echo $_COOKIE['company'];

   ?>
   <script type="text/javascript">
   
   datapass('<?php echo $_COOKIE['company'];?>');
   </script>
   <?php }