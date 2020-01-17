<?php

function checkValues($value)

{

	// Use this function on all those values where you want to check for both sql injection and cross site scripting

	 //Trim the value

	 $value = trim($value);

	 

	// Stripslashes

	if (get_magic_quotes_gpc()) {

		$value = stripslashes($value);

	}

	

	 // Convert all &lt;, &gt; etc. to normal html and then strip these

	 $value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));

	

	 // Strip HTML Tags

	 $value = strip_tags($value);

	

	// Quote the value

	$value = mysql_real_escape_string($value);

	return $value;

	

}	



$url = explode("search.php",'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);

$furl = $url['0'];



include("configuration.php");



$obj = new JConfig();

$host = $obj->host;

$dbUser = $obj->user;

$dbPwd = $obj->password;

$dbName = $obj->db;



$link = mysql_connect($host, $dbUser, $dbPwd);

@mysql_select_db($dbName,$link);	



$rec = checkValues($_REQUEST['val']);
//$rec = $_REQUEST['val'];



//get table contents

if($rec){

	/*echo $sql = "select * from jos_mt_links_products where pName like '%$rec%' or pDesc like '%$rec%' and status='1' order by pName desc";*/

  /* new search code added*/

  /*$sql = "select cfv.*, mt.link_name, mt.link_desc, mt.alias, img.filename

from jos_mt_cfvalues as cfv, jos_mt_links as mt, jos_mt_images as img

where cfv.value like '%$rec%' and cfv.cf_id='28' and cfv.link_id=mt.link_id and mt.link_id=img.link_id ";*/

 //$sql = "select cfv.*,mt.link_name, mt.link_desc, mt.alias,img.filename from jos_mt_cfvalues as cfv left join jos_mt_links as mt on(cfv.link_id=mt.link_id)left join jos_mt_images as img on(mt.link_id=img.link_id)  where cfv.cf_id='28' and mt.link_published='1' and cfv.value like '%$rec%'  order by mt.link_name ";
 $sql = "select distinct cfv.*,cpv.value,mt.link_name, mt.link_desc, mt.alias,img.filename from jos_mt_cfvalues as cfv 
 left join jos_mt_links as mt on(cfv.link_id=mt.link_id)
 left join jos_mt_cfvalues as cpv  on(cpv.link_id=mt.link_id)
 left join jos_mt_images as img on(mt.link_id=img.link_id)
 where cfv.cf_id='28' and mt.link_published='1' and cfv.value like '%$rec%' and cpv.cf_id='30' and img.ordering='1'   order by mt.link_name ";









} else {

	//$sql = "select * from jos_mt_links_products where status='1' order by pName desc";

}



$rsd = mysql_query($sql);

 $total =  mysql_num_rows($rsd);





?>



	<div >

<?php

while ($productsDtls = mysql_fetch_object($rsd)){

	//echo "<pre>";

	//print_r($productsDtls);

	

 ?>

	<?php $imgs = explode(",",$productsDtls->filename);

	if(empty($imgs[0]))

	{

	$img=$furl."/media/com_mtree/images/noimage_thb.png";	

	}

	else

		{
			/* new code added here for fetching images from custom folder structure */
            $linkids=$productsDtls->link_id;
			$sql="select folder_name from jos_folder where link_id='$linkids'";
			$folders = mysql_query($sql);
			$folderarray=mysql_fetch_object($folders);
            $usertypes=$productsDtls->value;
            $foldername=$folderarray->folder_name;
            /*
              $foldername = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '',$foldername);
               if (preg_match('/\s/',$foldername) )
			   {
			   	$foldername = str_replace(' ','_', $foldername);
			   }
			   else
			   {
			   	$foldername = $foldername;
			   }*/
			   if($usertypes=="Supplier")
					{
                    $usertypes="suppliers";
					}
					if($usertypes=="Member")
					{
					$usertypes="members";	
					}
					
 

     $img=$furl."/uf_data/".$usertypes."/".$foldername."/logos/".$imgs[0];

			/* code ends here */

			//$img=$furl.'/media/com_mtree/images/listings/m/'.$imgs[0];

			/* echo $furl; ?>/media/com_mtree/images/listings/m/<?php echo $imgs[0];*/

		}?>

	<div class="memberProductList">

		<div class="product-list-summary">

			<div class="list-title">

			

				<h3><a href="<?php echo $furl; ?>/supplier-search/<?php echo $productsDtls->link_id.'-'.$productsDtls->alias; ?>"><span itemprop="name"><?php echo $productsDtls->link_name; ?></span></a></h3>

			</div>

			<div class="prod-img">

			

				<img src="<?php echo $img;?>" width="100" height="100" class="image-left" alt="" />

			</div>

			<div class="descriptions">

			<p><?php echo truncate($productsDtls->link_desc,'300'); ?> </p>

			<!--<p><?php //echo substr($productsDtls->link_desc,0,300);  ?></p>-->

			<?php $description=strlen($productsDtls->link_desc);

			if($description>300)

			{?>

            <a class="readon" href="<?php echo $furl; ?>supplier-search/<?php echo $productsDtls->link_id.'-'.$productsDtls->alias; ?>">Read More</a>



			<?php } ?>



			</div>	

			

		</div>

	</div>

	</div>

<?php }



if($total==0){ echo '<div class="no-rec">No Record Found </div>';}?>



<!-- function for trimming the word ---->

<?php 

function truncate($text, $length = 0, $end='...')

{



if ($length > 0 && strlen($text) > $length)

{



$tmp = substr($text, 0, $length);

$tmp = substr($tmp, 0, strrpos($tmp, ' '));



if (strlen($tmp) >= $length - 3) {

$tmp = substr($tmp, 0, strrpos($tmp, ' '));

}



$text = $tmp.$end;

}

return $text;

} ?>





