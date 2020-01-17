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
<option value="all">ALL</option>
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
<div id="comp"><h2></h2><div>
<table class="table table-hover table_supplier_admin">
</table>

</form>
<span id="msg"></span>
<script>


function alldata()
{
  var company_ids=1;
  var sitePath = '<?php  echo JURI::root() ?>';
        
    var url = sitePath + 'administrator/index.php?option=com_smadmin&task=Smembers.getallusers';

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
              //var doc_length=parsedJson.doc.length;
              //console.log(parsedJson.length);

               jQuery.each(parsedJson, function (i,k) 
              {
                var company_id='';

                jQuery.each(k, function (o,p)
                {
                   company_id=p.comp_list;
                });
                
                if(i !="doc" && k.length>0)
                {
              row1='<tr class="table_supplier_admin_thead"><td><strong>Member:'+i+'<strong></td><td></td><td></td><td></td><td></td><td></td></tr>';
              jQuery('table').append(row1);
                
               
                console.log(i+'=='+k.length);
                //console.log(k);
               // jQuery("#comp").text(i);
                //console.log("company name=="+i);
               var html='<tr><th>Name</th><th>Email Address</th>';
               jQuery.each(parsedJson.doc, function (i,v) 
              {
                html+='<th><input id="selectall'+"_"+i+"_"+company_id+'" onclick="checkall(this)" type="checkbox">'+v.doc_name+'</th>';
              });
               html+='</tr>';

              jQuery('table').append(html);
                jQuery.each(k, function (r,m)
                {
                  
                var row='<tr><td>'+ m.name+
                    '</td><td>'+m.email+'</td>';
                    jQuery.each(parsedJson.doc, function (k,n) 
              {
                row+='<td>';
                if(k=="3")
                {

                  if(m.group_id == "12")
                  {
                     row+='<input id="checkbox_'+n.id+'"';
                 if(jQuery.inArray(n.id,m.doc_name)>-1)
                 {
                    row+= 'checked="checked"';
                 }
                row+='type="checkbox" class="case'+"_"+k+"_"+company_id+'" name="document['+m.id+'][]" value="'+n.id+'">';

                    
                  }

                }
                else
                {

                row+='<input id="checkbox_'+n.id+'"';
                 if(jQuery.inArray(n.id,m.doc_name)>-1)
                 {
                    row+= 'checked="checked"';
                 }
                row+='type="checkbox" class="case'+"_"+k+"_"+company_id+'" name="document['+m.id+'][]" value="'+n.id+'">';

                }
                

              });

                  //console.log(m.username);

               row+='</tr>';

                jQuery('table').append(row);
                });
           
            }
              
             });
               jQuery('table').after("<input type='submit' name='save' id='updatebutton' value='update' class='btn btn-small btn-success'>");
              
              
        }
        else
        {
           // jQuery("#msg").text("Please select the company");

        }

        }

    });

}
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
              //var doc_length=parsedJson.doc.length;
              var html='<tr><th>Name</th><th>Company Name</th><th>Email Address</th>';
              jQuery.each(parsedJson.doc, function (i,v) 
              {
                html+='<th>'+v.doc_name+'</th>';
              });
              html+='</tr>';

              jQuery('table').append(html);
              jQuery.each(parsedJson.users, function (i,v) 
              {
                var row='<tr><td>'+ v.name+
                    '</td><td>'+v.link_name+'</td><td>'+v.email+'</td>';
                jQuery.each(parsedJson.doc, function (k,m) 
              {
                row+='<td>';
                if(k=="3")
                {

                  if(v.group_id == "12")
                  {
                     row+='<input id="checkbox_'+m.id+'"';
                 if(jQuery.inArray(m.id,v.doc_name)>-1)
                 {
                    row+= 'checked="checked"';
                 }
                row+='type="checkbox" name="document['+v.id+'][]" value="'+m.id+'">';

                    
                  }

                }
                else
                {

                row+='<input id="checkbox_'+m.id+'"';
                 if(jQuery.inArray(m.id,v.doc_name)>-1)
                 {
                    row+= 'checked="checked"';
                 }
                row+='type="checkbox" name="document['+v.id+'][]" value="'+m.id+'">';

                }
                

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
function checkall(me)
{
  var checkbox_id=me.id;
  var arr = checkbox_id.split('_');
  var classid=".case_"+arr[1]+"_"+arr[2];

             if(jQuery("#"+checkbox_id).is(":checked"))
             {

                //alert("Checkbox is checked.");
                jQuery(classid).attr('checked', 'checked');

            }

            else
            {

                //alert("Checkbox is unchecked.");
                jQuery(classid).removeAttr("checked");

            }
        

}

jQuery(function()
{

  // add multiple select / deselect functionality
  jQuery("#selectall0").click(function () {
    alert("first checkbox is checked");
      jQuery('.case').attr('checked', this.checked);
  });

  // if all checkbox are selected, check the selectall checkbox
  // and viceversa
  jQuery(".case").click(function(){

    if(jQuery(".case").length == jQuery(".case:checked").length)
     {
      jQuery("#selectall").attr("checked", "checked");
    } else {
      jQuery("#selectall").removeAttr("checked");
    }

  });


jQuery("#company_name").on("change", function()
    {
        jQuery("#user_name").empty();
        jQuery("#updatebutton").remove();
        
       jQuery('table').children().empty();
       var supplier_id=jQuery('#supplier_name').val();
        var company_ids=jQuery(this).val();
        if(company_ids=="all")
        {
     //alert("all selected");
     alldata();
        }
        else
        {
          datapass(company_ids);
        }
    
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