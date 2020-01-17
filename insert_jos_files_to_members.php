<?php

$files=array();

$con = mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
$db=mysql_select_db('ffstagin_livenew', $con)or die('database not selected');
//$res=mysql_query('select * from  member_files',$con);
//where WHERE sf.uploaded< Now() and sf.uploaded > DATE_ADD(Now(), INTERVAL- 6 MONTH) and approved="y"
//$res=mysql_query('select mf.*,sf.supplierid,sf.description from  member_files as mf left join supplier_files sf on(sf.id=mf.fileid) where sf.id in(SELECT id FROM supplier_files WHERE uploaded< Now() and uploaded > DATE_ADD(Now(), INTERVAL- 6 MONTH) and approved="y")',$con);
$res=mysql_query('select mf.*,sf.supplierid,sf.description from  member_files as mf left join supplier_files sf on(sf.id=mf.fileid) where fileid="1710"',$con);


while($row = mysql_fetch_array($res)) 
         {
       $files[]=$row;
         	
            
        }

  mysql_close($con);
  //echo "<pre>";
 // echo count($files);
//print_r($files);
 //die("stop here for checking  data");

  $con1 =mysql_connect('localhost','ffstagin_ukfarmr','THdT*MT].K5h') or die ('not connected');
  $db1=mysql_select_db('ffstagin_ukfarmers', $con1)or die('database not selected');
if(!empty($files))
        {
        	foreach ($files as $value) 
        	{
        			$id=$value['id'];
        			$memberid=$value['memberid'];
        			$fileid=$value['fileid'];
        			$viewed=$value['viewed'];
        			$approved=$value['approved'];
        			$mail_sent=$value['mail_sent'];
        			$email_sent=$value['email_sent'];
              $comp_ids=$value['supplierid'];
              echo $decriptions=$value['description'];
        	if($approved=="y")
            {
              $approved=1;

            }
            else
            {
             $approved=0; 
            }

            if($email_sent=="y")
            {
              $email_sent=1;

            }
            else
            {
             $email_sent=0; 
            }
            if($viewed=="y")
            {
              $viewed=1;

            }
            else
            {
              $viewed=0;
            }

$q1 = mysql_query("select * from jos_juga_groups where id='$comp_ids'");
if(mysql_num_rows($q1) == '1')
 {
 $result = mysql_fetch_object($q1);
 echo $title=$result->title;
 $q2 = mysql_query("select * from jos_mt_links where link_name ='$title'");
  if(mysql_num_rows($q2) == '1')
  {
   $result = mysql_fetch_object($q2);
   echo "newcompid".$newCompId = $result->link_id;
   echo "<br>";
   echo "newcompname".$newcompname=$result->link_name;
   echo "<br>";
  }
   $query3="select * from jos_supplier_files where description ='$decriptions' ";
  $q3 = mysql_query($query3);
  if(mysql_num_rows($q3) == '1')
  {
    $result1 = mysql_fetch_object($q3);
    
  $newfileids=$result1->id;
    }


 }
 
 echo "<br>";
 /*new code for fetching member id */
 $q4 = mysql_query("select * from jos_juga_groups where id='$memberid'");
if(mysql_num_rows($q4) == '1')
 {
 $result = mysql_fetch_object($q4);
 $title1=$result->title;
 $q5 = mysql_query("select * from jos_mt_links where link_name ='$title1'");
  if(mysql_num_rows($q5) == '1')
  {
   $result = mysql_fetch_object($q5);
   $newmemid = $result->link_id;
 }
}
 /*code ends here */


    


  echo $sql5="INSERT INTO  jos_files_to_members (id,fileId,fileNameId,user_id,comp_id,memberId,approve,viewFile,deleteRequest,sentEmail,publishDate) VALUES
('','$newfileids','$newfileids','','$newCompId','$newmemid','$approved','$viewed','0','$email_sent','')";

echo "<br>";
die("stop here");



 //$result1=mysql_query($sql5,$con1);
 //$newids=mysql_insert_id();

 if($result1)
 {
  echo " jos_files_to_members Data inserted successfully".$newids;
 }
 else
 {
  echo "jos_files_to_members Data not inserted";
 }







        			
        	}

        }
mysql_close($con1);

  ?>