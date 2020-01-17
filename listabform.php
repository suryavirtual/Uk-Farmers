<?php

include("configuration.php");



$obj = new JConfig();
//print_r($obj);

$host = $obj->host;

$dbUser = $obj->user;

$dbPwd = $obj->password;

$dbName = $obj->db;



$link = mysql_connect($host, $dbUser, $dbPwd);

mysql_select_db($dbName,$link); 

?>


<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script src="validatejs/jquery-1.9.1.min.js"></script>
<script src="validatejs/jquery.validate.min.js"></script>
<script>
       $(function() {
        $("#myform").validate({
    rules: {
        "tabslevel": {
           required:true
          },
          "url":{
              required:true,
              url:true
              
         
          },
          "Description":{
              required:true
              
         
          }
      },
      
      messages:{
       "tabslevel":"please enter the Tab name",
       "Description":"please enter the Description",
       "url": {
                required: "please enter the url",
                url: "please Enter the valid url"
            }
      },
      
      });
      

       });
       function myFunction(me) 
        {
          
          var id=me.id;
         
          
   $.ajax({
   url: 'delete_tab.php',
   data: {comp_id:id},

   
   success: function(data) 
   {
    //alert(data);
   // console.log(data);
    window.location.reload();
   },
   
   });



  }
      
      </script>
  <style>
    .error{
        font-weight: normal;
        color: red;
        font-size: 12px;
    }
table.tab-list th {
    text-align: center;
}

table.tab-list td {
    text-align: center;
}
</style>

<?php 

if(!empty($_REQUEST['comp_id']))

{

 $company_id=$_REQUEST['comp_id']; 

}

else

{

  $company_id="";

}




 

$rsd=mysql_query("select * from  jos_company_tab where comp_id='$company_id'") ;
 while ($productsDtls = mysql_fetch_array($rsd))
 {	
  $data[]=$productsDtls;
}
?>
<html>
<head>
  
</head>
<body>

<table class="tab-list">
<h1> Tab List </h1>
<tr><th>Sr</th>
<th>Level</th>
<!--<th>Url</th>-->
<!--<th>Description</th>-->
<th colspan="2">Option</th>
</tr>
<?php $i=1;foreach($data as $comp):?>
<tr><td><?php echo $i ?></td>
<td><?php echo $comp['label']; ?></td>
<!--<td><?php echo $comp['url']; ?></td>-->
<!--<td><?php echo substr($comp['description'],0,10); ?></td>-->
<td><a class="various btn btn-success pull-right" data-fancybox-type="iframe" href="tabform.php?list_id=<?php echo $comp['id'];?>">Edit</a></td>
<td><a href="#" onclick="myFunction(this)" id="<?php echo $comp['id'];?>">Delete</a></td>
</tr>
<?php $i++;endforeach; ?>
<table></body></html>

<style>
table.tab-list{ width: 100%; text-align: left; }
.tab-list th {
    background: #409740;
    font-size: 14px;
    padding: 5px;
    color: #fff;
   font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
   font-weight: normal;
}
.tab-list td {background: #f1f1f1;
    font-size: 12px;
    text-align: left;
    padding: 5px;   
    text-transform: capitalize;
     font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
   font-weight: normal;}
.tab-list td a{    text-decoration: none;
        color: #b91313;
    font-weight: 600;}
    .tab-list td a:hover{    text-decoration: underline; }

</style>