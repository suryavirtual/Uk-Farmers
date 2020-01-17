<script src="assets/js/jquery-ui.min.js"></script>

<link rel="stylesheet" href="assets/css/colorbox.css" />

<script src="assets/js/jquery.colorbox.js"></script>

<script>

jQuery(document).ready(function(){

	//Examples of how to assign the Colorbox event to elements

	jQuery(".inline").colorbox({inline:true, width:"50%"});

});



</script>

<?php

include("configuration.php");



$obj = new JConfig();

$db_host = $obj->host;

$db_username = $obj->user;

$db_password = $obj->password;

$db_name = $obj->db;

$item_per_page = 20; //item to display per page



$link = mysql_connect($db_host, $db_username, $db_password);

@mysql_select_db($db_name,$link);



if(isset($_POST) && isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){

	//Get page number from Ajax POST

	if(isset($_POST["page"])){

		$page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); //filter number

		if(!is_numeric($page_number)){die('Invalid page number!');} //incase of invalid page number

	}else{

		$page_number = 1; //if there's no page number, set it to 1

	}

	

	if(!empty($_REQUEST['val'])){

		$val = $_REQUEST['val'];

	} else {

		$val = '';

	} 

	$user_id = $_REQUEST['user_id'];

	

	?>

	

	

	<table width="100%" border="0" class="fabrikTable">

		<tr>

			<th style="width: 8.3%;">Share</th>

			<th style="width: 28%;">Description</th>

			<th style="width: 20.35%;">Category</th>

			<th style="width: 19.2%;">Sent To</th>

			<th>Expiry</th>

		</tr>

	<?php

	//Limit our results within a specified range. 



	//$getQuerySelect = "SELECT mt.link_id as compId, u.id as userId, sf.*, dt.doc_name FROM `jos_mt_links` as mt, jos_users as u, jos_supplier_files as sf, jos_document_type as dt ";

	//$getQuerySelect .= " where sf.userid='".$user_id ."'";

	//$getQuerySelect .= " and mt.link_id=u.comp_list and find_in_set(u.id, sf.userid) and sf.type=dt.id";

	/*new query for fetching data 17-05-2017 */
	$getQuerySelect = "SELECT  sf.*, dt.doc_name FROM `jos_supplier_files` as sf, jos_document_type as dt ";

	$getQuerySelect .= " where sf.comp_id='".$user_id ."'";

	$getQuerySelect .= " and  sf.type=dt.id and sf.expiry >= date_sub(now(), interval 3 month) ";
	//echo $getQuerySelect;


	

	if(!empty($val)){

		$getQuerySelect .= "and sf.description like '%$val%' ";

	}

	$getQuerySelect .= " ORDER BY sf.uploaded DESC ";



	$getFiles = mysql_query($getQuerySelect);



	$totCount = mysql_num_rows($getFiles);

	//break records into pages

	$total_pages = ceil($totCount/$item_per_page);

	//get starting position to fetch the records

	$page_position = (($page_number-1) * $item_per_page);



	$querylimmit = mysql_query($getQuerySelect . "LIMIT $page_position, $item_per_page");



	$k=0;

	if($totCount != '0'){

	while ($sendFile = mysql_fetch_object($querylimmit)){

		$shareMember = mysql_query("select fm.*, mt.link_name from jos_files_to_members as fm, jos_mt_links as mt where fm.fileId='$sendFile->id' and fm.memberId=mt.link_id and mt.link_published='1' group by mt.link_name");

		if(mysql_num_rows($shareMember)==0){

			$share = "Not Share";

		} else {

			$share = "<a class='inline' href=\"#inline_member$k\">Members</a>";

		}

		

		$todays_date = date("Y-m-d");

		$expiry_date = $sendFile->expiry;

		$today = strtotime($todays_date);

		$expiration_date = strtotime($expiry_date);

		if ($expiration_date > $today) {

			$cls = 'valid';

		} else {

			$cls = 'expire';

		}

		?>

		<tr>

			<td>

				<input type="checkbox" name="share[]" value="<?php echo $sendFile->id ;?>">

				<input type="hidden" name="user_id" id="user_id" value="<?php echo $user_id; ?>" />

			</td>

			<!--<td><a class="inline" href="#inline_files<?php //echo $k; ?>"><?php //echo $sendFile->description ;?></a></td>-->

			<?php $filepaths= base64_encode($sendFile->filename); ?> 
			<td><a href="download.php?path=<?php echo $filepaths;?>"><?php echo $sendFile->description ;?></a></td>

			<td><?php echo $sendFile->doc_name ;?></td>

			<td><?php echo $share; ?></td>

			<td class="<?php echo $cls; ?>"><?php  echo $newDate = date("d-m-Y", strtotime($expiry_date));?></td>

		</tr>

		

		

		

		<?php $attachedFile = mysql_query("select fn.* from jos_supplier_file_name as fn where fn.fid='$sendFile->id'"); ?>

		<tr>

			<td colspan="5"  style='padding:0px !important; border:0px;'>

				<div style='display:none'>

					<div id="inline_files<?php echo $k; ?>" style='padding:10px; background:#fff; height:400px; overflow-x:scroll;'>

						<table width='100%' border="1">

							<tr><th>S No</th><th>Attached Files</th></tr>

							<?php $f=1; 

							while($attachedFiles = mysql_fetch_object($attachedFile)){

								echo "<tr><td>".$f."</td><td><a href='upload/files/".$attachedFiles->fname."' target='_blank'>".$attachedFiles->fname."</a></td></tr>";

								$f++;

							} ?>

						</table>

					</div>

				</div>

			</td>

		</tr>

		

		<tr>

			<td colspan="5"  style='padding:0px !important; border:0px;'>

				<div style='display:none'>

					<div id="inline_member<?php echo $k; ?>" style='padding:10px; background:#fff; height:400px; overflow-x:scroll;'>

						<table width='100%' border="1">

							<tr><th>S No</th><th>Members</th></tr>

							<?php $m=1;

							while($memberFiles = mysql_fetch_object($shareMember)){

								echo "<tr><td>".$m."</td><td>".$memberFiles->link_name."</td></tr>";

								$m++;

							} ?>

						</table>

					</div>

				</div>

			</td>

		</tr>		

		<?php $k++; }

	} else { ?>

		<tr>
		<!--<td colspan="5">No Record found</td>-->
		</tr>

	<?php }

	echo '</table>';



	echo '<br>';

	echo '<div align="center">';

	/* We call the pagination function here to generate Pagination link for us. 

	As you can see I have passed several parameters to the function. */



	echo paginate_function($item_per_page, $page_number, $totCount, $total_pages,$val);

	echo '</div>';

	

	exit;

}

################ pagination function #########################################

function paginate_function($item_per_page, $current_page, $total_records, $total_pages, $val)

{

	$pagination = '';

    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ //verify total pages and current page number

        $pagination .= '<ul class="pagination">';

        

        $right_links    = $current_page + 3; 

        $previous       = $current_page - 3; //previous link 

        $next           = $current_page + 1; //next link

        $first_link     = true; //boolean var to decide our first link

        

        if($current_page > 1){

			$previous_link = ($previous==0)? 1: $previous;

            $pagination .= '<li class="first"><a href="#" data-page="1" data-val="'.$val.'" title="First">&laquo;</a></li>'; //first link

            //$pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link

                for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links

                    if($i > 0){

                        $pagination .= '<li><a href="#" data-page="'.$i.'" data-val="'.$val.'" title="Page'.$i.'">'.$i.'</a></li>';

                    }

                }   

            $first_link = false; //set first link to false

        }

        

        if($first_link){ //if current active page is first link

            $pagination .= '<li class="first active">'.$current_page.'</li>';

        }elseif($current_page == $total_pages){ //if it's the last active link

            $pagination .= '<li class="last active">'.$current_page.'</li>';

        }else{ //regular current link

            $pagination .= '<li class="active">'.$current_page.'</li>';

        }

                

        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links

            if($i<=$total_pages){

                $pagination .= '<li><a href="#" data-page="'.$i.'" data-val="'.$val.'" title="Page '.$i.'">'.$i.'</a></li>';

            }

        }

        if($current_page < $total_pages){ 

				$next_link = ($i > $total_pages) ? $total_pages : $i;

                $pagination .= '<li><a href="#" data-page="'.$next_link.'" data-val="'.$val.'" title="Next">&gt;</a></li>'; //next link

                $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" data-val="'.$val.'" title="Last">&raquo;</a></li>'; //last link

        }

        

        $pagination .= '</ul>'; 

    }

    return $pagination; //return pagination links

}



?>



