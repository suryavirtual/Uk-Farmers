<?php 
$con = mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
$db=mysql_select_db('ffstagin_ukfarmers', $con)or die('database not selected');

$q1 = mysql_query("select * from jos_juga_groups where id > 10");
while($row = mysql_fetch_object($q1)){
	//echo "<pre>"; print_r($row); echo "</pre>";
	$titls = $row->title;
	$oldId = $row->id;
	$alias = strtolower(str_replace(" ", "_", $titls));
	
	$q2 = mysql_query("select * from jos_mt_links where link_name like '%$titls%'");
	if(mysql_num_rows($q2) == '1'){
		$result = mysql_fetch_object($q2);
		$newCompId = $result->link_id;
		
		$q3 = mysql_query("select * from jos_juga_u2g where group_id = '$oldId'");
		$uid = array();
		while($row1 = mysql_fetch_object($q3)){
			$uid[] = $row1->user_id;
		}
		$fuid = implode("','", $uid);
		
		$q4 = mysql_query("update jos_users set comp_list='$newCompId' where id in ('$fuid')");
		//echo "<pre>"; print_r($uid); echo "</pre>";
	} else {
		echo "###".$titls."<br>";
	}
}
?>