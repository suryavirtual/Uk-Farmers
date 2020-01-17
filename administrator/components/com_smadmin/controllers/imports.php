<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_smadmin
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Supplier/Member Controller
 *
 * @since  0.0.1
 */
require_once(JPATH_COMPONENT.'/'.'simplexlsx.class.php');
class SmAdminControllerImports extends JControllerAdmin
{
        /**
         * Proxy for getModel.
         *
         * @param   string  $name    The model name. Optional.
         * @param   string  $prefix  The class prefix. Optional.
         * @param   array   $config  Configuration array for model. Optional.
         *
         * @return  object  The model.
         *
         * @since   1.6
         */

function clean($string) {
   $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}


 function upload()
        {
                $app = JFactory::getApplication();
                $db = JFactory::getDbo();
                $referer = JURI::getInstance($_SERVER['HTTP_REFERER']);
                $name = $_FILES['files']['tmp_name'];
 if($name)
 {
 if ( $xlsx = SimpleXLSX::parse($name))
 {
    /*new code empty the table before insert new data */
         $truncatequery="TRUNCATE table jos_member_access";
         $db->setQuery($truncatequery);
         $db->query();
       $supplier_array1=$xlsx->rows();
  $array = array_map('array_filter', $supplier_array1);
  $supplier_array = array_filter($array);
  $a = array_shift($supplier_array);
   $missingusers=array();
   foreach ($supplier_array as  $supplier_name)
     {

          $check_permission=trim($supplier_name[0]);
       $supplier_names = trim(str_replace("'","", $supplier_name[1]));
    //   $member_names = trim(str_replace("'","",$supplier_name[2]));
         //  $supplier_names= mysql_real_escape_string(trim($supplier_name[1]));
          // $member_names= mysql_real_escape_string(trim($supplier_name[2]));
          $member_names = addslashes(trim($supplier_name[2]));
          $user_name=trim($supplier_name[3]);
          $user_email=trim($supplier_name[4]);

     $querySupplierMember= "SELECT m1.link_id as member_id, s1.link_id as supplier_id  FROM jos_mt_links  m1 join jos_mt_links  s1 where m1.link_name  = '$member_names' AND s1.link_name = '$supplier_names'";
       
       $db->setQuery($querySupplierMember);
      $queryresult=$db->loadObject();
       

          if(! empty($queryresult))
          {
             $suplierid=$queryresult->supplier_id;
             $memberid=$queryresult->member_id;
          }
          else
          {
          // echo "missing supplier name".$supplier_names."<br>";
           // echo "missing Member name".$member_names;
           $missingusers['supplier'][]=$supplier_names;
           $missingusers['member'][]=$member_names;
          }
       $queryUser = "select id from #__users where email = '$user_email'";
         $db->setQuery($queryUser);
         $user=$db->loadObject();
         if(!empty($user))
         {
                $user_id=$user->id;
         }
       else
       {
       $missingusers['users'][]=$user_email."  User name: ".$user_name;
       }

      if($check_permission == "MC")
      {
        $docIds ='1,2,3,4';
      }
      else
      {
        $docIds ='1,2,3';
      }

      /*now query for insert new data only */
      if(($suplierid !="") && ($memberid != "") )
      {
       $insertquery ="INSERT INTO jos_member_access(id, supplierId, memberId,userId,docIds) VALUES ('', '$suplierid', '$memberid', '$user_id', '$docIds')";
        $db->setQuery($insertquery);
        $db->query();

       }
      $this->setRedirect($referer, "permission uploaded successfully");



   }
   
     $uniqeuserdata=array_unique($missingusers['users']);
     if(!empty($uniqeuserdata))
    {
     echo "<br>";
     echo "<br>"; 
     $app->enqueueMessage('<b>Missing user email Id </b>', 'message');

    foreach ($uniqeuserdata as $value)
     {
      $app->enqueueMessage($value);

      }
    echo "<br>";
   $app->enqueueMessage('------------------------------------------');
   }
   $uniquedata=array_unique($missingusers['supplier']);
	if(!empty($uniquedata))
    {
    $app->enqueueMessage('<b>Missing Supplier Company Name </b>', 'message');
    echo "<br>";
     foreach ($uniquedata  as $value)
     {
      $app->enqueueMessage($value);
      }
    echo "<br>";
    $app->enqueueMessage('------------------------------------------');
   }
    $uniquememdata=array_unique($missingusers['member']);
    if(!empty($uniquememdata))
    {
    $app->enqueueMessage('<b>Missing Member Company Name </b>', 'message');
    echo "<br>";
    
        foreach ($uniquememdata  as $value)
     {
      $app->enqueueMessage($value);
      }
   }

}
else
{
$this->setRedirect($referer, "permission not  uploaded");
}

}
else
{
$this->setRedirect($referer, "please upload xls file");
}

}

}











