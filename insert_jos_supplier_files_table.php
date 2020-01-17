<?php

$files=array();

$con = mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
$db=mysql_select_db('ffstagin_livenew', $con)or die('database not selected');
//$res=mysql_query('select * from  supplier_files LIMIT 100 OFFSET 9',$con);
$res=mysql_query('SELECT * FROM supplier_files WHERE uploaded< Now() and uploaded > DATE_ADD(Now(), INTERVAL- 6 MONTH) and approved="y"',$con);
//$res=mysql_query('select sf.*,m.memberid  from  supplier_files as sf left join  member_files as m on (sf.id=m.fileid)',$con);

while($row = mysql_fetch_array($res)) 
         {
       $files[]=$row;
         	
            
        }

  mysql_close($con);
  //echo "<pre>";
  //print_r($files);
  //echo "totalcount".count($files);
  //die("stop here for checking old files ");
  $con1 =mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
  $db1=mysql_select_db('ffstagin_ukfarmers', $con1)or die('database not selected');
        if(!empty($files))
        {
        	foreach ($files as $value) 
        	{
 
        		$id=$value['id'];
        		$userid=$value['userid'];
        		echo "companyid".$com_id=$value['supplierid'];
            /*new code for fetching company name from mt_links table */

           $q1 = mysql_query("select * from jos_juga_groups where id='$com_id'");
 if(mysql_num_rows($q1) == '1')
 {
 $result = mysql_fetch_object($q1);
  echo $title=$result->title;
  $q2 = mysql_query("select * from jos_mt_links where link_name ='$title'");
  if(mysql_num_rows($q2) == '1'){
    $result = mysql_fetch_object($q2);
   $newCompId = $result->link_id;
    $newcompname=$result->link_name;
   
   

        		$filename=$value['filepath'];

            //$filename=str_replace('/uploads/', ' ', $filename);
             $filename=end(explode('/', $filename));
         
        		$email_url=$value['email_url'];
        		$description=$value['description'];
        		$type=$value['type'];
        	  $uploaded=$value['uploaded'];
        		$expiry=$value['expiry'];
            $test = new DateTime($expiry);
            $expiry1= date_format($test, 'Y-m-d'); 
        		$approved=$value['approved'];
      
        		
            switch ($type) 
            {
              case 'Marketing':
                $type=1;
                break;
              case 'Offers':
                $type=2;
                break;
              case 'Price List':
                $type=3;
                break;
              case 'Terms':
                $type=4;
                break;
 
            }
            
            if($approved=="y")
            {
              $approved=1;

            }
            else
            {
             $approved=0; 
            }
        		
 /*new custom code start here for dynamic file path from uf_data folder */
        
              $foldername=strtolower($newcompname);
              $foldername = preg_replace('/[-`~!@#$%\^&*()+={}[\]\\\\|;:\'",.><?\/]/', '',$foldername);
               if (preg_match('/\s/',$foldername) )
         {
          $foldername = str_replace(' ','_', $foldername);
     }
         else
         {
          $foldername = $foldername;
         }
         $docfolder=$value['type'];
         switch ($docfolder) 
            {
              case 'Marketing':
                $$docfolder="marketing";
                break;
              case 'Offers':
                $docfolder="offers";
                break;
              case 'Price List':
                $docfolder="pricelists";
                break;
              case 'Terms':
                $docfolder="terms";
                break;
 
            }
          
  $oldpath=$value['filepath'];
  $new_filepath="uf_data/suppliers/".$foldername."/".$docfolder."/".$filename;
 /*file copy code start from here*/
 $new_image_path=$_SERVER['DOCUMENT_ROOT']."/uk_farmers/uf_data/suppliers/".$foldername."/".$docfolder."/".$filename;
  $old_image_path=$_SERVER['DOCUMENT_ROOT']."/uk_farmers/memfiles/".$filename;
     copy($old_image_path,$new_image_path);
 /*end here */
          
      /* dynamic code ends here */
    
    //echo "<pre>"; print_r($uid); echo "</pre>";
  } else 
  {
    echo "###".$titls."<br>";
  }
}

            /*code ends here */

    

        		echo $sql1="INSERT INTO jos_supplier_files (id, userid,comp_id, filename, description, type,uploaded, expiry, approved, viewed, ordering) VALUES
('', '$userid','$newCompId', '$new_filepath', '$description', '$type', '$uploaded', '$expiry1', '$approved','0','')";
echo "<br>";
//die("stop here for query checking before insert in table");



 $result=mysql_query($sql1,$con1);
$newid=mysql_insert_id();
 echo "newid".$newid;

 if($result)
 {
  echo " jos_supplier_files Data inserted successfully";
 }
 else
 {
  echo "jos_supplier_files Data not inserted";
 }

echo "sql2".$sql2="INSERT INTO  jos_supplier_file_name (id,fid,fname) VALUES
('','$newid','$new_filepath')";

$result2=mysql_query($sql2,$con1);
echo"newid2". $newid2=mysql_insert_id();
 if($result2)
 {
  echo "jos_supplier_file_name Data inserted successfully";
  echo "<br>";
 }
 else
 {
  echo "jos_supplier_file_name Data not inserted";
 }
 //echo "sql3".$sql3="INSERT INTO  jos_files_to_members (id,fileId,fileNameId,user_id,memberId,approve,viewEmail,deleteRequest,sentEmail,publishDate) VALUES
//('','$newid','$newid2','$userid','$supplierid','$approved','0','0','0','$uploaded')";
 
//$result3=mysql_query($sql3,$con1);
/*if($result3)
 {
  echo "jos_files_to_members  Data inserted successfully";
 }
 else
 {
  echo "jos_files_to_members  Data not inserted";
 } */
        		

        		
        	}

        }

mysql_close($con1);



?>