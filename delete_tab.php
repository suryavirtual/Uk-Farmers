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
 $id=$_REQUEST['comp_id'];

$del=mysql_query("delete from  jos_company_tab where id='$id'") ;

if($del)
{
	echo "sucess";
	die;

}
?>