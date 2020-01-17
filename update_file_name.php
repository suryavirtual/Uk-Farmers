<?php

$files=array();

$con = mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
$db=mysql_select_db('ffstagin_ukfarmers', $con)or die('database not selected');

$query="SELECT sf.*, mt.link_name FROM jos_supplier_files as sf, jos_users as u, jos_mt_links as mt where sf.userid=u.id and u.comp_list = mt.link_id";
$res=mysql_query($query,$con);


while($row = mysql_fetch_object($res)) 
{
  $oldPath = "";
  
  $type=$row->type;
  $suppliername=str_replace(' ','_',strtolower($row->link_name));
  $filetype="";
  switch ($type) 
  {
    case 1:
      $filetype="marketing";
      break;
    case 2:
      $filetype="offers";
      break;
    case 3:
      $filetype="pricelists";
      break;
    case 4:
      $filetype="terms";
      break;
  }

  $fname=explode("/",$row->filename);
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
    $id = $row->id;
    echo "<br>";
    //echo $oldPath."/".$filename."===".$newPath."/".$filename;
    rename($oldPath."/".$filename,$newPath."/".$filename);

    $newfilename = "uf_data/suppliers/".$suppliername."/".$filetype."/".$filename;
    $sql1="update jos_supplier_files set filename='".$newfilename."' where id='".$id."' ";
    $result=mysql_query($sql1,$con);
    echo "<br>";
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
  echo "totaldata available".count($files);

  $con1 = mysql_connect('localhost','root','') or die ('not connected');
  $db1=mysql_select_db('uk_farmers', $con1)or die('database not selected');
        if(!empty($files))
        {
        	foreach ($files as $value) 
        	{
            //echo "<pre>"; print_r($value);


 
        		$id=$value['id'];
       
          /*end folder location ends here */

        		$filename=$value['filename'];
            $mainfile=end(explode('/',$filename));
            
            
            $filenamearray=explode('/',$filename);
           $folderpath=array_pop($filenamearray);
         $path = implode($filenamearray,'/');
         echo "oldpath".$folderpath=$_SERVER['DOCUMENT_ROOT'].'/uk_farmers/'.$path.'/'.$mainfile;
         echo "<br>";
         if($cmpnames != "" && $filetype!= "" && $mainfile!= "")
         {
          echo "newpath".$newlocation=$_SERVER['DOCUMENT_ROOT'].'/uk_farmers/uf_data/suppliers/'.$cmpnames.'/'.$filetype.'/'.$mainfile;
         }
         
          
          
         if(file_exists($folderpath))
         {
          $feedDir = $_SERVER['DOCUMENT_ROOT']."/uk_farmers/uf_data/suppliers/";
          if(!is_dir($feedDir."/".$cmpnames))
                 {
                 mkdir($feedDir."/".$cmpnames, 0777, true);
                 mkdir($feedDir."/".$cmpnames."/terms", 0777, true);
                 mkdir($feedDir."/".$cmpnames."/marketing", 0777, true);
                 mkdir($feedDir."/".$cmpnames."/pricelists", 0777, true);
                 mkdir($feedDir."/".$cmpnames."/offers", 0777, true);
                 mkdir($feedDir."/".$cmpnames."/logos", 0777, true);
                 }

          $newfilename='uf_data/suppliers/'.$cmpnames.'/'.$filetype.'/'.$mainfile;
          echo "file found";
          rename($folderpath,$newlocation);
          /*update code here */
        $sql1="update jos_supplier_files set filename='".$newfilename."' where id='".$id."' ";

        $result=mysql_query($sql1,$con1);
          /*update code ends here*/

         }
         else
         {
          //echo "file not found";
         }
           //die("stop here");
          
          }
        }
      
        	

mysql_close($con1);



?>