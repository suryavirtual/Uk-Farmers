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

		if(!is_numeric($page_number)){die('Invalid page number');} //incase of invalid page number

	}else{

		$page_number = 1; //if there's no page number, set it to 1

	}

	

	if(!empty($_REQUEST['val'])){

		$val = $_REQUEST['val'];

	} else {

		$val = '';

	}

	 $user_id = $_REQUEST['user_id'];

	

	if(!empty($val)){

		$results = mysql_query("select COUNT(*) as total from jos_structured_terms where termName like '%$val%' and supplierUserId = $user_id");

	} else{

		$results = mysql_query("select COUNT(*) as total from jos_structured_terms where supplierUserId = $user_id");

	}

	

	if(!empty($_REQUEST['comp_id']))

	{

     $compid = $_REQUEST['comp_id'];

	}

	else

	{

		$compid="";



	}

	

	?>

	

	

		

	<?php



	//get starting position to fetch the records

    $page_position = (($page_number-1) * $item_per_page);

	//Limit our results within a specified range. 

	$getQuerySelect = "SELECT termId, termName, effectiveFrom,termsFile, validTo, termsFile FROM jos_structured_terms ";

	$getQuerySelect .= "where supplierUserId = $user_id ";

	//$getQuerySelect .= "where cmpId = $user_id ";

	if(!empty($val)){

		$getQuerySelect .= "and termName like '%$val%' ";

	}

	$getQuerySelect .= "order by termId desc ";

	//$getQuerySelect .= "LIMIT $page_position, $item_per_page";

	

	

	$getQuery = mysql_query($getQuerySelect);

	$totCount = mysql_num_rows($getQuery);



   $total_pages = ceil($totCount/$item_per_page);

    $querylimmit = mysql_query($getQuerySelect . "LIMIT $page_position, $item_per_page");

	

	



	$k=0;

	$cnt = mysql_num_rows($querylimmit);

	

	if($cnt != '0'){ $t=1;
		?>
		
		<table width="100%" border="0" class="terms-admin ">

		<tr>

			<tr>

			<th class="one">Sr No</th>

			<th class="tab11"> Terms Name</th>

			<th class="tab22">Filename</th>
			<th class="tab22">Sent To</th>

			<th class="tab33">Terms Start</th>

			<th class="tab44">Terms Expire</th>

			<th class="tab55">Select</th>

		</tr>
		<?php 

	while ($row = mysql_fetch_object($querylimmit)){

		$shareMember = mysql_query("select st.*, mt.link_name from jos_structured_terms as st,`jos_structured_terms_to_members` as stm, jos_mt_links as mt where st.termId='$row->termId' and st.termId=stm.terms_id and mt.link_id=stm.member_id and stm.approved='1' group by mt.link_name");

		//$shareMember = mysql_query("select fm.*, mt.link_name from jos_files_to_members as fm, jos_mt_links as mt where fm.fileId='$sendFile->id' and fm.memberId=mt.link_id and mt.link_published='1' group by mt.link_name");

		if(mysql_num_rows($shareMember)==0){

			$share = "Not Share";

		} else {

			$share = "<a class='inline' href=\"#inline_member$k\">Members</a>";

		}

		?>

		<tr>

		<td><?php echo $t; ?></td>

			<!--<td>

				<input type="checkbox" name="share[]" value="<?php //echo $row->termId; ?>">

				<input type="hidden" name="user_id" id="user_id" value="<?php //echo $user_id; ?>" />

			</td>-->
             <?php   $url=$_SERVER['REQUEST_URI'].str_replace("administrator"," ", $row->termsFile);
              $lastname= end(explode('/', $_SERVER['REQUEST_URI'])); ?>
			<td><a href="<?php echo str_replace($lastname,'',$url);'' ?>" target="_blank"><?php echo $row->termName; ?></a></td>

						<td>

				<?php  echo end(explode('/', $row->termsFile)); ?>

			</td>
			<td><?php echo $share; ?></td>

			<td> <?php echo $row->effectiveFrom; ?></td>

			<?php $todays_date = date("Y-m-d");

				$expiry_date = $row->validTo;

				$today = strtotime($todays_date);

				$expiration_date = strtotime($expiry_date);

				if ($expiration_date > $today) { ?>

					<td class="valid"><?php echo $row->validTo; ?></td>

				<?php } else { ?>

					<td><span style= "color:#ff0000;"> <?php echo $row->validTo; ?></span></td>

				<?php } ?>

				

				<td><input type="checkbox" name="share[]" value="<?php echo $row->termId; ?>"></td>



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

		

		

		<?php $k++;$t++; }

	} else { ?>

		<tr>

			<td colspan="5">No Record found</td>

		</tr>

	<?php  }

	echo '</table>';



	echo '<br>';

	echo '<div align="center">';

	/* We call the pagination function here to generate Pagination link for us. 

	As you can see I have passed several parameters to the function. */

	

	echo paginate_function($item_per_page, $page_number, $totCount->total, $total_pages,$val);

	echo '</div>';

	?>

	<div class="clearfix"></div>

	<?php 

	

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



