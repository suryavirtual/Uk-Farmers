<?php
/**
* @version 1.0
* @package SalesHistory
* @copyright (C) 2008 Matt Hooker
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Direct Access to this location is not allowed.' );
$database = JFactory::getDbo();
$user = JFactory::getUser();
$gps = $user->groups;
$usr_id = (int)$user->get('id','0');
$url = JURI::current();

if(isset($_REQUEST['loaded']) && $_REQUEST['loaded'] != null){ ?>
<script>
	$ = jQuery;
	$(document).ready(function(){
	// function initializeLinks(){
		console.log('Coming Initialize');
		$('a.delete').each(function(){
			$this = $(this);
			$(this).click(function(e){
				e.preventDefault();
				var docID = parseInt($(this).parent().attr('data-docid'));
				var userID = parseInt($(this).parent().attr('data-userid'));        
				$.ajax({
					type: "POST",
					url: '<?=JURI::root();?>'+'index.php?option=com_users&task=contactsetup.deletedocs',
					data: {docid: docID,userid: userID}
				}).done(function(msg){
					var parsedJson = jQuery.parseJSON(msg);
					var success = parsedJson.success;
					$this.parent().hide();
					
					console.log($(this));
					if(parseInt(success)==1){
						console.log($('#' + docID + '_' + userID));
						$('#' + docID + '_' + userID).hide();
						$this.parent().hide();
					}
				});
			})
		});
	}); 
</script>
<?php }
?>

<?php if(!empty($usr_id)){ ?>
<div id="supplier_contact_setup">
	<div class="heading">Contact Setup</div>
	<form name="supplier_form"  id="supplier" action="<?php  echo JURI::root(); ?>/index.php?option=com_users&task=Contactsetup.updateuser" method="post">
		<?php $query = "select cf.link_id, mt.link_name from #__mt_cfvalues as cf, #__mt_links as mt where cf.value='Member' and cf.link_id=mt.link_id and mt.link_published='1' order by mt.link_name";
		$database->setQuery( $query );
		$member = $database->loadObjectList();

		?>
		
		
				<select name="company" id="company_name">
					<?php for($k=0; $k<count($member); $k++){
						if(isset($_POST['members'])){
							if(in_array($member[$k]->link_id, $_POST['members'])){
								$class="selected";
							} else {
								$class="";
							}
						 ?>
							<option value="<?php echo $member[$k]->link_id; ?>" <?php echo $class; ?>><?php echo $member[$k]->link_name; ?></option>
						<?php } else {?>
						<option value="<?php echo $member[$k]->link_id; ?>"><?php echo $member[$k]->link_name; ?></option>
						<?php } } ?>
				</select>
			
			
			
		

		<table class="table">
        </table>
	</form>
	
	<!-- display listing -->
	
	

</div><!--supplier_contact_setup end here-->

<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login!!!', 'error'));
} ?>

<script>
function datapass(company_ids)
{
  var sitePath = '<?php  echo JURI::root() ?>';
        
         var url = sitePath + 'index.php?option=com_users&task=Contactsetup.getusers';

        jQuery.ajax({
        type: 'post',
        url: url,
        data: {company_id:company_ids},
        success:function(data)
        {
        	
        if(data)
        {   
              var parsedJson = jQuery.parseJSON(data);
              var doc_length=parsedJson.doc.length;
              var html='<tr><th>Username</th><th>Email Address</th>';
              jQuery.each(parsedJson.doc, function (i,v) 
              {
                html+='<th>'+v.doc_name+'</th>';
              });
              html+='</tr>';

              jQuery('table').append(html);
              jQuery.each(parsedJson.users, function (i,v) 
              {
                var row='<tr><td>'+ v.username+
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