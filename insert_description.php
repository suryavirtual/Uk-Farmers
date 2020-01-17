<?php


$group_id=array();
$user_id=array();
$old_data=array();

$con = mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
$db=mysql_select_db('ffstagin_ukfarmers', $con)or die('database not selected');



$q1 = mysql_query("select * from  jos_mt_links_8may");
while($row = mysql_fetch_array($q1))
{
  $old_data[]=$row;
 
}
//echo "<pre>";print_r($old_data); die;



if(!empty($old_data))
{

  foreach($old_data as $old)
  {
    echo "oldlink_id".$old_link_id=$old['link_id'];
    echo "old_title".$old_link_name=$old['link_name'];
    echo "olddecription".$old_description=$old['link_desc'];
    echo "<br>";
   // $q2 = mysql_query("select * from jos_mt_links where link_name like '%$old_link_name%'");
    $q2 = mysql_query("select * from jos_mt_links where link_name = '$old_link_name'");
    if(mysql_num_rows($q2) == '1')
    { 
      $result = mysql_fetch_object($q2);
      //echo "<pre>";print_r($result); die("stop here");

     echo "newid".$newid=$result->link_id;
     echo "newname".$newname=$result->link_name;
     echo "newdecription".$newdescription=$result->link_desc;
     echo "update jos_mt_links set link_desc='$old_description' where link_id ='$newid'";

     //$q4 = mysql_query("update jos_company_tab set comp_id='$newid' where comp_id ='$old_link_id'");
     $q4 = mysql_query("update jos_mt_links set link_desc='$old_description' where link_id ='$newid'");
    }
    else
    {
      echo "not updated".$old_link_name;
    }
    
    





  }
}
die;
  
  







?>