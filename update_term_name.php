<?php

$files=array();

$con = mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
$db=mysql_select_db('ffstagin_ukfarmers', $con)or die('database not selected');

$query="SELECT tr.*, mt.link_name FROM jos_structured_terms as tr,jos_mt_links as mt where tr.supplierUserId= mt.link_id";
$res=mysql_query($query,$con);


while($row = mysql_fetch_object($res)) 
{
  //echo "<pre>"; print_r($row);
  //echo "toatlcount".count($row);
  
  $oldPath = "";
  
  
  $suppliername=str_replace(' ','_',strtolower($row->link_name));
  $filetype="terms";
  

  $fname=explode("/",$row->termsFile);
  $cnt = count($fname);
  $filename = trim($fname[$cnt-1]);

  $fPath = "";
  for($i=0; $i<$cnt-1; $i++){
      $fPath[] = $fname[$i];
  }
  $oldPath = $_SERVER['DOCUMENT_ROOT']."/uk_farmers/".implode("/",$fPath);
  $newDirPath = $_SERVER['DOCUMENT_ROOT']."/uk_farmers/uf_data/suppliers/";
  $newPath = $_SERVER['DOCUMENT_ROOT']."/uk_farmers/uf_data/suppliers/".$suppliername."/".$filetype;

  echo "<br>";
  if(file_exists($oldPath."/".$filename)){
    if(!is_dir($newDirPath."/".$suppliername))
    {
      mkdir($newDirPath."/".$suppliername, 0777, true);
      mkdir($newDirPath."/".$suppliername."/terms", 0777, true);
      mkdir($newDirPath."/".$suppliername."/marketing", 0777, true);
      mkdir($newDirPath."/".$suppliername."/pricelists", 0777, true);
      mkdir($newDirPath."/".$suppliername."/offers", 0777, true);
      mkdir($newDirPath."/".$suppliername."/logos", 0777, true);
    }
    // 34 records available
    $id = $row->termId;
    echo "<br>";
    echo $oldPath."/".$filename."===".$newPath."/".$filename;
    rename($oldPath."/".$filename,$newPath."/".$filename);

    $newfilename = "uf_data/suppliers/".$suppliername."/".$filetype."/".$filename;
    $sql1="update jos_structured_terms set termsFile='".$newfilename."' where termId='".$id."' ";
    $result=mysql_query($sql1,$con);
    echo "<br>";
    echo "terms move sucessfully".$newfilename."and old path is:  ".$oldPath."and new file is  ".$newPath."id is  ".$id;
  } else {
    //echo "No";
    //echo "<br>";
  }
  //echo "<pre>"; print_r($row); echo "</pre>";

}
die("11111");

  mysql_close($con);
 //echo "<pre>";
  //print_r($files);
 

?>