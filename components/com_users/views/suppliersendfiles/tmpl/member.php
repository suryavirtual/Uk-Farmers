<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc =& JFactory::getDocument();
$database = JFactory::getDBO();
$user = JFactory::getUser();
$usr_id = $user->id;
//$comp_id=$user->comp_list;
$session = JFactory::getSession();

$company_id=$session->get('comp_id');
if(!empty($company_id))
{
	$comp_id=$company_id;
}
else
{
	$comp_id=$user->comp_list;
}
$queryusertype="select group_id from #__user_usergroup_map where user_id='$usr_id '";
$database->setQuery($queryusertype);
//$usertypes=$database->loadObject();
$usertypes=$database->loadObjectList();
//$usertype=$usertypes->group_id;
$arr = array();
foreach($usertypes as $value)
{
	$arr[] = $value->group_id;
}
$target = array('8', '12','2');
$totalusertype = count(array_intersect($target,$arr));

if($usr_id!=""){ ?>
	<div id="view_supplier_files">
	<h2><?php echo $doc->getTitle(); ?></h2>
	<h4>RECEIVED FILES </h4>
<?php 

    
      $queryFiles="SELECT  ftm.id,sf.filename,sf.id as fileids,sf.description,sf.type,sf.uploaded,sf.expiry,sf.approved,sf.userid as supplier_userid,mt.link_id as supplier_id,mt.link_name as company_name,u.name as supplier_name,dt.doc_name, ftm.fileId FROM jos_files_to_members as ftm
     left join jos_supplier_files as sf on(sf.id=ftm.fileId)
     left join jos_users as u on (u.id=sf.userid)
     left join jos_mt_links as mt on (mt.link_id = sf.comp_id)  
     left join jos_document_type as dt on (dt.id=sf.type) 
     left join jos_supplier_file_viewed as jsfv on (ftm.id=jsfv.fid and jsfv.user_id='$usr_id') 
     where ftm.memberId='$comp_id'and sf.approved='1' and  ftm.viewFile='0' and sf.expiry >= date_sub(now(), interval 3 month) and  ftm.fileId 
     NOT IN(SELECT fid FROM jos_supplier_file_viewed where user_id='$usr_id' and comp_id='$comp_id') 
     order by sf.uploaded desc,mt.link_name ";

     /*ends here*/
$database->setQuery( $queryFiles );
$files = $database->loadObjectList();
 $queryFiles1="select fv.*,sf.*,dt.doc_name,mt.link_name from jos_supplier_file_viewed as fv,jos_supplier_files as sf,jos_mt_links as mt,jos_document_type as dt
          where  sf.id=fv.fid and mt.link_id=sf.comp_id and dt.id=sf.type and fv.user_id='$usr_id' and fv.comp_id='$comp_id'and  sf.approved='1'and sf.expiry >= date_sub(now(), interval 3 month)
 order by mt.link_name asc ";
	   

	 /*query ends here */

$database->setQuery( $queryFiles1 );
$files1 = $database->loadObjectList();


	?>
	<script>
jQuery(function(){
jQuery(".checked").click(function() {
  var fileids=jQuery(this).attr("data-fileid");
  var user = jQuery(this).attr("data-user");
  var company = jQuery(this).attr("data-comp");
  var sitePath = '<?php  echo JURI::root();?>';
   var durl = sitePath + 'member-files';
 


        
    var url = sitePath + 'index.php?option=com_users&task=suppliersendfiles.markviewedfiles';

     jQuery.ajax({
        type: 'post',
        url: url,
        data: {user_id:user,comp_id:company,fileid:fileids},
        success:function(data)
        {
        location.href = durl;
        
        }
     });

});  

});


</script>
		<form action="index.php?option=com_users&task=suppliersendfiles.markviewedfiles" method="post" name="share" id="share">
			<table border="1" width="100%">
			<tr>
				<!--<th>Select</th>-->
				<!--<th>Supplier Name</th>-->
				<th>Company Name</th>
				<th>Description</th>
				<th>File Type</th>
				<!--<th>Files</th>-->
				<th>Publishing Date</th>
				<th>Expiry Date</th>
				<!--<th>Action</th>-->
				<!--<th>Status</th>-->
			</tr>
			<?php 
			$today = date("Y-m-d");
			if(count($files) > 0 )
			{
				$m=0;
				foreach($files as $sendFiles)
				{
                            
                              if((($sendFiles->doc_name == "Terms") || ($sendFiles->doc_name == "Price List")) && ($totalusertype < 1))
				{
					
					unset($sendFiles);
				}
                              else{

						$today = strtotime(date("Y-m-d"));
						$expiration_date = strtotime($sendFiles->expiry);
						if ($expiration_date > $today) 
						{

							$cls = 'valid';
						}
						?>
					<tr>

						<!--<td><input type="checkbox" name="selectedFiles[]" value="<?php //echo $sendFiles->id; ?>"></td>-->
						<!--<td><?php //echo $sendFiles->supplier_name; ?></td>-->
						<td><?php echo $sendFiles->company_name; ?></td>
						<?php $filepaths= base64_encode($sendFiles->filename);?>
						<!--<td><a class="checked"  href="<?php echo JURI::root().'download.php?path='.$filepaths; ?>" ><?php echo $sendFiles->description; ?></a></td>-->
                                                 <td><a class="checked"  data-fileid="<?php echo $sendFiles->fileids; ?>" data-user="<?php echo $usr_id ;?>" data-comp="<?php echo $comp_id; ?>"  data-href="<?php echo JURI::root().'download.php?path='.$filepaths; ?>" href="<?php echo JURI::root().'download.php?path='.$filepaths; ?>" ><?php echo $sendFiles->description; ?></a></td>
						
						<td><?php echo $sendFiles->doc_name; ?></td>
					
						<!--<td><a href="<?php //echo JURI::root()?>upload/files/<?php //echo $sendFiles->filename; ?>" target="_blank">Sended File</a>
						</td>-->
						<?php $test = new DateTime($sendFiles->uploaded);
                             $uploaded= date_format($test, 'd-m-Y');  ?>
						<td><?php echo $uploaded; ?></td>
						<?php $test1 = new DateTime($sendFiles->expiry);
                             $expiry =date_format($test1, 'd-m-Y'); ?>
                             <?php if ($expiration_date < $today) :?>
						<td style="color:red"><?php echo $expiry; ?></td>
					<?php else :?>
					<td class="<?php echo $cls; ?>"><?php echo $expiry; ?></td>
					<?php endif; ?>
					<!--<td ><a class="checked1"  data-fileid="<?php echo $sendFiles->fileids; ?>" data-user="<?php echo $usr_id ;?>" data-comp="<?php echo $comp_id; ?>"  data-href="<?php echo JURI::root().'download.php?path='.$filepaths; ?>" href="#" >View</a></td>-->
						<!--<td>New</td>-->
					</tr>
					<?php }
					   $m++;
					 
				
			}

					 ?>
					<?php if($m=='0'){ ?>
						<tr>
						<!--<td colspan="8" align="center">No Record Found !!!</td>-->
						<td></td>
						</tr>
					<?php } ?>
				<tr><td colspan="8" align="center">
				<input type="hidden" name="user_id" value="<?php echo $usr_id; ?>"/>
				<input type="hidden" name="comp_id" value="<?php echo $comp_id; ?>"/>
				<!--<input type="submit" name="markedView" id="markedView" value="Mark as Viewed" />-->
				</td></tr>
			<?php } else { ?>
				<tr>
				<td colspan="6" align="center">No Record Found !!!</td>
				</tr>
			<?php }?>
			</table>
		</form>
		
		<h4>VIEWED FILES</h4>
		<table border="1" width="100%">
			<tr>
				<!--<th>Supplier Name</th>-->
				<th>Company Name</th>
				<th>Description</th>
				<th>File Type</th>
				<!--<th>Files</th>-->
				<th>Publishing Date</th>
				<th>Expiry Date</th>

				<!--<th>Action</th>-->
			</tr>
			<?php $p=0;
			foreach($files1 as $sendFiles)
			{
                        if((($sendFiles->doc_name == "Terms") || ($sendFiles->doc_name == "Price List")) && ($totalusertype < 1))
				{
					
					unset($sendFiles);
				}else {
				
					$today = strtotime(date("Y-m-d"));
					$expiration_date = strtotime($sendFiles->expiry);
					if ($expiration_date > $today) 
					{
						$cls = 'valid';
					}
					 ?>
				<tr>
					<!--<td><?php //echo $sendFiles->supplier_name; ?></td>-->
					<td><?php echo $sendFiles->link_name; ?></td>
						<?php $filepaths= base64_encode($sendFiles->filename);?>
					<td><a href="<?php echo JURI::root().'download.php?path='.$filepaths; ?>"><?php echo $sendFiles->description; ?></a></td>
						
						<td><?php echo $sendFiles->doc_name; ?></td>
					<?php $test = new DateTime($sendFiles->uploaded);
                             $uploaded= date_format($test, 'd-m-Y');  ?>
						<td><?php echo $uploaded; ?></td>
						<?php $test1 = new DateTime($sendFiles->expiry);
                             $expiry =date_format($test1, 'd-m-Y'); ?>
                             <?php if ($expiration_date < $today): ?>
                             	<td style="color:red"><?php echo $expiry; ?></td>
                             <?php else : ?>
					<td class="<?php echo $cls; ?>"><?php echo $expiry; ?></td>
					<?php endif; ?>
					<!--<td><a href="index.php?option=com_users&task=suppliersendfiles.deleteRequestFiles&id=<?php //echo $sendFiles->ftmid; ?>">Delete</a></td>-->
				</tr>
				<?php } $p++;  ?>
			<?php } ?>
			<?php if($p=='0'){ ?>
				<tr>
				<!--<td colspan="8" align="center">No Record Found !!!</td>-->
				<td></td>
				</tr>
			<?php } ?>
		</table>
	</div>
	
	
<?php } else { 
	$app =& JFactory::getApplication();
	$app->redirect(JURI::base(), JFactory::getApplication()->enqueueMessage('Please login', 'error'));
}?>
